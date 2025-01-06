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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
 // Formdan gelen verileri al
 $name = $_POST['name'] ?? '';
 $card_number = $_POST['card-number'] ?? '';
 $expiry_date = $_POST['expiry-date'] ?? '';
 $cvv = $_POST['cvv'] ?? '';
 $amount = $_POST['amount'] ?? 0;
 $email = $_POST['email'] ?? '';
 $address = $_POST['address'] ?? '';
 $city = $_POST['city'] ?? '';
 $zip = $_POST['zip'] ?? '';

 // Hata ayıklama: Verileri ekrana yazdır
  echo "<pre>";
  print_r($_POST);
  echo "</pre>";

  // Alanların boş olup olmadığını kontrol et
  if (empty($name) || empty($card_number) || empty($expiry_date) || empty($cvv) || empty($amount) || empty($email) || empty($address) || empty($city) || empty($zip)) {
      echo "<script>alert('Lütfen tüm alanları doldurun!');</script>";
  } else {
      // SQL sorgusunu çalıştır
      $stmt = $conn->prepare("INSERT INTO odemeler (name, card_number, expiry_date, cvv, amount, email, address, city, zip) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
      $stmt->bind_param("sssssssss", $name, $card_number, $expiry_date, $cvv, $amount, $email, $address, $city, $zip);

      if ($stmt->execute()) {
          echo "<script>
                  alert('Ödeme başarıyla alındı!');
                  window.location.href = 'index.php';
                </script>";
      } else {
          echo "<script>alert('Veritabanına kaydedilirken hata oluştu: " . $stmt->error . "');</script>";
      }

      $stmt->close();
  }
}


// Sepet bilgilerini al
session_start();
$sepet = isset($_SESSION['sepet']) ? $_SESSION['sepet'] : [];
$toplam_fiyat = 0;

// Toplam tutarı hesapla
foreach ($sepet as $urun) {
    $toplam_fiyat += $urun['price'] * $urun['quantity'];
}

// Kargo ücreti, KDV ve toplam hesaplama
$kargo_ucreti = 39.99; // Sabit kargo ücreti
$kdv_orani = 0.18;
$kdv = $toplam_fiyat * $kdv_orani;
$genel_toplam = $toplam_fiyat + $kargo_ucreti + $kdv;

// Sepet toplamını session'a kaydet
$_SESSION['toplam_tutar'] = $genel_toplam;

?>













































<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
body {
    font-family: Arial;
    font-size: 17px;
    padding: 8px;
    background-image: url('images/1.jpg'); /* Arka planda gösterilecek resim */
    background-size: cover; /* Resmi ekran boyutuna göre ayarlamak */
    background-position: center center; /* Resmi sayfa ortasında hizalamak */
   /* display: flex;
    justify-content: center;
    align-items: center;
    */
    height: 100vh;
    background-attachment: fixed; /* Arka planı sabitlemek */
}

* {
  box-sizing: border-box;
}

.row {
  display: -ms-flexbox; /* IE10 */
  display: flex;
  -ms-flex-wrap: wrap; /* IE10 */
  flex-wrap: wrap;
  margin: 0 -16px;
}

.col-25 {
  -ms-flex: 25%; /* IE10 */
  flex: 25%;
}

.col-50 {
  -ms-flex: 50%; /* IE10 */
  flex: 50%;
}

.col-75 {
  -ms-flex: 75%; /* IE10 */
  flex: 75%;
}

.col-25,
.col-50,
.col-75 {
  padding: 0 16px;
}

.container {
  background-color: #f2f2f2;
  padding: 5px 20px 15px 20px;
  border: 1px solid lightgrey;
  border-radius: 3px;
}

input[type=text], input[type=email] {
  width: 100%; /* Genişliği %100 yaparak tüm alanı kapsamasını sağlarız */
  padding: 12px; /* İç boşluk ekler */
  border: 1px solid #ccc; /* Kenarlık rengi */
  border-radius: 3px; /* Köşe yuvarlama */
  margin-bottom: 20px; /* Alt boşluk ekler */
  box-sizing: border-box; /* Box modeline göre hesaplama yapar */
}

label {
  margin-bottom: 10px;
  display: block;
}

.icon-container {
  margin-bottom: 20px;
  padding: 7px 0;
  font-size: 24px;
}

.btn {
  background-color: #04AA6D;
  color: white;
  padding: 12px;
  margin: 10px 0;
  border: none;
  width: 100%;
  border-radius: 3px;
  cursor: pointer;
  font-size: 17px;
}

.btn:hover {
  background-color: #45a049;
}

a {
  color: #2196F3;
}

hr {
  border: 1px solid lightgrey;
}

span.price {
  float: right;
  color: grey;
}


@media (max-width: 800px) {
  .row {
    flex-direction: column-reverse;
  }
  .col-25 {
    margin-bottom: 20px;
  }
}
</style>
</head>
<body>

<h1>Ödeme Alanı</h1>
<div class="row">
  <div class="col-75">
    <div class="container">
      <form action="" method="POST">
      
        <div class="row">
          <div class="col-50">
            <h3>Billing Address</h3>
            <label for="name"><i class="fa fa-user"></i> Ad-Soyad</label>
            <input type="text" id="name" name="name" placeholder="John M. Doe" required>
            <label for="email"><i class="fa fa-envelope"></i> Email</label>
            <input type="email" id="email" name="email" placeholder="john@example.com" required>
            <label for="address"><i class="fa fa-address-card-o"></i> Adres</label>
            <input type="text" id="address" name="address" placeholder="542 W. 15th Street" required>
            <label for="city"><i class="fa fa-institution"></i> Şehir</label>
            <input type="text" id="city" name="city" placeholder="New York" required>
            <label for="zip"><i class="fa fa-institution"></i> Posta Kodu</label>
            <input type="text" id="zip" name="zip" placeholder="10001" required>
          </div>

          <div class="col-50">
            <h3>Ödeme</h3>
            <label for="fname">Accepted Cards</label>
            <div class="icon-container">
              <i class="fa fa-cc-visa" style="color:navy;"></i>
              <i class="fa fa-cc-amex" style="color:blue;"></i>
              <i class="fa fa-cc-mastercard" style="color:red;"></i>
              <i class="fa fa-cc-discover" style="color:orange;"></i>
            </div>
            <label for="cname">İsminiz</label>
            <input type="text" id="cname" name="cardname" placeholder="John More Doe" required>
            <label for="ccnum">Kredi Kartı Numaranız</label>
            <input type="text" id="card-number" name="card-number" placeholder="1111-2222-3333-4444" required>
            <div class="row">
              <div class="col-50">
              <label for="expiry-date">Son Kullanma Tarihi (MM/YY)</label>
              <input type="text" id="expiry-date" name="expiry-date" placeholder="MM/YY" required>
              </div>
              <div class="col-50">
                <label for="cvv">CVV</label>
                <input type="text" id="cvv" name="cvv" placeholder="352" required>
              </div>
            </div>
          </div>
        </div>
        <input type="hidden" id="amount" name="amount" value="<?php echo htmlspecialchars($genel_toplam); ?>">
        <input type="submit" value="Ödeme Yap" class="btn">
      </form>
    </div>
  </div>

  <div class="col-25">
    <div class="container">
      <h4>Sepet <span class="price" style="color:black"><i class="fa fa-shopping-cart"></i> <b><?php echo count($sepet); ?></b></span></h4>

      <?php if (!empty($sepet)): ?>
        <?php foreach ($sepet as $urun): ?>
          <p><a href="#"><?php echo htmlspecialchars($urun['name']); ?></a> 
            <span class="price">TL<?php echo number_format($urun['price'], 2); ?></span>
          </p>
        <?php endforeach; ?>
      <?php else: ?>
        <p>Sepetinizde ürün bulunmamaktadır.</p>
      <?php endif; ?>

      <hr>
      <p>Total <span class="price" style="color:black"><b>TL<?php echo number_format($genel_toplam, 2); ?></b></span></p>
      <input type="hidden" id="amount" name="amount" value="<?php echo htmlspecialchars($genel_toplam); ?>">

    </div>
  </div>
</div>

</body>
</html>