<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    // Mostrar o dashboard
    public function dashboard()
    {
        return view('dashboard.index');
    }
}
