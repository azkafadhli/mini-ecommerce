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

    public function show(int $id) {
        $userFromToken = auth()->user();
        $user = User::with('cart')->find($id);
        if (($userFromToken['id']==$user['id']) | (!User::isAdmin($user))) {
            // TODO: return with user address
            return $user;
        }
        
        return response()->json(['status' => 'unauthorized'], 401);
    }

    public function update(Request $request, int $id) {
        $userFromToken = auth()->user();
        $user = User::find($id);
        if ($userFromToken['id']===$user['id']) {
            $validatedData = $request->validate(
                [
                    'name' => ['max:256', ],
                    'email' => ['unique:users', 'email:rfc,dns']
                ]
            );
            $user->update($validatedData);
            return $user;   
        }
        return response()->json(['status' => 'unauthorized'], 401);
    }

    public function destroy(int $id) {
        $userFromToken = auth()->user();
        $user = User::find($id);
        if ($userFromToken['id']===$user['id']) {
            return $user->delete();
        }
        return response()->json(['status' => 'unauthorized'], 401);
    }

    public function restore() {}
}
