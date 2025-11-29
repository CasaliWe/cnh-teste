<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateNameRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            // Atualizar a senha diretamente
            /** @var User $user */
            $user = Auth::user();
            $user->password = Hash::make($request->validated()['password']);
            $user->save();

            Log::info('Senha atualizada com sucesso', ['user_id' => $user->id]);

            return redirect()->route('profile')
                ->with('success', 'Senha atualizada com sucesso!');

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
