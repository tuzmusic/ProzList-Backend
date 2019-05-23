<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ServiceRequest;
use App\User;

class Strikes extends Model
{
    protected $table = 'strikes';
    protected $fillable = [
        'user_id','comments','created_at','updated_at'
    ];
}
