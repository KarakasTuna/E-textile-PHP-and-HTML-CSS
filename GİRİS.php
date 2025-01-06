<?php
// Output buffering başlatılır
ob_start(); 

session_start();

// Veritabanı bağlantısı
include("baglanti.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $username = trim($_POST["username"]);
    $password = trim($_POST["psw"]);
    $verification_code = trim($_POST["verification_code"]);

    // E-posta formatı kontrolü
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Geçersiz e-posta formatı.";
        exit;
    }

    // Kullanıcıyı veritabanında kontrol et
    $stmt = $conn->prepare("SELECT id, username, email, parola, dogrulama_kodu FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Şifre doğrulama
        if (password_verify($password, $user['parola'])) {
            // Doğrulama kodu kontrolü
            if ($verification_code === $user['dogrulama_kodu']) {
                $_SESSION['login'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];

                  // Oturum durumunu kontrol et
                   $is_logged_in = 'true';

                // Çıktı tamponlamayı temizle ve yönlendirmeyi yap
                ob_clean(); // Önceden tamponlanan tüm çıktıyı temizle
                header("Location: http://localhost/teksil/index.php"); // Yönlendirme
                exit;
            } else {
                echo "Geçersiz doğrulama kodu.";
            }
        } else {
            echo "Şifre yanlış.";
        }
    } else {
        echo "Kullanıcı adı veya e-posta bulunamadı.";
    }

    $stmt->close();
}

$conn->close();

// Output buffering'yi sonlandır
ob_end_clean(); 
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

.girisbtn {
  background-color: #04AA6D;
  color: white;
  padding: 16px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  opacity: 0.9;
}

.girisbtn:hover {
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

<form action="GİRİS.php" method="POST">
  <div class="container">
    <h1>GİRİŞ YAP</h1>
    <p>Hesap Giriş Yapmak için lütfen formu doldurun.</p>
    <hr>

    <label for="email"><b>E-posta</b></label>
    <input type="text" placeholder="E-postanızı Girin" name="email" id="email" class="
      <?php if (!empty($email_err)) { echo 'is-invalid'; } ?>" required>
    <div class="invalid-feedback"><?php echo $email_err; ?></div>

    <label for="psw"><b>Şifre</b></label>
    <input type="password" placeholder="Şifrenizi Girin" name="psw" id="psw" class="
      <?php if (!empty($password_err)) { echo 'is-invalid'; } ?>" required>
    <div class="invalid-feedback"><?php echo $password_err; ?></div>

    <label for="verification_code"><b>Doğrulama Kodu</b></label>
    <input type="text" placeholder="Doğrulama kodunuzu girin" name="verification_code" id="verification_code" class="
      <?php if (!empty($verification_err)) { echo 'is-invalid'; } ?>" required>
    <div class="invalid-feedback"><?php echo $verification_err; ?></div>

    <hr>
    <p>Hesap oluşturarak <a href="#">Şartlarımızı ve Gizlilik Politikamızı</a> kabul etmiş olursunuz.</p>

    
    <p>Hesabınız yok mu? <a href="kayıt.php">Kayıt olun</a></p> <!-- 'kayit.php' yerine kendi kayıt sayfanızın yolunu yazın -->
    <button type="submit" name="Giris" class="girisbtn">Giriş Yap</button>

  </div>
</form>

</body>
</html>
