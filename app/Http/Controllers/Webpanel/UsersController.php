<?php

namespace App\Http\Controllers\Webpanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index()
    {
        return view('pages.users.users-layout-1');
    }
    public function usersLayout2()
    {
        return view('pages.users.users-layout-2');
    }
    public function usersLayout3()
    {
        return view('pages.users.users-layout-3');
    }

}
