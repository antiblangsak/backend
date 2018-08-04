<?php
/**
 * Created by PhpStorm.
 * User: Syukri
 * Date: 6/25/18
 * Time: 12:33 AM
 */

namespace App\Http\Controllers\Auth;

use App\Models\PasswordReset;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;

class CustomForgotPasswordController extends Controller {

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get the password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:6',
        ];
    }

    /**
     * Reset the given user's password.
     *
     * @param  \Illuminate\Contracts\Auth\CanResetPassword  $user
     * @param  string  $password
     * @return void
     */
    protected function resetPassword($user, $password)
    {
        $user->forceFill([
            'password' => bcrypt($password),
            'remember_token' => Str::random(60),
        ])->save();

        $this->guard()->login($user);
    }

    public function sendResetEmail(Request $request) {
        $success = false;

        $userEmail = $request['email'];
        $user = User::where('email', $userEmail)->first();
        if ($user === null) {
            $data = [
                'success' => $success,
                'error' => 'Email tidak terdaftar.'
            ];
            return response(['data' => $data], 404);
        }

        $token = $this->generateToken();
        $user->sendPasswordResetNotification($token);

        $reset = PasswordReset::where('email', $userEmail)->where('is_valid', $value=true)->get();
        foreach ($reset as $res) {
            $res->is_valid = false;
            $res->save();
        }

        $passwordReset = new PasswordReset();
        $passwordReset->email = $userEmail;
        $passwordReset->token = $token;
        $passwordReset->is_valid = true;
        $passwordReset->save();

        $success = true;
        $data = [
            'success' => $success,
            'token' => $token,
        ];
        return response(['data' => $data], 200);
    }

    public function generateToken() {
        $length = 6;
        $str = "";
        $characters = array_merge(range('a','z'), range('0','9'));
        $max = count($characters) - 1;
        for ($i = 0; $i < $length ; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }

    public function validateResetPasswordToken(Request $request) {
        $success = false;

        $token = $request['token'];
        $reset = PasswordReset::where('token', $token)->first();
        if ($reset === null) {
            $data = [
                'success' => $success,
                'error' => 'Token tidak ditemukan.'
            ];
            return response(['data' => $data], 404);
        }

        if (!$reset->is_valid) {
            $data = [
                'success' => $success,
                'error' => 'Token sudah tidak valid.'
            ];
            return response(['data' => $data], 403);
        }

        $success = true;
        $data = [
            'success' => $success,
            'token' => $token,
            'email' => $reset->email
        ];
        return response(['data' => $data], 200);
    }

    public function doResetPassword(Request $request) {
        $success = false;

        $token = $request['token'];
        $newPassword = $request['new_password'];

        $reset = PasswordReset::where('token', $token)->first();
        if ($reset === null) {
            $data = [
                'success' => $success,
                'error' => 'Token tidak ditemukan.'
            ];
            return response(['data' => $data], 404);
        }

        if (!$reset->is_valid) {
            $data = [
                'success' => $success,
                'error' => 'Token sudah tidak valid.'
            ];
            return response(['data' => $data], 403);
        }

        $user = User::where('email', $reset->email)->first();
        $user->password = bcrypt($newPassword);
        $user->api_token = str_random(60);
        $user->save();

        $reset->is_valid = false;
        $reset->save();

        $success = true;
        $data = [
            'success' => $success
        ];
        return response(['data' => $data], 201);
    }
}
