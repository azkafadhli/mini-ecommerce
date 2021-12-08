<?php

namespace App\Http\Controllers;

use App\User;
use App\CartItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CartItemController extends Controller {
    public function index() {
        $user = auth()->user();

        if (!User::isAdmin($user)) {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        return CartItem::all();
    }

    public function store(Request $request) {
        $user = User::find(auth()->user()->id);
        $validatedData = $request->validate(
            [
                'product_id' => [
                    'required',
                    'exists:products,id',
                    Rule::unique('cart_items')->where('user_id', $user->id)
                ],
                'quantity' => ['required', 'numeric', 'gte:0']
            ]
        );
        $user->cart()->attach($validatedData['product_id'], ['quantity' => $validatedData['quantity']]);
        return response()->json($user, 201);
    }

    public function show(int $user_id) {
        //see App\User()->cart();
    }

    public function update(Request $request, int $id) {
        $cart = CartItem::find($id);
        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 400);
        }
        $user = auth()->user();
        if (!($cart->user_id === $user->id)) {
            return response()->json(['status' => 'unauthorized'], 401);
        }
        $validatedData = $request->validate(
            [
                'quantity' => ['required', 'numeric', 'gte:0']
            ]
        );
        $cart->update($validatedData);
        return $cart;
    }

    public function destroy(int $id) {
        $cart = CartItem::find($id);
        $user = auth()->user();
        if (!($cart->user_id === $user->id)) {
            return response()->json(['status' => 'unauthorized'], 401);
        }
        return $cart->delete();
    }
}
