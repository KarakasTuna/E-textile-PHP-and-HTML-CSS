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

// Veritabanından verileri çekme
$sql = "
    SELECT 
        ürünler.name AS urun_adi, 
        ürünler.price AS urun_fiyat, 
        odemeler.amount AS toplam_tutar, 
        odemeler.payment_date AS siparis_tarihi 
    FROM 
        ürünler
    JOIN 
        odemeler 
    ON 
        ürünler.id = odemeler.id
    ORDER BY 
        odemeler.payment_date DESC
";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siparişlerim</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Genel stil ayarları */
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
            color: #333;
            box-sizing: border-box;
        }

        /* Sayfa konteyneri */
        .container {
            width: 80%;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        }

        h1 {
            font-size: 36px;
            color: #34495e;
            text-align: center;
            margin-bottom: 40px;
            font-weight: 700;
        }

        /* Tablo düzeni */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th, td {
            padding: 16px;
            text-align: left;
            font-size: 16px;
            border-bottom: 1px solid #ececec;
        }

        th {
            background-color: #2980b9;
            color: white;
            font-weight: 700;
        }

        td {
            background-color: #f9f9f9;
        }

        tr:hover td {
            background-color: #f1f1f1;
        }

        /* Fiyatlar */
        .price {
            color: #27ae60;
            font-weight: 600;
        }

        .total-price {
            font-size: 18px;
            color: #2c3e50;
            font-weight: 700;
        }

        .total-amount {
            color: #e74c3c;
            font-size: 20px;
            font-weight: 700;
        }

        /* Buton ve geri dönüş */
        .button-container {
            text-align: center;
            margin-top: 40px;
        }

        .back-button {
            background-color: #3498db;
            color: white;
            padding: 14px 40px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .back-button:hover {
            background-color: #2980b9;
        }

        /* Mobil uyumlu */
        @media (max-width: 768px) {
            .container {
                width: 95%;
                padding: 20px;
            }

            h1 {
                font-size: 28px;
            }

            table {
                font-size: 14px;
            }

            .back-button {
                width: 100%;
                padding: 16px;
                font-size: 16px;
            }
        }
    </style>
</head>

<body>

<!-- Sayfa içeriği -->
<div class="container">
    <h1>Siparişlerim</h1>

    <!-- Sipariş Tablosu -->
    <table>
        <thead>
            <tr>
                <th>Ürün Adı</th>
                <th>Fiyat</th>
                <th>Toplam Tutar</th>
                <th>Sipariş Tarihi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result->num_rows > 0): ?>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['urun_adi']); ?></td>
                        <td>TL <?php echo number_format($row['urun_fiyat'], 2, ',', '.'); ?></td>
                        <td class="price">TL <?php echo number_format($row['toplam_tutar'], 2, ',', '.'); ?></td>
                        <td><?php echo date("d M Y", strtotime($row['siparis_tarihi'])); ?></td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Henüz sipariş yok.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Geri Dön Butonu -->
    <div class="button-container">
        <a href="index.php" class="back-button">Ana Sayfaya Dön</a>
    </div>
</div>

</body>
</html>
