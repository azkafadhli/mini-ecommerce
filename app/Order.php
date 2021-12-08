<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model {
    use SoftDeletes;

    protected $fillable = ['user_addresses_id'];

    public function details() {
        return $this->hasMany('App\OrderDetails');
    }
}
