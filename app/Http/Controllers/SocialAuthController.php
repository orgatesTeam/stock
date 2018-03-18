<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Socialite;
use App\Services\SocialAccountService;

class SocialAuthController extends Controller
{
    public function facebookLogin()
    {
        return Socialite::driver('facebook')->redirect();
    }

    public function facebookCallback(SocialAccountService $service)
    {
        $user = $service->createOrGetUser(Socialite::driver('facebook')->user());

        auth()->login($user);

        return redirect()->to(route('analysis.short.show'));
    }
}