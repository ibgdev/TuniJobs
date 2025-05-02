<?php
// src/Service/MailerService.php

namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailerService
{
    public function sendEmail($to, $subject, $body)
    {
        // Looking to send emails in production? Check out our Email API/SMTP product!
        $mail = new PHPMailer();


        try {
            //Server settings
            $mail->isSMTP();
            $mail->Host = 'sandbox.smtp.mailtrap.io';
            $mail->SMTPAuth = true;
            $mail->Port = 2525;
            $mail->Username = 'c350196cb88018';
            $mail->Password = 'f2c428521345dc';
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64'; 

            //Recipients
            $mail->setFrom('no-reply@tunijobs.com', 'TuniJobs'); // Set sender
            $mail->addAddress($to);                              // Add recipient

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $this->buildHtmlTemplate($subject, $body);
            $mail->AltBody = $body;
            $mail->send();
            return '✅ Email sent successfully.';
        } catch (Exception $e) {
            return '❌ Error: ' . $mail->ErrorInfo;
        }
    }

    private function buildHtmlTemplate(string $title, string $message): string
    {
        return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            color: #2b2d42;
            padding: 20px;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #4361ee;
            color: white;
            padding: 10px 20px;
            border-radius: 6px 6px 0 0;
            font-size: 20px;
        }
        .content {
            margin-top: 20px;
            font-size: 16px;
            line-height: 1.5;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            color: #8d99ae;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">$title</div>
        <div class="content">
            <p>$message</p>
            <p>Merci pour votre confiance,<br>L’équipe TuniJobs</p>
        </div>
        <div class="footer">
            © TuniJobs 2025. Tous droits réservés.
        </div>
    </div>
</body>
</html>
HTML;
    }
}
