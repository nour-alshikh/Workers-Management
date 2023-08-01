<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:clients', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');
        $token = auth()->guard('clients')->attempt($credentials);

        if (!$token) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 401);
        }

        $client =  auth()->guard('clients')->user();
        return response()->json([
            'client' => $client,
            'authorization' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:clients',
            'password' => 'required|string|min:6',
            'photo' => 'required|mimes:jpg,bmp,png,jpeg',
        ]);

        $photo = $request->file('photo');

        $fileName = 'Client-' . uniqid() . "." . $photo->getClientOriginalExtension();

        $photo->storeAs("Images", $fileName, "Media");

        $client = Client::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'photo' => $fileName,
        ]);



        return response()->json([
            'message' => 'Client created successfully',
            'client' => $client
        ]);
    }

    public function logout()
    {
        auth('clients')->logout();
        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'client' =>  auth('clients')->user(),
            'authorisation' => [
                'token' =>  auth('clients')->refresh(),
                'type' => 'bearer',
            ]
        ]);
    }
}
