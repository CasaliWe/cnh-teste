# 🚗 CNH sem Segredo

Sistema de gestão para CNH desenvolvido em Laravel 12 com autenticação via Google OAuth e recuperação de senha por email.

## 📋 Funcionalidades

- ✅ **Autenticação Completa**
  - Login tradicional (email/senha)
  - Login via Google OAuth
  - Recuperação de senha por email
  - Rate limiting para segurança
  - Logout seguro

- ✅ **Dashboard & Perfil**
  - Dashboard protegido por autenticação
  - Página de perfil do usuário
  - Sessões com Redis para performance

- ✅ **API RESTful**
  - Endpoint para registro de usuários
  - Rate limiting nas APIs
  - Estrutura preparada para expansão

- ✅ **Segurança & Logs**
  - Rate limiting (5 tentativas login, 3 recuperação senha)
  - Logs detalhados de ações
  - Proteção CSRF
  - Hash seguro de senhas

## 🛠️ Tecnologias

- **Backend:** Laravel 12, PHP 8.2+
- **Frontend:** TailwindCSS 4.0, Vite
- **Database:** SQLite (desenvolvimento) / MySQL (produção)
- **Cache:** Redis
- **Autenticação:** Laravel Socialite (Google)
- **Email:** SMTP (configurável)
- **Testes:** PHPUnit com Feature Tests

## ⚡ Instalação Rápida

### 1. Clone o repositório
```bash
git clone https://github.com/CasaliWe/cnh-teste.git
cd cnh-teste
```

### 2. Instale as dependências
```bash
composer install
npm install
```

### 3. Configure o ambiente
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Configure o banco de dados
```bash
# SQLite (padrão para desenvolvimento)
touch database/database.sqlite
php artisan migrate

# Ou MySQL (edite .env primeiro)
# php artisan migrate
```

### 5. Inicie o servidor
```bash
# Servidor completo (Laravel + Vite + Queue)
composer run dev

# Ou manualmente
php artisan serve
npm run dev
```

## 🔧 Configuração

### Variáveis de Ambiente Essenciais

```env
# Aplicação
APP_NAME='CNH sem segredo'
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Google OAuth
GOOGLE_CLIENT_ID=seu_client_id_aqui
GOOGLE_CLIENT_SECRET=seu_client_secret_aqui
GOOGLE_REDIRECT_URI=http://localhost:8000/auth/callback/google

# Email (SMTP)
MAIL_MAILER=smtp
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_USERNAME=seu_email@dominio.com
MAIL_PASSWORD=sua_senha
MAIL_FROM_ADDRESS=seu_email@dominio.com

# Redis (Cache e Sessões)
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
```

### Configurando Google OAuth

1. Acesse [Google Cloud Console](https://console.cloud.google.com/)
2. Crie um novo projeto ou selecione existente
3. Ative a API do Google+
4. Crie credenciais OAuth 2.0
5. Configure as URLs autorizadas:
   - **Origens autorizadas:** `http://localhost:8000`
   - **URIs de redirecionamento:** `http://localhost:8000/auth/callback/google`
6. Copie Client ID e Client Secret para o `.env`

### Configurando Email SMTP

```env
# Exemplo Hostinger
MAIL_HOST=smtp.hostinger.com
MAIL_PORT=465
MAIL_ENCRYPTION=ssl

# Exemplo Gmail
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls
```

## 🔗 Rotas da API

### Autenticação Web
- `GET /login` - Formulário de login
- `POST /login` - Processar login
- `GET /esqueci-senha` - Formulário recuperação senha
- `POST /esqueci-senha` - Processar recuperação
- `GET /auth/redirect/google` - Redirect Google OAuth
- `GET /auth/callback/google` - Callback Google OAuth

### Rotas Protegidas (requer autenticação)
- `GET /` - Dashboard principal
- `GET /perfil` - Perfil do usuário
- `POST /logout` - Logout

### API REST
- `POST /api/registro` - Registrar novo usuário

## 📁 Estrutura do Projeto

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── AuthController.php        # Autenticação web
│   │   ├── DashboardController.php   # Dashboard
│   │   ├── ProfileController.php     # Perfil
│   │   └── api/
│   │       └── AuthController.php    # API de autenticação
│   └── Requests/
│       ├── LoginRequest.php          # Validação login
│       └── ForgotPasswordRequest.php # Validação recuperação
├── Mail/
│   ├── NewUserPassword.php          # Email nova senha
│   └── NewUserPasswordReset.php     # Email reset senha
└── Models/
    └── User.php                     # Model usuário

resources/
├── views/
│   ├── auth/                       # Templates autenticação
│   ├── dashboard/                  # Templates dashboard
│   └── client/                     # Templates cliente
└── css/ & js/                      # Assets front-end

tests/
├── Feature/
│   ├── AuthTest.php               # Testes autenticação
│   └── PasswordResetTest.php      # Testes recuperação
└── Unit/                          # Testes unitários
```

## 🧪 Testes

### Executar todos os testes
```bash
composer run test
# ou
php artisan test
```

### Testes específicos
```bash
# Apenas testes de autenticação
php artisan test --filter AuthTest

```

## 🚀 Deploy

### Ambiente de Produção

1. **Configure variáveis de produção:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seudominio.com

# Database MySQL
DB_CONNECTION=mysql
DB_HOST=seu_host
DB_DATABASE=seu_banco
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

2. **Execute comandos de deploy:**
```bash
# Instalar dependências produção
composer install --optimize-autoloader --no-dev

# Build assets
npm run build

# Configurar aplicação
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Executar migrations
php artisan migrate --force

# Configurar permissões
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

3. **Configure servidor web (Nginx/Apache):**
- Document root: `public/`
- PHP 8.2+ com extensões necessárias
- HTTPS obrigatório

### Scripts Composer Úteis

```bash
# Setup completo do projeto
composer run setup

# Desenvolvimento (servidor + queue + vite)
composer run dev

# Executar testes
composer run test
```

## 🔒 Segurança

- **Rate Limiting:** 5 tentativas login, 3 recuperação senha por minuto
- **CSRF Protection:** Token em todos os formulários  
- **Password Hashing:** Bcrypt com 12 rounds
- **Session Security:** Redis com regeneração automática
- **Input Validation:** Form Requests em todas entradas
- **SQL Injection:** Eloquent ORM com prepared statements
- **Logs de Auditoria:** Todas ações críticas logadas