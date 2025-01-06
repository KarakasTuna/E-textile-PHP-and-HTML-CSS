<?php
session_start();

ini_set('display_errors', 1); // Hata görüntülemeyi açar
error_reporting(E_ALL); // Tüm hataları raporlar

// Veritabanı bağlantısı
$host = "localhost"; // Sunucu
$username = "root"; // Veritabanı kullanıcı adı
$password = ""; // Veritabanı şifresi
$dbname = "tekstil"; // Veritabanı adı

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Veritabanı bağlantısı başarısız: " . $e->getMessage());
}

// Sepet bilgilerini al
$sepet = isset($_SESSION['sepet']) ? $_SESSION['sepet'] : [];
$toplam_fiyat = 0;

// Ürün bilgilerini veritabanından al ve sepeti güncelle
foreach ($sepet as $key => $urun) {
    $stmt = $pdo->prepare("SELECT * FROM ürünler WHERE id = :id");
    $stmt->execute(['id' => $urun['id']]);
    $urun_bilgisi = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($urun_bilgisi) {
        // Ürün bilgilerini güncelle
        $sepet[$key]['name'] = $urun_bilgisi['name']; // Adı
        $sepet[$key]['price'] = $urun_bilgisi['price']; // Fiyatı
        $sepet[$key]['image'] = $urun_bilgisi['image']; // Resmi
    } else {
        // Ürün veritabanında yoksa sepetten çıkar
        unset($sepet[$key]);
    }
}

// Toplam tutarı hesapla
foreach ($sepet as $urun) {
    $toplam_fiyat += $urun['price'] * $urun['quantity'];
}

// Kargo ücreti, KDV ve toplam hesaplama
$kargo_ucreti = 39.99; // Sabit kargo ücreti
$kdv_orani = 0.18;
$kdv = $toplam_fiyat * $kdv_orani;
$genel_toplam = $toplam_fiyat + $kargo_ucreti + $kdv;

// Kullanıcı oturum durumu kontrolü
$is_logged_in = isset($_SESSION['login']) && $_SESSION['login'] === true ? 'true' : 'false';

// Sepet toplamını session'a kaydet
$_SESSION['toplam_tutar'] = $genel_toplam;
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alışveriş Sepeti</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            background: #fff;
        }
        .header {
            background-color: #ffa500;
            padding: 15px;
            color: white;
            text-align: center;
            font-size: 24px;
        }
        .product {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 20px 0;
            padding: 15px;
            border-bottom: 1px solid #ddd;
        }
        .product img {
            max-width: 150px;
            height: auto;
            margin-right: 20px;
            border: 2px solid #ddd;
            padding: 5px;
            border-radius: 8px;
        }
        .product-info {
            flex: 1;
        }
        .product-actions {
            text-align: right;
        }
        .order-summary {
            border: 1px solid #ddd;
            padding: 15px;
            margin-top: 20px;
        }
        .order-summary h3 {
            margin: 0 0 15px;
        }
        .order-summary p {
            margin: 5px 0;
            display: flex;
            justify-content: space-between;
        }
        .order-summary .total {
            font-size: 18px;
            font-weight: bold;
        }
        .checkout-button {
            display: block;
            width: 100%;
            padding: 12px;
            background-color: #ffa500;
            color: white;
            text-align: center;
            text-decoration: none;
            margin-top: 10px;
            font-size: 16px;
            border: none;
            cursor: pointer;
        }
        .checkout-button:hover {
            background-color: #e69500;
        }
    </style>
</head>
<body>
    <div class="header">Alışveriş Sepeti</div>
    <div class="container">
        <?php if (empty($sepet)): ?>
            <p>Sepetinizde ürün yok.</p>
        <?php else: ?>
            <!-- Ürün Listesi -->
            <?php foreach ($sepet as $urun): ?>
            <div class="product">
                <?php
                // Resimleri ayrıştır (örneğin virgül ile ayrılmış)
                $images = isset($urun['image']) ? explode(',', $urun['image']) : []; // Eğer varsa ayır, yoksa boş bir dizi yap
                $imagePath = !empty($images) && file_exists(__DIR__ . "/images/" . trim($images[0])) 
                    ? "http://localhost/teksil/images/" . trim($images[0]) 
                    : "http://localhost/teksil/images/default.jpg";
                ?>

                <img src="<?php echo htmlspecialchars($imagePath); ?>" alt="<?php echo htmlspecialchars($urun['name']); ?>">
                <div class="product-info">
                    <h4><?php echo htmlspecialchars($urun['name']); ?></h4>
                    <p>Fiyat: <?php echo htmlspecialchars($urun['price']); ?> TL</p>
                    <p>Adet: <?php echo htmlspecialchars($urun['quantity']); ?></p>
                </div>
                <div class="product-actions">
                    <p>Toplam: <?php echo htmlspecialchars($urun['price'] * $urun['quantity']); ?> TL</p>
                    <a href="ürün-kaldır.php?id=<?php echo $urun['id']; ?>" style="color: red;">Kaldır</a>
                </div>
            </div>
            <?php endforeach; ?>
            
            <!-- Sipariş Özeti -->
            <div class="order-summary">
                <h3>Sipariş Özeti</h3>
                <p><span>Ürünlerin Toplamı:</span> <span><?php echo number_format($toplam_fiyat, 2); ?> TL</span></p>
                <p><span>Kargo Ücreti:</span> <span><?php echo number_format($kargo_ucreti, 2); ?> TL</span></p>
                <p><span>KDV (%<?php echo $kdv_orani * 100; ?>):</span> <span><?php echo number_format($kdv, 2); ?> TL</span></p>
                <p class="total"><span>Genel Toplam:</span> <span><?php echo number_format($genel_toplam, 2); ?> TL</span></p>
                <!-- Sepeti onayla butonunun JavaScript ile yönlendirmesi -->
                <button class="checkout-button" onclick="redirectToPayment()">Sepeti Onayla</button>
            </div>

            <script>
                // PHP'den gelen oturum durumu
                const isLoggedIn = "<?php echo $is_logged_in; ?>"; // PHP'den gelen değeri string olarak al

                // Ödeme sayfasına yönlendirme fonksiyonu
                function redirectToPayment() {
                    if (isLoggedIn === 'true') {
                        // Kullanıcı giriş yapmışsa ödeme sayfasına yönlendir
                        window.location.href = "ödeme2.php";
                    } else {
                        // Kullanıcı giriş yapmamışsa giriş sayfasına yönlendir
                        alert("Ödeme sayfasına erişebilmek için lütfen giriş yapınız.");
                        window.location.href = "GİRİS.php";
                    }
                }
            </script> 

        <?php endif; ?>
    </div>
</body>
</html>
