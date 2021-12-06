<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller {

    public function index() {
        $user = auth()->user();
        if (!User::isAdmin($user)) {
            return response()->json(['status' => 'unauthorized'], 401);
        }
        return response()->json(User::all());
    }

    public function store(Request $request) {
        $validatedData = $request->validate([
            'email' => ['required', 'unique:users', 'email:rfc,dns'],
            'name' => ['max:256', ],
            'role_id' => function($attribute, $value, $fail) {
                if ($value !== 2) {
                    $fail($attribute.' is invalid');
                }
            },
            'password' => ['required']
        ]);
        $validatedData['password'] =  Hash::make($validatedData['password']);
        $user = User::create($validatedData);
        $user->save();
        return $user;
    }

    public function show(User $user) {
        //
    }

    public function update(Request $request, User $user) {
        //
    }

    public function destroy(User $user) {
        //
    }
}
