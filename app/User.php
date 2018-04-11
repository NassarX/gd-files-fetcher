<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

	/**
	 * Get Google Drive token of the user.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
    public function tokens()
    {
    	return $this->hasMany(GDToken::class, 'user_id');
    }

	/**
	 * Get User files.
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
	 */
	public function files()
	{
		return $this->hasMany(UserFiles::class);
	}

	/**
	 * Update user files.
	 *
	 * @param $files
	 */
	public function updateFiles($files)
	{
		UserFiles::truncate();
		UserFiles::insert($files);
	}
}
