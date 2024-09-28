<?php

namespace App\Http\Controllers;

use Laravel\Socialite\Facades\Socialite;
use App\Models\SocialToken;
use Illuminate\Support\Facades\Auth;

class SocialAuthController extends Controller
{
    public function redirectToProvider($provider)
    {
        if ($provider === 'youtube') {
            return Socialite::driver($provider)
                ->scopes(['https://www.googleapis.com/auth/youtube.upload'])
                ->redirect();
        } elseif ($provider === 'reddit') {
            return Socialite::driver($provider)
                ->scopes(['identity', 'submit', 'read', 'save', 'history']) // Define Reddit scopes
                ->redirect();
        } elseif (in_array($provider, ['tiktok', 'wechat', 'linkedin', 'snapchat', 'twitter', 'pinterest', 'reddit', 'telegram', 'tumblr', 'medium', 'discord', 'douyin', 'baidu', 'kuaishou', 'weibo', 'vk', 'line', 'flickr', 'meetup', 'twitch', 'mix'])) {
            return Socialite::driver($provider)->redirect();
        }
    }

    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();

        // Storing or updating the token for the authenticated user
        SocialToken::updateOrCreate(
            ['user_id' => Auth::id(), 'provider' => $provider],
            [
                'token' => $user->token,
                'refresh_token' => $user->refreshToken,
                'expires_at' => now()->addSeconds($user->expiresIn),
            ]
        );

        return response()->json(['message' => "Connected to $provider successfully."], 200);
    }
}
