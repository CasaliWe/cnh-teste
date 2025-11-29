<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senha Alterada - {{ config('app.name') }}</title>
    <style>
        /*! tailwindcss v4.0.7 | MIT License | https://tailwindcss.com */
        @import url('https://fonts.bunny.net/css?family=instrument-sans:400,500,600');
        
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            line-height: 1.6; 
            background-color: #f8f9fa; 
            padding: 20px;
        }
        
        .container { 
            max-width: 600px; 
            margin: 0 auto; 
            background: white; 
            border-radius: 8px; 
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .header { 
            background: linear-gradient(135deg, #1b1b18 0%, #3E3E3A 100%); 
            color: white; 
            padding: 40px 30px; 
            text-align: center; 
        }
        
        .header h1 { 
            font-size: 28px; 
            font-weight: 600; 
            margin-bottom: 8px; 
        }
        
        .header p { 
            opacity: 0.9; 
            font-size: 16px; 
        }
        
        .content { 
            padding: 40px 30px; 
            color: #1b1b18; 
        }
        
        .greeting { 
            font-size: 18px; 
            margin-bottom: 24px; 
        }
        
        .info-box { 
            background: #d4edda; 
            border: 1px solid #28a745; 
            border-radius: 8px; 
            padding: 24px; 
            margin: 24px 0; 
            border-left: 4px solid #28a745; 
        }
        
        .info-title { 
            font-weight: 600; 
            color: #155724; 
            margin-bottom: 12px; 
            display: flex; 
            align-items: center; 
            font-size: 16px;
        }
        
        .info-text { 
            color: #155724; 
            font-size: 14px; 
        }
        
        .details-box { 
            background: #f8f9fa; 
            border: 1px solid #e3e3e0; 
            border-radius: 8px; 
            padding: 24px; 
            margin: 24px 0; 
            border-left: 4px solid #1b1b18; 
        }
        
        .detail-item { 
            margin-bottom: 16px; 
        }
        
        .detail-label { 
            font-weight: 600; 
            color: #706f6c; 
            font-size: 14px; 
            margin-bottom: 4px; 
        }
        
        .detail-value { 
            font-size: 16px; 
            color: #1b1b18; 
        }
        
        .warning-box { 
            background: #fff3cd; 
            border: 1px solid #ffc107; 
            border-radius: 6px; 
            padding: 20px; 
            margin: 24px 0; 
            border-left: 4px solid #ffc107; 
        }
        
        .warning-title { 
            font-weight: 600; 
            color: #856404; 
            margin-bottom: 12px; 
            display: flex; 
            align-items: center; 
        }
        
        .warning-list { 
            color: #856404; 
            font-size: 14px; 
            margin: 0; 
            padding-left: 20px; 
        }
        
        .warning-list li { 
            margin-bottom: 6px; 
        }
        
        .login-button { 
            display: inline-block; 
            background: #1b1b18; 
            color: white; 
            padding: 14px 28px; 
            text-decoration: none; 
            border-radius: 6px; 
            font-weight: 600; 
            margin: 20px 0; 
            transition: background-color 0.2s; 
        }
        
        .login-button:hover { 
            background: #000; 
        }
        
        .footer { 
            background: #f8f9fa; 
            padding: 30px; 
            text-align: center; 
            color: #706f6c; 
            font-size: 14px; 
            border-top: 1px solid #e3e3e0; 
        }
        
        .footer-logo { 
            font-weight: 600; 
            color: #1b1b18; 
            margin-bottom: 8px; 
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🔐 Senha Alterada - {{ config('app.name') }}</h1>
            <p>Sua senha foi alterada com sucesso</p>
        </div>
        
        <!-- Main Content -->
        <div class="content">
            <div class="greeting">
                Olá <strong>{{ $user->name }}</strong>,
            </div>
            
            <!-- Success Message -->
            <div class="info-box">
                <div class="info-title">
                    ✅ Senha Alterada com Sucesso
                </div>
                <div class="info-text">
                    Sua senha foi alterada com sucesso no sistema {{ config('app.name') }}. Esta alteração foi realizada através do seu perfil.
                </div>
            </div>
            
            <!-- Details -->
            <div class="details-box">
                <div class="detail-item">
                    <div class="detail-label">📧 Email da conta:</div>
                    <div class="detail-value">{{ $user->email }}</div>
                </div>
                
                <div class="detail-item">
                    <div class="detail-label">🕐 Data e hora da alteração:</div>
                    <div class="detail-value">{{ now()->setTimezone('America/Sao_Paulo')->format('d/m/Y \à\s H:i:s') }} (horário de Brasília)</div>
                </div>
            </div>
            
            <!-- Security Warning -->
            <div class="warning-box">
                <div class="warning-title">
                    🛡️ Informações de Segurança
                </div>
                <ul class="warning-list">
                    <li>Por segurança, todas as suas outras sessões ativas foram invalidadas</li>
                    <li>Se você não fez esta alteração, entre em contato conosco imediatamente</li>
                    <li>Nunca compartilhe suas credenciais com terceiros</li>
                    <li>Use senhas fortes e únicas para sua conta</li>
                    <li>Em caso de atividade suspeita, altere sua senha novamente</li>
                </ul>
            </div>
            
            <!-- Login Button -->
            <div style="text-align: center; margin: 32px 0;">
                <a href="{{ url('/login') }}" class="login-button">
                    🚀 Acessar Minha Conta
                </a>
            </div>
            
            <!-- Additional Info -->
            <p style="font-size: 14px; color: #706f6c; margin-top: 24px;">
                <strong>Não foi você?</strong> Se você não fez esta alteração, entre em contato conosco imediatamente através do nosso suporte.
            </p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-logo">{{ config('app.name') }}</div>
            <p>Este é um email automático do sistema.<br>
            Não responda a este email.</p>
            
            <p style="margin-top: 16px; font-size: 12px; opacity: 0.8;">
                © {{ date('Y') }} {{ config('app.name') }}. Todos os direitos reservados.
            </p>
        </div>
    </div>
</body>
</html>