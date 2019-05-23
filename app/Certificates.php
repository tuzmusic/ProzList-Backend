<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Certificates extends Model
{
	protected $table = 'certificate';
	protected $fillable = [
		'user_id','image','width','height','created_at'
	];
	
}
