<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Carbon\Carbon;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    private function sendToken($token)
    {
        if ($token) {
            return response()->json([
                'token' => $token,
            ], Response::HTTP_OK);
        } else {
            return response()->json([
                'error' => 'Could not authenticate',
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    public function register(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name' => 'required|min:3',
            'email' => 'required|email|unique:users',
            'password'  => 'required|min:6',
        ]);
        if ($v->fails()) {
            return response()->json([
                'errors' => $v->errors()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $user = new User;
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();
        return response(null, Response::HTTP_OK);
    }

    public function login(Request $request)
    {
        // $credentials = $request->only('email', 'password');
        // if ($token = $this->guard()->attempt($credentials)) {
        //     return response()->json(['status' => 'success'], Response::HTTP_OK)->header('Authorization', $token);
        // }
        // return response()->json(['error' => 'login_error'], 401);

        //Another version. Error checking with try...catch is possible
        $credentials = $request->only('email', 'password');
        try {
            $token = JWTAuth::attempt($credentials);
        } catch (JWTException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->sendToken($token);
    }

    public function logout()
    {
        $this->guard()->logout();
        return response()->json([
            'msg' => 'Logged out Successfully.'
        ], Response::HTTP_OK);
    }

    public function user()
    {
        // $user = User::find(Auth::id());
        // return response($user->jsonSerialize(), Response::HTTP_OK);

        // We can get user and all his dependencies without filtering entire table
        return response(Auth::user(), Response::HTTP_OK);
    }

    public function refresh()
    {
        // if ($token = $this->guard()->refresh()) {
        //     return response()
        //         ->json(['status' => 'success'], Response::HTTP_OK)
        //         ->header('Authorization', $token);
        // }
        // return response()->json(['error' => 'refresh_token_error'], 401);

        try {
            $token = $this->guard()->refresh();
        } catch (JWTException $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ], Response::HTTP_BAD_REQUEST);
        }

        return $this->sendToken($token);
    }

    private function guard()
    {
        return Auth::guard();
    }
}