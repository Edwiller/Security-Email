<?php

class ChaveService{
    private const DIRETORIO_CHAVES = __DIR__ . '/../storage/keys/';

    private const ARQUIVO_CRIPTOGRAFIA_PUBLICA =
        self::DIRETORIO_CHAVES . 'encryption_public.key';

    private const ARQUIVO_CRIPTOGRAFIA_PRIVADA =
        self::DIRETORIO_CHAVES . 'encryption_private.key';

    private const ARQUIVO_ASSINATURA_PUBLICA =
        self::DIRETORIO_CHAVES . 'signing_public.key';

    private const ARQUIVO_ASSINATURA_PRIVADA =
        self::DIRETORIO_CHAVES . 'signing_private.key';


    /**
     * Verifica se todas as chaves já existem.
     */
    public static function existemChaves(): bool
    {
        return file_exists(self::ARQUIVO_CRIPTOGRAFIA_PUBLICA)
            && file_exists(self::ARQUIVO_CRIPTOGRAFIA_PRIVADA)
            && file_exists(self::ARQUIVO_ASSINATURA_PUBLICA)
            && file_exists(self::ARQUIVO_ASSINATURA_PRIVADA);
    }


    /**
     * Gera e salva todas as chaves do sistema.
     */
    public static function gerarChaves(): void
    {
        try {

            if (!is_dir(self::DIRETORIO_CHAVES)) {
                mkdir(
                    self::DIRETORIO_CHAVES,
                    0700,
                    true
                );
            }

            /*
             * ==========================================
             * CHAVES DE CRIPTOGRAFIA
             * ==========================================
             */

            $keypairCriptografia =
                sodium_crypto_box_keypair();

            $chavePublicaCriptografia =
                sodium_crypto_box_publickey(
                    $keypairCriptografia
                );

            $chavePrivadaCriptografia =
                sodium_crypto_box_secretkey(
                    $keypairCriptografia
                );


            /*
             * ==========================================
             * CHAVES DE ASSINATURA
             * ==========================================
             */

            $keypairAssinatura =
                sodium_crypto_sign_keypair();

            $chavePublicaAssinatura =
                sodium_crypto_sign_publickey(
                    $keypairAssinatura
                );

            $chavePrivadaAssinatura =
                sodium_crypto_sign_secretkey(
                    $keypairAssinatura
                );


            /*
             * ==========================================
             * SALVAR
             * ==========================================
             */

            self::salvarChave(
                self::ARQUIVO_CRIPTOGRAFIA_PUBLICA,
                $chavePublicaCriptografia
            );

            self::salvarChave(
                self::ARQUIVO_CRIPTOGRAFIA_PRIVADA,
                $chavePrivadaCriptografia
            );

            self::salvarChave(
                self::ARQUIVO_ASSINATURA_PUBLICA,
                $chavePublicaAssinatura
            );

            self::salvarChave(
                self::ARQUIVO_ASSINATURA_PRIVADA,
                $chavePrivadaAssinatura
            );

        } catch (Throwable $erro) {

            throw new RuntimeException(
                'Erro ao gerar e salvar as chaves: '
                . $erro->getMessage(),
                0,
                $erro
            );
        }
    }


    /**
     * Salva uma chave em arquivo.
     */
    private static function salvarChave(
        string $arquivo,
        string $chave
    ): void {

        $resultado = file_put_contents(
            $arquivo,
            base64_encode($chave),
            LOCK_EX
        );

        if ($resultado === false) {
            throw new RuntimeException(
                "Não foi possível salvar a chave: $arquivo"
            );
        }
    }


    /**
     * Carrega uma chave do arquivo.
     */
    private static function carregarChave(
        string $arquivo
    ): string {

        if (!file_exists($arquivo)) {
            throw new RuntimeException(
                "Chave não encontrada: $arquivo"
            );
        }

        $conteudo = file_get_contents($arquivo);

        if ($conteudo === false) {
            throw new RuntimeException(
                "Não foi possível ler a chave: $arquivo"
            );
        }

        $chave = base64_decode(
            $conteudo,
            true
        );

        if ($chave === false) {
            throw new RuntimeException(
                "A chave armazenada é inválida: $arquivo"
            );
        }

        return $chave;
    }


    /**
     * Retorna todas as chaves.
     */
    public static function carregarChaves(): array
    {
        return [
            'criptografia' => [
                'publica' => self::carregarChave(
                    self::ARQUIVO_CRIPTOGRAFIA_PUBLICA
                ),

                'privada' => self::carregarChave(
                    self::ARQUIVO_CRIPTOGRAFIA_PRIVADA
                )
            ],

            'assinatura' => [
                'publica' => self::carregarChave(
                    self::ARQUIVO_ASSINATURA_PUBLICA
                ),

                'privada' => self::carregarChave(
                    self::ARQUIVO_ASSINATURA_PRIVADA
                )
            ]
        ];
    }
}