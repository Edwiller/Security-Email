<?php

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Dotenv\Dotenv;

class EmailService
{
    public static function enviar(
        string $destinatario,
        string $assunto,
        string $mensagem
    ): void {
        try {

            require_once __DIR__ . '/../vendor/autoload.php';

            /*
             * Carrega as variáveis do arquivo .env
             */
            $dotenv = Dotenv::createImmutable(
                __DIR__ . '/..'
            );

            $dotenv->load();

            /*
             * Cria o objeto PHPMailer
             */
            $mail = new PHPMailer(true);

            /*
             * Configuração SMTP
             */
            $mail->isSMTP();

            $mail->Host =
                $_ENV['SMTP_HOST'];

            $mail->SMTPAuth = true;

            $mail->Username =
                $_ENV['SMTP_USERNAME'];

            $mail->Password =
                $_ENV['SMTP_PASSWORD'];

            $mail->SMTPSecure =
                PHPMailer::ENCRYPTION_STARTTLS;

            $mail->Port =
                (int) $_ENV['SMTP_PORT'];

            /*
             * Remetente
             */
            $mail->setFrom(
                $_ENV['SMTP_USERNAME'],
                'SecureMail'
            );

            /*
             * Destinatário
             */
            $mail->addAddress(
                $destinatario
            );

            /*
             * Conteúdo
             */
            $mail->isHTML(false);

            $mail->Subject =
                $assunto;

            $mail->Body =
                $mensagem;

            /*
             * Envia
             */
            $mail->send();

        } catch (Exception $erro) {

            throw new RuntimeException(
                'Erro ao enviar e-mail: '
                . $erro->getMessage(),
                0,
                $erro
            );

        } catch (Throwable $erro) {

            throw new RuntimeException(
                'Erro inesperado ao enviar e-mail: '
                . $erro->getMessage(),
                0,
                $erro
            );
        }
    }
}