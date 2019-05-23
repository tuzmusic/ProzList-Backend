<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ServiceRequest;
use App\User;

class UserRating extends Model
{
    protected $table = 'user_rating';
    protected $fillable = [
        'service_request_id','customer_id','service_provider_id','rating','review','review_from','created_at','updated_at'
    ];

    public function Service_provider_detail() {
      	return $this->belongsTo('App\User','service_provider_id','id')->withDefault();
	}
	public function Customer_detail() {
      	return $this->belongsTo('App\User','customer_id','id')->withDefault();
	}
}
