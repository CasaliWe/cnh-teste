@extends('layouts.app')

@section('title', 'Recuperar Senha - ' . config('app.name'))

@section('content')
<div class="min-h-screen flex">
    <!-- Lado esquerdo - Formulário -->
    <div class="w-full lg:w-[40%] bg-gradient-to-br from-slate-600 via-slate-700 to-slate-800 flex items-center justify-center p-4 lg:p-8">
        <div class="w-full max-w-sm">
            <!-- Logo e título -->
            <div class="flex items-center justify-center mb-0">
                <img src="{{ asset('logo.png') }}" alt="Logo CNH" class="w-60 lg:w-72">
            </div>
            
            <!-- Formulário -->
            <div class="bg-white bg-opacity-90 rounded-lg p-4 lg:p-6 shadow-lg">
                <div class="text-center mb-4 lg:mb-6">
                    <h2 class="text-lg font-semibold text-gray-800 mb-2">Recuperar Senha</h2>
                    <p class="text-gray-700 text-sm">Digite seu e-mail para receber as instruções de recuperação.</p>
                </div>
                
                <!-- Mensagem de sucesso -->
                @if (session('status'))
                    <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded-md text-sm">
                        {{ session('status') }}
                    </div>
                @endif
                
                <form method="POST" action="{{ route('forgot-password') }}" class="space-y-3 lg:space-y-4" id="forgotForm">
                    @csrf
                    
                    <div>
                        <label for="email" class="block text-sm text-gray-700 mb-1">E-mail</label>
                        <input 
                            type="email" 
                            id="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md text-gray-800 bg-white focus:outline-none focus:ring-2 focus:ring-yellow-400 focus:border-transparent text-sm"
                            placeholder="Digite seu e-mail cadastrado"
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button 
                        type="submit"
                        id="forgotBtn"
                        class="w-full bg-yellow-400 hover:bg-yellow-500 hover:cursor-pointer text-gray-800 font-semibold py-2.5 px-4 rounded-md transition-colors duration-200 text-sm disabled:opacity-70 disabled:cursor-not-allowed"
                    >
                        <span id="btnText">Enviar Link de Recuperação</span>
                        <span id="loadingText" class="hidden">
                            <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-gray-800 inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Enviando...
                        </span>
                    </button>
                    
                    <div class="text-center text-xs text-gray-600 mt-4">
                        <a href="{{ route('login') }}" class="hover:text-gray-800">Voltar ao Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Lado direito - Imagem (apenas desktop) -->
    <div class="hidden lg:flex lg:w-[60%] items-center" style="background-image: url('{{ asset('capa-login.png') }}'); background-size: cover; background-position: center;">
        <div class="text-white/35 w-[60%] ms-10 text-3xl font-light">Curiosidade: <br> Se você continuar vendo essa tela é porque ainda não passou. <br><br> Estude mais!</div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const forgotForm = document.getElementById('forgotForm');
    const forgotBtn = document.getElementById('forgotBtn');
    const btnText = document.getElementById('btnText');
    const loadingText = document.getElementById('loadingText');
    
    forgotForm.addEventListener('submit', function() {
        // Desabilita o botão
        forgotBtn.disabled = true;
        
        // Oculta o texto normal e mostra o loading
        btnText.classList.add('hidden');
        loadingText.classList.remove('hidden');
    });
});
</script>
@endsection