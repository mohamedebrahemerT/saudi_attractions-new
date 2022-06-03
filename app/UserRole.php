<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{

    public $fillable = [
        'name',
    ];

    public static $rules = [
        'name' => 'required',
    ];

    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function roles_permission()
    {
        return $this->belongsToMany('App\Permission','roles_permission','user_role_id','permission_id');
    }

}
