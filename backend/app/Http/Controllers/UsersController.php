<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Response;

class UsersController extends Controller
{
    public function index()
    {
        return response(User::all()->jsonSerialize(), Response::HTTP_OK);
    }
}
