<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject {
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'name', 'email', 'role_id', 'password'
    ];

    protected $hidden = ['password', 'role_id', 'created_at', 'updated_at'];

    protected $casts = [];

    public function getJWTIdentifier() {
        return $this->getKey();
    }

    public function getJWTCustomClaims() {
        return [];
    }

    public static function isAdmin(Authenticatable $user) {
        return $user["role_id"] == 1;
    }

    public function cart() {
        return $this->belongsToMany('App\Product', 'App\CartItem');
    }
    public function address() {
        return $this->hasMany('App\UserAddress');
    }
}
