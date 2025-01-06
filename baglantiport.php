<?php
//Mail gönderme işlemi yapılıyormu diye oluşturulan bir örnek


$host = "smtp.gmail.com";
$port = 587; // Veya 465

$connection = fsockopen($host, $port, $errno, $errstr, 10);

if (!$connection) {
    echo "Port $port üzerinde $host bağlantısı başarısız: $errstr ($errno)";
} else {
    echo "Port $port üzerinde $host bağlantısı başarılı!";
    fclose($connection);
}
?>


<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//sonra çözülecek

require 'C:/xampp/htdocs/teksil/phpmailer/src/PHPMailer.php';
require 'C:/xampp/htdocs/teksil/phpmailer/src/SMTP.php';
require 'C:/xampp/htdocs/teksil/phpmailer/src/Exception.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["sendMail"])) {
    $email = $_POST["email"];
    $message = $_POST["message"];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Geçerli bir e-posta adresi giriniz.";
    } else {
        $mail = new PHPMailer(true);

        try {
            // Mail sunucusu ayarları
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'karakastuna457@gmail.com'; // Gmail adresinizi yazın
            $mail->Password = 'nbwa ccgn dvar wepk';  // Gmail uygulama şifrenizi yazın
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );
            // Gönderen ve alıcı bilgileri
            $mail->setFrom('karakastuna457@gmail.com', 'Test Mail');
            $mail->addAddress($email);

            // Mail içeriği
            $mail->isHTML(true);
            $mail->Subject = 'Test E-Postası';
            $mail->Body = "<h1>Merhaba!</h1><p>Bu bir test e-postasıdır.</p><p>Mesajınız: $message</p>";

            // Gönderim
            $mail->send();
            echo "E-posta başarıyla gönderildi!";
        } catch (Exception $e) {
            echo "E-posta gönderim hatası: {$mail->ErrorInfo}";
        }
    }
}
?>




<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basit Mail Formu</title>
</head>
<body>
    <h1>Mail Gönderme Formu</h1>
    <form method="POST" action="">
        <label for="email">E-posta Adresi:</label><br>
        <input type="email" id="email" name="email" required><br><br>
        <label for="message">Mesaj:</label><br>
        <textarea id="message" name="message" rows="4" cols="50" required></textarea><br><br>
        <button type="submit" name="sendMail">Gönder</button>
    </form>
</body>
</html>
