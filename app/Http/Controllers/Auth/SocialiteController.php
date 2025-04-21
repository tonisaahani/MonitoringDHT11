<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Laravel\Socialite\Facades\Socialite;
use App\Models\Socialite as SocialiteModel;

class SocialiteController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
            // dd($provider);
            try {
                $socialUser = Socialite::driver($provider)->user();
// dd($socialUser);
                $userStatus = $this->store($socialUser, $provider);

                Auth::login($userStatus['user']);

                if ($userStatus['new']) {
                    // Redirect ke halaman buat password
                    return redirect()->route('password.create');
                }

                return redirect()->route('home');
            } catch (\Exception $e) {
                return redirect('/login')->with('error', 'Something went wrong!');
        }
    }

    public function store($socialUser, $provider)
{
    // dd($socialUser);
    $socialAccount = SocialiteModel::where('provider_id', $socialUser->getId())
        ->where('provider_name', $provider)
        ->first();

        dd($socialAccount);

    if ($socialAccount) {
        $user = User::where('email', $socialUser->getEmail())->first();

        $isNew = false;

        if (!$user) {
            $user = User::create([
                // 'user_img' => $socialUser->avatar,
                'name' => $socialUser->getName() ?? $socialUser->getNickname(),
                'email' => $socialUser->getEmail(),
                'password' => null, // Password belum dibuat
                'is_oauth' => true,
                'email_verified_at' => now(),
            ]);
            $isNew = true;
        }

        $user->socialite()->create([
            'provider_id' => $socialUser->getId(),
            'provider_name' => $provider,
            'provider_token' => $socialUser->token,
            'provider_refresh_token' => $socialUser->refreshToken,
        ]);

        return ['user' => $user, 'new' => $isNew];
    }

    return ['user' => $socialAccount->user, 'new' => false];
    }
}
