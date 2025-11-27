<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use App\Models\User;
use App\Mail\NewUserPassword;
use App\Mail\NewUserPasswordReset;


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

        // Verifica se o usuário existe pelo e-mail
        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Esse e-mail não está cadastrado em nossos registros.',
            ])->onlyInput('email');
        }

        // Verifica se a senha confere
        if (!\Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'password' => 'A senha informada está incorreta.',
            ])->onlyInput('email');
        }

        // Se passou pelas verificações, tenta autenticar normalmente
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        // Fallback (caso raro)
        return back()->withErrors([
            'email' => 'Não foi possível autenticar. Tente novamente.',
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


    // Mostrar o formulário de recuperação de senha
    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    
    // Processar solicitação de recuperação de senha
    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Por favor, digite um e-mail válido.',
        ]);

        // Verifica se o e-mail existe no banco de dados
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            return back()->withErrors([
                'email' => 'Este e-mail não está cadastrado em nosso sistema.',
            ])->onlyInput('email');
        }

        // Gerar nova senha aleatória de 8 caracteres
        $newPassword = Str::random(8);

        // Atualizar a senha do usuário no banco de dados
        $user->password = Hash::make($newPassword);
        $user->save();

        // Enviar a nova senha por e-mail
        Mail::to($user->email)->send(new NewUserPasswordReset($user, $newPassword));

        // Retornar uma mensagem de sucesso
        return back()->with('status', 'Enviamos uma nova senha para seu seu e-mail!');
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

    // Registrar novo usuário via POST (req do Kirvano)
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


    // login com google redirect
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    // login com google callback
    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        // Tenta encontrar usuário pelo e-mail
        $user = User::where('email', $googleUser->getEmail())->first();

        // Se não existir devolve erro avisando que o usuário não está cadastrado
        if (!$user) {
            return redirect('/login')->withErrors([
                'email' => 'Esse e-mail não está cadastrado em nossos registros.',
            ])->onlyInput('email');
        }

        // Autentica o usuário
        Auth::login($user, true);

        return redirect()->intended('/');
    }
}