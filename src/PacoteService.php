<?php

class PacoteService
{
    public static function criar(
        string $mensagemCriptografada,
        string $assinatura
    ): string {
        try {
            $pacote = [
                'versao' => '1.0',
                'id' => bin2hex(random_bytes(16)),
                'data' => date('c'),
                'mensagem' => $mensagemCriptografada,
                'assinatura' => $assinatura
            ];

            $json = json_encode(
                $pacote,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            );

            if ($json === false) {
                throw new RuntimeException(
                    'Não foi possível criar o JSON do pacote.'
                );
            }

            return $json;

        } catch (Throwable $erro) {
            throw new RuntimeException(
                'Erro ao criar pacote: '
                . $erro->getMessage(),
                0,
                $erro
            );
        }
    }

    public static function ler(string $pacoteJson): array
    {
        try {
            $pacote = json_decode(
                $pacoteJson,
                true
            );

            if (!is_array($pacote)) {
                throw new RuntimeException(
                    'O pacote JSON é inválido.'
                );
            }

            $camposObrigatorios = [
                'versao',
                'id',
                'data',
                'mensagem',
                'assinatura'
            ];

            foreach ($camposObrigatorios as $campo) {
                if (!array_key_exists($campo, $pacote)) {
                    throw new RuntimeException(
                        "Campo obrigatório ausente: $campo"
                    );
                }
            }

            return $pacote;

        } catch (Throwable $erro) {
            throw new RuntimeException(
                'Erro ao ler pacote: '
                . $erro->getMessage(),
                0,
                $erro
            );
        }
    }
}