<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model {
    use SoftDeletes;

    protected $fillable = ["name", "price"];

    public function categories() {
        return $this->belongsToMany('App\Category');
    }
    public function cart() {
        return $this->belongsToMany('App\User', 'App\CartItem');
    }
}
