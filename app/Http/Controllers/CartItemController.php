<?php

namespace App\Http\Controllers;

use App\User;
use App\CartItem;
use Illuminate\Http\Request;

class CartItemController extends Controller {
    public function index() {
        $user = auth()->user();
        
        if (!User::isAdmin($user)) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        return CartItem::all();
    }

    public function store(Request $request) {
        //
    }

    public function show(int $user_id) {
        //
    }

    public function update(Request $request, CartItem $cartItem) {
        //
    }

    public function destroy(CartItem $cartItem) {
        //
    }
}
