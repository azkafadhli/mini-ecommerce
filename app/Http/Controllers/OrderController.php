<?php

namespace App\Http\Controllers;

use App\CartItem;
use App\Order;
use App\OrderDetails;
use App\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller {
    public function index() {
        return Order::all();
    }

    public function store(Request $request) {
        $validatedData = $request->validate(
            [
                'carts' => ['required', Rule::exists('cart_items', 'id')->where('user_id', auth()->user()->id)],
                'address' => ['required', 'integer', Rule::exists('user_addresses', 'id')->where('user_id', auth()->user()->id)]
            ]
        );
        
        $order = Order::create(['user_addresses_id' => $validatedData['address']]);
        
        $productsToOrder = [];
        foreach (CartItem::find($validatedData['carts']) as $cart) {
            array_push(
                $productsToOrder,
                [
                    'order_id'=> $order['id'], 
                    'product_id' => $cart['product_id'],
                    'quantity' => $cart['quantity'],
                    'created_at' => now(),
                    'updated_at' => now()
                ]
            );
        }
        OrderDetails::insert($productsToOrder);
        CartItem::destroy($validatedData['carts']);
        return Order::with('details')->find($order['id']);
    }

    public function show(int $id) {
        return Order::with('details')->find($id);
    }

    public function update(Request $request, int $id) {
        $validatedData = $request->validate(
            [
                'user_addresses_id' => ['exists:user_addresses,id']
            ]
        );
        $order = Order::find($id);
        $order->update($validatedData);
        return $order;
    }

    public function destroy(int $id) {
        $order = Order::find($id);
        return $order->delete();
    }
}
