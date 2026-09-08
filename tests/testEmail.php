<?php

require_once __DIR__ . '/../src/EmailService.php';

echo "============================================\n";
echo "       SECUREMAIL AI - TESTE DE E-MAIL\n";
echo "============================================\n\n";

try {

    echo "1. Preparando e-mail...\n";

    $destinatario = 'edwillerdelima@gmail.com';

    $assunto = 'Teste SecureMail AI';

    $mensagem =
        "Olá!\n\n"
        . "Este é o primeiro e-mail enviado pelo "
        . "SecureMail AI usando PHP, PHPMailer e Gmail.\n\n"
        . "Este e-mail ainda não possui nossa "
        . "criptografia. É apenas um teste do envio SMTP.\n\n"
        . "SecureMail AI";

    echo "✅ E-mail preparado.\n";

    echo "\n2. Enviando e-mail...\n";

    EmailService::enviar(
        $destinatario,
        $assunto,
        $mensagem
    );

    echo "✅ E-MAIL ENVIADO COM SUCESSO!\n";

    echo "\n3. Verifique a caixa de entrada do Gmail.\n";

    echo "\n============================================\n";
    echo "          TESTE DE E-MAIL APROVADO!\n";
    echo "============================================\n";

} catch (Throwable $erro) {

    echo "\n============================================\n";
    echo "                    ERRO\n";
    echo "============================================\n";

    echo $erro->getMessage() . "\n";
}