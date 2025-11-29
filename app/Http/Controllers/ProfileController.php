<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateNameRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PasswordUpdatedNotification;

class ProfileController extends Controller
{
    // Mostrar o perfil
    public function profile()
    {
        return view('client.profile');
    }

    // Atualizar nome do usuário
    public function updateName(UpdateNameRequest $request)
    {
        try {
            // Atualizar o nome
            /** @var User $user */
            $user = Auth::user();
            $user->name = $request->validated()['name'];
            $user->save();

            Log::info('Nome atualizado com sucesso', [
                'user_id' => $user->id,
                'novo_nome' => $request->validated()['name']
            ]);

            return redirect()->route('profile')
                ->with('success', 'Nome atualizado com sucesso!');

        } catch (\Exception $e) {
            Log::error('Erro ao atualizar nome', [
                'user_id' => Auth::id(),
                'erro' => $e->getMessage()
            ]);

            return redirect()->route('profile')
                ->with('error', 'Erro ao atualizar nome. Tente novamente.');
        }
    }

    // Atualizar senha do usuário
    public function updatePassword(UpdatePasswordRequest $request)
    {
        try {
            /** @var User $user */
            $user = Auth::user();
            
            DB::transaction(function () use ($user, $request) {
                // Atualizar a senha
                $user->password = Hash::make($request->validated()['password']);
                $user->save();
                
                // Invalidar todas as sessões ativas exceto a atual
                // Regenera a session_id da sessão atual mantendo o usuário logado
                $currentSessionId = $request->session()->getId();
                
                // Invalida outras sessões (Laravel guarda hash da session_id no remember_token quando há remember_me)
                // Para invalidar outras sessões precisamos limpar o remember_token se existir
                if ($user->remember_token) {
                    $user->remember_token = null;
                    $user->save();
                }
                
                // Regenera a sessão atual para manter o usuário logado com nova session_id
                $request->session()->regenerate();
                
                // Enviar email de notificação
                Mail::to($user->email)->send(new PasswordUpdatedNotification($user));
            });

            Log::info('Senha atualizada com sucesso', ['user_id' => $user->id]);

            return redirect()->route('profile')
                ->with('success', 'Senha atualizada com sucesso! Por segurança, suas outras sessões foram invalidadas e um email de confirmação foi enviado.');

        } catch (\Exception $e) {
            Log::error('Erro ao atualizar senha', [
                'user_id' => Auth::id(),
                'erro' => $e->getMessage()
            ]);

            return redirect()->route('profile')
                ->with('error', 'Erro ao atualizar senha. Tente novamente.');
        }
    }
}
