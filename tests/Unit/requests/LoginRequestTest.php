<?php

namespace Tests\Unit;

use App\Http\Requests\LoginRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginRequestTest extends TestCase
{
    use RefreshDatabase;

    // Testa se a requisição de login é autorizada
    public function test_login_request_is_authorized()
    {
        $request = new LoginRequest();
        
        $this->assertTrue($request->authorize());
    }

    // Testa se as regras de validação estão corretas
    public function test_login_request_has_correct_validation_rules()
    {
        $request = new LoginRequest();
        
        $rules = $request->rules();
        
        $this->assertEquals([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ], $rules);
    }

    // Testa se as mensagens de erro estão corretas
    public function test_login_request_has_correct_error_messages()
    {
        $request = new LoginRequest();
        
        $messages = $request->messages();
        
        $expectedMessages = [
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Digite um e-mail válido.',
            'password.required' => 'A senha é obrigatória.',
        ];
        
        $this->assertEquals($expectedMessages, $messages);
    }

    // Testa se a validação passa com dados válidos
    public function test_login_request_validation_passes_with_valid_data()
    {
        $data = [
            'email' => 'test@example.com',
            'password' => 'password123'
        ];

        $request = new LoginRequest();
        $validator = $this->app['validator']->make($data, $request->rules());

        $this->assertFalse($validator->fails());
    }

    // Testa se a validação falha quando email não é fornecido
    public function test_login_request_validation_fails_without_email()
    {
        $data = [
            'password' => 'password123'
        ];

        $request = new LoginRequest();
        $validator = $this->app['validator']->make($data, $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    // Testa se a validação falha com email inválido
    public function test_login_request_validation_fails_with_invalid_email()
    {
        $data = [
            'email' => 'invalid-email',
            'password' => 'password123'
        ];

        $request = new LoginRequest();
        $validator = $this->app['validator']->make($data, $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    // Testa se a validação falha quando senha não é fornecida
    public function test_login_request_validation_fails_without_password()
    {
        $data = [
            'email' => 'test@example.com'
        ];

        $request = new LoginRequest();
        $validator = $this->app['validator']->make($data, $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
    }
}