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

        return sendSuccessInternalResponse('user_registered_successfully', [
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
            return sendFailInternalResponse('user_not_found');
        }

        if (!Hash::check($data['password'], $user->password)) {
            return sendFailInternalResponse('password_not_correct');
        }

        if(! $user->hasVerifiedEmail()) {
            return sendFailInternalResponse('email_not_verified', code: HttpStatusCode::FORBIDDEN);
        }

        $token = $this->createToken($user);

        return sendSuccessInternalResponse('login_successfully', [
            'user'  => new UserResource($user),
            'token' => $token,
        ]);
    }

    public function sendResetPasswordLink(array $data): array
    {
        $user = User::where('email', $data['email'])->first();

        if(! $user) {
            return sendFailInternalResponse('user_not_found');
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

        return sendSuccessInternalResponse('password_reset_link_sent_successfully');
    }

    public function resetPassword(array $data): array
    {
        $record = DB::table('password_reset_tokens')->where('email', $data['email'])->first();

        if(! $record || ! Hash::check($data['token'], $record->token)) {
            return sendFailInternalResponse('password_reset_link_invalid');
        }

        if(Carbon::parse($record->created_at)->addMinutes(30)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $data['email'])->delete();
            return sendFailInternalResponse('password_reset_link_expired', code: HttpStatusCode::EXPIRED);
        }

        User::where('email', $data['email'])->update(['password' => bcrypt($data['password'])]);

        DB::table('password_reset_tokens')->where('email', $data['email'])->delete();

        return sendSuccessInternalResponse('password_reset_successfully');
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

        return sendSuccessInternalResponse('logout_successfully');
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
            return sendFailInternalResponse('verification_url_invalid');
        }

        if($user->hasVerifiedEmail()) {
            return sendFailInternalResponse('email_already_verified');
        }

        $user->update([
            'email_verified_at'  => now(),
            'verification_token' => null,
        ]);

        return sendSuccessInternalResponse('email_verified_successfully');
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
            return sendFailInternalResponse('user_not_found');
        }

        if($user->hasVerifiedEmail()) {
            return sendFailInternalResponse('email_already_verified');
        }

        $user->update([
            'verification_token' => $this->createVerificationToken(),
        ]);

        event(new UserRegister($user));

        return sendSuccessInternalResponse('verification_email_sent_successfully');
    }
}
