<?php
// Veritabanı bağlantısı
$host = "localhost"; // Sunucu
$username = "root"; // Veritabanı kullanıcı adı
$password = ""; // Veritabanı şifresi
$dbname = "tekstil"; // Veritabanı adı


// Bağlantı oluştur
$conn = new mysqli($host, $username, $password, $dbname);

// Bağlantı hatası kontrolü
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}
else {
    echo "Bağlantı başarılı!"; // Debug için
}



?>