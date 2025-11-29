<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestoesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questoes = [
            [
                'imagem' => 'placa_pare.png',
                'pergunta' => 'O que significa esta placa de trânsito?',
                'opcoes' => [
                    'a' => 'Diminua a velocidade',
                    'b' => 'Pare obrigatório',
                    'c' => 'Dê preferência',
                    'd' => 'Proibido estacionar'
                ],
                'resposta_certa' => 'b',
                'dica' => 'Placa vermelha octogonal sempre significa parada obrigatória'
            ],
            [
                'imagem' => 'semaforo.png',
                'pergunta' => 'Em um semáforo, qual a sequência correta das cores?',
                'opcoes' => [
                    'a' => 'Verde, Amarelo, Vermelho',
                    'b' => 'Vermelho, Amarelo, Verde',
                    'c' => 'Verde, Vermelho, Amarelo',
                    'd' => 'Amarelo, Verde, Vermelho'
                ],
                'resposta_certa' => 'a',
                'dica' => 'Verde para seguir, amarelo para atenção, vermelho para parar'
            ],
            [
                'imagem' => null,
                'pergunta' => 'Qual a velocidade máxima permitida em vias urbanas?',
                'opcoes' => [
                    'a' => '40 km/h',
                    'b' => '50 km/h',
                    'c' => '60 km/h',
                    'd' => '70 km/h'
                ],
                'resposta_certa' => 'c',
                'dica' => 'Nas cidades, a velocidade padrão é 60 km/h'
            ]
        ];

        foreach ($questoes as $questao) {
            \App\Models\Questao::create($questao);
        }
    }
}
