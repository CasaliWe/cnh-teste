<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Mail\NewUserPassword;


class AuthController extends Controller
{
    // Mostrar o formulário de login
    public function showLoginForm()
    {
        // Se o usuário já estiver logado, redirecionar para dashboard
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        
        return view('auth.login');
    }


    // Processar o login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas não conferem com nossos registros.',
        ])->onlyInput('email');
    }


    // Processar o logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }


    // Mostrar o dashboard
    public function dashboard()
    {
        return view('dashboard.index');
    }


    // Mostrar o perfil
    public function profile()
    {
        return view('client.profile');
    }

    // Registrar novo usuário via POST
    public function register(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'unique:users'],
        ]);

        // Gerar senha aleatória de 8 caracteres
        $randomPassword = Str::random(8);

        // Criar o usuário
        $user = User::create([
            'name' => explode('@', $request->email)[0], // Usar a parte antes do @ como nome
            'email' => $request->email,
            'password' => Hash::make($randomPassword),
        ]);

        // Enviar email com a senha
        try {
            Mail::to($user->email)->send(new NewUserPassword($user, $randomPassword));
            
            return response()->json([
                'message' => 'Usuário criado com sucesso! A senha foi enviada para o email.',
                'user_id' => $user->id
            ], 201);
        } catch (\Exception $e) {
            // Se falhar o envio do email, deletar o usuário criado
            $user->delete();
            
            return response()->json([
                'message' => 'Erro ao enviar email. Tente novamente.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}