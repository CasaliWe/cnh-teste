<?php

namespace Tests\Unit;

use App\Http\Requests\api\ApiRegisterRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiRegisterRequestTest extends TestCase
{
    use RefreshDatabase;

    // Testa se a requisição de registro via API é autorizada
    public function test_api_register_request_is_authorized()
    {
        $request = new ApiRegisterRequest();
        
        $this->assertTrue($request->authorize());
    }

    // Testa se as regras de validação estão corretas
    public function test_api_register_request_has_correct_validation_rules()
    {
        $request = new ApiRegisterRequest();
        
        $rules = $request->rules();
        
        $this->assertEquals([
            'email' => ['required', 'email', 'unique:users'],
        ], $rules);
    }

    // Testa se as mensagens de erro estão corretas
    public function test_api_register_request_has_correct_error_messages()
    {
        $request = new ApiRegisterRequest();
        
        $messages = $request->messages();
        
        $expectedMessages = [
            'email.required' => 'O campo e-mail é obrigatório.',
            'email.email' => 'Digite um e-mail válido.',
            'email.unique' => 'Este e-mail já está cadastrado.',
        ];
        
        $this->assertEquals($expectedMessages, $messages);
    }

    // Testa se a validação passa com email válido
    public function test_api_register_request_validation_passes_with_valid_data()
    {
        $data = [
            'email' => 'test@example.com'
        ];

        $request = new ApiRegisterRequest();
        $validator = $this->app['validator']->make($data, $request->rules());

        $this->assertFalse($validator->fails());
    }

    // Testa se a validação falha quando email não é fornecido
    public function test_api_register_request_validation_fails_without_email()
    {
        $data = [];

        $request = new ApiRegisterRequest();
        $validator = $this->app['validator']->make($data, $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    // Testa se a validação falha com formato inválido de email
    public function test_api_register_request_validation_fails_with_invalid_email_format()
    {
        $data = [
            'email' => 'invalid-email'
        ];

        $request = new ApiRegisterRequest();
        $validator = $this->app['validator']->make($data, $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }

    // Testa se a validação falha com email já existente
    public function test_api_register_request_validation_fails_with_duplicate_email()
    {
        // Create a user first
        \App\Models\User::factory()->create([
            'email' => 'test@example.com'
        ]);

        $data = [
            'email' => 'test@example.com'
        ];

        $request = new ApiRegisterRequest();
        $validator = $this->app['validator']->make($data, $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
    }
}