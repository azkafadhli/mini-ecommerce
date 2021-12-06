<?php

namespace App\Http\Controllers;

use App\User;
use App\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller {

    public function index() {
        return Category::all();
    }

    public function store(Request $request) {
        $user = auth()->user();
        if (!User::isAdmin($user)) {
            return response()->json(['status' => 'unauthorized'], 401);
        }
        $validatedData = $request->validate(['name' => ['max:256',],]);
        $category = Category::create($validatedData);
        $category->save();
        return response()->json($category, 201);
    }

    public function show(int $id) {
        return Category::with(['product'])->where('id', $id)->get();
    }

    public function update(Request $request, int $id) {
        $user = auth()->user();
        if (!User::isAdmin($user)) {
            return response()->json(['status' => 'unauthorized'], 401);
        }
        $validatedData = $request->validate(['name' => ['max:256',],]);
        $category = Category::find($id);
        $category->update($validatedData);
        return $category;
    }

    public function destroy() {
        return response()->json(['status' => 'Method not allowed'], 405);
    }
}
