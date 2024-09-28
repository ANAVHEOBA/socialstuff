<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SocialToken extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'provider', 'token', 'refresh_token', 'expires_at'];
}


