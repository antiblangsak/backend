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

        $passwordReset = new PasswordReset();
        $passwordReset->email = $userEmail;
        $passwordReset->token = $token;
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
        $email = $reset->email;

        $reset = PasswordReset::where('email', $email)->orderBy('created_at', 'desc')->first();
        if (strcmp(strtolower($token), strtolower($reset->token)) != 0) {
            $data = [
                'success' => $success,
                'error' => 'Token sudah tidak valid.'
            ];
            return response(['data' => $data], 404);
        }

        $success = true;
        $data = [
            'success' => $success,
            'email' => $reset->email
        ];
        return response(['data' => $data], 200);
    }

    public function doResetPassword(Request $request) {

    }
}
