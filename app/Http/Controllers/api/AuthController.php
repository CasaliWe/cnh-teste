<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Mail\NewUserPassword;

class AuthController extends Controller
{
    // Registrar novo usuário via POST (req do Kirvano)
    public function register(RegisterRequest $request)
    {
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
