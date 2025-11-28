<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    // Mostrar o dashboard
    public function dashboard()
    {
        return view('dashboard.index');
    }
}
