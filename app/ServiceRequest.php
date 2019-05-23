<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Images;
use App\ServiceCategory;
use App\UserServiceStatus;

class ServiceRequest extends Model
{
    protected $table = 'service_request';
    protected $fillable = [
     	'user_id','cat_id','service_provider_id','request_desc','status','address','lat','lng'
 	];
//  public function image()
//  {
//   return $this->morphTo();
// }
	public function Service_request_image()
	{
	    return $this->hasMany(Images::class,'image_id','id');
	}
	public function Service_category_name() {
      	return $this->belongsTo('App\ServiceCategory', 'cat_id', 'id')->withDefault();
	}
	public function Service_customer_detail() {
      	return $this->belongsTo('App\User','user_id','id')->withDefault();
	}
	public function Current_service_request() {
      	return $this->hasMany('App\UserServiceStatus','id','request_id')->withDefault();
	}
	public function Service_provider_detail() {
      	return $this->belongsTo('App\User','service_provider_id','id')->withDefault();
	}


	
}
