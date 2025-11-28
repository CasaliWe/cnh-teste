<?php

namespace Tests\Unit;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserModelTest extends TestCase
{
    use RefreshDatabase;

    // Testa se usuário pode ser criado com dados válidos
    public function test_user_can_be_created_with_valid_data()
    {
        $userData = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ];

        $user = User::create($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('Test User', $user->name);
        $this->assertEquals('test@example.com', $user->email);
        $this->assertTrue(Hash::check('password123', $user->password));
    }

    // Testa se a factory cria usuário válido
    public function test_user_factory_creates_valid_user()
    {
        $user = User::factory()->create();

        $this->assertInstanceOf(User::class, $user);
        $this->assertNotEmpty($user->name);
        $this->assertNotEmpty($user->email);
        $this->assertNotEmpty($user->password);
        $this->assertIsString($user->email);
        $this->assertTrue(filter_var($user->email, FILTER_VALIDATE_EMAIL) !== false);
    }

    // Testa se o usuário tem os atributos preenchíveis corretos
    public function test_user_has_fillable_attributes()
    {
        $user = new User();
        
        $expectedFillable = [
            'name',
            'email',
            'password',
        ];

        $this->assertEquals($expectedFillable, $user->getFillable());
    }

    // Testa se o usuário tem os atributos ocultos corretos
    public function test_user_has_hidden_attributes()
    {
        $user = new User();
        
        $expectedHidden = [
            'password',
            'remember_token',
        ];

        $this->assertEquals($expectedHidden, $user->getHidden());
    }

    // Testa se a senha do usuário é automaticamente hasheada
    public function test_user_password_is_cast_to_hashed()
    {
        $user = User::factory()->create([
            'password' => 'plain_password'
        ]);

        // Verify that the password is hashed
        $this->assertNotEquals('plain_password', $user->password);
        $this->assertTrue(Hash::check('plain_password', $user->password));
    }

    // Testa se o email do usuário deve ser único
    public function test_user_email_must_be_unique()
    {
        $email = 'test@example.com';
        
        // Create first user
        User::factory()->create(['email' => $email]);
        
        // Attempt to create second user with same email should fail
        $this->expectException(\Illuminate\Database\QueryException::class);
        User::factory()->create(['email' => $email]);
    }

    // Testa se o usuário tem timestamps (created_at, updated_at)
    public function test_user_has_timestamps()
    {
        $user = User::factory()->create();

        $this->assertNotNull($user->created_at);
        $this->assertNotNull($user->updated_at);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $user->created_at);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $user->updated_at);
    }

    // Testa se os atributos de verificação de email funcionam corretamente
    public function test_user_email_verification_attributes_work()
    {
        $user = User::factory()->unverified()->create();

        // Test email verification timestamp
        $this->assertArrayHasKey('email_verified_at', $user->getCasts());
        
        // By default, should be unverified
        $this->assertNull($user->email_verified_at);
        
        // Mark as verified
        $user->markEmailAsVerified();
        $this->assertNotNull($user->email_verified_at);
        $this->assertTrue($user->hasVerifiedEmail());
    }

    // Testa se o remember token pode ser definido
    public function test_user_remember_token_can_be_set()
    {
        $user = User::factory()->create();
        
        $token = 'test_remember_token';
        $user->setRememberToken($token);
        
        $this->assertEquals($token, $user->getRememberToken());
    }

    // Testa se o identificador de autenticação retorna a chave primária
    public function test_user_auth_identifier_returns_primary_key()
    {
        $user = User::factory()->create();
        
        $this->assertEquals($user->id, $user->getAuthIdentifier());
    }

    // Testa se o método de senha de autenticação retorna a senha
    public function test_user_auth_password_returns_password()
    {
        $password = 'test_password';
        $user = User::factory()->create([
            'password' => $password
        ]);
        
        $this->assertTrue(Hash::check($password, $user->getAuthPassword()));
    }

    // Testa se o usuário pode ser encontrado pelo email
    public function test_user_can_be_found_by_email()
    {
        $email = 'findme@example.com';
        $user = User::factory()->create(['email' => $email]);
        
        $foundUser = User::where('email', $email)->first();
        
        $this->assertInstanceOf(User::class, $foundUser);
        $this->assertEquals($user->id, $foundUser->id);
        $this->assertEquals($email, $foundUser->email);
    }

    // Testa se o usuário pode atualizar informações do perfil
    public function test_user_can_update_profile_information()
    {
        $user = User::factory()->create([
            'name' => 'Original Name',
            'email' => 'original@example.com'
        ]);
        
        $user->update([
            'name' => 'Updated Name',
            'email' => 'updated@example.com'
        ]);
        
        $this->assertEquals('Updated Name', $user->name);
        $this->assertEquals('updated@example.com', $user->email);
    }

    // Testa se a serialização exclui dados sensíveis
    public function test_user_serialization_excludes_sensitive_data()
    {
        $user = User::factory()->create();
        
        $serialized = $user->toArray();
        
        // Should not include password or remember_token in serialization
        $this->assertArrayNotHasKey('password', $serialized);
        $this->assertArrayNotHasKey('remember_token', $serialized);
        
        // Should include public attributes
        $this->assertArrayHasKey('id', $serialized);
        $this->assertArrayHasKey('name', $serialized);
        $this->assertArrayHasKey('email', $serialized);
        $this->assertArrayHasKey('created_at', $serialized);
        $this->assertArrayHasKey('updated_at', $serialized);
    }
}