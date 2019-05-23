<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Images;
use App\ServiceCategory;
use App\ServiceRequest;

class UserServiceStatus extends Model
{
    protected $table = 'user_service_status';
    protected $fillable = [
        'user_id','request_id','status','SPStatus','CustStatus','sp_distance','created_at','updated_at'
    ];

    public function Current_service_request() {
      	return $this->belongsTo('App\ServiceRequest','request_id','id')->withDefault();
	}
    
}
