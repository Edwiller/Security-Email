<?php

require_once __DIR__ . '/../src/ChaveService.php';
require_once __DIR__ . '/../src/CriptografiaService.php';
require_once __DIR__ . '/../src/AssinaturaService.php';
require_once __DIR__ . '/../src/PacoteService.php';

echo "====================================================\n";
echo "       SECUREMAIL AI - FLUXO COMPLETO\n";
echo "====================================================\n\n";

try {

    /*
     * 1. VERIFICAR CHAVES
     */

    echo "1. Verificando chaves...\n";

    if (!ChaveService::existemChaves()) {
        ChaveService::gerarChaves();
        echo "✅ Chaves geradas.\n";
    } else {
        echo "✅ Chaves encontradas.\n";
    }

    $chaves = ChaveService::carregarChaves();

    echo "✅ Chaves carregadas.\n";


    /*
     * 2. MENSAGEM ORIGINAL
     */

    echo "\n2. Criando mensagem...\n";

    $mensagemOriginal =
        "Olá! Esta mensagem será protegida pelo SecureMail AI.";

    echo "Mensagem original:\n";
    echo $mensagemOriginal . "\n";


    /*
     * 3. ASSINATURA
     */

    echo "\n3. Criando assinatura digital...\n";

    $assinatura =
        AssinaturaService::assinar(
            $mensagemOriginal,
            $chaves['assinatura']['privada']
        );

    echo "✅ Assinatura criada.\n";


    /*
     * 4. CRIPTOGRAFIA
     */

    echo "\n4. Criptografando mensagem...\n";

    $mensagemCriptografada =
        CriptografiaService::criptografar(
            $mensagemOriginal,
            $chaves['criptografia']['publica']
        );

    echo "✅ Mensagem criptografada.\n";


    /*
     * 5. CRIAR PACOTE
     */

    echo "\n5. Criando pacote seguro...\n";

    $pacoteJson =
        PacoteService::criar(
            $mensagemCriptografada,
            $assinatura
        );

    echo "✅ Pacote criado.\n";

    echo "\nPacote que seria enviado:\n\n";
    echo $pacoteJson . "\n";


    /*
     * 6. SIMULAR ENVIO
     */

    echo "\n6. Simulando envio do pacote...\n";

    $pacoteRecebido =
        $pacoteJson;

    echo "✅ Pacote recebido.\n";


    /*
     * 7. LER PACOTE
     */

    echo "\n7. Lendo pacote recebido...\n";

    $dadosPacote =
        PacoteService::ler(
            $pacoteRecebido
        );

    echo "✅ Pacote válido.\n";


    /*
     * 8. DESCRIPTOGRAFAR
     */

    echo "\n8. Descriptografando mensagem...\n";

    $mensagemDescriptografada =
        CriptografiaService::descriptografar(
            $dadosPacote['mensagem'],
            $chaves['criptografia']['publica'],
            $chaves['criptografia']['privada']
        );

    echo "✅ Mensagem descriptografada.\n";


    /*
     * 9. VERIFICAR ASSINATURA
     */

    echo "\n9. Verificando assinatura...\n";

    $assinaturaValida =
        AssinaturaService::verificar(
            $mensagemDescriptografada,
            $dadosPacote['assinatura'],
            $chaves['assinatura']['publica']
        );

    if (!$assinaturaValida) {
        throw new RuntimeException(
            'A assinatura da mensagem é inválida.'
        );
    }

    echo "✅ ASSINATURA VÁLIDA!\n";


    /*
     * 10. COMPARAR MENSAGEM
     */

    echo "\n10. Comparando mensagem original...\n";

    if ($mensagemOriginal !== $mensagemDescriptografada) {
        throw new RuntimeException(
            'A mensagem recuperada é diferente da original.'
        );
    }

    echo "✅ Mensagem original recuperada corretamente.\n";


    /*
     * 11. TESTAR ADULTERAÇÃO
     */

    echo "\n11. Testando adulteração da mensagem...\n";

    $mensagemAdulterada =
        $mensagemDescriptografada .
        " ALTERAÇÃO MALICIOSA!";

    $assinaturaAdulterada =
        AssinaturaService::verificar(
            $mensagemAdulterada,
            $dadosPacote['assinatura'],
            $chaves['assinatura']['publica']
        );

    if ($assinaturaAdulterada) {
        throw new RuntimeException(
            'ERRO: a adulteração não foi detectada.'
        );
    }

    echo "✅ ADULTERAÇÃO DETECTADA!\n";


    /*
     * RESULTADO FINAL
     */

    echo "\n====================================================\n";
    echo "          FLUXO COMPLETO APROVADO!\n";
    echo "====================================================\n";

} catch (Throwable $erro) {

    echo "\n====================================================\n";
    echo "                    ERRO\n";
    echo "====================================================\n";

    echo $erro->getMessage() . "\n";
}