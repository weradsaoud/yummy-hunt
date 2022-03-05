<?php

namespace App\Http\Controllers\API\Dealont;

use Illuminate\Support\Facades\Hash;
use App\Models\AppUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Restorant;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'username' => 'required|string',
            'email' => 'required|string|unique:app_users,email',
            'password' => 'required|string'
        ]);
        $user_photo = 'storage/usersAvatars/default.png';
        AppUser::Create([
            'name' => $fields['username'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password']),
            'photo' => $user_photo
        ]);
        //$token = $user->createToken('myappToken')->plainTextToken;
        $response = [
            'code' => 200,
            'message' => 'Registration new account was successfull'
        ];
        return response($response, 200);
    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = AppUser::where('email', $fields['username'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'success' => false,
                'data' => []
            ], 401);
        }

        $token = $user->createToken('myappToken')->plainTextToken;

        $user_photo = asset($user->photo);

        $response = [
            'success' => true,
            'data' => [
                'id' => $user->id,
                'display_name' => $user->name,
                'user_nicename' => '',
                'user_photo' => $user_photo,
                'user_url' => 'url',
                'user_level' => 'level',
                'description' => 'user description',
                'tag' => 'user tag',
                'rate' => 'user rate',
                'token' => $token,
                'user_email' => $user->email
            ]
        ];
        return response($response, 200);
    }

    public function logout(Request $request)
    {
    }

    public function updateprofile(Request $request)
    {
        $user = $request->user();
        if ($user->email == $request->email) {
            $fields = $request->validate([
                'name' => 'required|string',
                'email' => 'required|string',
                'url' => 'string',
                'description' => 'string',
            ]);
        } else {
            $fields = $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|unique:users,email',
                'url' => 'string',
                'description' => 'string',
            ]);
        }
        $didUpdate = $user->update([
            'name' => @$fields['name'],
            'email' => @$fields['email'],
            'url' => @$fields['url'],
            'description' => @$fields['description']
        ]);
        if ($didUpdate) {
            $response = [
                'code' => 200,
                'message' => 'Update profile was successfull'
            ];
            return response($response, 200);
        } else {
            $response = [
                'code' => 500,
                'message' => 'Update profile failed.'
            ];
            return response($response, 500);
        }
    }

    public function userInfo(Request $request)
    {
        $user = $request->user();
        $user_photo = asset($user->photo);
        $response = [
            'success' => true,
            'data' => [
                'id' => $user->id,
                'display_name' => $user->name,
                'user_nicename' => '',
                'user_photo' => @$user_photo,
                'user_url' => 'url',
                'user_level' => 'level',
                'description' => 'user description',
                'tag' => 'user tag',
                'rate' => 'user rate',
                'token' => $user->token,
                'user_email' => $user->email
            ]
        ];
        return response($response, 200);
    }

    public function addresturant(Request $request)
    {
        $user = $request->user();
        $restorant = Restorant::first();

        $user->wishListResturants()->save($restorant);


        return response(200);
    }

    public function changePassword(Request $request)
    {
        $user = $request->user();
        if ($user) {
            $fields = $request->validate([
                'password' => 'required|string'
            ]);
            $newPassword = bcrypt($fields['password']);
            $user->password = $newPassword;
            $user->save();
            return response([
                'success' => true,
                'data' => []
            ], 200);
        }
    }
}
