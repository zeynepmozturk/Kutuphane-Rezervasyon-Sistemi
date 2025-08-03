<?php
include("baglanti.php");



// Hata mesajları için değişkenler
$username_err = "";
$parola_err = "";
$genel_bilgi = "";

// Form verilerini kontrol et
if ($_SERVER["REQUEST_METHOD"] === "POST") 
{
    if (isset($_POST["giris"])) {
        // Giriş işlemi
        if (empty($_POST["kullaniciadi"])) {
            $username_err = "Kullanıcı adı boş geçilemez.";
        } else {
            $username = $_POST["kullaniciadi"];
        }

        if (empty($_POST["parola"])) {
            $parola_err = "Parola alanı boş geçilemez.";
        } else {
            $parola = $_POST["parola"];
        }

        if (isset($username) && isset($parola)) {
            $secim = "SELECT * FROM kullanicilar WHERE kullanici_adi='$username'";
            $calistir = mysqli_query($baglanti, $secim);
            $kayitsayisi = mysqli_num_rows($calistir);

            if ($kayitsayisi > 0) {
                $ilgilikayit = mysqli_fetch_assoc($calistir);
                $hashlisifre = $ilgilikayit["parola"];
                if (password_verify($parola, $hashlisifre)) {
                    session_start();
                    $_SESSION["kullanici_adi"] = $ilgilikayit["kullanici_adi"];
                  $_SESSION["email"] = $ilgilikayit["email"];
                  $_SESSION["login"] = true;
                    $genel_bilgi = "Giriş başarılı, hoş geldiniz!";
                    header("Location: rezervasyon2.php");
                    $_SESSION["user_id"] = $ilgilikayit["id"]; // Kullanıcı ID'sini session'a kaydediyoruz
                    exit; 
                    
                } else {
                    $genel_bilgi = "Parola yanlış!";
                }
            } else {
                $genel_bilgi = "Kullanıcı adı bulunamadı!";
            }
        }
    } elseif (isset($_POST["kayit"])) {
         //Kullanıcı adı doğrulama
    if(empty($_POST["yeni_kullanici"]))
    {
        $username_err="Kullanıcı adı boş geçilemez.";
    }
    else if (strlen($_POST["yeni_kullanici"])<6) {
        $username_err="Kullanıcı adı en az 6 karakterden oluşmalıdır.";
    }
    else if (!preg_match('/^[a-z\d_]{5,20}$/i', $_POST["yeni_kullanici"]))//https://www.phpzag.com/15-regular-expressions-for-php-developers/ bu siteden hazır olarak aldım bu bloğu
    {
        $username_err="Kullanıcı adı büyük küçük harf ve rakamdan oluşmalıdır.";
    }
    else {
        $username=$_POST["yeni_kullanici"];
    }
    
    //parola doğrulama kısmı
    if(empty($_POST["yeni_parola"]))
    {
        $parola_err="Parola alanı boş geçilemez.";
    }
    else{
        $parola=password_hash($_POST["yeni_parola"], PASSWORD_DEFAULT);
    }
    
    if (isset($username) && isset($parola)) {
        // SQL sorgusu
        $ekle = "INSERT INTO kullanicilar (kullanici_adi, parola) VALUES ('$username', '$parola')";
        $calistirekle = mysqli_query($baglanti, $ekle);

        if ($calistirekle) {
            echo '<div class="alert alert-success" role="alert">
                Kayıt başarılı bir şekilde eklendi!
                </div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">
                Kayıt eklenirken bir problem oluştu!
                </div>';
        }
    
    mysqli_close($baglanti);
}
    }
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
   
    
</head>
<body>
   
<div class="container mt-5">

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="giris-tab" data-bs-toggle="tab" data-bs-target="#giris" type="button" role="tab" aria-controls="giris" aria-selected="true">Giriş</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="kayit-tab" data-bs-toggle="tab" data-bs-target="#kayit" type="button" role="tab" aria-controls="kayit" aria-selected="false">Kayıt</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="sifre-tab" data-bs-toggle="tab" data-bs-target="#sifre" type="button" role="tab" aria-controls="sifre" aria-selected="false">Şifre Sıfırla</button>
        </li>
    </ul>
    <div class="tab-content mt-3" id="myTabContent">
        <!-- Giriş Tabı -->
        <div class="tab-pane fade show active" id="giris" role="tabpanel" aria-labelledby="giris-tab">
            <form method="POST">
                <div class="mb-3">
                    <label for="kullaniciadi" class="form-label">Kullanıcı Adı</label>
                    <input type="text" class="form-control" name="kullaniciadi" id="kullaniciadi">
                </div>
                <div class="mb-3">
                    <label for="parola" class="form-label">Parola</label>
                    <input type="password" class="form-control" name="parola" id="parola">
                </div>
                <button type="submit" name="giris" class="btn btn-primary">Giriş Yap</button>
            </form>
        </div>
        <!-- Kayıt Tabı -->
        <div class="tab-pane fade" id="kayit" role="tabpanel" aria-labelledby="kayit-tab">
            <form method="POST">
                <div class="mb-3">
                    <label for="yeni_kullanici" class="form-label">Kullanıcı Adı</label>
                    <input type="text" class="form-control
                     <?php
                 if(!empty($username_err))
                 {
                    echo "is-invalid";
                 }
                ?> 
                    " name="yeni_kullanici" id="yeni_kullanici">
                <div id="validationServer03Feedback" class="invalid-feedback">
                   <?php
                   echo $username_err;
                   ?>
                </div>
                </div>
                <div class="mb-3">
                    <label for="yeni_parola" class="form-label">E-mail</label>
                    <input type="text" class="form-control
                    <?php
                 if(!empty($email_err))
                 {
                    echo "is-invalid";
                 }
                ?>   
                    " name="yeni_email" id="yeni_email">
                <div id="validationServer03Feedback" class="invalid-feedback">
                <?php
                   echo $email_err;
                   ?>
                </div>
                </div>
                <div class="mb-3">
                    <label for="yeni_parola" class="form-label">Parola</label>
                    <input type="password" class="form-control
                    <?php
                 if(!empty($parola_err))
                 {
                    echo "is-invalid";
                 }
                ?>
                    " name="yeni_parola" id="yeni_parola">
                    <div id="validationServer03Feedback" class="invalid-feedback">
                <?php
                   echo $parola_err;
                   ?>
                </div>
                </div>
                <div class="mb-3">
                    <label for="yeni_parola" class="form-label">Parola Tekrar</label>
                    <input type="password" class="form-control
                     <?php
                 if(!empty($parolatkr_err))
                 {
                    echo "is-invalid";
                 }
                ?>
                    " name="yeni_parolatkr" id="yeni_parolatkr">
                <div id="validationServer03Feedback" class="invalid-feedback">
                <?php
                   echo $parolatkr_err;
                   ?>
                </div>
                </div>
                <button type="submit" name="kayit" class="btn btn-success">Kayıt Ol</button>
            </form>
        </div>
        <!-- Şifre Sıfırla Tabı -->
        <div class="tab-pane fade" id="sifre" role="tabpanel" aria-labelledby="sifre-tab">
            <form method="POST">
                <div class="mb-3">
                    <label for="reset_kullanici" class="form-label">Kullanıcı Adı</label>
                    <input type="text" class="form-control" name="reset_kullanici" id="reset_kullanici">
                </div>
                <div class="mb-3">
                    <label for="reset_parola" class="form-label">Yeni Parola</label>
                    <input type="password" class="form-control" name="reset_parola" id="reset_parola">
                </div>
                <button type="submit" name="sifre_reset" class="btn btn-warning">Şifre Sıfırla</button>
            </form>
        </div>
    </div>
    <div class="mt-3">
        <?php if (!empty($genel_bilgi)) echo "<div class='alert alert-info'>$genel_bilgi</div>"; ?>
    </div>
</div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
                