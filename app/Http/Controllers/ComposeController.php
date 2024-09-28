<?php

namespace App\Http\Controllers;

use App\Models\SocialToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ComposeController extends Controller
{
    public function publish(Request $request)
    {
        $provider = $request->input('provider');
        $socialToken = SocialToken::where('user_id', auth()->id())
            ->where('provider', $provider)
            ->first();

        // Refresh token if expired
        if ($socialToken->expires_at && $socialToken->expires_at->isPast()) {
            $newTokenData = $this->refreshAccessToken($provider, $socialToken->refresh_token);
            $socialToken->update([
                'token' => $newTokenData['access_token'],
                'expires_at' => now()->addSeconds($newTokenData['expires_in']),
            ]);
        }

        $token = $socialToken->token;
        $title = $request->input('title');
        $description = $request->input('description');
        $video = $request->file('video');

        // Handle YouTube Post
        if ($provider === 'youtube') {
            $response = Http::withToken($token)->post('https://www.googleapis.com/upload/youtube/v3/videos', [
                'part' => 'snippet,status',
                'snippet' => [
                    'title' => $title,
                    'description' => $description,
                    'tags' => ['example', 'youtube', 'api'],
                    'categoryId' => '22',
                ],
                'status' => [
                    'privacyStatus' => 'public',
                ],
            ], [
                'video' => fopen($video->getPathname(), 'r'),
            ]);
        }

        // Handle TikTok Post
        if ($provider === 'tiktok') {
            $response = Http::withToken($token)->post('https://open-api.tiktok.com/v2/video/upload', [
                'video' => fopen($video->getPathname(), 'r'),
                'description' => $description,
            ]);
        }

        // Handle WeChat Post
        if ($provider === 'wechat') {
            $response = Http::withToken($token)->post('https://api.weixin.qq.com/cgi-bin/message/custom/send', [
                'touser' => $request->input('touser'),
                'msgtype' => 'text',
                'text' => [
                    'content' => $description,
                ],
            ]);
        }

        // Handle LinkedIn Post
        if ($provider === 'linkedin') {
            $response = Http::withToken($token)->post('https://api.linkedin.com/v2/ugcPosts', [
                'author' => "urn:li:person:{$socialToken->user->linkedin_urn}",
                'lifecycleState' => 'PUBLISHED',
                'specificContent' => [
                    'com.linkedin.ugc.ShareContent' => [
                        'shareCommentary' => [
                            'text' => $description,
                        ],
                        'shareMediaCategory' => 'NONE',
                    ],
                ],
                'visibility' => [
                    'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC',
                ],
            ]);
        }

        // Handle Snapchat Post
        if ($provider === 'snapchat') {
            $response = Http::withToken($token)->post('https://adsapi.snapchat.com/v1/media/upload', [
                'title' => $title,
                'description' => $description,
                'video' => fopen($video->getPathname(), 'r'),
            ]);
        }

        // Handle Twitter Post
        if ($provider === 'twitter') {
            $response = Http::withToken($token)->post('https://api.twitter.com/2/tweets', [
                'text' => $description,
                'media' => [
                    'media_ids' => $this->uploadMediaToTwitter($token, $video),
                ],
            ]);
        }

        // Handle Pinterest Post
        if ($provider === 'pinterest') {
            $response = Http::withToken($token)->post('https://api.pinterest.com/v5/pins', [
                'title' => $title,
                'description' => $description,
                'media_source' => [
                    'source_type' => 'video',
                    'content_type' => $video->getMimeType(),
                    'data' => fopen($video->getPathname(), 'r'),
                ],
            ]);
        }

        // Handle Reddit Post
        if ($provider === 'reddit') {
            $response = Http::withToken($token)->post('https://oauth.reddit.com/api/submit', [
                'sr' => $request->input('subreddit'),  // subreddit name
                'kind' => 'self',  // 'self' for text post, 'link' for URL, 'image' for images
                'title' => $title,
                'text' => $description,  // if it's a text post
            ]);
        }

        // Handle Telegram Post
        if ($provider === 'telegram') {
            $chat_id = $request->input('chat_id');
            $response = Http::post("https://api.telegram.org/bot$token/sendMessage", [
                'chat_id' => $chat_id,
                'text' => $description,
            ]);
        }

        // Handle Tumblr Post
        if ($provider === 'tumblr') {
            $response = Http::withToken($token)->post('https://api.tumblr.com/v2/blog/{blog-identifier}/post', [
                'type' => 'text',
                'title' => $title,
                'body' => $description,
            ]);
        }

        // Handle Medium Post
        if ($provider === 'medium') {
            $response = Http::withToken($token)->post('https://api.medium.com/v1/users/me/posts', [
                'title' => $title,
                'contentFormat' => 'html',
                'content' => $description,
                'publishStatus' => 'public',
            ]);
        }

        // Handle Discord Post
        if ($provider === 'discord') {
            $webhookUrl = $request->input('webhook_url');
            $response = Http::post($webhookUrl, [
                'content' => $description,
                'username' => 'Your Bot Name', // Optional: customize your bot's name
            ]);
        }

        // Handle Douyin Post
        if ($provider === 'douyin') {
            $response = Http::withToken($token)->post('https://open.douyin.com/video/upload', [
                'video' => fopen($video->getPathname(), 'r'),
                'description' => $description,
            ]);
        }

        // Handle Baidu Post
        if ($provider === 'baidu') {
            $response = Http::withToken($token)->post('https://openapi.baidu.com/rest/2.0/smartapp/v1.0/create', [
                'title' => $title,
                'content' => $description,
                'video' => fopen($video->getPathname(), 'r'),
            ]);
        }

        // Handle Kuaishou Post
        if ($provider === 'kuaishou') {
            $response = Http::withToken($token)->post('https://open.kuaishou.com/n/creation/video', [
                'video' => fopen($video->getPathname(), 'r'),
                'description' => $description,
            ]);
        }

        // Handle Weibo Post
        if ($provider === 'weibo') {
            $response = Http::withToken($token)->post('https://api.weibo.com/2/statuses/share.json', [
                'status' => $description,
                'url' => $request->input('url'), // Optional: attach a URL
            ]);
        }

        // Handle VK Post
        if ($provider === 'vk') {
            $response = Http::withToken($token)->post('https://api.vk.com/method/wall.post', [
                'owner_id' => $request->input('owner_id'), // Group or user ID
                'message' => $description,
                'access_token' => $token,
                'v' => '5.131', // API version
            ]);
        }

        // Handle LINE Post
        if ($provider === 'line') {
            $response = Http::withToken($token)->post('https://api.line.me/v2/bot/message/push', [
                'to' => $request->input('to'), // User ID or group ID
                'messages' => [
                    [
                        'type' => 'text',
                        'text' => $description,
                    ],
                ],
            ]);
        }

        // Handle Flickr Post
        if ($provider === 'flickr') {
            $response = Http::withToken($token)->post('https://www.flickr.com/services/upload/', [
                'photo' => fopen($video->getPathname(), 'r'),
                'title' => $title,
                'description' => $description,
                'is_public' => 1,
                'tags' => $request->input('tags', ''),
                'format' => 'json',
                'nojsoncallback' => 1,
            ]);
        }

        // Handle Meetup Post
        if ($provider === 'meetup') {
            $groupUrlname = $request->input('group_urlname');
            $response = Http::withToken($token)->post("https://api.meetup.com/$groupUrlname/events", [
                'name' => $title,
                'description' => $description,
                'time' => $request->input('event_time'),
                'duration' => $request->input('event_duration'),
                'venue_id' => $request->input('venue_id'),
            ]);
        }

        // Handle Twitch Post
        if ($provider === 'twitch') {
            $channelId = $request->input('channel_id');
            $response = Http::withToken($token)
                ->withHeaders(['Client-ID' => config('services.twitch.client_id')])
                ->post("https://api.twitch.tv/helix/chat/announcements", [
                    'broadcaster_id' => $channelId,
                    'moderator_id' => $channelId, // Assuming the user is posting to their own channel
                    'message' => $description,
                ]);
        }

        // Handle Mix Post
        if ($provider === 'mix') {
            $response = Http::withToken($token)->post('https://api.mix.com/api/v1/posts', [
                'title' => $title,
                'content' => $description,
                'url' => $request->input('url'),
                'tags' => $request->input('tags', []),
            ]);
        }

        return response()->json(['message' => ucfirst($provider) . ' post published successfully.'], 200);
    }

    // Helper method to refresh access token using refresh token
    protected function refreshAccessToken($provider, $refreshToken)
    {
        return match ($provider) {
            'snapchat' => Http::post('https://adsapi.snapchat.com/v1/oauth2/refresh', [
                'refresh_token' => $refreshToken,
                'client_id' => config('services.snapchat.client_id'),
                'client_secret' => config('services.snapchat.client_secret'),
                'grant_type' => 'refresh_token',
            ])->json(),
            'twitter' => Http::post('https://api.twitter.com/oauth2/token', [
                'refresh_token' => $refreshToken,
                'client_id' => config('services.twitter.client_id'),
                'client_secret' => config('services.twitter.client_secret'),
                'grant_type' => 'refresh_token',
            ])->json(),
            'medium' => Http::post('https://api.medium.com/v1/tokens', [
                'refresh_token' => $refreshToken,
                'client_id' => config('services.medium.client_id'),
                'client_secret' => config('services.medium.client_secret'),
                'grant_type' => 'refresh_token',
            ])->json(),
            'discord' => Http::post('https://discord.com/api/oauth2/token', [
                'refresh_token' => $refreshToken,
                'client_id' => config('services.discord.client_id'),
                'client_secret' => config('services.discord.client_secret'),
                'grant_type' => 'refresh_token',
            ])->json(),
            'douyin' => Http::post('https://open.douyin.com/oauth/token', [
                'refresh_token' => $refreshToken,
                'client_id' => config('services.douyin.client_id'),
                'client_secret' => config('services.douyin.client_secret'),
                'grant_type' => 'refresh_token',
            ])->json(),
            'baidu' => Http::post('https://openapi.baidu.com/oauth/2.0/token', [
                'refresh_token' => $refreshToken,
                'client_id' => config('services.baidu.client_id'),
                'client_secret' => config('services.baidu.client_secret'),
                'grant_type' => 'refresh_token',
            ])->json(),
            'kuaishou' => Http::post('https://open.kuaishou.com/oauth2/token', [
                'refresh_token' => $refreshToken,
                'client_id' => config('services.kuaishou.client_id'),
                'client_secret' => config('services.kuaishou.client_secret'),
                'grant_type' => 'refresh_token',
            ])->json(),
            'weibo' => Http::post('https://api.weibo.com/oauth2/access_token', [
                'refresh_token' => $refreshToken,
                'client_id' => config('services.weibo.client_id'),
                'client_secret' => config('services.weibo.client_secret'),
                'grant_type' => 'refresh_token',
            ])->json(),
            'vk' => Http::post('https://oauth.vk.com/access_token', [
                'refresh_token' => $refreshToken,
                'client_id' => config('services.vk.client_id'),
                'client_secret' => config('services.vk.client_secret'),
                'grant_type' => 'refresh_token',
            ])->json(),
            'line' => Http::post('https://api.line.me/oauth2/v2.1/token', [
                'refresh_token' => $refreshToken,
                'client_id' => config('services.line.client_id'),
                'client_secret' => config('services.line.client_secret'),
                'grant_type' => 'refresh_token',
            ])->json(),
            'flickr' => Http::post('https://www.flickr.com/services/oauth/access_token', [
                'oauth_token' => $refreshToken,
                'oauth_consumer_key' => config('services.flickr.client_id'),
                'oauth_signature_method' => 'HMAC-SHA1',
                'oauth_signature' => $this->generateFlickrSignature($refreshToken),
            ])->json(),
            'meetup' => Http::post('https://secure.meetup.com/oauth2/access', [
                'refresh_token' => $refreshToken,
                'client_id' => config('services.meetup.client_id'),
                'client_secret' => config('services.meetup.client_secret'),
                'grant_type' => 'refresh_token',
            ])->json(),
            'twitch' => Http::post('https://id.twitch.tv/oauth2/token', [
                'refresh_token' => $refreshToken,
                'client_id' => config('services.twitch.client_id'),
                'client_secret' => config('services.twitch.client_secret'),
                'grant_type' => 'refresh_token',
            ])->json(),
            'mix' => Http::post('https://api.mix.com/oauth/token', [
                'refresh_token' => $refreshToken,
                'client_id' => config('services.mix.client_id'),
                'client_secret' => config('services.mix.client_secret'),
                'grant_type' => 'refresh_token',
            ])->json(),
            default => '',
        };
    }

    protected function uploadMediaToTwitter($token, $video)
    {
        // Upload media to Twitter, return media IDs
        $uploadResponse = Http::withToken($token)->post('https://upload.twitter.com/1.1/media/upload.json', [
            'media' => fopen($video->getPathname(), 'r'),
        ]);
        
        return $uploadResponse['media_id_string'];
    }

    protected function generateFlickrSignature($refreshToken)
    {
        // This is a placeholder method. In a real-world scenario, you would need to implement
        // the HMAC-SHA1 signature generation according to Flickr's OAuth 1.0a specifications.
        // The actual implementation would involve creating a base string from your request parameters
        // and your consumer secret, then applying the HMAC-SHA1 algorithm.
        
        return 'generated_signature_here';
    }
}