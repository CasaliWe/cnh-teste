@extends('layouts.app')

@section('title', 'Start simulado - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#4A5568] via-[#5A6B7D] to-[#6B7A92]">
    <!-- Mobile Layout -->
    <div class="lg:hidden">
        <!-- Top Header -->
        <div class="bg-[#4A5568] px-5 py-6 flex items-start justify-between">
            <div class="text-left">
                <div class="text-xs text-gray-300">Bem vindo,</div>
                <div class="text-sm font-semibold text-white">{{ Auth::user()->name }}</div>
            </div>
            <div class="flex items-center space-x-2">
                <!-- Ícone de Perfil -->
                <a href="{{ route('profile') }}" class="p-1.5 text-gray-300 hover:text-blue-400 rounded transition-colors" title="Perfil">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                </a>
                <!-- Ícone de Logout -->
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="p-1.5 text-gray-300 hover:text-red-400 rounded transition-colors" title="Sair">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="p-4">
            <!-- Logo and Character Section -->
            <div class="mb-0 flex flex-col items-center">
                <img src="{{ asset('logo.png') }}" alt="CNH sem segredo" class="w-48 h-auto">
            </div>

            <!-- Dynamic Text Block -->
            <div class="bg-white rounded-lg p-4 mb-6 min-h-[120px] flex flex-col justify-center">
                <h4 class="text-yellow-600 font-semibold text-base mb-2 text-center">Motora diz:</h4>
                <p class="text-gray-800 text-sm leading-relaxed text-center">
                    Aqui estão seus números. Continue melhorando, a taxa recomendada é 80% para ir para prova mais tranquilo.
                </p>
            </div>

            <!-- Simulado Selection -->
            <div class="bg-white rounded-lg px-6 py-10 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Iniciar Simulado</h3>
                
                <!-- Cards de dificuldade - Mobile (vertical) -->
                <div class="space-y-3 mb-6">
                    <!-- Card Fácil -->
                    <div id="card-facil-mobile" class="difficulty-card bg-yellow-400 rounded-lg p-4 cursor-pointer" data-difficulty="facil">
                        <h3 class="text-base font-bold text-black mb-2 text-center">Fácil</h3>
                        <div class="text-xs text-black text-center space-y-1 mb-4 leading-tight">
                            <div>Sem timer</div>
                            <div>Com respostas certas imediatamente</div>
                            <div>Com dicas</div>
                        </div>
                        <div class="text-center">
                            <button class="card-button bg-black text-yellow-400 px-3 py-1 rounded-full text-xs font-semibold">
                                SELECIONADO
                            </button>
                        </div>
                    </div>
                    
                    <!-- Card Médio -->
                    <div id="card-medio-mobile" class="difficulty-card bg-yellow-400 bg-opacity-30 rounded-lg p-4 opacity-50 cursor-pointer" data-difficulty="medio">
                        <h3 class="text-base font-bold text-gray-600 mb-2 text-center">Médio</h3>
                        <div class="text-xs text-gray-500 text-center space-y-1 mb-4 leading-tight">
                            <div>Sem timer</div>
                            <div>Com respostas certas após cada sessão</div>
                            <div>Sem dicas</div>
                        </div>
                        <div class="text-center">
                            <button class="card-button border border-gray-400 text-gray-600 px-3 py-1 rounded-full text-xs font-semibold bg-transparent">
                                SELECIONAR
                            </button>
                        </div>
                    </div>
                    
                    <!-- Card Difícil -->
                    <div id="card-dificil-mobile" class="difficulty-card bg-yellow-400 bg-opacity-30 rounded-lg p-4 opacity-50 cursor-pointer" data-difficulty="dificil">
                        <h3 class="text-base font-bold text-gray-600 mb-2 text-center">Difícil</h3>
                        <div class="text-xs text-gray-500 text-center space-y-1 mb-4 leading-tight">
                            <div>Com timer</div>
                            <div>Com respostas certas só no final</div>
                            <div>Sem dicas</div>
                        </div>
                        <div class="text-center">
                            <button class="card-button border border-gray-400 text-gray-600 px-3 py-1 rounded-full text-xs font-semibold bg-transparent">
                                SELECIONAR
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Start Button -->
                <button id="btn-comecar-simulado-mobile" class="block w-full text-black font-semibold py-3 rounded-lg transition-colors text-center shadow-md" style="background-color: #facc15 !important;">
                    <span class="btn-text">Começar Simulado</span>
                    <span class="btn-loading hidden">
                        <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-black inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Criando simulado...
                    </span>
                </button>

                <!-- Botões Mobile -->
                <div class="flex justify-center items-center mt-6">
                    <span class="text-xs text-gray-500">Refazer último simulado</span>
                </div>
            </div>
        </div>
    </div>
    <!-- Mobile Layout -->

    <!-- Desktop Layout -->
    <div class="hidden lg:block">
        <!-- Main Content -->
        <div class="flex min-h-screen">
            <!-- Left Section -->
            <div class="w-1/2 p-8 flex flex-col justify-center items-center">
                <!-- Logo and Character (sem espaçamento com o bloco) -->
                <div class="mb-0">
                    <img src="{{ asset('logo.png') }}" alt="CNH sem segredo" class="w-[400px] h-auto">
                </div>

                <!-- Dynamic Text Block -->
                <div class="bg-white rounded-lg p-6 max-w-md min-h-[160px] flex flex-col justify-center shadow-lg">
                    <h4 class="text-yellow-600 font-semibold text-[28px] mb-3">Motora diz:</h4>
                    <p class="text-gray-800 text-[25px] leading-relaxed text-left">
                        Aqui estão seus números. Continue melhorando, a taxa recomendada é 80% para ir para prova mais tranquilo.
                    </p>
                </div>
            </div>
            <!-- Left Section -->


            <!-- Right Section -->
            <div class="w-1/2 bg-gray-200 p-8">
                <!-- Header da seção direita (fora do bloco) -->
                <div class="flex items-start justify-between mb-12">
                    <div class="text-left">
                        <div class="text-base text-gray-600">Bem vindo,</div>
                        <div class="text-lg font-semibold text-gray-800">{{ Auth::user()->name }}</div>
                    </div>
                    <div class="flex items-center space-x-3">
                        <!-- Ícone de Perfil -->
                        <a href="{{ route('profile') }}" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Perfil">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </a>
                        <!-- Ícone de Logout -->
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Sair">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Bloco dos dados -->
                <div class="mt-[15%] flex justify-center">
                    <div class="bg-white rounded-lg p-8 max-w-2xl w-full shadow-lg">
                        <!-- Título -->
                        <h2 class="text-lg font-medium text-gray-700 mb-6">Iniciar Simulado</h2>
                        
                        <!-- Cards de dificuldade - 3 lado a lado -->
                        <div class="grid grid-cols-3 gap-4 mb-8">
                            <!-- Card Fácil -->
                            <div id="card-facil" class="difficulty-card bg-yellow-400 rounded-lg p-4 cursor-pointer" data-difficulty="facil">
                                <h3 class="text-lg font-bold text-black mb-4 text-center">Fácil</h3>
                                <div class="text-xs text-black text-center space-y-2 mb-6 leading-tight">
                                    <div>Sem timer</div>
                                    <div>Com respostas certas imediatamente</div>
                                    <div>Com dicas</div>
                                </div>
                                <div class="text-center">
                                    <button class="card-button bg-black text-yellow-400 px-3 py-1 rounded-full text-xs font-semibold">
                                        SELECIONADO
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Card Médio -->
                            <div id="card-medio" class="difficulty-card bg-yellow-400 bg-opacity-30 rounded-lg p-4 opacity-50 cursor-pointer" data-difficulty="medio">
                                <h3 class="text-lg font-bold text-gray-600 mb-4 text-center">Médio</h3>
                                <div class="text-xs text-gray-500 text-center space-y-2 mb-6 leading-tight">
                                    <div>Sem timer</div>
                                    <div>Com respostas certas após cada sessão</div>
                                    <div>Sem dicas</div>
                                </div>
                                <div class="text-center">
                                    <button class="card-button border border-gray-400 text-gray-600 px-3 py-1 rounded-full text-xs font-semibold bg-transparent">
                                        SELECIONAR
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Card Difícil -->
                            <div id="card-dificil" class="difficulty-card bg-yellow-400 bg-opacity-30 rounded-lg p-4 opacity-50 cursor-pointer" data-difficulty="dificil">
                                <h3 class="text-lg font-bold text-gray-600 mb-4 text-center">Difícil</h3>
                                <div class="text-xs text-gray-500 text-center space-y-2 mb-6 leading-tight">
                                    <div>Com timer</div>
                                    <div>Com respostas certas só no final</div>
                                    <div>Sem dicas</div>
                                </div>
                                <div class="text-center">
                                    <button class="card-button border border-gray-400 text-gray-600 px-3 py-1 rounded-full text-xs font-semibold bg-transparent">
                                        SELECIONAR
                                    </button>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Botões na parte inferior -->
                        <div class="flex justify-between items-center">
                            <!-- Texto esquerdo -->
                            <span class="text-xs text-gray-500">Refazer último simulado</span>
                            
                            <!-- Botão direito -->
                            <button id="btn-comecar-simulado" class="text-black font-semibold py-3 px-6 rounded-lg transition-colors text-sm shadow-md" style="background-color: #facc15 !important;">
                                <span class="btn-text">Começar Simulado</span>
                                <span class="btn-loading hidden">
                                    <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-black inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Criando simulado...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Right Section -->
        </div>
    </div>
    <!-- Desktop Layout -->
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.difficulty-card');
    const btnComecarSimulado = document.getElementById('btn-comecar-simulado');
    const btnComecarSimuladoMobile = document.getElementById('btn-comecar-simulado-mobile');
    let selectedDifficulty = 'facil'; // Padrão é fácil
    
    cards.forEach(card => {
        card.addEventListener('click', function() {
            // Atualiza a dificuldade selecionada
            selectedDifficulty = this.dataset.difficulty;
            
            // Remove seleção de todos os cards (desktop e mobile)
            cards.forEach(c => {
                c.classList.remove('bg-yellow-400');
                c.classList.add('bg-yellow-400', 'bg-opacity-30', 'opacity-50');
                
                // Atualiza título para cinza
                const title = c.querySelector('h3');
                title.classList.remove('text-black');
                title.classList.add('text-gray-600');
                
                // Atualiza texto para cinza
                const textDiv = c.querySelector('.text-xs');
                textDiv.classList.remove('text-black');
                textDiv.classList.add('text-gray-500');
                
                // Atualiza botão para não selecionado
                const button = c.querySelector('.card-button');
                button.classList.remove('bg-black', 'text-yellow-400');
                button.classList.add('border', 'border-gray-400', 'text-gray-600', 'bg-transparent');
                button.textContent = 'SELECIONAR';
            });
            
            // Seleciona o card clicado
            this.classList.remove('bg-opacity-30', 'opacity-50');
            this.classList.add('bg-yellow-400');
            
            // Atualiza título para preto
            const title = this.querySelector('h3');
            title.classList.remove('text-gray-600');
            title.classList.add('text-black');
            
            // Atualiza texto para preto
            const textDiv = this.querySelector('.text-xs');
            textDiv.classList.remove('text-gray-500');
            textDiv.classList.add('text-black');
            
            // Atualiza botão para selecionado
            const button = this.querySelector('.card-button');
            button.classList.remove('border', 'border-gray-400', 'text-gray-600', 'bg-transparent');
            button.classList.add('bg-black', 'text-yellow-400');
            button.textContent = 'SELECIONADO';
            
            // Sincroniza seleção entre desktop e mobile
            const targetDifficulty = this.dataset.difficulty;
            cards.forEach(c => {
                if (c.dataset.difficulty === targetDifficulty) {
                    c.classList.remove('bg-opacity-30', 'opacity-50');
                    c.classList.add('bg-yellow-400');
                    
                    const title = c.querySelector('h3');
                    title.classList.remove('text-gray-600');
                    title.classList.add('text-black');
                    
                    const textDiv = c.querySelector('.text-xs');
                    textDiv.classList.remove('text-gray-500');
                    textDiv.classList.add('text-black');
                    
                    const button = c.querySelector('.card-button');
                    button.classList.remove('border', 'border-gray-400', 'text-gray-600', 'bg-transparent');
                    button.classList.add('bg-black', 'text-yellow-400');
                    button.textContent = 'SELECIONADO';
                }
            });
        });
    });
    
    // Função para criar simulado
    async function criarSimulado(button) {
        // Mostra loading e desabilita botão
        button.disabled = true;
        button.querySelector('.btn-text').classList.add('hidden');
        button.querySelector('.btn-loading').classList.remove('hidden');
        
        try {
            // Faz requisição para criar o simulado ************************************
            const response = await fetch('api/criar-simulado', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    dificuldade: selectedDifficulty,
                    user: {{ Auth::id() }}
                })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Redireciona para a página do simulado
                window.location.href = `/simulado/${data.simulado_id}`;
            } else {
                throw new Error(data.message || 'Erro ao criar simulado');
            }
            
        } catch (error) {
            console.error('Erro:', error);
            alert('Erro ao criar simulado. Tente novamente.');
            
            // Remove loading e reabilita botão
            button.disabled = false;
            button.querySelector('.btn-text').classList.remove('hidden');
            button.querySelector('.btn-loading').classList.add('hidden');
        }
    }
    
    // Event listener para o botão desktop "Começar Simulado"
    if (btnComecarSimulado) {
        btnComecarSimulado.addEventListener('click', function() {
            criarSimulado(this);
        });
    }
    
    // Event listener para o botão mobile "Começar Simulado"
    if (btnComecarSimuladoMobile) {
        btnComecarSimuladoMobile.addEventListener('click', function() {
            criarSimulado(this);
        });
    }
});
</script>
@endsection