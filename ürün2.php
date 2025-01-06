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

// URL'den gelen id'yi al
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Geçerli bir id olup olmadığını kontrol et
if ($product_id <= 0) {
    echo "Geçersiz ürün ID'si.";
    exit;
}

// Veritabanından ürün bilgilerini çek
$sql = "SELECT * FROM ürünler WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

// Ürün kontrolü
if ($result->num_rows > 0) {
    $product = $result->fetch_assoc(); // Ürün bilgilerini dizi olarak al
} else {
    echo "Ürün bulunamadı.";
    exit;
}



//$stmt->close();
$conn->close();





?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?></title>
    <link rel="stylesheet" href="ürünstyle.css">

 <style>

  /*sağ sol butonu*/
  /* Genel stil */
 .product-detail {
    text-align: center;
    font-family: Arial, sans-serif;
 }

 .main-image {
    max-width: 100%;
    height: auto;
    transition: transform 0.3s ease;
    cursor: pointer;
    margin-bottom: 20px;
 }

 .main-image:hover {
    transform: scale(1.1);  /* Görüntü büyütme efekti */
 }

 /* Navigasyon butonları */
 .navigation-buttons {
    position: relative;
    display: flex;
    justify-content: center;
    margin-top: 10px;
    gap: 40px; /* Butonlar arasına boşluk ekler */
 }

 .nav-button {
    background-color: #333;
    color: white;
    padding: 15px;
    font-size: 24px;
    cursor: pointer;
    border-radius: 50%;
    width: 60px; /* Buton boyutunu artırdık */
    height: 60px; /* Buton boyutunu artırdık */
    display: flex;
    justify-content: center;
    align-items: center;
    transition: background-color 0.3s, transform 0.3s;
    text-decoration: none;
 }

 .nav-button:hover {
    background-color: #555;  /* Hover ile koyulaşma */
    transform: scale(1.1);  /* Hover ile buton büyür */
 }

 /* Sepete Ekle butonu */
 .buy-button {
    padding: 12px 25px;
    background-color: #4CAF50;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    margin-top: 20px;
    font-size: 18px;
 }

  .buy-button:hover {
    background-color: #45a049;
 }

 /* Metin stil */
 p {
    font-size: 16px;
    margin: 10px 0;
 }

 strong {
    font-weight: bold;
 }

 body {
        margin: 0;
        font-family: Arial, sans-serif;
        background-image: url('images/foto6.jpg'); /* Buraya kendi resim yolunuzu ekleyin */
        background-size: 2000px;
        background-position: center;
        background-repeat: no-repeat;
        min-height: 100vh;
    }

    /* Sayfa içeriğine opak bir arka plan eklemek */
    .product-detail {
        background-color: rgba(255, 255, 255, 0.8); /* Beyaz yarı şeffaf arka plan */
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
        max-width: 600px;
        margin: 50px auto;
    }













 </style>
</head>







<body>







     








    <div class="Katagoriphp">
        <ul>
            <li><a href="Erkek.html">
                    <h3>Erkek Giyim</h3>
                </a></li>
            <li><a href="Kadın.html">
                    <h3>Kadın Giyim</h3>
                </a></li>
            <li><a href="Bebek.html">
                    <h3>Bebek Giyim</h3>
                </a></li>
            <li><a href="Cocuk.html">
                    <h3>Çocuk Giyim</h3>
                </a></li>
        </ul>

    </div>




    <div class="product-detail">
     <h1><?php echo htmlspecialchars($product['name']); ?></h1>

     <?php
     // Veritabanındaki resim yollarını virgülle ayırarak alıyoruz
     $images = explode(',', $product['image']);
     $total_images = count($images); // Toplam resim sayısı
     $current_image_index = isset($_GET['image']) ? (int)$_GET['image'] : 0; // Geçerli resim indeksi

     // Eğer geçerli resim indeksi sınırları aşarsa, ilk resme yönlendiriyoruz
     if ($current_image_index < 0) {
        $current_image_index = $total_images - 1;
     } elseif ($current_image_index >= $total_images) {
        $current_image_index = 0;
     }

     // Geçerli resmi almak
     $current_image = $images[$current_image_index];
     ?>

     <!-- Ana resim -->
     <img src="images/<?php echo $current_image; ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="max-width: 300px;">

     <!-- Sağ ve sol ok butonları -->
     <div class="navigation-buttons">
        <a href="?id=<?php echo $product_id; ?>&image=<?php echo ($current_image_index - 1 + $total_images) % $total_images; ?>" class="nav-button">‹</a>
        <a href="?id=<?php echo $product_id; ?>&image=<?php echo ($current_image_index + 1) % $total_images; ?>" class="nav-button">›</a>
     </div>

     <p><strong>Fiyat:</strong> <?php echo htmlspecialchars($product['price']); ?> TL</p>
     <p><strong>Açıklama:</strong> <?php echo htmlspecialchars($product['text']); ?></p>
     <a href="sepet.php?id=<?php echo $product_id; ?>" class="buy-button" onclick="return showConfirmation();">Sepete Ekle</a>












         




     <div class="rating">
        <span>&#9733;</span>
        <span>&#9733;</span>
        <span>&#9733;</span>
        <span>&#9734;</span>
        <span>&#9734;</span>

      </div>    




     </div>
    


    


<!-- Yorumlar Kısmı -->
<div class="comments-section">
    <h2>Yorumlar</h2>

    <!-- Sabit Yorumlar -->
    <div class="comment">
        <div class="comment-header">
            <span class="username">Ahmet Y.</span>
            <div class="rating">
                <span>&#9733;</span>
                <span>&#9733;</span>
                <span>&#9733;</span>
                <span>&#9734;</span>
                <span>&#9734;</span>
            </div>
        </div>
        <p>Harika bir ürün! Kesimi çok iyi, rahat ve şık. Çok memnun kaldım.</p>
    </div>

    <div class="comment">
        <div class="comment-header">
            <span class="username">Elif K.</span>
            <div class="rating">
                <span>&#9733;</span>
                <span>&#9733;</span>
                <span>&#9733;</span>
                <span>&#9733;</span>
                <span>&#9734;</span>
            </div>
        </div>
        <p>Fiyatına göre çok kaliteli bir ürün. Tam bedenime uygun geldi.</p>
    </div>

    <div class="comment">
        <div class="comment-header">
            <span class="username">Murat S.</span>
            <div class="rating">
                <span>&#9733;</span>
                <span>&#9733;</span>
                <span>&#9734;</span>
                <span>&#9734;</span>
                <span>&#9734;</span>
            </div>
        </div>
        <p>Ürünü beğendim, ancak biraz daha geniş olabilirdi. Yine de kullanışlı.</p>
    </div>

    <!-- Dinamik Yorumlar İçin Alan -->
    <div id="dynamic-comments">
        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = htmlspecialchars($_POST['username']);
            $rating = (int)$_POST['rating'];
            $comment = htmlspecialchars($_POST['comment']);

            echo '<div class="comment">';
            echo '<div class="comment-header">';
            echo '<span class="username">' . $username . '</span>';
            echo '<div class="rating">';
            for ($i = 1; $i <= 5; $i++) {
                echo $i <= $rating ? '&#9733;' : '&#9734;';
            }
            echo '</div>';
            echo '</div>';
            echo '<p>' . $comment . '</p>';
            echo '</div>';
        }
        ?>
    </div>

    <!-- Yorum Ekleme Formu -->
    <div class="add-comment">
        <h3>Yorum Yap</h3>
        <form method="post">
            <input type="text" name="username" placeholder="Adınız" required><br>
            <select name="rating" required>
                <option value="">Puan Verin</option>
                <option value="1">&#9733;</option>
                <option value="2">&#9733;&#9733;</option>
                <option value="3">&#9733;&#9733;&#9733;</option>
                <option value="4">&#9733;&#9733;&#9733;&#9733;</option>
                <option value="5">&#9733;&#9733;&#9733;&#9733;&#9733;</option>
            </select><br>
            <textarea name="comment" placeholder="Yorumunuz" required></textarea><br>
            <button type="submit">Yorum Ekle</button>
        </form>
    </div>
</div>

    


</body>
</html>
