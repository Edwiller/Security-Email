<?php

require_once __DIR__ . '/../src/ChaveService.php';
require_once __DIR__ . '/../src/CriptografiaService.php';
require_once __DIR__ . '/../src/AssinaturaService.php';

echo "============================================\n";
echo "      SECUREMAIL AI - TESTE DE MENSAGEM\n";
echo "============================================\n\n";

try {

    /*
     * ==========================================
     * 1. GARANTIR QUE AS CHAVES EXISTEM
     * ==========================================
     */

    echo "1. Verificando chaves...\n";

    if (!ChaveService::existemChaves()) {

        ChaveService::gerarChaves();

        echo "✅ Chaves geradas.\n";

    } else {

        echo "✅ Chaves encontradas.\n";
    }


    /*
     * ==========================================
     * 2. CARREGAR CHAVES
     * ==========================================
     */

    echo "\n2. Carregando chaves...\n";

    $chaves = ChaveService::carregarChaves();

    echo "✅ Chaves carregadas.\n";


    /*
     * ==========================================
     * 3. MENSAGEM
     * ==========================================
     */

    $mensagem =
        "Olá! Esta é uma mensagem protegida pelo SecureMail AI.";

    echo "\n3. Mensagem original:\n";
    echo $mensagem . "\n";


    /*
     * ==========================================
     * 4. ASSINAR
     * ==========================================
     */

    echo "\n4. Criando assinatura digital...\n";

    $assinatura =
        AssinaturaService::assinar(
            $mensagem,
            $chaves['assinatura']['privada']
        );

    echo "✅ Assinatura criada.\n";


    /*
     * ==========================================
     * 5. CRIPTOGRAFAR
     * ==========================================
     */

    echo "\n5. Criptografando mensagem...\n";

    $mensagemCriptografada =
        CriptografiaService::criptografar(
            $mensagem,
            $chaves['criptografia']['publica']
        );

    echo "✅ Mensagem criptografada.\n";


    /*
     * ==========================================
     * 6. SIMULAR ENVIO
     * ==========================================
     */

    echo "\n6. Simulando envio...\n";

    echo "📦 Pacote enviado:\n\n";

    echo "Mensagem criptografada:\n";
    echo $mensagemCriptografada . "\n\n";

    echo "Assinatura:\n";
    echo $assinatura . "\n";


    /*
     * ==========================================
     * 7. DESCRIPTOGRAFAR
     * ==========================================
     */

    echo "\n7. Descriptografando mensagem...\n";

    $mensagemDescriptografada =
        CriptografiaService::descriptografar(
            $mensagemCriptografada,
            $chaves['criptografia']['publica'],
            $chaves['criptografia']['privada']
        );

    echo "✅ Mensagem descriptografada.\n";


    /*
     * ==========================================
     * 8. VERIFICAR ASSINATURA
     * ==========================================
     */

    echo "\n8. Verificando assinatura...\n";

    $assinaturaValida =
        AssinaturaService::verificar(
            $mensagemDescriptografada,
            $assinatura,
            $chaves['assinatura']['publica']
        );

    if ($assinaturaValida) {

        echo "✅ ASSINATURA VÁLIDA!\n";

    } else {

        echo "❌ ASSINATURA INVÁLIDA!\n";
    }


    /*
     * ==========================================
     * 9. MOSTRAR MENSAGEM
     * ==========================================
     */

    echo "\n9. Mensagem recuperada:\n";
    echo $mensagemDescriptografada . "\n";


    /*
     * ==========================================
     * 10. TESTAR ADULTERAÇÃO
     * ==========================================
     */

    echo "\n10. Testando adulteração...\n";

    $mensagemAdulterada =
        $mensagemDescriptografada .
        " ALTERAÇÃO!";

    $assinaturaAdulterada =
        AssinaturaService::verificar(
            $mensagemAdulterada,
            $assinatura,
            $chaves['assinatura']['publica']
        );

    if (!$assinaturaAdulterada) {

        echo "✅ ADULTERAÇÃO DETECTADA!\n";

    } else {

        echo "❌ ERRO: adulteração não detectada!\n";
    }


    /*
     * ==========================================
     * FINAL
     * ==========================================
     */

    echo "\n============================================\n";
    echo "             TESTE CONCLUÍDO\n";
    echo "============================================\n";

} catch (Throwable $erro) {

    echo "\n============================================\n";
    echo "                  ERRO\n";
    echo "============================================\n";

    echo $erro->getMessage() . "\n";
}