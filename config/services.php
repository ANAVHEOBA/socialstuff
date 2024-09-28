<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'facebook' => [
    'client_id' => env('FACEBOOK_CLIENT_ID'),
    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    'redirect' => env('FACEBOOK_REDIRECT_URL'),
],


'youtube' => [
    'client_id' => env('YOUTUBE_CLIENT_ID'),
    'client_secret' => env('YOUTUBE_CLIENT_SECRET'),
    'redirect' => env('YOUTUBE_REDIRECT_URL'),
],



'instagram' => [
    'client_id' => env('INSTAGRAM_CLIENT_ID'),
    'client_secret' => env('INSTAGRAM_CLIENT_SECRET'),
    'redirect' => env('INSTAGRAM_REDIRECT_URL'),
],


'tiktok' => [
    'client_id' => env('TIKTOK_CLIENT_ID'),
    'client_secret' => env('TIKTOK_CLIENT_SECRET'),
    'redirect' => env('TIKTOK_REDIRECT_URL'),
],


'wechat' => [
    'client_id' => env('WECHAT_CLIENT_ID'),
    'client_secret' => env('WECHAT_CLIENT_SECRET'),
    'redirect' => env('WECHAT_REDIRECT_URL'),
],



'linkedin' => [
    'client_id' => env('LINKEDIN_CLIENT_ID'),
    'client_secret' => env('LINKEDIN_CLIENT_SECRET'),
    'redirect' => env('LINKEDIN_REDIRECT_URL'),
],



'snapchat' => [
    'client_id' => env('SNAPCHAT_CLIENT_ID'),
    'client_secret' => env('SNAPCHAT_CLIENT_SECRET'),
    'redirect' => env('SNAPCHAT_REDIRECT_URL'),
],


'twitter' => [
    'client_id' => env('TWITTER_CLIENT_ID'),
    'client_secret' => env('TWITTER_CLIENT_SECRET'),
    'redirect' => env('TWITTER_REDIRECT_URL'),
],


'pinterest' => [
    'client_id' => env('PINTEREST_CLIENT_ID'),
    'client_secret' => env('PINTEREST_CLIENT_SECRET'),
    'redirect' => env('PINTEREST_REDIRECT_URL'),
],


'reddit' => [
    'client_id' => env('REDDIT_CLIENT_ID'),
    'client_secret' => env('REDDIT_CLIENT_SECRET'),
    'redirect' => env('REDDIT_REDIRECT_URL'),
],


'telegram' => [
    'client_id' => env('TELEGRAM_CLIENT_ID'),
    'client_secret' => env('TELEGRAM_CLIENT_SECRET'),
    'redirect' => env('TELEGRAM_REDIRECT_URL'),
],


'tumblr' => [
    'client_id' => env('TUMBLR_CLIENT_ID'),
    'client_secret' => env('TUMBLR_CLIENT_SECRET'),
    'redirect' => env('TUMBLR_REDIRECT_URL'),
],


'medium' => [
    'client_id' => env('MEDIUM_CLIENT_ID'),
    'client_secret' => env('MEDIUM_CLIENT_SECRET'),
    'redirect' => env('MEDIUM_REDIRECT_URL'),
],


'discord' => [
    'client_id' => env('DISCORD_CLIENT_ID'),
    'client_secret' => env('DISCORD_CLIENT_SECRET'),
    'redirect' => env('DISCORD_REDIRECT_URL'),
],


'douyin' => [
    'client_id' => env('DOUYIN_CLIENT_ID'),
    'client_secret' => env('DOUYIN_CLIENT_SECRET'),
    'redirect' => env('DOUYIN_REDIRECT_URL'),
],


'baidu' => [
    'client_id' => env('BAIDU_CLIENT_ID'),
    'client_secret' => env('BAIDU_CLIENT_SECRET'),
    'redirect' => env('BAIDU_REDIRECT_URL'),
],


'kuaishou' => [
    'client_id' => env('KUAISHOU_CLIENT_ID'),
    'client_secret' => env('KUAISHOU_CLIENT_SECRET'),
    'redirect' => env('KUAISHOU_REDIRECT_URL'),
],


'weibo' => [
    'client_id' => env('WEIBO_CLIENT_ID'),
    'client_secret' => env('WEIBO_CLIENT_SECRET'),
    'redirect' => env('WEIBO_REDIRECT_URL'),
],


'vk' => [
    'client_id' => env('VK_CLIENT_ID'),
    'client_secret' => env('VK_CLIENT_SECRET'),
    'redirect' => env('VK_REDIRECT_URL'),
],


'line' => [
    'client_id' => env('LINE_CLIENT_ID'),
    'client_secret' => env('LINE_CLIENT_SECRET'),
    'redirect' => env('LINE_REDIRECT_URL'),
],


'flickr' => [
    'client_id' => env('FLICKR_CLIENT_ID'),
    'client_secret' => env('FLICKR_CLIENT_SECRET'),
    'redirect' => env('FLICKR_REDIRECT_URL'),
],


'meetup' => [
    'client_id' => env('MEETUP_CLIENT_ID'),
    'client_secret' => env('MEETUP_CLIENT_SECRET'),
    'redirect' => env('MEETUP_REDIRECT_URL'),
],


'twitch' => [
    'client_id' => env('TWITCH_CLIENT_ID'),
    'client_secret' => env('TWITCH_CLIENT_SECRET'),
    'redirect' => env('TWITCH_REDIRECT_URL'),
],


'mix' => [
    'client_id' => env('MIX_CLIENT_ID'),
    'client_secret' => env('MIX_CLIENT_SECRET'),
    'redirect' => env('MIX_REDIRECT_URL'),
],


];
