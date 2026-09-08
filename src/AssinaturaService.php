<?php

class AssinaturaService
{
    /**
     * Cria uma assinatura digital.
     */
    public static function assinar(
        string $mensagem,
        string $chavePrivada
    ): string {

        try {

            $assinatura =
                sodium_crypto_sign_detached(
                    $mensagem,
                    $chavePrivada
                );

            return sodium_bin2base64(
                $assinatura,
                SODIUM_BASE64_VARIANT_ORIGINAL
            );

        } catch (Throwable $erro) {

            throw new RuntimeException(
                'Erro ao criar assinatura: '
                . $erro->getMessage(),
                0,
                $erro
            );
        }
    }


    /**
     * Verifica uma assinatura digital.
     */
    public static function verificar(
        string $mensagem,
        string $assinaturaBase64,
        string $chavePublica
    ): bool {

        try {

            $assinatura =
                sodium_base642bin(
                    $assinaturaBase64,
                    SODIUM_BASE64_VARIANT_ORIGINAL
                );

            return sodium_crypto_sign_verify_detached(
                $assinatura,
                $mensagem,
                $chavePublica
            );

        } catch (Throwable $erro) {

            throw new RuntimeException(
                'Erro ao verificar assinatura: '
                . $erro->getMessage(),
                0,
                $erro
            );
        }
    }
}