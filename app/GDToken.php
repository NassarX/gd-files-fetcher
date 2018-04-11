<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GDToken extends Model
{
	protected $table = 'user_gd_tokens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'access_token', 'refresh_token', 'expires_in',
    ];
}
