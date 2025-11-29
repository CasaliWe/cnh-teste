<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simulado CNH</title>
</head>
<body>
    <h1>Simulado CNH</h1>
    
    @foreach($simulado as $index => $questao)
        <div style="border: 1px solid #ccc; margin: 20px; padding: 15px;">
            <h3>Questão {{ $index + 1 }}</h3>
            
            @if($questao->imagem)
                <img src="/images/{{ $questao->imagem }}" alt="Imagem da questão" style="max-width: 300px;">
            @endif
            
            <p><strong>{{ $questao->pergunta }}</strong></p>
            
            <div>
                @foreach($questao->opcoes as $letra => $opcao)
                    <div style="margin: 10px 0;">
                        <input type="radio" name="questao_{{ $questao->id }}" value="{{ $letra }}" id="q{{ $questao->id }}_{{ $letra }}">
                        <label for="q{{ $questao->id }}_{{ $letra }}">{{ strtoupper($letra) }}) {{ $opcao }}</label>
                    </div>
                @endforeach
            </div>
            
            @if($questao->dica)
                <div style="background: #f0f0f0; padding: 10px; margin-top: 10px;">
                    <strong>Dica:</strong> {{ $questao->dica }}
                </div>
            @endif
            
            <small style="color: #666;">Resposta correta: {{ strtoupper($questao->resposta_certa) }}</small>
        </div>
    @endforeach
</body>
</html>
