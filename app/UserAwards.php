<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ServiceRequest;
use App\User;
use App\Awards;

class UserAwards extends Model
{
    protected $table = 'user_awards';
    protected $fillable = [
        'user_id','award_id','created_at','updated_at'
    ];

  public function Award_detail() {
      	return $this->belongsTo('App\Awards','award_id','id')->withDefault();
	}
	
}
