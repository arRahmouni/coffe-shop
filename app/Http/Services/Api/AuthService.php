<?php

namespace app\Http\Services\Api;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Hash;
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
        DB::transaction(function () use($data) {
            $this->data['user']   = User::create($data);
            $this->data['token']  = $this->createToken($this->data['user']);
        });

        return sendSuccessInternalResponse('user_registered_successfully', [
            'user'  => new UserResource($this->data['user']),
            'token' => $this->data['token'],
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

        $token = $this->createToken($user);

        return sendSuccessInternalResponse('login_successfully', [
            'user'  => new UserResource($user),
            'token' => $token,
        ]);
    }

    /**
     * Forget password
     *
     * @param array $data
     * @return array
     */
    public function forgetPassword(array $data): array
    {
        $user = User::where('phone_number', $data['phone_number'])->first();

        if(Hash::check($data['new_password'], $user->password)) {
            return sendFailInternalResponse('new_password_cannot_be_same_as_old_password');
        }

        $user->update([
            'password' => $data['new_password'],
        ]);

        return sendSuccessInternalResponse('password_changed_successfully');
    }

    /**
     * Logout user
     *
     * @param User $user
     * @return array
     */
    public function logout($user): array
    {
        $user->tokens()->delete();

        $user->fcmTokens()->delete();

        return sendSuccessInternalResponse('logout_successfully');
    }

    /**
     * Create Api token
     *
     * @param User $user
     * @param string $tokenName
     * @return TokenResource
     */
    private function createToken($user): TokenResource
    {
        $tokenName      = Str::random(20);
        $exiprationTime = Carbon::now()->addMinutes((int) config('sanctum.expiration'));
        $token = $user->createToken($tokenName, ['*'], $exiprationTime);

        return new TokenResource($token);
    }

    /**
     * Check user for change password
     *
     * @param User $user
     * @param string $password
     * @return array
     */
    private function checkUserForChangePassword($user, $password): array
    {
        if(Hash::check($password, $user->password)) {
            return sendFailInternalResponse('new_password_cannot_be_same_as_old_password');
        }

        return sendSuccessInternalResponse('can_change_password');
    }
}
