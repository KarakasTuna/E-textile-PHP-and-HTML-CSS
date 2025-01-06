<?php
session_start(); // Oturum başlat

// Oturumu temizle ve sonlandır
session_unset();
session_destroy();

// Yönlendirme (çıkış başarılı ise)
header("Location: http://localhost/teksil/index.php");
exit;
?>
