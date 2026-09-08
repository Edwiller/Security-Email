<?php

require_once __DIR__ . '/../src/PacoteService.php';

echo "============================================\n";
echo "       SECUREMAIL AI - TESTE DE PACOTE\n";
echo "============================================\n\n";

try {

    echo "1. Criando dados de teste...\n";

    $mensagemCriptografada =
        "MENSAGEM_CRIPTOGRAFADA_DE_TESTE";

    $assinatura =
        "ASSINATURA_DIGITAL_DE_TESTE";

    echo "✅ Dados preparados.\n";

    echo "\n2. Criando pacote...\n";

    $pacoteJson =
        PacoteService::criar(
            $mensagemCriptografada,
            $assinatura
        );

    echo "✅ Pacote criado.\n";

    echo "\n3. Pacote JSON:\n\n";
    echo $pacoteJson . "\n";

    echo "\n4. Lendo pacote...\n";

    $pacote =
        PacoteService::ler(
            $pacoteJson
        );

    echo "✅ Pacote lido com sucesso.\n";

    echo "\n5. Verificando dados recuperados...\n";

    if (
        $pacote['mensagem'] === $mensagemCriptografada
        &&
        $pacote['assinatura'] === $assinatura
    ) {
        echo "✅ Dados recuperados corretamente.\n";
    } else {
        echo "❌ ERRO: dados não correspondem.\n";
    }

    echo "\n6. Testando pacote inválido...\n";

    try {

        PacoteService::ler(
            '{"versao":"1.0"}'
        );

        echo "❌ ERRO: pacote inválido foi aceito.\n";

    } catch (Throwable $erro) {

        echo "✅ Pacote inválido foi rejeitado.\n";
    }

    echo "\n============================================\n";
    echo "             TESTE CONCLUÍDO\n";
    echo "============================================\n";

} catch (Throwable $erro) {

    echo "\n============================================\n";
    echo "                  ERRO\n";
    echo "============================================\n";

    echo $erro->getMessage() . "\n";
}