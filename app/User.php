<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Certificates;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','status','role','password','phone','address','profile_pic','latitude','longitude','tax_id','country','state','city','social_security_number','driving_licence','basic_price','range','company_name','working_area_radius','company_desc','licence_type','licence_number'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function Certificate_image()
    {
        return $this->hasMany(Certificates::class,'user_id','id');
    }
}
