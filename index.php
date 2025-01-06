
<?php
session_start(); // Oturum başlat


/*$_SESSION['login'] = true; // Kullanıcı giriş yaptıysa 'login' oturum değişkenini true yapın
 
$_SESSION['username'] =  $username;// Kullanıcı adı
$_SESSION['email'] =  $email ;*/
//şimdilik böyle kalsın



// Sepet bilgilerini al
$sepet = isset($_SESSION['sepet']) ? $_SESSION['sepet'] : [];

// Sepet içindeki toplam ürün sayısını hesapla
$sepet_sayisi = 0;
foreach ($sepet as $urun) {
$sepet_sayisi += $urun['quantity']; // Her ürünün adetini ekliyoruz
}

?>







<!DOCTYPE html>
<html lang="tr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tekstil Dünyası</title>
    <!-- Font Awesome kütüphanesini dahil edin -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<div>
    <br></br>

    <h1>Tekstil Dünyası</h1>

    <div class="Tekstil-Dünyası">
        <div>
            <ul>
                <li><a href="index.php"><i class="fas fa-home"></i> Anasayfa</a></li>
                <li><a href="ürünler.html"><i class="fas fa-tshirt"></i> Ürünler</a></li>
                <li><a href="Hakkımız.html"><i class="fas fa-info-circle"></i> Hakkımızda</a></li>
                <li class="menü-iletisim">
                    <a href="#"><i class="fas fa-phone-alt"></i> İletişim</a>
                    <div class="dropdown">
                        <ul class="submenü">
                            <li><a href="https://www.instagram.com/"><img src="images/instagram.png" alt="Instagram" style="width: 25px;"> İnstagram</a></li>
                            <li><a href="https://workspace.google.com/"><img src="images/gmail.png" alt="Instagram" style="width: 20px;">eposta</a></li>
                            <li><a href="https://www.whatsapp.com/"><img src="images/whatsapp.png" alt="Instagram" style="width: 25px;"> WhatsApp</a></li>
                        </ul>
                    </div>
                </li>
            </ul>
        </div>
    </div>














    <!-- Sabit ikona kapsayıcı -->
    
    <div class="ikonlar">


        






     












         <!-- Sepet ikonu -->
        <div class="siparis.ikon">
            <a href="siparis.php">
            <img src="images/48.png"  alt="Siparis" class="siparis-img" width="48" height="48">
                <!--<img src="images/basket.png" alt="Sepet" class="sepet-img">-->
            </a>
        </div>





















        <!-- Sepet ikonu -->
        <div class="sepet-ikon">
            <a href="sepet.php">
                <img src="images/basket.png" alt="Sepet" class="sepet-img">
                <span class="sepet-sayisi"><?php echo $sepet_sayisi; ?></span>
            </a>
        </div>

    
        <!-- Giriş ikonu -->
        <div class="Giris-ikon">
            <?php if (isset($_SESSION['login']) && $_SESSION['login'] === true): ?>
            <!-- Giriş yapılmışsa, kullanıcı adı ve e-posta gösterilecek -->
            <a href="#">
                <img src="images/person-add-icon.png" alt="Giriş" class="user-img">
            </a>
            <div class="altkısım">
                <ul class="altmenü">
                    <li><strong>Ad:</strong>
                     <?php echo isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Kullanıcı adı bulunamadı değişebilir'; ?>
                    </li>
                    <li><strong>E-posta:</strong>
                     <?php echo isset($_SESSION['email']) ? htmlspecialchars($_SESSION['email']) : 'E-posta bulunamadı değişebilir'; ?>
                    </li>
                    <li><a href="logout.php">Çıkış Yap</a></li> <!-- Çıkış butonu -->
                </ul>
            </div>
            <?php else: ?>
            <!-- Giriş yapılmamışsa, Giriş ve Kayıt Ol menüleri görünecek -->
            <a href="GİRİS.php">
                <img src="images/person-add-icon.png" alt="Giriş" class="user-img">
            </a>
            <div class="altkısım">
                <ul class="altmenü">
                    <li><a href="http://localhost/teksil/GİRİS.php">Giriş</a></li>
                    <li><a href="http://localhost/teksil/kayıt.php">Kayıt Ol</a></li>
                </ul>
            </div>
            <?php endif; ?>
        </div>









    </div>




















    
    <form method="GET" action="ara.php">
    <div class="search">
        <input type="text" name="Ara" placeholder="Arama yapın..." required>
        <button type="submit">Ara</button>
    </div>
    </form>



    <!-- action="search.php" - Form gönderildiğinde arama terimi search.php dosyasına gönderilecek.
    method="GET" - Bu, form verilerinin URL üzerinden GET metodu ile gönderilmesini sağlar. -->









    <div class="Katagori">
      <div class="menu-ikon">☰</div>
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
   



    <div class="container">
        <!-- İlk Öne Çıkan Bölüm -->
        <div class="featured-section">
            <h2>Öne Çıkan Erkek Giyim Ürünleri</h2>
            <div class="featured-items">
                <div class="item">
                    <img src="images/erkekgömlek.jpg" alt="Gömlek">
                    <h3>Şık Gömlek</h3>
                    <p>350 TL</p>
                    <a href="http://localhost/teksil/%C3%BCr%C3%BCn2.php?id=11">Satın Al</a>

                </div>
                <div class="item">
                    <img src="images/erkekçeket.jpg" alt="Ceket">
                    <h3>Klasik Ceket</h3>
                    <p>700 TL</p>
                    <a href="http://localhost/teksil/%C3%BCr%C3%BCn2.php?id=2">Satın Al</a>

                </div>
                <div class="item">
                    <img src="images/Kadınpantolon.jpg" alt="Pantolon">
                    <h3>Kot Pantolon</h3>
                    <p>400 TL</p>
                    <a href="http://localhost/teksil/%C3%BCr%C3%BCn2.php?id=6">Satın Al</a>

                </div>
                <div class="item">
                    <img src="images/Kravat.jpg" alt="Kravat">
                    <h3>Kravat</h3>
                    <p>150 TL</p>
                    <a href="http://localhost/teksil/%C3%BCr%C3%BCn2.php?id=5">Satın Al</a>
                </div>
            </div>
        </div>

        <!-- İkinci Öne Çıkan Bölüm -->
        <div class="featured-section2">
            <h2>Yeni Gelen Erkek Kadın Giyim Ürünleri</h2>
            <div class="featured-items2">
                <div class="item2">
                    <img src="images/indir (8).jpg" alt="Spor Tişört">
                    <h3>Spor Tişört</h3>
                    <p>200 TL</p>
                    <a href="http://localhost/teksil/%C3%BCr%C3%BCn2.php?id=12">Satın Al</a>

                </div>
                <div class="item2">
                    <img src="images/indir (1).jpg" alt="Sweatshirt">
                    <h3>Kapüşonlu Sweatshirt</h3>
                    <p>300 TL</p>
                    <a href="http://localhost/teksil/%C3%BCr%C3%BCn2.php?id=13">Satın Al</a>

                </div>
                <div class="item2">
                    <img src="images/erkekpantolon.jpg" alt="Kot Pantolon">
                    <h3>Kot Pantolon</h3>
                    <p>450 TL</p>
                    <a href="http://localhost/teksil/%C3%BCr%C3%BCn2.php?id=7">Satın Al</a>

                </div>
            </div>
        </div>
    </div>




    <div class="container">
        <!-- İlk Öne Çıkan Bölüm -->
        <div class="featured-section">
            <h2>Öne Çıkan Giyim Ürünleri</h2>
            <div class="featured-items">
                <div class="item">
                    <img src="images/kadıngömlek(13).jpg" alt="Gömlek">
                    <h3>Şık Gömlek</h3>
                    <p>350 TL</p>
                    <a href="http://localhost/teksil/%C3%BCr%C3%BCn2.php?id=8">Satın Al</a>

                </div>
                <div class="item">
                    <img src="images/Kadınklasikçeketkadın (13).jpg" alt="Ceket">
                    <h3>Klasik Ceket</h3>
                    <p>700 TL</p>
                    <a href="http://localhost/teksil/%C3%BCr%C3%BCn2.php?id=9">Satın Al</a>

                </div>
                <div class="item">
                    <img src="images/kadın.jpg" alt="Pantolon">
                    <h3>Resmi Pantolon</h3>
                    <p>400 TL</p>
                    <a href="http://localhost/teksil/%C3%BCr%C3%BCn2.php?id=4">Satın Al</a>

                </div>

            </div>
        </div>

        <!-- İkinci Öne Çıkan Bölüm -->
        <div class="featured-section2">
            <h2>Yeni Gelen Erkek Giyim Ürünleri</h2>
            <div class="featured-items2">
                <div class="item2">
                    <img src="images/erkek sport.jpg" alt="Spor Tişört">
                    <h3>Spor Tişört</h3>
                    <p>200 TL</p>
                    <a href="http://localhost/teksil/%C3%BCr%C3%BCn2.php?id=12">Satın Al</a>

                </div>
                <div class="item2">
                    <img src="images/kapşön.jpg" alt="Sweatshirt">
                    <h3>Kapüşonlu Sweatshirt</h3>
                    <p>300 TL</p>
                    <a href="http://localhost/teksil/%C3%BCr%C3%BCn2.php?id=14">Satın Al</a>

                </div>
                <div class="item2">
                    <img src="images/ep.jpg" alt="Kot Pantolon">
                    <h3>Kot Pantolon</h3>
                    <p>450 TL</p>
                    <a href="http://localhost/teksil/%C3%BCr%C3%BCn2.php?id=7">Satın Al</a>

                </div>
            </div>
        </div>
    </div>
















    <div class="container">
        <!-- İlk Öne Çıkan Bölüm -->
        <div class="featured-section">
            <h2>Öne Çıkan Bebek Giyim Ürünleri</h2>
            <div class="featured-items">
                <div class="item">
                    <img src="images/bebekgömlek.jpg" alt="Gömlek">
                    <h3>Bebek Gömleği</h3>
                    <p>350 TL</p>
                    <a href="http://localhost/teksil/%C3%BCr%C3%BCn2.php?id=15">Satın Al</a>

                </div>
                <div class="item">
                    <img src="images/bebekşort.jpg" alt="Ceket">
                    <h3>bebek şort</h3>
                    <p>700 TL</p>
                    <a href="http://localhost/teksil/%C3%BCr%C3%BCn2.php?id=16">Satın Al</a>

                </div>
                <div class="item">
                    <img src="images/bebkpantolon.jpg" alt="Pantolon">
                    <h3>Bebek Pantolon</h3>
                    <p>400 TL</p>
                    <a href="http://localhost/teksil/%C3%BCr%C3%BCn2.php?id=17">Satın Al</a>

                </div>
            </div>
        </div>

        <!-- İkinci Öne Çıkan Bölüm -->
        <div class="featured-section2">
            <h2>Yeni Gelen Bebek Giyim Ürünleri</h2>
            <div class="featured-items2">
                <div class="item2">
                    <img src="images/bebktişört.jpg" alt="Spor Tişört">
                    <h3>Bebek Tişört</h3>
                    <p>200 TL</p>
                    <a href="http://localhost/teksil/%C3%BCr%C3%BCn2.php?id=18">Satın Al</a>

                </div>
                <div class="item2">
                    <img src="images/kapşönbebek.jpg" alt="Sweatshirt">
                    <h3>Kapüşonlu Bebek Sweatshirt</h3>
                    <p>300 TL</p>
                    <a href="http://localhost/teksil/%C3%BCr%C3%BCn2.php?id=19">Satın Al</a>

                </div>
                <div class="item2">
                    <img src="images/bebekkot.jpg" alt="Kot Pantolon">
                    <h3>Bebek Kot Pantolon</h3>
                    <p>450 TL</p>
                    <a href="http://localhost/teksil/%C3%BCr%C3%BCn2.php?id=20">Satın Al</a>

                </div>
            </div>
        </div>
    </div>



    <!-- web sitenin en alt kısmı -->

    <footer style="background-color: #fff4ee; padding: 20px; font-family: Arial, sans-serif;">
  <div style="display: flex; justify-content: space-between; flex-wrap: wrap;">
    <!-- Kurumsal -->
    <div>
      <h3>Kurumsal</h3>
      <ul style="list-style-type: none; padding: 0;">
        <li><a href="Hakkımız.html">Hakkımızda</a></li>
        <li><a href="">İş Ortaklarımız</a></li>  
        <li><a href="yatırımcı.html">Yatırımcı İlişkileri</a></li>
        <li><a href="kariyer.html">Kariyer</a></li>
      </ul>
    </div>

  

    <!-- Sosyal Medya -->
    <div>
      <h3>Bizi Takip Edin</h3>
      <ul style="list-style-type: none; display: flex; gap: 10px; padding: 0;">
        <li><a href="https://www.instagram.com/"><img src="images/instagram.png" alt="Instagram" style="width: 20px;"></a></li>
        <li><a href="https://www.youtube.com/"><img src="images/youtube.png" alt="YouTube" style="width: 20px;"></a></li>
        <li><a href="https://x.com/i/flow/login"><img src="images/twitter.png" alt="X platformu" style="width: 20px;"></a></li>
      </ul>
    </div>

    <!-- Destek -->
    <div>
      <h3>Aklınıza takılan bir soru mu var?</h3>
      <p>Çağrı Merkezimizi arayın:</p>
      <p><strong>0850 252 40 00</strong></p>
      <a href="https://www.whatsapp.com/" style="color: green;">WhatsApp Destek</a>
    </div>
  </div>
</footer>























</body>
    <!--buraya menü kısmının javascript kodu gelecek -->

    <script>
         document.querySelector('.menu-ikon').addEventListener('click', function() {
         this.classList.toggle('active');
         });
   </script>
   

</html>