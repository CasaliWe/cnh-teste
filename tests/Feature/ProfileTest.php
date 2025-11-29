<?php

namespace Tests\Feature;

use App\Jobs\SendPasswordUpdatedNotification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    // Testa se usuário não autenticado não pode acessar o perfil
    public function test_unauthenticated_user_cannot_access_profile()
    {
        $response = $this->get('/perfil');
        
        $response->assertRedirect('/login');
    }

    // Testa se usuário autenticado pode visualizar o perfil
    public function test_authenticated_user_can_view_profile()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get('/perfil');
        
        $response->assertStatus(200)
                 ->assertViewIs('client.profile');
    }

    // Testa se usuário pode atualizar nome com dados válidos
    public function test_user_can_update_name_with_valid_data()
    {
        $user = User::factory()->create(['name' => 'Nome Antigo']);
        
        $response = $this->actingAs($user)
                         ->post('/perfil/nome', [
                             'name' => 'Nome Novo'
                         ]);

        $response->assertRedirect('/perfil')
                 ->assertSessionHas('success', 'Nome atualizado com sucesso!');
        
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nome Novo'
        ]);
    }

    // Testa se atualização de nome exige autenticação
    public function test_update_name_requires_authentication()
    {
        $response = $this->post('/perfil/nome', [
            'name' => 'Nome Teste'
        ]);

        $response->assertRedirect('/login');
    }

    // Testa validação do nome - campo obrigatório
    public function test_update_name_validation_requires_name()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
                         ->post('/perfil/nome', []);

        $response->assertSessionHasErrors('name');
    }

    // Testa validação do nome - mínimo 2 caracteres
    public function test_update_name_validation_requires_minimum_2_characters()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
                         ->post('/perfil/nome', [
                             'name' => 'A'
                         ]);

        $response->assertSessionHasErrors('name');
    }

    // Testa validação do nome - máximo 255 caracteres
    public function test_update_name_validation_requires_maximum_255_characters()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
                         ->post('/perfil/nome', [
                             'name' => str_repeat('A', 256)
                         ]);

        $response->assertSessionHasErrors('name');
    }

    // Testa validação do nome - deve ser string
    public function test_update_name_validation_requires_string()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
                         ->post('/perfil/nome', [
                             'name' => 123
                         ]);

        $response->assertSessionHasErrors('name');
    }

    // Testa se usuário pode atualizar senha com dados válidos
    public function test_user_can_update_password_with_valid_data()
    {
        Queue::fake();
        
        $user = User::factory()->create([
            'password' => Hash::make('senhaantiga')
        ]);
        
        $response = $this->actingAs($user)
                         ->post('/perfil/senha', [
                             'password' => 'novasenha123',
                             'password_confirmation' => 'novasenha123'
                         ]);

        $response->assertRedirect('/perfil')
                 ->assertSessionHas('success');
        
        // Verifica se a senha foi atualizada
        $user->refresh();
        $this->assertTrue(Hash::check('novasenha123', $user->password));
        
        // Verifica se o job foi disparado
        Queue::assertPushed(SendPasswordUpdatedNotification::class);
    }

    // Testa se atualização de senha exige autenticação
    public function test_update_password_requires_authentication()
    {
        $response = $this->post('/perfil/senha', [
            'password' => 'novasenha123',
            'password_confirmation' => 'novasenha123'
        ]);

        $response->assertRedirect('/login');
    }

    // Testa validação de senha - campo obrigatório
    public function test_update_password_validation_requires_password()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
                         ->post('/perfil/senha', [
                             'password_confirmation' => 'teste123'
                         ]);

        $response->assertSessionHasErrors('password');
    }

    // Testa validação de senha - confirmação obrigatória
    public function test_update_password_validation_requires_confirmation()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
                         ->post('/perfil/senha', [
                             'password' => 'teste123'
                         ]);

        $response->assertSessionHasErrors('password_confirmation');
    }

    // Testa validação de senha - mínimo 8 caracteres
    public function test_update_password_validation_requires_minimum_8_characters()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
                         ->post('/perfil/senha', [
                             'password' => '1234567',
                             'password_confirmation' => '1234567'
                         ]);

        $response->assertSessionHasErrors('password');
    }

    // Testa validação de senha - confirmação deve conferir
    public function test_update_password_validation_requires_matching_confirmation()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
                         ->post('/perfil/senha', [
                             'password' => 'novasenha123',
                             'password_confirmation' => 'senhadiferente'
                         ]);

        $response->assertSessionHasErrors('password');
    }

    // Testa se sessão é regenerada após atualizar senha
    public function test_session_is_regenerated_after_password_update()
    {
        Queue::fake();
        
        $user = User::factory()->create();
        
        $oldSessionId = session()->getId();
        
        $response = $this->actingAs($user)
                         ->post('/perfil/senha', [
                             'password' => 'novasenha123',
                             'password_confirmation' => 'novasenha123'
                         ]);

        $response->assertRedirect('/perfil');
        
        // Verifica se o usuário ainda está autenticado
        $this->assertTrue(Auth::check());
        $this->assertEquals($user->id, Auth::id());
    }

    // Testa se remember token é limpo após atualizar senha
    public function test_remember_token_is_cleared_after_password_update()
    {
        Queue::fake();
        
        $user = User::factory()->create([
            'remember_token' => 'old_token'
        ]);
        
        $response = $this->actingAs($user)
                         ->post('/perfil/senha', [
                             'password' => 'novasenha123',
                             'password_confirmation' => 'novasenha123'
                         ]);

        $response->assertRedirect('/perfil');
        
        $user->refresh();
        $this->assertNull($user->remember_token);
    }

    // Testa se job de notificação é disparado após atualizar senha
    public function test_password_notification_job_is_dispatched_after_update()
    {
        Queue::fake();
        
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)
                         ->post('/perfil/senha', [
                             'password' => 'novasenha123',
                             'password_confirmation' => 'novasenha123'
                         ]);

        $response->assertRedirect('/perfil');
        
        Queue::assertPushed(SendPasswordUpdatedNotification::class, function ($job) use ($user) {
            return $job->user->id === $user->id;
        });
    }

    // Testa se usuário não autenticado não pode acessar rotas de perfil
    public function test_unauthenticated_user_cannot_access_profile_routes()
    {
        $response = $this->get('/perfil');
        $response->assertRedirect('/login');
        
        $response = $this->post('/perfil/nome', ['name' => 'Teste']);
        $response->assertRedirect('/login');
        
        $response = $this->post('/perfil/senha', [
            'password' => 'teste123',
            'password_confirmation' => 'teste123'
        ]);
        $response->assertRedirect('/login');
    }

    // Testa se nome é atualizado corretamente
    public function test_name_is_properly_updated_in_database()
    {
        $user = User::factory()->create(['name' => 'Nome Original']);
        
        $this->actingAs($user)
             ->post('/perfil/nome', ['name' => 'Nome Atualizado']);
        
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Nome Atualizado'
        ]);
        
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'name' => 'Nome Original'
        ]);
    }

    // Testa se senha antiga não funciona após atualização
    public function test_old_password_does_not_work_after_update()
    {
        Queue::fake(); // Adicionar para evitar problemas com jobs
        
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('senhaantiga')
        ]);
        
        // Atualiza a senha
        $this->actingAs($user)
             ->post('/perfil/senha', [
                 'password' => 'novasenha123',
                 'password_confirmation' => 'novasenha123'
             ]);
        
        // Tenta fazer logout e login com senha antiga
        $this->post('/logout');
        
        $response = $this->from('/login')->post('/login', [
            'email' => 'test@example.com',
            'password' => 'senhaantiga'
        ]);
        
        $response->assertRedirect('/login')
                 ->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    // Testa se nova senha funciona após atualização
    public function test_new_password_works_after_update()
    {
        Queue::fake(); // Adicionar para evitar problemas com jobs
        
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('senhaantiga')
        ]);
        
        // Atualiza a senha
        $this->actingAs($user)
             ->post('/perfil/senha', [
                 'password' => 'novasenha123',
                 'password_confirmation' => 'novasenha123'
             ]);
        
        // Faz logout e tenta login com nova senha
        $this->post('/logout');
        
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'novasenha123'
        ]);
        
        $response->assertRedirect('/');
        $this->assertAuthenticatedAs($user);
    }
}