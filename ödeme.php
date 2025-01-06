<?php
// Veritabanı bağlantısı
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "tekstil"; // Veritabanı adınızı buraya yazın

$conn = new mysqli($servername, $username, $password, $dbname);

// Bağlantı hatası kontrolü
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Form verilerini işle
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Formdan gelen veriler
    $name = $_POST['name'];
    $card_number = $_POST['card-number'];
    $expiry_date = $_POST['expiry-date'];
    $cvv = $_POST['cvv'];
    $amount = $_POST['amount'];

    // SQL sorgusu ile ödeme verilerini veritabanına kaydetme
    $stmt = $conn->prepare("INSERT INTO odemeler (name, card_number, expiry_date, cvv, amount) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $card_number, $expiry_date, $cvv, $amount);

    if ($stmt->execute()) {
        echo "<script>
                alert('Ödeme başarıyla alındı!');
                setTimeout(function() {
                    window.location.href = 'index.php';
                }, 3000); // 3 saniye sonra yönlendirme
              </script>";
    } else {
        echo "<script>alert('Ödeme alınırken bir hata oluştu. Lütfen tekrar deneyin.');</script>";
    }

    // Bağlantıyı kapatma
    $stmt->close();
    $conn->close();
}
?>





<?php
session_start();

// Sepet toplam tutarını session'dan al
$toplam_tutar = isset($_SESSION['toplam_tutar']) ? $_SESSION['toplam_tutar'] : 0;
?>










<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ödeme Sistemi</title>
    <link rel="stylesheet" href="ödeme.css">
</head>
<body>
    <div class="container">
        <h1>Ödeme Bilgilerini Girin</h1>
        <form action="" method="POST">
            <div class="form-group">
                <label for="name">Ad Soyad</label>
                <input type="text" id="name" name="name" placeholder="Adınızı ve Soyadınızı Girin" required>
            </div>
            <div class="form-group">
                <label for="card-number">Kart Numarası</label>
                <input type="text" id="card-number" name="card-number" placeholder="Kart Numarasını Girin" required>
            </div>
            <div class="form-group">
                <label for="expiry-date">Son Kullanma Tarihi</label>
                <input type="text" id="expiry-date" name="expiry-date" placeholder="MM/YY" required>
            </div>
            <div class="form-group">
                <label for="cvv">CVV</label>
                <input type="text" id="cvv" name="cvv" placeholder="CVV Kodu" required>
            </div>
            <div class="form-group">
                <label for="amount">Ödenecek Tutar</label>
                <input type="number" id="amount" name="amount" placeholder="Ödenecek Tutar" value="<?php echo $toplam_tutar; ?>" required readonly>
            </div>
            <div class="form-group">
                <button type="submit" class="pay-button">Ödeme Yap</button>
            </div>
        </form>
    </div>
</body>
</html>
