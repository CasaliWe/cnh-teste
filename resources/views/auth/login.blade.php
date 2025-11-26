@extends('layouts.app')

@section('title', 'Login - ' . config('app.name'))

@section('content')
<div class="flex items-center justify-center min-h-screen px-6 py-12">
    <div class="w-full max-w-md">
        <div class="bg-white dark:bg-[#161615] rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-semibold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">{{ config('app.name') }}</h1>
                <p class="text-[#706f6c] dark:text-[#A1A09A] text-sm">Entre com suas credenciais</p>
            </div>
            
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                        Email
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required
                        class="w-full px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-md text-[#1b1b18] dark:text-[#EDEDEC] bg-white dark:bg-[#161615] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="seu@email.com"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-[#F53003] dark:text-[#FF4433]">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
                        Senha
                    </label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        class="w-full px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-md text-[#1b1b18] dark:text-[#EDEDEC] bg-white dark:bg-[#161615] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="••••••••"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-[#F53003] dark:text-[#FF4433]">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        id="remember" 
                        name="remember"
                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-[#e3e3e0] dark:border-[#3E3E3A] rounded"
                    >
                    <label for="remember" class="ml-2 block text-sm text-[#706f6c] dark:text-[#A1A09A]">
                        Lembrar de mim
                    </label>
                </div>

                <button 
                    type="submit"
                    class="w-full bg-[#1b1b18] dark:bg-[#eeeeec] hover:bg-black dark:hover:bg-white text-white dark:text-[#1C1C1A] font-medium py-2.5 px-4 rounded-md transition-colors duration-200"
                >
                    Entrar
                </button>
            </form>
        </div>
    </div>
</div>
@endsection