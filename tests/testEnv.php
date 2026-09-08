<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

echo "============================================\n";
echo "       SECUREMAIL AI - TESTE .ENV\n";
echo "============================================\n\n";

try {

    echo "1. Carregando arquivo .env...\n";

    $dotenv = Dotenv::createImmutable(
        __DIR__ . '/..'
    );

    $dotenv->load();

    echo "✅ .env carregado.\n";

    echo "\n2. Verificando configurações...\n";

    $host = $_ENV['SMTP_HOST'] ?? '';
    $port = $_ENV['SMTP_PORT'] ?? '';
    $username = $_ENV['SMTP_USERNAME'] ?? '';
    $password = $_ENV['SMTP_PASSWORD'] ?? '';

    if ($host === '') {
        throw new RuntimeException(
            'SMTP_HOST não foi configurado.'
        );
    }

    if ($port === '') {
        throw new RuntimeException(
            'SMTP_PORT não foi configurado.'
        );
    }

    if ($username === '') {
        throw new RuntimeException(
            'SMTP_USERNAME não foi configurado.'
        );
    }

    if ($password === '') {
        throw new RuntimeException(
            'SMTP_PASSWORD não foi configurado.'
        );
    }

    echo "✅ SMTP_HOST configurado.\n";
    echo "✅ SMTP_PORT configurado.\n";
    echo "✅ SMTP_USERNAME configurado.\n";
    echo "✅ SMTP_PASSWORD configurado.\n";

    /*
     * Não mostramos a senha no terminal.
     */
    echo "\n3. Configurações carregadas:\n";

    echo "Host: " . $host . "\n";
    echo "Porta: " . $port . "\n";
    echo "Usuário: " . $username . "\n";
    echo "Senha: ********\n";

    echo "\n============================================\n";
    echo "           TESTE .ENV APROVADO!\n";
    echo "============================================\n";

} catch (Throwable $erro) {

    echo "\n============================================\n";
    echo "                    ERRO\n";
    echo "============================================\n";

    echo $erro->getMessage() . "\n";
}