<?php

namespace app\Http\Services\Api;

use Carbon\Carbon;
use App\Models\User;
use App\Events\UserRegister;
use app\Enums\HttpStatusCode;
use App\Mail\PasswordResetLink;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Http\Resources\TokenResource;
use app\Http\Services\Api\BaseApiService;

class AuthService extends BaseApiService
{
    /**
     * Register a new user
     *
     * @param array $data
     * @return array
     */
    public function register(array $data): array
    {
        $data['verification_token'] = $this->createVerificationToken();

        DB::transaction(function () use($data) {
            $this->data['user']   = User::create($data);
        });

        event(new UserRegister($this->data['user']));

        return sendSuccessInternalResponse('User registered successfully', [
            'user'  => new UserResource($this->data['user']),
        ]);
    }

    /**
     * Login user
     *
     * @param array $data
     * @return array
     */
    public function login(array $data): array
    {
        $user = User::where('email', $data['email'])->first();

        if (! $user) {
            return sendFailInternalResponse('User not found');
        }

        if (!Hash::check($data['password'], $user->password)) {
            return sendFailInternalResponse('Invalid credentials');
        }

        if(! $user->hasVerifiedEmail()) {
            return sendFailInternalResponse('Email not verified', code: HttpStatusCode::FORBIDDEN);
        }

        $token = $this->createToken($user);

        return sendSuccessInternalResponse('User logged in successfully', [
            'user'  => new UserResource($user),
            'token' => $token,
        ]);
    }

    public function sendResetPasswordLink(array $data): array
    {
        $user = User::where('email', $data['email'])->first();

        if(! $user) {
            return sendFailInternalResponse('User not found');
        }

        $token = $this->createVerificationToken();

        DB::table('password_reset_tokens')->updateOrInsert(
            [
                'email' => $user->email,
            ],
            [
                'token'         => Hash::make($token),
                'created_at'    => now(),
            ]
        );

        Mail::to($user->email)->send(new PasswordResetLink($token, $user->email));

        return sendSuccessInternalResponse('Password reset link sent successfully');
    }

    public function resetPassword(array $data): array
    {
        $record = DB::table('password_reset_tokens')->where('email', $data['email'])->first();

        if(! $record || ! Hash::check($data['token'], $record->token)) {
            return sendFailInternalResponse('Password reset link invalid');
        }

        if(Carbon::parse($record->created_at)->addMinutes(30)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $data['email'])->delete();
            return sendFailInternalResponse('password_reset_link_expired', code: HttpStatusCode::EXPIRED);
        }

        User::where('email', $data['email'])->update(['password' => bcrypt($data['password'])]);

        DB::table('password_reset_tokens')->where('email', $data['email'])->delete();

        return sendSuccessInternalResponse('Password reset successfully');
    }

    /**
     * Logout user
     *
     * @param User $user
     * @return array
     */
    public function logout(User $user): array
    {
        $user->tokens()->delete();

        return sendSuccessInternalResponse('Logged out successfully');
    }

    /**
     * Create Api token
     *
     * @param User $user
     * @return TokenResource
     */
    private function createToken(User $user): TokenResource
    {
        $exiprationTime = Carbon::now()->addMinutes((int) config('sanctum.expiration'));
        $token          = $user->createToken($user->username, ['*'], $exiprationTime);

        return new TokenResource($token);
    }

    /**
     * Create verification token
     *
     * @return string
     */
    private function createVerificationToken(): string
    {
        return bin2hex(random_bytes(16));
    }

    /**
     * Verify email
     *
     * @param string $token
     * @return array
     */
    public function verifyEmail(string $token): array
    {
        $user = User::where('verification_token', $token)->first();

        if(! $user) {
            return sendFailInternalResponse('Verification url not valid');
        }

        if($user->hasVerifiedEmail()) {
            return sendFailInternalResponse('Email already verified');
        }

        $user->update([
            'email_verified_at'  => now(),
            'verification_token' => null,
        ]);

        return sendSuccessInternalResponse('Email verified successfully');
    }

    /**
     * Resend verification email
     *
     * @param string $email
     * @return array
     */
    public function resendVerificationEmail($email)
    {
        $user = User::where('email', $email)->first();

        if(! $user) {
            return sendFailInternalResponse('User not found');
        }

        if($user->hasVerifiedEmail()) {
            return sendFailInternalResponse('Email already verified');
        }

        $user->update([
            'verification_token' => $this->createVerificationToken(),
        ]);

        event(new UserRegister($user));

        return sendSuccessInternalResponse('Verification email sent successfully');
    }
}
