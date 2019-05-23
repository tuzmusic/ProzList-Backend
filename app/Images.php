<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ServiceRequest;
use App\ServiceCategory;

class Images extends Model
{
	protected $table = 'images';
	protected $fillable = [
		'image','width','height','image_id','image_type'
	];
	
}
