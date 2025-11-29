<?php

namespace Tests\Unit;

use App\Http\Requests\UpdateNameRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UpdateNameRequestTest extends TestCase
{
    // Testa se o request é autorizado
    public function test_update_name_request_is_authorized()
    {
        $request = new UpdateNameRequest();
        
        $this->assertTrue($request->authorize());
    }

    // Testa se o request tem as regras de validação corretas
    public function test_update_name_request_has_correct_validation_rules()
    {
        $request = new UpdateNameRequest();
        $rules = $request->rules();
        
        $this->assertArrayHasKey('name', $rules);
        $this->assertEquals(['required', 'string', 'min:2', 'max:255'], $rules['name']);
    }

    // Testa se o request tem as mensagens de erro corretas
    public function test_update_name_request_has_correct_error_messages()
    {
        $request = new UpdateNameRequest();
        $messages = $request->messages();
        
        $this->assertEquals('O nome é obrigatório.', $messages['name.required']);
        $this->assertEquals('O nome deve ter pelo menos 2 caracteres.', $messages['name.min']);
        $this->assertEquals('O nome deve ter no máximo 255 caracteres.', $messages['name.max']);
    }

    // Testa se a validação passa com dados válidos
    public function test_update_name_request_validation_passes_with_valid_data()
    {
        $request = new UpdateNameRequest();
        $validator = Validator::make(
            ['name' => 'João Silva'],
            $request->rules(),
            $request->messages()
        );

        $this->assertTrue($validator->passes());
    }

    // Testa se a validação falha sem o nome
    public function test_update_name_request_validation_fails_without_name()
    {
        $request = new UpdateNameRequest();
        $validator = Validator::make(
            [],
            $request->rules(),
            $request->messages()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
        $this->assertEquals('O nome é obrigatório.', $validator->errors()->first('name'));
    }

    // Testa se a validação falha com nome muito curto
    public function test_update_name_request_validation_fails_with_too_short_name()
    {
        $request = new UpdateNameRequest();
        $validator = Validator::make(
            ['name' => 'A'],
            $request->rules(),
            $request->messages()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
        $this->assertEquals('O nome deve ter pelo menos 2 caracteres.', $validator->errors()->first('name'));
    }

    // Testa se a validação falha com nome muito longo
    public function test_update_name_request_validation_fails_with_too_long_name()
    {
        $request = new UpdateNameRequest();
        $validator = Validator::make(
            ['name' => str_repeat('A', 256)],
            $request->rules(),
            $request->messages()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
        $this->assertEquals('O nome deve ter no máximo 255 caracteres.', $validator->errors()->first('name'));
    }

    // Testa se a validação falha com tipo de dado inválido
    public function test_update_name_request_validation_fails_with_invalid_data_type()
    {
        $request = new UpdateNameRequest();
        $validator = Validator::make(
            ['name' => 123],
            $request->rules(),
            $request->messages()
        );

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
    }

    // Testa validação com nomes válidos de diferentes tamanhos
    public function test_update_name_request_validation_with_various_valid_names()
    {
        $request = new UpdateNameRequest();
        $validNames = [
            'João',
            'Maria Silva',
            'José da Silva Santos',
            'Ana Beatriz Costa Oliveira',
            str_repeat('A', 255) // Nome no limite máximo
        ];

        foreach ($validNames as $name) {
            $validator = Validator::make(
                ['name' => $name],
                $request->rules(),
                $request->messages()
            );

            $this->assertTrue($validator->passes(), "Nome '$name' deveria ser válido");
        }
    }

    // Testa validação com caracteres especiais válidos
    public function test_update_name_request_validation_accepts_special_characters()
    {
        $request = new UpdateNameRequest();
        $validNames = [
            'João da Silva',
            'Maria José',
            'José-Carlos',
            "Ana D'Angelo",
            'Pierre-François'
        ];

        foreach ($validNames as $name) {
            $validator = Validator::make(
                ['name' => $name],
                $request->rules(),
                $request->messages()
            );

            $this->assertTrue($validator->passes(), "Nome '$name' deveria ser válido");
        }
    }
}