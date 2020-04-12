<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    public function index()
    {
        return response(User::all()->jsonSerialize(), Response::HTTP_OK);
    }

    public function update(Request $request)
    {
        Auth::user()->name = $request->name;
        return response(Auth::user()->jsonSerialize(), Response::HTTP_OK);
    }

    public function delete()
    {
        User::destroy(Auth::user()->id);
        return response(null, Response::HTTP_OK);
    }
}
