<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    public $fillable = [
        'name'
    ];

    public static $rules = [
        'name' => 'required',
    ];

    public function roles_permission()
    {
        return $this->belongsToMany('App\UserRole','roles_permission','permission_id','user_role_id');
    }
}
