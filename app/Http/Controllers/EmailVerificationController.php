<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidRequestException;
use App\Models\User;
use App\Notifications\EmailVerificationNotification;
use Illuminate\Http\Request;
use Cache;

class EmailVerificationController extends Controller
{
    public function verify(Request $request)
    {
        $email = $request->input('email');
        $token = $request->input('token');
        if (!$email || !$token) {
            throw new  InvalidRequestException('验证链接不正确');
        }

        $key = 'email_verification_' . $email;
        $verifyToken = Cache::get($key);
        if (!$verifyToken || !hash_equals((string)$token, (string)$verifyToken)) {
            throw new InvalidRequestException('验证链接不正确或已过期');
        }

        if (!$user = User::where('email', $email)->first()) {
            throw new InvalidRequestException('用户不存在');
        }

        Cache::forget($key);
        $user->update(['email_verified' => true]);
        return view('pages.success', ['msg' => '邮箱验证成功']);
    }

    public function send(Request $request)
    {
        $user = $request->user();

        if ($user->email_verified) {
            throw new InvalidRequestException('您已验证过邮箱了');
        }

        $user->notify(new EmailVerificationNotification());

        return view('pages.success',  ['msg' => '邮件发送成功']);
    }
}
