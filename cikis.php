<?php
session_start(); // Oturum başlat
session_unset(); // Oturum verilerini temizle
session_destroy(); // Oturumu sonlandır

// Çıkış sonrası anasayfaya yönlendir
header("Location: anasayfa.php");
exit();
?>