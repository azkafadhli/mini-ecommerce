<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\User;

class UserAddress extends Model {
    use SoftDeletes;

    protected $fillable = ['address', 'user_id', 'is_main_address'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
