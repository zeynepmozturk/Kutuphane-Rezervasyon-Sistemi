<!DOCTYPE html>
<html lang="tr">
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
        <div id="logo">Erzincan Kütüphaneleri</div>
        <nav>
             <a href="rezervasyon.php"><i class="fa-solid fa-chair ikon"></i>Rezervasyon</a>
             <a href="login.php"><i class="fa-solid fa-key ikon"></i>Oturum Aç</a>
             <a href="kayit.php?sekme=kayit"id="kayitBtn"><i class="fa-solid fa-user ikon"></i>Üye Ol</a>


             
        </nav>
    </section>
    <section id="banner">
        <div id="black">
            
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- JavaScript ile Kayıt Butonuna Tıklanınca Kayit Sekmesini Açma -->
    <script>
        document.getElementById("kayitBtn").addEventListener("click", function() {
            var myTab = new bootstrap.Tab(document.getElementById("kayit-tab"));
            myTab.show();  // Kayit sekmesini aktif yap
        });
    </script>
    

</body>
</html>