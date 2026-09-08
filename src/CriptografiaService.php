<?php

class CriptografiaService
{
    /**
     * Criptografa uma mensagem usando
     * a chave pública do destinatário.
     */
    public static function criptografar(
        string $mensagem,
        string $chavePublica
    ): string {

        try {

            $mensagemCriptografada =
                sodium_crypto_box_seal(
                    $mensagem,
                    $chavePublica
                );

            return sodium_bin2base64(
                $mensagemCriptografada,
                SODIUM_BASE64_VARIANT_ORIGINAL
            );

        } catch (Throwable $erro) {

            throw new RuntimeException(
                'Erro ao criptografar mensagem: '
                . $erro->getMessage(),
                0,
                $erro
            );
        }
    }


    /**
     * Descriptografa uma mensagem usando
     * as chaves pública e privada do destinatário.
     */
    public static function descriptografar(
        string $mensagemCriptografadaBase64,
        string $chavePublica,
        string $chavePrivada
    ): string {

        try {

            $mensagemCriptografada =
                sodium_base642bin(
                    $mensagemCriptografadaBase64,
                    SODIUM_BASE64_VARIANT_ORIGINAL
                );

            $keypair =
                sodium_crypto_box_keypair_from_secretkey_and_publickey(
                    $chavePrivada,
                    $chavePublica
                );

            $mensagem =
                sodium_crypto_box_seal_open(
                    $mensagemCriptografada,
                    $keypair
                );

            if ($mensagem === false) {
                throw new RuntimeException(
                    'Não foi possível descriptografar a mensagem.'
                );
            }

            return $mensagem;

        } catch (Throwable $erro) {

            throw new RuntimeException(
                'Erro ao descriptografar mensagem: '
                . $erro->getMessage(),
                0,
                $erro
            );
        }
    }
}