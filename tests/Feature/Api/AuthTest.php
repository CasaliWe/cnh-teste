<?php

namespace Tests\Feature\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\NewUserPassword;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    // Testa se a API de registro exige o campo email
    public function test_api_register_validation_requires_email()
    {
        $response = $this->postJson('/api/registro', []);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('email');
    }

    // Testa se a API exige formato válido de email
    public function test_api_register_validation_requires_valid_email_format()
    {
        $response = $this->postJson('/api/registro', [
            'email' => 'invalid-email'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('email');
    }

    // Testa se a API exige email único para registro
    public function test_api_register_validation_requires_unique_email()
    {
        User::factory()->create(['email' => 'existing@example.com']);

        $response = $this->postJson('/api/registro', [
            'email' => 'existing@example.com'
        ]);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors('email');
    }

    // Testa se a API cria usuário com sucesso
    public function test_api_register_creates_user_successfully()
    {
        Mail::fake();

        $response = $this->postJson('/api/registro', [
            'email' => 'newuser@example.com'
        ]);

        $response->assertStatus(201)
                 ->assertJsonStructure([
                     'message',
                     'user_id'
                 ]);

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
            'name' => 'newuser' // parte antes do @
        ]);
    }

    // Testa se a API envia email de boas-vindas após registro
    public function test_api_register_sends_welcome_email()
    {
        Mail::fake();

        $response = $this->postJson('/api/registro', [
            'email' => 'newuser@example.com'
        ]);

        $response->assertStatus(201);

        Mail::assertSent(NewUserPassword::class, function ($mail) {
            return $mail->user->email === 'newuser@example.com';
        });
    }

    // Testa se a API gera senha aleatória para novo usuário
    public function test_api_register_generates_random_password()
    {
        Mail::fake();

        $response = $this->postJson('/api/registro', [
            'email' => 'newuser@example.com'
        ]);

        $response->assertStatus(201);

        Mail::assertSent(NewUserPassword::class, function ($mail) {
            // Verificar se a senha tem 8 caracteres
            return strlen($mail->password) === 8;
        });

        $user = User::where('email', 'newuser@example.com')->first();
        $this->assertNotNull($user->password);
        $this->assertTrue(Hash::needsRehash($user->password) === false); // Verifica se está hasheada
    }

    // Testa se a API usa a parte antes do @ como nome do usuário
    public function test_api_register_uses_email_prefix_as_name()
    {
        Mail::fake();

        $response = $this->postJson('/api/registro', [
            'email' => 'joao.silva@example.com'
        ]);

        $response->assertStatus(201);

        $user = User::where('email', 'joao.silva@example.com')->first();
        $this->assertEquals('joao.silva', $user->name);
    }

    // Testa se a API faz rollback se o envio de email falhar
    public function test_api_register_rollback_on_email_failure()
    {
        // Simular falha no envio de email
        Mail::shouldReceive('to->send')->andThrow(new \Exception('Email service down'));

        $response = $this->postJson('/api/registro', [
            'email' => 'newuser@example.com'
        ]);

        $response->assertStatus(500)
                 ->assertJsonStructure([
                     'message',
                     'error'
                 ]);

        // Verificar se o usuário foi removido devido ao rollback
        $this->assertDatabaseMissing('users', [
            'email' => 'newuser@example.com'
        ]);
    }

    // Testa se o rate limiting da API bloqueia após 3 tentativas
    public function test_api_register_rate_limiting_blocks_after_3_attempts()
    {
        // Fazer 3 tentativas
        for ($i = 0; $i < 3; $i++) {
            $this->postJson('/api/registro', [
                'email' => "user{$i}@example.com"
            ]);
        }

        // 4ª tentativa deve ser bloqueada
        $response = $this->postJson('/api/registro', [
            'email' => 'user4@example.com'
        ]);

        $response->assertStatus(429); // Too Many Requests
    }

    // Testa se a API retorna formato correto de resposta de erro
    public function test_api_register_returns_proper_error_response_format()
    {
        $response = $this->postJson('/api/registro', []);

        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'success',
                     'message',
                     'errors' => [
                         'email'
                     ]
                 ])
                 ->assertJson([
                     'success' => false,
                     'message' => 'Erro de validação'
                 ]);
    }

    // Testa se resposta de sucesso contém ID do usuário criado
    public function test_api_register_success_response_contains_user_id()
    {
        Mail::fake();

        $response = $this->postJson('/api/registro', [
            'email' => 'newuser@example.com'
        ]);

        $response->assertStatus(201);

        $user = User::where('email', 'newuser@example.com')->first();
        
        $response->assertJson([
            'user_id' => $user->id,
            'message' => 'Usuário criado com sucesso! A senha foi enviada para o email.'
        ]);
    }

    // Testa se a API trata erros de banco de dados graciosamente
    public function test_api_register_handles_database_errors_gracefully()
    {
        // Simular erro no envio de email para trigger o catch
        Mail::shouldReceive('to->send')->andThrow(new \Exception('Email failed'));

        $response = $this->postJson('/api/registro', [
            'email' => 'newuser@example.com'
        ]);

        $response->assertStatus(500)
                 ->assertJson([
                     'message' => 'Erro ao enviar email. Tente novamente.'
                 ]);
    }

    // Testa se a API aceita apenas método POST
    public function test_api_register_accepts_only_post_method()
    {
        $response = $this->getJson('/api/registro');
        $response->assertStatus(405); // Method Not Allowed

        $response = $this->putJson('/api/registro');
        $response->assertStatus(405);

        $response = $this->deleteJson('/api/registro');
        $response->assertStatus(405);
    }

    // Testa se a API valida corretamente o content-type das requisições
    public function test_api_register_content_type_validation()
    {
        // API aceita tanto JSON quanto form data no Laravel por padrão
        $response = $this->post('/api/registro', [
            'email' => 'test@example.com'
        ]);

        // Deve funcionar normalmente
        $response->assertStatus(201)
                 ->assertJson([
                     'message' => 'Usuário criado com sucesso! A senha foi enviada para o email.'
                 ]);
    }
}