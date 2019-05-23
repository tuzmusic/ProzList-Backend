<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ServiceRequest;
use App\Images;

class ServiceCategory extends Model
{
    protected $table = 'service_category';
    protected $fillable = [
       'name','image','status'
    ];
    public function Service_requests()
	{
	    return $this->hasMany('App\ServiceRequest', 'cat_id');
	}
    
}
