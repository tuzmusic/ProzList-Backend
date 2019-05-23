<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ServiceRequest;
use App\User;
use App\UserAwards;

class Awards extends Model
{
    protected $table = 'awards';
    protected $fillable = [
        'award_name','description','tag_line','created_at','updated_at'
    ];
}
