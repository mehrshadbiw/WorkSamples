<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function store(Request $request)
    {
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return [
            'status' => 'ok',
            'result' => "User #$user->id created"
        ];
    }


    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->update();

        return [
            'status' => 'ok',
            'result' => "User #$user->id updated"
        ];
    }

    public function showUsersList()
    {
        $users = User::all();
        $response = [];
        foreach ($users as $user) {
            $response[] = [
                'id' => $user->id,
                'name' => $user->name,
            ];
        }
        return $response;
    }

    public function login(Request $request)
    {
        $user = User::where('email', '=', $request->email)->first();
        return [
            'status' => 'ok',
            'result' => [
                'jwt' => auth()->attempt([
                    'email' => $request->email,
                    'password' => $request->password
                ]),
            ]
        ];
    }
}
