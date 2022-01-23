<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $page_title = 'Manajemen Pengguna';
        $page_description = 'Manajemen pengguna';

        $users = User::all();

        return view('user', compact('users', 'page_title', 'page_description'));
    }
}
