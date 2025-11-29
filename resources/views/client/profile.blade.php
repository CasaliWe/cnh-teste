@extends('layouts.app')

@section('title', 'Perfil - ' . config('app.name'))

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
                <!-- Ícone de Dashboard -->
                <a href="{{ route('dashboard') }}" class="p-1.5 text-gray-300 hover:text-blue-400 rounded transition-colors" title="Dashboard">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m3 12 2-2m0 0 7-7 7 7M5 10v10a1 1 0 0 0 1 1h3m0 0a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1m-6 0h6" />
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

            <!-- Perfil Forms -->
            <div class="space-y-4">
                <!-- Editar Nome -->
                <div class="bg-white rounded-lg p-4">
                    <h3 class="text-base font-semibold text-gray-800 mb-3">Editar Nome</h3>
                    <form>
                        <div class="mb-3">
                            <label for="name_mobile" class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                            <input type="text" id="name_mobile" name="name" value="{{ Auth::user()->name }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent text-sm">
                        </div>
                        <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-black font-semibold py-2.5 rounded-lg transition-colors text-sm">
                            Atualizar Nome
                        </button>
                    </form>
                </div>

                <!-- Atualizar Senha -->
                <div class="bg-white rounded-lg p-4">
                    <h3 class="text-base font-semibold text-gray-800 mb-3">Atualizar Senha</h3>
                    <form>
                        <div class="mb-3">
                            <label for="password_mobile" class="block text-sm font-medium text-gray-700 mb-1">Nova Senha</label>
                            <input type="password" id="password_mobile" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent text-sm">
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation_mobile" class="block text-sm font-medium text-gray-700 mb-1">Confirmar Senha</label>
                            <input type="password" id="password_confirmation_mobile" name="password_confirmation" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent text-sm">
                        </div>
                        <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-black font-semibold py-2.5 rounded-lg transition-colors text-sm">
                            Atualizar Senha
                        </button>
                    </form>
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
                        <!-- Ícone de Dashboard -->
                        <a href="{{ route('dashboard') }}" class="p-2 text-gray-600 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Dashboard">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m3 12 2-2m0 0 7-7 7 7M5 10v10a1 1 0 0 0 1 1h3m0 0a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1m-6 0h6" />
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

                <!-- Bloco do perfil -->
                <div class="mt-[15%] flex justify-center">
                    <div class="bg-white rounded-xl p-6 w-full max-w-lg shadow-xl space-y-6">
                        <h1 class="text-2xl font-bold text-gray-800 mb-6 text-center">Perfil</h1>
                        
                        <!-- Editar Nome -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Editar Nome</h3>
                            <form>
                                <div class="mb-4">
                                    <label for="name_desktop" class="block text-sm font-medium text-gray-700 mb-2">Nome</label>
                                    <input type="text" id="name_desktop" name="name" value="{{ Auth::user()->name }}" class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                                </div>
                                <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-black font-semibold py-3 rounded-lg transition-colors shadow-md hover:shadow-lg">
                                    Atualizar Nome
                                </button>
                            </form>
                        </div>

                        <!-- Linha separadora -->
                        <div class="border-t border-gray-200"></div>

                        <!-- Atualizar Senha -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Atualizar Senha</h3>
                            <form>
                                <div class="mb-4">
                                    <label for="password_desktop" class="block text-sm font-medium text-gray-700 mb-2">Nova Senha</label>
                                    <input type="password" id="password_desktop" name="password" class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                                </div>
                                <div class="mb-4">
                                    <label for="password_confirmation_desktop" class="block text-sm font-medium text-gray-700 mb-2">Confirmar Senha</label>
                                    <input type="password" id="password_confirmation_desktop" name="password_confirmation" class="w-full px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent">
                                </div>
                                <button type="submit" class="w-full bg-yellow-400 hover:bg-yellow-500 text-black font-semibold py-3 rounded-lg transition-colors shadow-md hover:shadow-lg">
                                    Atualizar Senha
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Right Section -->
        </div>
    </div>
    <!-- Desktop Layout -->
</div>
@endsection