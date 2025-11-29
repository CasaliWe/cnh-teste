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
            <!-- Mensagens de sucesso/erro -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

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
                    <form method="POST" action="{{ route('profile.update-name') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="name_mobile" class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                            <input type="text" id="name_mobile" name="name" value="{{ old('name', Auth::user()->name) }}" class="w-full px-3 py-2 border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent text-sm text-gray-900 placeholder-gray-500">
                            @error('name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" id="nameBtn" class="w-full bg-yellow-400 hover:bg-yellow-500 text-black font-semibold py-2.5 rounded-lg transition-colors text-sm">
                            <span class="btn-text">Atualizar Nome</span>
                            <span class="loading hidden">
                                <svg class="animate-spin h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Atualizando...
                            </span>
                        </button>
                    </form>
                </div>

                <!-- Atualizar Senha -->
                <div class="bg-white rounded-lg p-4">
                    <h3 class="text-base font-semibold text-gray-800 mb-3">Atualizar Senha</h3>
                    <form method="POST" action="{{ route('profile.update-password') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="password_mobile" class="block text-sm font-medium text-gray-700 mb-1">Nova Senha</label>
                            <input type="password" id="password_mobile" name="password" class="w-full px-3 py-2 border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent text-sm text-gray-900 placeholder-gray-500">
                            @error('password')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation_mobile" class="block text-sm font-medium text-gray-700 mb-1">Confirmar Senha</label>
                            <input type="password" id="password_confirmation_mobile" name="password_confirmation" class="w-full px-3 py-2 border {{ $errors->has('password_confirmation') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent text-sm text-gray-900 placeholder-gray-500">
                            @error('password_confirmation')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit" id="passwordBtn" class="w-full bg-yellow-400 hover:bg-yellow-500 text-black font-semibold py-2.5 rounded-lg transition-colors text-sm">
                            <span class="btn-text">Atualizar Senha</span>
                            <span class="loading hidden">
                                <svg class="animate-spin h-4 w-4 inline mr-2" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Atualizando...
                            </span>
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
                        
                        <!-- Mensagens de sucesso/erro -->
                        @if(session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                <ul class="list-disc list-inside">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        
                        <!-- Editar Nome -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Editar Nome</h3>
                            <form method="POST" action="{{ route('profile.update-name') }}">
                                @csrf
                                <div class="mb-4">
                                    <label for="name_desktop" class="block text-sm font-medium text-gray-700 mb-2">Nome</label>
                                    <input type="text" id="name_desktop" name="name" value="{{ old('name', Auth::user()->name) }}" class="w-full px-3 py-3 border {{ $errors->has('name') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent text-gray-900 placeholder-gray-500">
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="submit" id="nameDesktopBtn" class="w-full bg-yellow-400 hover:bg-yellow-500 text-black font-semibold py-3 rounded-lg transition-colors shadow-md hover:shadow-lg">
                                    <span class="btn-text">Atualizar Nome</span>
                                    <span class="loading hidden">
                                        <svg class="animate-spin h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Atualizando...
                                    </span>
                                </button>
                            </form>
                        </div>

                        <!-- Linha separadora -->
                        <div class="border-t border-gray-200"></div>

                        <!-- Atualizar Senha -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-3">Atualizar Senha</h3>
                            <form method="POST" action="{{ route('profile.update-password') }}">
                                @csrf
                                <div class="mb-4">
                                    <label for="password_desktop" class="block text-sm font-medium text-gray-700 mb-2">Nova Senha</label>
                                    <input type="password" id="password_desktop" name="password" class="w-full px-3 py-3 border {{ $errors->has('password') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent text-gray-900 placeholder-gray-500">
                                    @error('password')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="mb-4">
                                    <label for="password_confirmation_desktop" class="block text-sm font-medium text-gray-700 mb-2">Confirmar Senha</label>
                                    <input type="password" id="password_confirmation_desktop" name="password_confirmation" class="w-full px-3 py-3 border {{ $errors->has('password_confirmation') ? 'border-red-500' : 'border-gray-300' }} rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent text-gray-900 placeholder-gray-500">
                                    @error('password_confirmation')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button type="submit" id="passwordDesktopBtn" class="w-full bg-yellow-400 hover:bg-yellow-500 text-black font-semibold py-3 rounded-lg transition-colors shadow-md hover:shadow-lg">
                                    <span class="btn-text">Atualizar Senha</span>
                                    <span class="loading hidden">
                                        <svg class="animate-spin h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Atualizando...
                                    </span>
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

<script>
// Função para mostrar loading
function showLoading(button) {
    const btnText = button.querySelector('.btn-text');
    const loading = button.querySelector('.loading');
    
    if (btnText && loading) {
        btnText.classList.add('hidden');
        loading.classList.remove('hidden');
        button.disabled = true;
        button.classList.add('opacity-75', 'cursor-not-allowed');
    }
}

// Event listeners quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    // Validação rigorosa para formulários de senha
    const passwordForms = document.querySelectorAll('form[action*="senha"]');
    
    passwordForms.forEach(form => {
        const passwordInput = form.querySelector('input[name="password"]');
        const confirmInput = form.querySelector('input[name="password_confirmation"]');
        const submitButton = form.querySelector('button[type="submit"]');
        
        if (passwordInput && confirmInput && submitButton) {
            // Função para validar senhas
            function validatePasswordForm() {
                const password = passwordInput.value;
                const confirm = confirmInput.value;
                
                // Remover mensagens de erro anteriores
                const existingErrors = form.querySelectorAll('.password-validation-error');
                existingErrors.forEach(el => el.remove());
                
                let isValid = true;
                const errors = [];
                
                // Verificar se senha tem pelo menos 8 caracteres
                if (password.length < 8 && password.length > 0) {
                    errors.push('A senha deve ter pelo menos 8 caracteres.');
                    isValid = false;
                }
                
                // Verificar se as senhas coincidem
                if (confirm && password !== confirm) {
                    errors.push('As senhas não conferem.');
                    isValid = false;
                }
                
                // Verificar se ambos campos estão preenchidos
                if (!password || !confirm) {
                    isValid = false;
                }
                
                // Mostrar erros se houver
                if (errors.length > 0) {
                    const errorContainer = document.createElement('div');
                    errorContainer.className = 'password-validation-error mt-2 p-2 bg-red-100 border border-red-400 text-red-700 text-xs rounded';
                    errorContainer.innerHTML = errors.map(error => `<div>• ${error}</div>`).join('');
                    confirmInput.parentElement.appendChild(errorContainer);
                    
                    // Adicionar borda vermelha aos campos
                    passwordInput.classList.add('border-red-500');
                    confirmInput.classList.add('border-red-500');
                } else {
                    // Remover bordas vermelhas
                    passwordInput.classList.remove('border-red-500');
                    confirmInput.classList.remove('border-red-500');
                    passwordInput.classList.add('border-gray-300');
                    confirmInput.classList.add('border-gray-300');
                }
                
                // Habilitar/desabilitar botão
                submitButton.disabled = !isValid;
                if (isValid) {
                    submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    submitButton.classList.add('hover:bg-yellow-500');
                } else {
                    submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                    submitButton.classList.remove('hover:bg-yellow-500');
                }
                
                return isValid;
            }
            
            // Event listeners para validação em tempo real
            passwordInput.addEventListener('input', validatePasswordForm);
            confirmInput.addEventListener('input', validatePasswordForm);
            
            // Prevenir submit se inválido
            form.addEventListener('submit', function(e) {
                if (!validatePasswordForm()) {
                    e.preventDefault();
                    return false;
                }
                showLoading(submitButton);
            });
            
            // Inicializar estado
            validatePasswordForm();
        }
    });
    
    // Event listeners para formulários de nome (sem validação especial)
    const nameForms = document.querySelectorAll('form[action*="nome"]');
    nameForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitButton = form.querySelector('button[type="submit"]');
            if (submitButton) {
                showLoading(submitButton);
            }
        });
    });
});
</script>
@endsection