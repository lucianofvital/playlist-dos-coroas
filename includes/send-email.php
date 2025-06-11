<?php
<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

function sendDownloadEmail($nome, $email) {
    $mail = new PHPMailer(true);
    $downloadLink = "https://mega.nz/folder/LAQ3kTzC#Nmch1j_w3QX7kmNDsxQT9g";

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'playlistmusical90@gmail.com'; // Seu email
        $mail->Password = '2872 153 403'; // Sua senha de app
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;
        $mail->CharSet = 'UTF-8';

        $mail->setFrom('playlistmusical90@gmail.com', 'Playlist dos Coroas');
        $mail->addAddress($email, $nome);
        $mail->isHTML(true);
        $mail->Subject = 'Seu Download da Playlist dos Coroas está Pronto!';
        
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;'>
                <h2>Olá, {$nome}!</h2>
                <p>Seu pagamento foi confirmado! Clique no botão abaixo para baixar sua playlist:</p>
                <p><a href='{$downloadLink}' 
                      style='background: linear-gradient(45deg, #ff6b6b, #ff8e53);
                             color: white;
                             padding: 12px 25px;
                             text-decoration: none;
                             border-radius: 5px;
                             display: inline-block;'>
                    BAIXAR PLAYLIST AGORA
                </a></p>
            </div>";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erro ao enviar email: {$mail->ErrorInfo}");
        return false;
    }
}