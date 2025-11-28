<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{
    // Mostrar o perfil
    public function profile()
    {
        return view('client.profile');
    }
}
