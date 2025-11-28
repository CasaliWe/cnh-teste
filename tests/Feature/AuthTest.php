<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    // Testa se o usuário pode visualizar o formulário de login
    public function test_user_can_view_login_form()
    {
        $response = $this->get('/login');
        
        $response->assertStatus(200)
                 ->assertViewIs('auth.login');
    }

    // Testa se usuário autenticado é redirecionado quando tenta acessar a página de login
    public function test_authenticated_user_is_redirected_from_login()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/login');
        
        $response->assertRedirect('/');
    }

    // Testa se o usuário consegue fazer login com credenciais válidas
    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    // Testa se o usuário não consegue fazer login com senha incorreta
    public function test_user_cannot_login_with_wrong_password()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('correctpassword')
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertRedirect('/login')
                 ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    // Testa se o usuário não consegue fazer login com email inexistente
    public function test_user_cannot_login_with_nonexistent_email()
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect('/login')
                 ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    // Testa se a validação de login exige o campo email
    public function test_login_validation_requires_email()
    {
        $response = $this->post('/login', [
            'password' => 'password123'
        ]);

        $response->assertSessionHasErrors('email');
    }

    // Testa se a validação de login exige formato válido de email
    public function test_login_validation_requires_valid_email_format()
    {
        $response = $this->post('/login', [
            'email' => 'invalid-email',
            'password' => 'password123'
        ]);

        $response->assertSessionHasErrors('email');
    }

    // Testa se a validação de login exige o campo senha
    public function test_login_validation_requires_password()
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com'
        ]);

        $response->assertSessionHasErrors('password');
    }

    // Testa se o login com "lembrar-me" define o token de lembrar
    public function test_login_with_remember_me_sets_remember_token()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
            'remember' => true
        ]);

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
        $this->assertNotNull($user->fresh()->remember_token);
    }

    // Testa se o usuário consegue fazer logout
    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
                        ->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    // Testa se o rate limiting bloqueia login após 5 tentativas falhas
    public function test_login_rate_limiting_blocks_after_5_attempts()
    {
        // Fazer 5 tentativas de login falhadas
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', [
                'email' => 'test@example.com',
                'password' => 'wrongpassword'
            ]);
        }

        // 6ª tentativa deve ser bloqueada
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(429); // Too Many Requests
    }

    // Testa se o OAuth do Google redireciona corretamente para o Google
    public function test_google_oauth_redirects_to_google()
    {
        $response = $this->get('/auth/redirect/google');
        
        $response->assertStatus(302);
        $this->assertStringContainsString('google.com', $response->getTargetUrl());
    }

    // Testa se o callback do Google autentica usuário existente
    public function test_google_oauth_callback_authenticates_existing_user()
    {
        $user = User::factory()->create(['email' => 'google@example.com']);

        $googleUser = (object) [
            'email' => 'google@example.com',
            'id' => '12345'
        ];

        Socialite::shouldReceive('driver')
            ->with('google')
            ->once()
            ->andReturnSelf();
            
        Socialite::shouldReceive('user')
            ->once()
            ->andReturn($googleUser);

        $response = $this->get('/auth/callback/google');

        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }

    // Testa se o callback do Google falha para usuário inexistente
    public function test_google_oauth_callback_fails_for_nonexistent_user()
    {
        $googleUser = (object) [
            'email' => 'nonexistent@example.com',
            'id' => '12345'
        ];

        Socialite::shouldReceive('driver')
            ->with('google')
            ->once()
            ->andReturnSelf();
            
        Socialite::shouldReceive('user')
            ->once()
            ->andReturn($googleUser);

        $response = $this->get('/auth/callback/google');

        $response->assertRedirect('/login')
                 ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    // Testa se o dashboard exige autenticação para acesso
    public function test_dashboard_requires_authentication()
    {
        $response = $this->get('/');
        
        $response->assertRedirect('/login');
    }

    // Testa se usuário autenticado pode acessar o dashboard
    public function test_authenticated_user_can_access_dashboard()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/');
        
        $response->assertStatus(200)
                 ->assertViewIs('dashboard.index');
    }

    // Testa se o perfil exige autenticação para acesso
    public function test_profile_requires_authentication()
    {
        $response = $this->get('/perfil');
        
        $response->assertRedirect('/login');
    }

    // Testa se usuário autenticado pode acessar o perfil
    public function test_authenticated_user_can_access_profile()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/perfil');
        
        $response->assertStatus(200)
                 ->assertViewIs('client.profile');
    }
}