@extends('layouts.app')

@section('title', 'Dashboard - ' . config('app.name'))

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

            <!-- Dashboard Stats -->
            <div class="bg-white rounded-lg p-4 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Dashboard</h3>
                
                <div class="grid grid-cols-3 gap-4 mb-6">
                    <!-- Perguntas feitas -->
                    <div class="text-center">
                        <div class="text-2xl font-bold text-gray-800 mb-1">284</div>
                        <div class="text-xs text-gray-600">Perguntas feitas</div>
                    </div>
                    
                    <!-- Respostas certas -->
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-500 mb-1">130</div>
                        <div class="text-xs text-green-600">Respostas certas</div>
                    </div>
                    
                    <!-- Respostas erradas -->
                    <div class="text-center">
                        <div class="text-2xl font-bold text-red-500 mb-1">154</div>
                        <div class="text-xs text-red-600">Respostas erradas</div>
                    </div>
                </div>

                <!-- Progress Section -->
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm text-gray-600 font-medium">Objetivo</span>
                        <span class="text-sm text-green-600 font-semibold">Meta de 80%</span>
                    </div>
                    
                    <!-- Progress Bar -->
                    <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                        <div class="bg-gradient-to-r from-orange-400 to-yellow-400 h-3 rounded-full" style="width: 45%"></div>
                    </div>
                    
                    <div class="text-center">
                        <p class="text-sm font-bold text-orange-500">Taxa de acerto</p>
                        <span class="text-2xl font-bold text-orange-500">45%</span>
                    </div>
                </div>

                <!-- Start Button -->
                <a href="{{ route('start') }}" class="block w-full text-black font-semibold py-3 rounded-lg transition-colors text-center shadow-md" style="background-color: #facc15 !important;">
                    Iniciar novo Simulado
                </a>
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
                    <div class="bg-white rounded-xl p-8 w-full max-w-xl shadow-xl">
                        <h1 class="text-3xl font-bold text-gray-800 mb-8 text-center">Dashboard</h1>
                        
                        <div class="grid grid-cols-3 gap-6 mb-10">
                            <!-- Perguntas feitas -->
                            <div class="text-center">
                                <div class="text-4xl font-bold text-gray-700 mb-2">284</div>
                                <div class="text-sm text-gray-500 leading-tight">Perguntas feitas</div>
                            </div>
                            
                            <!-- Respostas certas -->
                            <div class="text-center">
                                <div class="text-4xl font-bold text-green-500 mb-2">130</div>
                                <div class="text-sm text-green-600 leading-tight">Respostas certas</div>
                            </div>
                            
                            <!-- Respostas erradas -->
                            <div class="text-center">
                                <div class="text-4xl font-bold text-red-500 mb-2">154</div>
                                <div class="text-sm text-red-600 leading-tight">Respostas erradas</div>
                            </div>
                        </div>

                        <!-- Progress Section -->
                        <div class="mb-8">
                            <div class="flex justify-between items-center mb-3">
                                <span class="text-sm text-gray-600 font-medium">Objetivo</span>
                                <span class="text-sm text-green-600 font-semibold">Meta de 80%</span>
                            </div>
                            
                            <!-- Progress Bar -->
                            <div class="w-full bg-gray-200 rounded-full h-4 mb-4 overflow-hidden">
                                <div class="bg-gradient-to-r from-orange-400 to-yellow-400 h-4 rounded-full transition-all duration-300" style="width: 45%"></div>
                            </div>
                            
                            <div class="text-center">
                                <p class="text-sm font-bold text-orange-500">Taxa de acerto</p>
                                <span class="text-3xl font-bold text-orange-500">45%</span>
                            </div>
                        </div>

                        <!-- Start Button -->
                        <a href="{{ route('start') }}" class="block w-full text-black font-bold py-4 rounded-xl transition-all duration-200 text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 text-center" style="background-color: #facc15 !important;">
                            Iniciar novo Simulado
                        </a>
                    </div>
                </div>
            </div>
            <!-- Right Section -->
        </div>
    </div>
    <!-- Desktop Layout -->
</div>
@endsection