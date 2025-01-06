<?php
session_start();

// Veritabanı bağlantısı
$host = "localhost";
$username = "root";
$password = "";
$dbname = "tekstil";
$conn = new mysqli($host, $username, $password, $dbname);

// Bağlantı hatası kontrolü
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// Gelen ürün ID'sini al
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Ürün bilgilerini veritabanından çek
$sql = "SELECT * FROM ürünler WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();

    // Sepet oturumunu başlat
    if (!isset($_SESSION['sepet'])) {
        $_SESSION['sepet'] = [];
    }

    // Ürünü sepete ekle veya var olan ürünün miktarını artır
    $urun_var = false;
    foreach ($_SESSION['sepet'] as &$urun) {
        if ($urun['id'] === $product['id']) {
            $urun['quantity']++;
            $urun_var = true;
            break;
        }
    }

    // Ürün sepette yoksa ekle
    if (!$urun_var) {
        $_SESSION['sepet'][] = [
            "id" => $product['id'],
            "name" => $product['name'],
            "price" => $product['price'],
            "quantity" => 1
        ];
    }
    
}

// Sepet görüntüleme sayfasına yönlendir
header("Location: sepet-goruntule.php");
exit;
?>
