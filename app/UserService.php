<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
class UserService extends Model
{
    protected $table = 'user_service';
    protected $fillable = [
       'user_id','service_id','price','discount','created_at','updated_at'
    ];
    
}
