@extends('layouts.app')

@section('title', 'Perfil - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a]">
    <!-- Header -->
    <header class="bg-[#1b1b18] dark:bg-[#161615] shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <h1 class="text-xl font-semibold text-white">Perfil {{ config('app.name') }}</h1>
                <nav class="flex items-center space-x-4">
                    <a 
                        href="{{ route('dashboard') }}" 
                        class="px-4 py-2 text-white dark:text-[#EDEDEC] hover:bg-[#3E3E3A] dark:hover:bg-[#eeeeec] hover:text-white dark:hover:text-[#1C1C1A] rounded-md text-sm font-medium transition-colors"
                    >
                        Dashboard
                    </a>
                    <a 
                        href="{{ route('profile') }}" 
                        class="px-4 py-2 bg-[#3E3E3A] dark:bg-[#eeeeec] text-white dark:text-[#1C1C1A] rounded-md text-sm font-medium"
                    >
                        Perfil
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button 
                            type="submit" 
                            class="px-4 py-2 bg-[#F53003] dark:bg-[#FF4433] text-white rounded-md text-sm font-medium hover:bg-red-700 transition-colors"
                        >
                            Sair
                        </button>
                    </form>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                    Meu Perfil
                </h2>
                <p class="text-[#706f6c] dark:text-[#A1A09A]">
                    Gerencie suas informações pessoais
                </p>
            </div>
            
            <!-- Profile Information -->
            <div class="space-y-6">
                <!-- Basic Information -->
                <div class="bg-[#FDFDFC] dark:bg-[#3E3E3A] rounded-lg p-6 border border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-6">
                        Informações Básicas
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                Nome Completo
                            </label>
                            <div class="px-3 py-2 bg-gray-50 dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-md text-[#1b1b18] dark:text-[#EDEDEC]">
                                {{ Auth::user()->name }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                Email
                            </label>
                            <div class="px-3 py-2 bg-gray-50 dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-md text-[#1b1b18] dark:text-[#EDEDEC]">
                                {{ Auth::user()->email }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                Membro desde
                            </label>
                            <div class="px-3 py-2 bg-gray-50 dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-md text-[#1b1b18] dark:text-[#EDEDEC]">
                                {{ Auth::user()->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-2">
                                Status do Email
                            </label>
                            <div class="px-3 py-2 bg-gray-50 dark:bg-[#161615] border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-md">
                                @if(Auth::user()->email_verified_at)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                        ✓ Verificado em {{ Auth::user()->email_verified_at->format('d/m/Y H:i') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        ✗ Não verificado
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Account Actions -->
                <div class="bg-[#FDFDFC] dark:bg-[#3E3E3A] rounded-lg p-6 border border-[#e3e3e0] dark:border-[#3E3E3A]">
                    <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-6">
                        Ações da Conta
                    </h3>
                    <div class="flex flex-wrap gap-4">
                        <button class="px-4 py-2 bg-[#1b1b18] dark:bg-[#eeeeec] text-white dark:text-[#1C1C1A] rounded-md hover:bg-black dark:hover:bg-white transition-colors">
                            Alterar Senha
                        </button>
                        <button class="px-4 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] text-[#1b1b18] dark:text-[#EDEDEC] rounded-md hover:bg-gray-50 dark:hover:bg-[#161615] transition-colors">
                            Editar Perfil
                        </button>
                        @if(!Auth::user()->email_verified_at)
                        <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            Verificar Email
                        </button>
                        @endif
                    </div>
                    <p class="mt-4 text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        <strong>Nota:</strong> As funcionalidades de edição estão em desenvolvimento.
                    </p>
                </div>

                <!-- Security Information -->
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-yellow-800 dark:text-yellow-200 mb-4">
                        🔒 Informações de Segurança
                    </h3>
                    <ul class="space-y-2 text-sm text-yellow-700 dark:text-yellow-300">
                        <li>• Sua conta foi criada automaticamente via sistema</li>
                        <li>• Recomendamos alterar sua senha inicial assim que possível</li>
                        <li>• Nunca compartilhe suas credenciais com terceiros</li>
                        <li>• Entre em contato conosco em caso de problemas de acesso</li>
                    </ul>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection