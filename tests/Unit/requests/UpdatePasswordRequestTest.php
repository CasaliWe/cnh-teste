<?php

namespace Tests\Unit;

use App\Http\Requests\UpdatePasswordRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UpdatePasswordRequestTest extends TestCase
{
    // Testa se o request é autorizado
    public function test_update_password_request_is_authorized()
    {
        $request = new UpdatePasswordRequest();
        
        $this->assertTrue($request->authorize());
    }

    // Testa se o request tem as regras de validação corretas
    public function test_update_password_request_has_correct_validation_rules()
    {
        $request = new UpdatePasswordRequest();
        $rules = $request->rules();
        
        $this->assertArrayHasKey('password', $rules);
        $this->assertArrayHasKey('password_confirmation', $rules);
        $this->assertEquals('required|min:8|confirmed', $rules['password']);
        $this->assertEquals('required', $rules['password_confirmation']);
    }

    // Testa se o request tem as mensagens de erro corretas
    public function test_update_password_request_has_correct_error_messages()
    {
        $request = new UpdatePasswordRequest();
        $messages = $request->messages();
        
        $this->assertEquals('A senha é obrigatória.', $messages['password.required']);
        $this->assertEquals('A senha deve ter pelo menos 8 caracteres.', $messages['password.min']);
        $this->assertEquals('As senhas não conferem.', $messages['password.confirmed']);
        $this->assertEquals('A confirmação da senha é obrigatória.', $messages['password_confirmation.required']);
    }

    // Testa se a validação passa com dados válidos
    public function test_update_password_request_validation_passes_with_valid_data()
    {
        $request = new UpdatePasswordRequest();
        $validator = Validator::make(
            [
                'password' => 'novasenha123',
                'password_confirmation' => 'novasenha123'
            ],
            $request->rules(),
            $request->messages()
        );

        $this->assertTrue($validator->passes());
    }

    // Testa se a validação falha sem a senha
    public function test_update_password_request_validation_fails_without_password()
    {
        $request = new UpdatePasswordRequest();
        $validator = Validator::make(
            ['password_confirmation' => 'teste123'],
            $request->rules(),
            $request->messages()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
        $this->assertEquals('A senha é obrigatória.', $validator->errors()->first('password'));
    }

    // Testa se a validação falha sem a confirmação da senha
    public function test_update_password_request_validation_fails_without_confirmation()
    {
        $request = new UpdatePasswordRequest();
        $validator = Validator::make(
            ['password' => 'teste123'],
            $request->rules(),
            $request->messages()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('password_confirmation', $validator->errors()->toArray());
        $this->assertEquals('A confirmação da senha é obrigatória.', $validator->errors()->first('password_confirmation'));
    }

    // Testa se a validação falha com senha muito curta
    public function test_update_password_request_validation_fails_with_too_short_password()
    {
        $request = new UpdatePasswordRequest();
        $validator = Validator::make(
            [
                'password' => '1234567',
                'password_confirmation' => '1234567'
            ],
            $request->rules(),
            $request->messages()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
        $this->assertEquals('A senha deve ter pelo menos 8 caracteres.', $validator->errors()->first('password'));
    }

    // Testa se a validação falha quando as senhas não conferem
    public function test_update_password_request_validation_fails_when_passwords_dont_match()
    {
        $request = new UpdatePasswordRequest();
        $validator = Validator::make(
            [
                'password' => 'novasenha123',
                'password_confirmation' => 'senhadiferente'
            ],
            $request->rules(),
            $request->messages()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
        $this->assertEquals('As senhas não conferem.', $validator->errors()->first('password'));
    }

    // Testa validação com senhas válidas de diferentes tipos
    public function test_update_password_request_validation_with_various_valid_passwords()
    {
        $request = new UpdatePasswordRequest();
        $validPasswords = [
            'minhasenha123',
            'SenhaComMaiuscula123',
            'senha.com.pontos',
            'senha_com_underscores',
            'senha-com-hifens',
            'Senh@ComS1mb0los!',
            'UmaSenhaMuitoLongaComMaisDeVinteCaracteres123'
        ];

        foreach ($validPasswords as $password) {
            $validator = Validator::make(
                [
                    'password' => $password,
                    'password_confirmation' => $password
                ],
                $request->rules(),
                $request->messages()
            );

            $this->assertTrue($validator->passes(), "Senha '$password' deveria ser válida");
        }
    }

    // Testa validação no limite mínimo de caracteres
    public function test_update_password_request_validation_with_minimum_length()
    {
        $request = new UpdatePasswordRequest();
        $password = '12345678'; // Exatamente 8 caracteres
        
        $validator = Validator::make(
            [
                'password' => $password,
                'password_confirmation' => $password
            ],
            $request->rules(),
            $request->messages()
        );

        $this->assertTrue($validator->passes());
    }

    // Testa falha de validação com dados vazios
    public function test_update_password_request_validation_fails_with_empty_data()
    {
        $request = new UpdatePasswordRequest();
        $validator = Validator::make(
            [],
            $request->rules(),
            $request->messages()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('password', $validator->errors()->toArray());
        $this->assertArrayHasKey('password_confirmation', $validator->errors()->toArray());
    }

    // Testa falha de validação com confirmação vazia
    public function test_update_password_request_validation_fails_with_empty_confirmation()
    {
        $request = new UpdatePasswordRequest();
        $validator = Validator::make(
            [
                'password' => 'minhasenha123',
                'password_confirmation' => ''
            ],
            $request->rules(),
            $request->messages()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('password_confirmation', $validator->errors()->toArray());
    }
}