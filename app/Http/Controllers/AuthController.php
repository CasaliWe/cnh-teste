<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\User;
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
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            Log::info('Login realizado', ['email' => $credentials['email'], 'ip' => $request->ip()]);
            $request->session()->regenerate();
            return redirect()->intended('/');
        }

        Log::warning('Tentativa de login falhou', ['email' => $credentials['email'], 'ip' => $request->ip()]);
        return back()->withErrors([
            'email' => 'As credenciais fornecidas não conferem com nossos registros.',
        ])->onlyInput('email');
    }



    // Processar o logout
    public function logout(Request $request)
    {
        Log::info('Logout realizado', ['user_id' => Auth::id(), 'ip' => $request->ip()]);
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
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        // Verifica se o e-mail existe no banco de dados
        $user = User::where('email', $request->email)->first();
        
        if (!$user) {
            Log::warning('Tentativa recuperação senha email inexistente', ['email' => $request->email, 'ip' => $request->ip()]);
            return back()->withErrors([
                'email' => 'Este e-mail não está cadastrado em nosso sistema.',
            ])->onlyInput('email');
        }

        try {
            DB::transaction(function () use ($user) {
                // Gerar nova senha aleatória de 8 caracteres
                $newPassword = Str::random(8);

                // Atualizar a senha do usuário no banco de dados
                $user->update(['password' => Hash::make($newPassword)]);

                // Enviar a nova senha por e-mail
                Mail::to($user->email)->send(new NewUserPasswordReset($user, $newPassword));
            });

            Log::info('Recuperação de senha processada', ['email' => $request->email, 'ip' => $request->ip()]);
            // Retornar uma mensagem de sucesso
            return back()->with('status', 'Enviamos uma nova senha para seu e-mail!');
        } catch (\Exception $e) {
            Log::error('Erro na recuperação de senha', ['email' => $user->email, 'error' => $e->getMessage()]);
            // Se falhar, a transaction já fez rollback automaticamente
            return back()->withErrors([
                'email' => 'Erro ao processar sua solicitação. Tente novamente.',
            ])->onlyInput('email');
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
        $email = is_object($googleUser) && property_exists($googleUser, 'email') ? $googleUser->email : $googleUser->getEmail();
        $user = User::where('email', $email)->first();

        // Se não existir devolve erro avisando que o usuário não está cadastrado
        if (!$user) {
            Log::warning('Login Google falhou - email não cadastrado', ['email' => $email]);
            return redirect('/login')->withErrors([
                'email' => 'Esse e-mail não está cadastrado em nossos registros.',
            ])->onlyInput('email');
        }

        // Autentica o usuário
        Auth::login($user, true);
        Log::info('Login Google realizado', ['email' => $email, 'user_id' => $user->id]);

        return redirect()->intended('/');
    }
}