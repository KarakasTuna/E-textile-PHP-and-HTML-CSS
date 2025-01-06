<?php
session_start();

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Sepetten ürünü kaldır
if (isset($_SESSION['sepet'])) {
    foreach ($_SESSION['sepet'] as $key => $urun) {
        if ($urun['id'] === $product_id) {
            unset($_SESSION['sepet'][$key]);
            break;
        }
    }
}

// Sepet sayfasına geri dön
header("Location: sepet-goruntule.php");
exit;
?>
