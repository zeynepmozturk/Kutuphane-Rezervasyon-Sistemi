<?php
include("baglanti.php");

// Hata ve bilgi mesajları
$username_err = "";
$parola_err = "";
$parolatkr_err = "";
$email_err = "";
$genel_bilgi = "";

// POST isteği kontrolü
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Giriş işlemi
    if (isset($_POST["giris"])) {
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

        if (empty($username_err) && empty($parola_err)) {
            $secim = "SELECT * FROM kullanicilar WHERE kullanici_adi = ?";
            $stmt = mysqli_prepare($baglanti, $secim);
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                $user = mysqli_fetch_assoc($result);
                if (password_verify($parola, $user["parola"])) {
                    session_start();
                    $_SESSION["kullanici_adi"] = $user["kullanici_adi"];
                    $_SESSION["email"] = $user["email"];
                    $genel_bilgi = "Giriş başarılı, hoş geldiniz!";
                    header("Location: rezervasyon2.php");
                    exit;
                } else {
                    $genel_bilgi = "Parola yanlış!";
                }
            } else {
                $genel_bilgi = "Kullanıcı adı bulunamadı!";
            }
        }
    }

    // Kayıt işlemi
    elseif (isset($_POST["kayit"])) {

        // Kullanıcı adı kontrolü
        if (empty($_POST["yeni_kullanici"])) {
            $username_err = "Kullanıcı adı boş geçilemez.";
        } elseif (strlen($_POST["yeni_kullanici"]) < 6) {
            $username_err = "Kullanıcı adı en az 6 karakterden oluşmalıdır.";
        } elseif (!preg_match('/^[a-z\d_]{5,20}$/i', $_POST["yeni_kullanici"])) {
            $username_err = "Kullanıcı adı büyük küçük harf ve rakamdan oluşmalıdır.";
        } else {
            $username = $_POST["yeni_kullanici"];
        }

        // Email kontrolü
        if (empty($_POST["yeni_email"])) {
            $email_err = "Email alanı boş geçilemez.";
        } elseif (!filter_var($_POST["yeni_email"], FILTER_VALIDATE_EMAIL)) {
            $email_err = "Geçersiz email formatı";
        } else {
            $email = $_POST["yeni_email"];
        }

        // Parola kontrolü
        if (empty($_POST["yeni_parola"])) {
            $parola_err = "Parola alanı boş geçilemez.";
        } else {
            $parola = $_POST["yeni_parola"];
        }

        // Parola tekrar kontrolü
        if (empty($_POST["yeni_parolatkr"])) {
            $parolatkr_err = "Parola tekrar alanı boş geçilemez.";
        } elseif ($_POST["yeni_parola"] !== $_POST["yeni_parolatkr"]) {
            $parolatkr_err = "Parolalar aynı değil!";
        } else {
            $parolatkr = $_POST["yeni_parolatkr"];
        }

        if (empty($username_err) && empty($email_err) && empty($parola_err) && empty($parolatkr_err)) {
            // Kullanıcı adı mevcut mu kontrol et
            $kontrolSorgu = "SELECT * FROM kullanicilar WHERE kullanici_adi = ?";
            $stmt = mysqli_prepare($baglanti, $kontrolSorgu);
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $kontrolSonuc = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($kontrolSonuc) > 0) {
                $genel_bilgi = "Bu kullanıcı adı zaten alınmış.";
            } else {
                $hashedPassword = password_hash($parola, PASSWORD_DEFAULT);

                $ekle = "INSERT INTO kullanicilar (kullanici_adi, email, parola) VALUES (?, ?, ?)";
                $stmt = mysqli_prepare($baglanti, $ekle);
                mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hashedPassword);

                if (mysqli_stmt_execute($stmt)) {
                    $genel_bilgi = '<div class="alert alert-success">Kayıt başarılı bir şekilde eklendi!</div>';
                } else {
                    $genel_bilgi = '<div class="alert alert-danger">Kayıt eklenirken bir problem oluştu!</div>';
                }
            }
        }
    }

    // Şifre sıfırlama işlemi
    elseif (isset($_POST["sifre_reset"])) {
        if (empty($_POST["reset_kullanici"])) {
            $username_err = "Kullanıcı adı boş geçilemez.";
        } else {
            $username = $_POST["reset_kullanici"];
        }

        if (empty($_POST["reset_parola"])) {
            $parola_err = "Yeni parola alanı boş geçilemez.";
        } else {
            $new_password = $_POST["reset_parola"];
        }

        if (empty($username_err) && empty($parola_err)) {
            $sorgu = "SELECT * FROM kullanicilar WHERE kullanici_adi = ?";
            $stmt = mysqli_prepare($baglanti, $sorgu);
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $sonuc = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($sonuc) > 0) {
                $hashedPassword = password_hash($new_password, PASSWORD_DEFAULT);

                $update = "UPDATE kullanicilar SET parola = ? WHERE kullanici_adi = ?";
                $stmt2 = mysqli_prepare($baglanti, $update);
                mysqli_stmt_bind_param($stmt2, "ss", $hashedPassword, $username);
                mysqli_stmt_execute($stmt2);

                if (mysqli_stmt_affected_rows($stmt2) > 0) {
                    $genel_bilgi = '<div style="color:green;">✅ Şifre başarıyla güncellendi.</div>';
                } else {
                    $genel_bilgi = '<div style="color:orange;">⚠️ Şifre zaten aynı olabilir veya güncellenemedi.</div>';
                }
            } else {
                $genel_bilgi = '<div style="color:red;">❌ Kullanıcı adı bulunamadı!</div>';
            }
        }
    }
}

mysqli_close($baglanti);
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
            <button class="nav-link " id="giris-tab" data-bs-toggle="tab" data-bs-target="#giris" type="button" role="tab" aria-controls="giris" aria-selected="true">Giriş</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="kayit-tab" data-bs-toggle="tab" data-bs-target="#kayit" type="button" role="tab" aria-controls="kayit" aria-selected="false">Kayıt</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="sifre-tab" data-bs-toggle="tab" data-bs-target="#sifre" type="button" role="tab" aria-controls="sifre" aria-selected="false">Şifre Sıfırla</button>
        </li>
    </ul>
    <div class="tab-content mt-3" id="myTabContent">
        <!-- Giriş Tabı -->
        <div class="tab-pane fade " id="giris" role="tabpanel" aria-labelledby="giris-tab">
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
        <div class="tab-pane fade show active" id="kayit" role="tabpanel" aria-labelledby="kayit-tab">
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
                