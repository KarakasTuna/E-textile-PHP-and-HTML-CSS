<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//sonra çözülecek

require 'C:/xampp/htdocs/teksil/phpmailer/src/PHPMailer.php';
require 'C:/xampp/htdocs/teksil/phpmailer/src/SMTP.php';
require 'C:/xampp/htdocs/teksil/phpmailer/src/Exception.php';

// Veritabanı bağlantısı
include("baglanti.php");

$username_err = $email_err = $password_err = $parolatkr_err = "";

if (isset($_POST["kaydet"])) {
    // Kullanıcı adı kontrolü
    if (empty($_POST["username"])) {
        $username_err = "Kullanıcı adı boş geçilemez";
    } else if (strlen($_POST["username"]) < 8) {
        $username_err = "Kullanıcı adı kısa";
    } else if (!preg_match('/^[a-z\d_]{5,20}$/i', $_POST["username"])) {
        $username_err = "Kullanıcı adı büyük/küçük harf ve rakamdan oluşmalı";
    } else {
        $username = $_POST["username"];
    }

    // E-posta kontrolü
    if (empty($_POST["email"])) {
        $email_err = "E-posta boş geçilemez";
    } else if (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email_err = "Geçerli bir e-posta adresi girin";
    } else {
        $email = $_POST["email"];
    }

    // Şifre doğrulama
    if (empty($_POST["psw"])) {
        $password_err = "Şifre boş geçilemez";
    } else if (strlen($_POST["psw"]) < 6) {
        $password_err = "Şifre çok kısa, en az 6 karakter olmalı";
    } else {
        $password = password_hash($_POST["psw-repeat"], PASSWORD_DEFAULT);
    }

    // Şifre tekrar kontrolü
    if (empty($_POST["psw-repeat"])) {
        $parolatkr_err = "Şifre tekrar kısmı boş geçilemez";
    } else if ($_POST["psw-repeat"] != $_POST["psw"]) {
        $parolatkr_err = "Şifreler eşleşmiyor";
    }

    // Eğer tüm girişler geçerliyse
    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($parolatkr_err)) {
        // Doğrulama Kodu Oluştur
        $verification_code = rand(100000, 999999);

        // Veritabanına kaydetme işlemi
        $hashed_password = password_hash($_POST["psw-repeat"], PASSWORD_DEFAULT);
        $query = "INSERT INTO users (username, email, parola, dogrulama_kodu) VALUES ('$username', '$email', '$hashed_password', '$verification_code')";

        if (mysqli_query($conn, $query)) {
            // PHPMailer ile E-posta Gönder
            $mail = new PHPMailer();

            try {
                // SMTP ayarları
                $mail->isSMTP();
                //$mail->SMTPDebug = 2; // Debug özelliğini aç, hata mesajlarını görmek için
                $mail->SMTPDebug = SMTP::DEBUG_OFF;
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'karakastuna457@gmail.com'; // Gmail adresinizi girin
                $mail->Password = 'nbwa ccgn dvar wepk';   // Gmail uygulama şifreniz
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                //$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port =587;

                $mail->SMTPOptions = array(
                  'ssl' => array(
                      'verify_peer' => false,
                      'verify_peer_name' => false,
                      'allow_self_signed' => true
                  )
               );




                // Gönderen ve Alıcı Bilgileri
                $mail->setFrom('karakastuna457@gmail.com', 'Kayıt Sistemi');
                $mail->addAddress($email, $username);

                // E-posta İçeriği
                $mail->isHTML(true);
                $mail->Subject = 'Hesap Doğrulama Kodu';
                $mail->Body = "<p>Merhaba <b>$username</b>,</p>
                               <p>Hesabınızı doğrulamak için aşağıdaki kodu kullanın:</p>
                               <h3>$verification_code</h3>
                               <p>Bu kodun geçerlilik süresi 10 dakikadır.</p>";

                // E-posta gönderme
                $mail->send();
                echo '<div class="alert alert-success">Doğrulama kodu e-posta adresinize gönderildi.</div>';

                 // Başarılı kayıt sonrası yönlendirme
                 header("Location: GİRİS.php");
            } catch (Exception $e) {
                echo '<div class="alert alert-danger">E-posta gönderiminde bir hata oluştu: ' . $mail->ErrorInfo . '</div>';
            }
        } else {
            echo '<div class="alert alert-danger">Kayıt sırasında bir hata oluştu: ' . mysqli_error($conn) . '</div>';
        }
    }
}
?>








<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body {
  font-family: Arial, Helvetica, sans-serif;
  background-color: black;
}

* {
  box-sizing: border-box;
}

.container {
  padding: 16px;
  background-color: white;
}

input[type=text], input[type=password] {
  width: 100%;
  padding: 15px;
  margin: 5px 0 22px 0;
  display: inline-block;
  border: none;
  background: #f1f1f1;
}

input[type=text].is-invalid, input[type=password].is-invalid {
  border: 2px solid red;
}

input[type=text]:focus, input[type=password]:focus {
  background-color: #ddd;
  outline: none;
}

.invalid-feedback {
  color: red;
  font-size: 0.9em;
  margin-top: -15px;
  margin-bottom: 10px;
  display: none;
}

.is-invalid + .invalid-feedback {
  display: block;
}

hr {
  border: 1px solid #f1f1f1;
  margin-bottom: 25px;
}

.registerbtn {
  background-color: #04AA6D;
  color: white;
  padding: 16px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  opacity: 0.9;
}

.registerbtn:hover {
  opacity: 1;
}

a {
  color: dodgerblue;
}

.signin {
  background-color: #f1f1f1;
  text-align: center;
}
</style>
</head>
<body>

<form action="" method="POST">
  <div class="container">
    <h1>Kayıt Ol</h1>
    <p>Hesap oluşturmak için lütfen formu doldurun.</p>
    <hr>

    <label for="username"><b>Kullanıcı Adı</b></label>
    <input type="text" placeholder="Kullanıcı Adınızı Girin" name="username" id="username" class="
    
    <?php
    
    if (!empty($username_err)) 
    {
        echo "is-invalid";
    }
    
    
    
    ?>
    
   " required>
    <div class="invalid-feedback">
         <?php
         echo $username_err;
         ?>
     </div>

    <label for="email"><b>E-posta</b></label>
    <input type="text" placeholder="E-postanızı Girin" name="email" id="email" class="
    
    
     <?php
      if (!empty($email_err)) 
     {
        echo "is-invalid";
     }
     ?>

    " required>
    <div class="invalid-feedback">
     <?php
      echo $email_err;
     ?>
    </div>

    <label for="psw"><b>Şifre</b></label>
    <input type="password" placeholder="Şifrenizi Girin" name="psw" id="psw" class="
     <?php
        if (!empty($password_err)) 
     {
        echo "is-invalid";
     }
    
    ?>
    
    " required>
    <div class="invalid-feedback"><?php echo $password_err ?></div>

    <label for="psw-repeat"><b>Şifreyi Tekrarla</b></label>
    <input type="password" placeholder="Şifrenizi Tekrar Girin" name="psw-repeat" id="psw-repeat" required>
    <div class="invalid-feedback"><?php echo $parolatkr_err ?></div>

    <hr>
    <p>Hesap oluşturarak <a href="#">Şartlarımızı ve Gizlilik Politikamızı</a> kabul etmiş olursunuz.</p>
    <p>Zaten bir hesabınız var mı?  <a href="GİRİS.php">Giris Yap</a></p> 
    

    <button type="submit" name="kaydet" class="registerbtn">Kayıt Ol</button>
  </div>
  
 
</form>

</body>
</html>


