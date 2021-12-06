<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {
    protected $fillable = ["name"];
    public $timestamps = false;

    public function product() {
        return $this->BelongsToMany('App\Product');
    }
}
