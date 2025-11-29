<?php

namespace App\Http\Controllers;

use App\Models\Simulado;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SimuladoController extends Controller
{
    // Mostrar a página do simulado
    public function mostrar($id)
    {
        try {
            // Validar formato do identificador (8 caracteres alfanuméricos)
            if (!preg_match('/^[a-zA-Z0-9]{8}$/', $id)) {
                Log::warning('Identificador de simulado inválido', [
                    'identificador' => $id,
                    'user_id' => Auth::id()
                ]);
                
                return redirect()->route('dashboard')
                    ->with('error', 'Identificador de simulado inválido.');
            }

            // Buscar simulado pelo identificador e verificar se pertence ao usuário autenticado
            $simulado = Simulado::where('identificador', $id)
                ->where('user_id', Auth::id())
                ->with('questoes')
                ->first();

            // Se simulado não existe ou não pertence ao usuário, redireciona para dashboard
            if (!$simulado) {
                Log::warning('Acesso negado ao simulado', [
                    'identificador' => $id,
                    'user_id' => Auth::id(),
                    'motivo' => 'Simulado não encontrado ou não pertence ao usuário'
                ]);
                
                return redirect()->route('dashboard')
                    ->with('error', 'Simulado não encontrado ou você não tem permissão para acessá-lo.');
            }

            // Passar simulado com questões para a view
            return view('simulado.index', ['simulado' => $simulado->questoes]);
            
        } catch (\Exception $e) {
            Log::error('Erro ao carregar simulado', [
                'identificador' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('dashboard')
                ->with('error', 'Erro ao carregar simulado. Tente novamente.');
        }
    }
}
