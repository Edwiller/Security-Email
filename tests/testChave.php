<?php

require_once __DIR__ . '/../src/ChaveService.php';

echo "============================================\n";
echo "        SECUREMAIL AI - TESTE DE CHAVES\n";
echo "============================================\n\n";

try {

    /*
     * ==========================================
     * VERIFICAR CHAVES
     * ==========================================
     */

    echo "1. Verificando se existem chaves...\n\n";

    if (ChaveService::existemChaves()) {

        echo "✅ Chaves existentes encontradas.\n";
        echo "Carregando chaves...\n\n";

    } else {

        echo "⚠️ Nenhuma chave encontrada.\n";
        echo "Gerando novas chaves...\n\n";

        ChaveService::gerarChaves();

        echo "✅ Chaves criadas.\n";
        echo "✅ Chaves armazenadas.\n\n";
    }


    /*
     * ==========================================
     * CARREGAR
     * ==========================================
     */

    $chaves = ChaveService::carregarChaves();

    echo "2. Chaves carregadas com sucesso!\n\n";


    /*
     * ==========================================
     * MOSTRAR INFORMAÇÕES
     * ==========================================
     */

    echo "3. Informações das chaves:\n\n";

    echo "Chave pública de criptografia: ";
    echo base64_encode(
        $chaves['criptografia']['publica']
    );
    echo "\n\n";

    echo "Chave pública de assinatura: ";
    echo base64_encode(
        $chaves['assinatura']['publica']
    );
    echo "\n\n";


    /*
     * ==========================================
     * TAMANHOS
     * ==========================================
     */

    echo "4. Tamanho das chaves:\n\n";

    echo "Criptografia pública: "
        . strlen($chaves['criptografia']['publica'])
        . " bytes\n";

    echo "Criptografia privada: "
        . strlen($chaves['criptografia']['privada'])
        . " bytes\n";

    echo "Assinatura pública: "
        . strlen($chaves['assinatura']['publica'])
        . " bytes\n";

    echo "Assinatura privada: "
        . strlen($chaves['assinatura']['privada'])
        . " bytes\n\n";


    echo "============================================\n";
    echo "             TESTE CONCLUÍDO\n";
    echo "============================================\n";

} catch (Throwable $erro) {

    echo "\n❌ ERRO:\n";
    echo $erro->getMessage() . "\n";
}