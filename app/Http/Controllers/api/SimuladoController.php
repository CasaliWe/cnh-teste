<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\api\CriarSimuladoRequest;
use App\Models\Questao;
use App\Models\Simulado;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SimuladoController extends Controller
{
    // Criar um simulado
    public function criar(CriarSimuladoRequest $request)
    {
        try {
            $dados = $request->validated();
            
            Log::info('Iniciando criação de simulado', [
                'user_id' => $dados['user'],
                'dificuldade' => $dados['dificuldade'],
                'ip' => $request->ip()
            ]);

            $simulado = DB::transaction(function () use ($dados) {
                // Gerar identificador único de 8 caracteres
                do {
                    $identificador = Str::random(8);
                } while (Simulado::where('identificador', $identificador)->exists());

                // Criar o simulado
                $simulado = Simulado::create([
                    'user_id' => $dados['user'],
                    'identificador' => $identificador,
                    'dificuldade' => $dados['dificuldade']
                ]);

                // Pegar questões aleatórias (30 questões)
                $questoes = Questao::inRandomOrder()->limit(30)->get();

                // Associar questões ao simulado
                $questoesIds = $questoes->pluck('id')->toArray();
                $simulado->questoes()->attach($questoesIds);

                return $simulado;
            });

            Log::info('Simulado criado com sucesso', [
                'simulado_id' => $simulado->id,
                'identificador' => $simulado->identificador,
                'user_id' => $dados['user'],
                'total_questoes' => 30
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Simulado criado com sucesso',
                'simulado_id' => $simulado->identificador
            ], 201);
            
        } catch (\Exception $e) {
            Log::error('Erro ao criar simulado', [
                'user_id' => $request->input('user'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro interno do servidor. Tente novamente.'
            ], 500);
        }
    }
}
