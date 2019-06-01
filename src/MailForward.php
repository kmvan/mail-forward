<?php

declare(strict_types = 1);

/**
 * INN Mail Forward.
 *
 * @version 1.0.0
 *
 * @author Km.Van <kmvan.com@email.com>
 * @license GPLv2
 *
 * @see https://inn-studio.com
 *
 * @todo Add attachment
 */

namespace InnStudio\MailForward;

use PHPMailer\PHPMailer\PHPMailer;

final class MailForward
{
    private $data = [];

    public function __construct()
    {
        $this->checkData();
        $this->send();
    }

    private function checkData(): void
    {
        $this->data = \json_decode(\file_get_contents('php://input') ?: '', true) ?: [];

        if ( ! $this->data) {
            $this->die([
                'code' => 1,
                'msg'  => 'Invalid data',
            ]);
        }
    }

    private function send(): void
    {
        [
            'smtpData' => $smtpData,
            'mailData' => $mailData,
        ] = $this->data;
        [
            $to,
            $subject,
            $body,
        ] = $mailData;

        $mail             = new PHPMailer();
        $mail->SMTPAuth   = true;
        $mail->Host       = $smtpData['Host'];
        $mail->Username   = $smtpData['Username'];
        $mail->Password   = $smtpData['Password'];
        $mail->SMTPSecure = $smtpData['SMTPSecure'];
        $mail->Port       = (int) $smtpData['Port'];
        $mail->Subject    = $subject;
        $mail->Body       = $body;
        $mail->CharSet    = 'UTF-8';
        $mail->isSMTP();
        $mail->setFrom($smtpData['From'], $smtpData['FromName']);

        if (\is_array($to)) {
            \array_map(function (string $to) use ($mail): void {
                $mail->addAddress($to);
            }, $to);
        }

        $mail->addAddress($to);
        $mail->isHTML(true);
        $sent = $mail->send();

        if ($sent) {
            $this->die();
        }
    }

    private function die(array $json = []): void
    {
        \header('Content-Type: application/json');

        die(\json_encode(\array_merge([
            'code' => 0,
            'msg'  => 'OK',
        ], $json)));
    }
}
