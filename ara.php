<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arama Sonuçları</title>
    <link rel="stylesheet" href="arama.css"> <!-- CSS dosyasını bağlama -->
</head>
<body>
    <?php
    // Veritabanı bağlantısı
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "tekstil";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Bağlantı hatası: " . $conn->connect_error);
    }

    if (isset($_GET['Ara'])) {
        $arama = $conn->real_escape_string($_GET['Ara']);
        $sql = "SELECT * FROM ürünler WHERE name LIKE '%$arama%' OR text LIKE '%$arama%'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo "<h2>Arama Sonuçları:</h2>";
            while ($row = $result->fetch_assoc()) {
                // image1 sütunundaki fotoğrafları virgüle göre ayır
                $images = explode(',', $row['image']); // image1 sütunu: "foto1.jpg,foto2.jpg"
                $imagePath1 = "/teksil/images/" . trim($images[0]); // İlk fotoğraf
                $imagePath2 = "/teksil/images/" . trim($images[1]); // İkinci fotoğraf
                $productId = $row['id']; // Ürün ID'sini al
    
                // Fotoğrafların varlığını kontrol et
                if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $imagePath1)) {
                    $imagePath1 = "/teksil/images/default.jpg"; // Varsayılan birinci fotoğraf
                }
                if (!file_exists($_SERVER['DOCUMENT_ROOT'] . $imagePath2)) {
                    $imagePath2 = "/teksil/images/default.jpg"; // Varsayılan ikinci fotoğraf
                }
    
                // Ürünü HTML olarak görüntüle
                echo "
                <div class='product-card'>
                    <div class='product-images'>
                        <img src='$imagePath1' alt='" . htmlspecialchars($row['name']) . "'>
                        <img src='$imagePath2' alt='" . htmlspecialchars($row['name']) . "'>
                    </div>
                    <h3>" . htmlspecialchars($row['name']) . "</h3>
                    <p><strong>" . number_format($row['price'], 2) . " TL</strong></p>
                    <a href='ürün2.php?id=$productId'><button>Satın Al</button></a>
                </div>";
            }
        } else {
            echo "Sonuç bulunamadı.";
        }
    }
    
    $conn->close();
    ?>