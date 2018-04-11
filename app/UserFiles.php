<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFiles extends Model
{
	protected $table = 'user_files';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
	protected $fillable = [
		'file_id', 'title', 'size', 'mime_type', 'download_url', 'user_id', 'created_at', 'updated_at'
	];

}
