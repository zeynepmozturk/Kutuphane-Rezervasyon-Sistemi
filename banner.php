<!DOCTYPE html>
<html lang="tr">
<?php
include("baglanti.php");
ob_start();
session_start();
if(!isset($_SESSION["login"]))
{
   header("Location:login.php");
}
else{
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];  // Giriş yapan kullanıcı ID'sini al
    } else {
        echo "Kullanıcı ID'si bulunamadı!";
    }
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erzincan Kütüphaneleri</title>
     <link rel="stylesheet" href="css/style.css">
    <script src="https://kit.fontawesome.com/d93f5ac481.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
</head>
<body>
    <section id="menu">
    <a href="anasayfa.php" id="logo">Erzincan Kütüphaneleri</a>
        <nav>
             <a href="rezervasyon.php"><i class="fa-solid fa-chair ikon"></i>Rezervasyon</a>
             <a href="login.php"><i class="fa-solid fa-key ikon"></i>Oturum Aç</a>
             <a href="kayit.php?sekme=kayit"><i class="fa-solid fa-user ikon"></i>Üye Ol</a>           
        </nav>
    </section>
</body>
</html>
