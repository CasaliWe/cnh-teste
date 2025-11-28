<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\NewUserPasswordReset;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    // Testa se o usuário pode visualizar o formulário "esqueci minha senha"
    public function test_user_can_view_forgot_password_form()
    {
        $response = $this->get('/esqueci-senha');
        
        $response->assertStatus(200)
                 ->assertViewIs('auth.forgot-password');
    }

    // Testa se a validação de redefinição de senha exige o campo email
    public function test_password_reset_validation_requires_email()
    {
        $response = $this->post('/esqueci-senha', []);

        $response->assertSessionHasErrors('email');
    }

    // Testa se a validação exige formato válido de email
    public function test_password_reset_validation_requires_valid_email_format()
    {
        $response = $this->post('/esqueci-senha', [
            'email' => 'invalid-email'
        ]);

        $response->assertSessionHasErrors('email');
    }

    // Testa se a redefinição de senha falha para email inexistente
    public function test_password_reset_fails_for_nonexistent_email()
    {
        $response = $this->from('/esqueci-senha')->post('/esqueci-senha', [
            'email' => 'nonexistent@example.com'
        ]);

        $response->assertRedirect('/esqueci-senha')
                 ->assertSessionHasErrors('email')
                 ->assertSessionHasErrorsIn('default', 'email', 'Este e-mail não está cadastrado em nosso sistema.');
    }

    // Testa se a redefinição de senha envia email para usuário existente
    public function test_password_reset_sends_email_for_existing_user()
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'test@example.com'
        ]);

        $response = $this->from('/esqueci-senha')->post('/esqueci-senha', [
            'email' => 'test@example.com'
        ]);

        $response->assertRedirect('/esqueci-senha')
                 ->assertSessionHas('status', 'Enviamos uma nova senha para seu e-mail!');

        Mail::assertSent(NewUserPasswordReset::class, function ($mail) use ($user) {
            return $mail->user->email === $user->email;
        });
    }

    // Testa se a redefinição de senha atualiza a senha do usuário
    public function test_password_reset_updates_user_password()
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('oldpassword')
        ]);

        $oldPasswordHash = $user->password;

        $this->from('/esqueci-senha')->post('/esqueci-senha', [
            'email' => 'test@example.com'
        ]);

        $user->refresh();
        $this->assertNotEquals($oldPasswordHash, $user->password);
    }

    // Testa se a redefinição de senha usa transação do banco (rollback em caso de falha)
    public function test_password_reset_uses_database_transaction()
    {
        // Simular falha no envio de email para testar rollback
        Mail::shouldReceive('to->send')->andThrow(new \Exception('Email failed'));

        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('oldpassword')
        ]);

        $oldPasswordHash = $user->password;

        $response = $this->from('/esqueci-senha')->post('/esqueci-senha', [
            'email' => 'test@example.com'
        ]);

        $response->assertRedirect('/esqueci-senha')
                 ->assertSessionHasErrors('email');

        // Verificar se a senha não foi alterada devido ao rollback
        $user->refresh();
        $this->assertEquals($oldPasswordHash, $user->password);
    }

    // Testa se o rate limiting bloqueia após 3 tentativas de redefinição
    public function test_password_reset_rate_limiting_blocks_after_3_attempts()
    {
        $user = User::factory()->create(['email' => 'test@example.com']);

        // Fazer 3 tentativas
        for ($i = 0; $i < 3; $i++) {
            $this->post('/esqueci-senha', [
                'email' => 'test@example.com'
            ]);
        }

        // 4ª tentativa deve ser bloqueada
        $response = $this->post('/esqueci-senha', [
            'email' => 'test@example.com'
        ]);

        $response->assertStatus(429); // Too Many Requests
    }

    // Testa se a redefinição de senha gera nova senha aleatória
    public function test_password_reset_generates_new_random_password()
    {
        Mail::fake();

        $user = User::factory()->create(['email' => 'test@example.com']);

        $this->post('/esqueci-senha', [
            'email' => 'test@example.com'
        ]);

        Mail::assertSent(NewUserPasswordReset::class, function ($mail) {
            // Verificar se a senha tem 8 caracteres (conforme configurado no controller)
            return strlen($mail->password) === 8;
        });
    }

    // Testa se o formulário exibe mensagem de sucesso após redefinição
    public function test_password_reset_form_displays_success_message()
    {
        Mail::fake();

        $user = User::factory()->create(['email' => 'test@example.com']);

        $response = $this->from('/esqueci-senha')->post('/esqueci-senha', [
            'email' => 'test@example.com'
        ]);

        $response->assertRedirect('/esqueci-senha')
                 ->assertSessionHas('status');

        // Verificar se a mensagem aparece na view
        $response = $this->get('/esqueci-senha');
        $response->assertSee('Enviamos uma nova senha para seu e-mail!');
    }

    // Testa se erros são tratados com mensagem genérica
    public function test_password_reset_error_handling_shows_generic_message()
    {
        // Simular falha no envio de email para trigger o catch
        Mail::shouldReceive('to->send')->andThrow(new \Exception('Email service down'));

        $user = User::factory()->create(['email' => 'test@example.com']);

        $response = $this->from('/esqueci-senha')->post('/esqueci-senha', [
            'email' => 'test@example.com'
        ]);

        $response->assertRedirect('/esqueci-senha')
                 ->assertSessionHasErrors('email')
                 ->assertSessionHasErrorsIn('default', 'email', 'Erro ao processar sua solicitação. Tente novamente.');
    }

    // Testa se dados antigos são preservados em caso de erro de validação
    public function test_password_reset_preserves_old_input_on_validation_error()
    {
        $response = $this->from('/esqueci-senha')->post('/esqueci-senha', [
            'email' => 'invalid-email'
        ]);

        $response->assertRedirect('/esqueci-senha')
                 ->assertSessionHasErrors('email');

        $response = $this->get('/esqueci-senha');
        $response->assertSee('invalid-email');
    }
}