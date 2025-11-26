@extends('layouts.app')

@section('title', 'Dashboard - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-[#FDFDFC] dark:bg-[#0a0a0a]">
    <!-- Header -->
    <header class="bg-[#1b1b18] dark:bg-[#161615] shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-4">
                <h1 class="text-xl font-semibold text-white">Dashboard {{ config('app.name') }}</h1>
                <nav class="flex items-center space-x-4">
                    <a 
                        href="{{ route('dashboard') }}" 
                        class="px-4 py-2 bg-[#3E3E3A] dark:bg-[#eeeeec] text-white dark:text-[#1C1C1A] rounded-md text-sm font-medium"
                    >
                        Dashboard
                    </a>
                    <a 
                        href="{{ route('profile') }}" 
                        class="px-4 py-2 text-white dark:text-[#EDEDEC] hover:bg-[#3E3E3A] dark:hover:bg-[#eeeeec] hover:text-white dark:hover:text-[#1C1C1A] rounded-md text-sm font-medium transition-colors"
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
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                    Bem-vindo ao Dashboard!
                </h2>
                <p class="text-[#706f6c] dark:text-[#A1A09A] text-lg">
                    Você está logado no sistema {{ config('app.name') }}.
                </p>
            </div>
            
            <!-- User Info Card -->
            <div class="bg-[#FDFDFC] dark:bg-[#3E3E3A] rounded-lg p-6 border border-[#e3e3e0] dark:border-[#3E3E3A]">
                <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                    Informações do Usuário
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <span class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Nome:</span>
                        <span class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium">{{ Auth::user()->name }}</span>
                    </div>
                    <div>
                        <span class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Email:</span>
                        <span class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium">{{ Auth::user()->email }}</span>
                    </div>
                    <div>
                        <span class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Membro desde:</span>
                        <span class="text-[#1b1b18] dark:text-[#EDEDEC] font-medium">{{ Auth::user()->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div>
                        <span class="block text-sm font-medium text-[#706f6c] dark:text-[#A1A09A] mb-1">Status:</span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                            Ativo
                        </span>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8">
                <h3 class="text-lg font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-4">
                    Ações Rápidas
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a 
                        href="{{ route('profile') }}" 
                        class="block p-4 bg-[#FDFDFC] dark:bg-[#3E3E3A] rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A] hover:shadow-md transition-shadow"
                    >
                        <h4 class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Ver Perfil</h4>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Visualize e edite suas informações pessoais</p>
                    </a>
                    
                    <div class="block p-4 bg-[#FDFDFC] dark:bg-[#3E3E3A] rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A]">
                        <h4 class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Configurações</h4>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Em breve...</p>
                    </div>
                    
                    <div class="block p-4 bg-[#FDFDFC] dark:bg-[#3E3E3A] rounded-lg border border-[#e3e3e0] dark:border-[#3E3E3A]">
                        <h4 class="font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Relatórios</h4>
                        <p class="text-sm text-[#706f6c] dark:text-[#A1A09A]">Em breve...</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
@endsection