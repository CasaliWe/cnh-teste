<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class DashboardController extends Controller
{
    // Mostrar o dashboard
    public function dashboard()
    {
        return view('dashboard.index');
    }

    // Mostrar o start
    public function start()
    {
        return view('dashboard.start');
    }
}
