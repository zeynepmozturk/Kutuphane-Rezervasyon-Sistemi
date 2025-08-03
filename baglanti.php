<?php
 
 $host="localhost";
 $kullanici="root";
 $parola="";
 $vt="rezervasyon";
 $baglanti= mysqli_connect($host,$kullanici,$parola,$vt);
 mysqli_set_charset($baglanti,"UTF8");
// Bağlantıyı kontrol et
if (!$baglanti) {
    die("Veritabanı bağlantısı başarısız: " . mysqli_connect_error());
}

mysqli_set_charset($baglanti, "UTF8");




?>