<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;

class ProductController extends Controller {
   
    public function index() {
        return Product::select(['id', 'name'])->get();
    }

    public function store(Request $request) {
        // TODO: implement this first to test the JWT token
        // reference: https://www.avyatech.com/rest-api-with-laravel-8-using-jwt-token/ (Step #12)
    }

    public function show(int $id) {
        return Product::with(['categories'])->where('id', $id)->get();
    }

    public function update(Request $request, Product $product) {
        //
    }

    public function destroy(Product $product) {
        //
    }
}
