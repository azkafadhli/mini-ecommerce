<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CartItem extends Pivot {
    protected $table = 'cart_items';
}
