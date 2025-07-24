<?php

return [
    'required' => 'O campo :attribute é obrigatório.',
    'email' => 'O campo :attribute deve ser um endereço de e-mail válido.',
    'min' => [
        'string' => 'O campo :attribute deve ter no mínimo :min caracteres.',
    ],
    'max' => [
        'string' => 'O campo :attribute não pode ter mais de :max caracteres.',
    ],
    'confirmed' => 'A confirmação do campo :attribute não confere.',
    'unique' => 'O valor do campo :attribute já está em uso.',
    'attributes' => [
        'name' => 'nome',
        'email' => 'e-mail',
        'password' => 'senha',
        'password_confirmation' => 'confirmação da senha',
        'telefone' => 'telefone',
        'cpf' => 'CPF',
        'cep' => 'CEP',
        'logradouro' => 'logradouro',
        'numero' => 'número',
        'bairro' => 'bairro',
        'cidade' => 'cidade',
        'uf' => 'UF',
    ],
];
