<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    // Mostrar o perfil
    public function profile()
    {
        return view('client.profile');
    }
}
