<?php

namespace App\Http\Controllers;

use App\UserAddress;
use Illuminate\Http\Request;

class UserAddressController extends Controller {
    public function index() {
        return UserAddress::all();
    }

    public function store(Request $request) {
        $validatedData = $request->validate(
            [
                'address' => ['required', 'string', 'unique:user_addresses,address']
            ]
        );
        $validatedData['is_main_address'] = false;
        $validatedData['user_id'] = auth()->user()->id;
        $address = UserAddress::create($validatedData);
        $address->save();
        return $address;
    }

    public function show(int $id) {
        return UserAddress::with('user')->find($id);
    }

    public function update(Request $request, int $id) {
        $address = UserAddress::find($id);
        if (!$address) {
            return response()->json(['message' => 'Address not found'], 400);
        }
        if ($address->user_id === auth()->user()->id){
            $validatedData = $request->validate(
                [
                    'address' => ['string', 'unique:user_addresses,address'],
                    'is_main_address' => ['boolean']
                ]
            );
            $address->update($validatedData);
            return $address;
        }
        return response()->json(['status' => 'unauthorized'], 401);
    }

    public function destroy(int $id) {
        $address = UserAddress::find($id);
        if (auth()->user()->id === $address->id) {
            return $address->delete();
        }
        return response()->json(['status' => 'unauthorized'], 401);
    }
}
