<?php

namespace Tests\Unit;

use App\Http\Requests\ForgotPasswordRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ForgotPasswordRequestTest extends TestCase
{
    use RefreshDatabase;

    // Testa se a requisição de esqueci senha é autorizada
    public function test_forgot_password_request_is_authorized()
    {
        $request = new ForgotPasswordRequest();
        
        $this->assertTrue($request->authorize());
    }

    // Testa se as regras de validação estão corretas
    public function test_forgot_password_request_has_correct_validation_rules()
    {
        $request = new ForgotPasswordRequest();
        
        $rules = $request->rules();
        
        $this->assertEquals([
            'email' => ['required', 'email', 'exists:users'],
        ], $rules);
    }

    // Testa se as mensagens de erro estão corretas
    public function test_forgot_password_request_has_correct_error_messages()
    {
        $request = new ForgotPasswordRequest();
        
        $messages = $request->messages();
        
        $expectedMessages = [
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Digite um e-mail válido.',
            'email.exists' => 'Este e-mail não está registrado no nosso sistema.',
        ];
        
        $this->assertEquals($expectedMessages, $messages);
    }

    // Testa se a validação passa com email válido e existente
    public function test_forgot_password_request_validation_passes_with_valid_existing_email()
    {
        // Create a test user first
        \App\Models\User::factory()->create([
            'email' => 'test@example.com'
        ]);

        $data = [
            'email' => 'test@example.com'
        ];

        $request = new ForgotPasswordRequest();
        $validator = $this->app['validator']->make($data, $request->rules());

        $this->assertFalse($validator->fails());
    }

    // Testa se a validação falha quando email não é fornecido
    public function test_forgot_password_request_validation_fails_without_email()
    {
        $data = [];

        $request = new ForgotPasswordRequest();
        $validator = $this->app['validator']->make($data, $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    // Testa se a validação falha com formato inválido de email
    public function test_forgot_password_request_validation_fails_with_invalid_email_format()
    {
        $data = [
            'email' => 'invalid-email'
        ];

        $request = new ForgotPasswordRequest();
        $validator = $this->app['validator']->make($data, $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    // Testa se a validação falha com email inexistente
    public function test_forgot_password_request_validation_fails_with_non_existing_email()
    {
        $data = [
            'email' => 'nonexistent@example.com'
        ];

        $request = new ForgotPasswordRequest();
        $validator = $this->app['validator']->make($data, $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }
}