<?php

namespace App\Http\Controllers;

use App\User;
use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller {

    public function index() {
        return Product::select(['id', 'name'])->get();
    }

    public function store(Request $request) {
        $user = auth()->user();
        if (!User::isAdmin($user)) {
            return response()->json(['status' => 'unauthorized'], 401);
        }
        $validatedData = $request->validate(
            [
                'name' => ['max:256'],
                'price' => ['numeric', 'gte:0']
            ]
        );
        $product = Product::create($validatedData);
        $product->save();
        return response()->json($product, 201);
    }

    public function show(int $id) {
        return Product::with(['categories'])->where('id', $id)->get();
    }

    public function update(Request $request, int $id) {
        $user = auth()->user();
        if (!User::isAdmin($user)) {
            return response()->json(['status' => 'unauthorized'], 401);
        }
        $validatedData = $request->validate([
            'name' => ['max:256'],
            'price' => ['numeric', 'gte:0']
        ]);
        $product = Product::find($id);
        $product->update($validatedData);
        return $product;
    }

    public function destroy(int $id) {
        $user = auth()->user();
        if (!User::isAdmin($user)) {
            return response()->json(['status' => 'unauthorized'], 401);
        }
        $product = Product::find($id);
        return $product->delete();
    }
}
