<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem-vindo ao {{ config('app.name') }}</title>
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
        
        .credentials-box { 
            background: #f8f9fa; 
            border: 1px solid #e3e3e0; 
            border-radius: 8px; 
            padding: 24px; 
            margin: 24px 0; 
            border-left: 4px solid #1b1b18; 
        }
        
        .credential-item { 
            margin-bottom: 16px; 
        }
        
        .credential-label { 
            font-weight: 600; 
            color: #706f6c; 
            font-size: 14px; 
            margin-bottom: 4px; 
        }
        
        .credential-value { 
            font-size: 16px; 
            color: #1b1b18; 
        }
        
        .password-display { 
            background: #1b1b18; 
            color: white; 
            padding: 12px 16px; 
            border-radius: 6px; 
            font-family: 'Courier New', monospace; 
            font-size: 18px; 
            font-weight: 600; 
            text-align: center; 
            letter-spacing: 3px; 
            margin: 8px 0; 
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
            <h1>🎯 Bem-vindo ao {{ config('app.name') }}!</h1>
            <p>Sua conta foi criada com sucesso</p>
        </div>
        
        <!-- Main Content -->
        <div class="content">
            <div class="greeting">
                Olá <strong>{{ $user->name }}</strong>,
            </div>
            
            <p style="margin-bottom: 24px;">
                Sua conta foi criada com sucesso no sistema {{ config('app.name') }}! Agora você pode acessar nossa plataforma usando as credenciais abaixo:
            </p>
            
            <!-- Credentials -->
            <div class="credentials-box">
                <div class="credential-item">
                    <div class="credential-label">📧 Email de acesso:</div>
                    <div class="credential-value">{{ $user->email }}</div>
                </div>
                
                <div class="credential-item">
                    <div class="credential-label">🔑 Senha temporária:</div>
                    <div class="password-display">{{ $password }}</div>
                </div>
            </div>
            
            <!-- Warning Box -->
            <div class="warning-box">
                <div class="warning-title">
                    ⚠️ Informações Importantes de Segurança
                </div>
                <ul class="warning-list">
                    <li>Esta é uma senha temporária gerada automaticamente pelo sistema</li>
                    <li>Recomendamos fortemente que você altere sua senha após o primeiro login</li>
                    <li>Nunca compartilhe suas credenciais com terceiros</li>
                    <li>Mantenha seus dados de acesso em local seguro</li>
                    <li>Em caso de problemas, entre em contato conosco imediatamente</li>
                </ul>
            </div>
            
            <!-- Login Button -->
            <div style="text-align: center; margin: 32px 0;">
                <a href="{{ url('/login') }}" class="login-button">
                    🚀 Fazer Login Agora
                </a>
            </div>
            
            <!-- Additional Info -->
            <p style="font-size: 14px; color: #706f6c; margin-top: 24px;">
                <strong>Dica:</strong> Salve este email até alterar sua senha. Se você não solicitou esta conta, pode ignorar este email com segurança.
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