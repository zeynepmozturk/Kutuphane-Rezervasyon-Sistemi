<?php
include("baglanti.php");

$username_err = "";
$parola_err = "";
$parolatkr_err = "";
$genel_bilgi = "";

// POST isteği kontrolü
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Kullanıcı adı kontrolü
    if (empty($_POST["kullaniciadi"])) {
        $username_err = "Kullanıcı adı boş geçilemez.";
    } else {
        $username = $_POST["kullaniciadi"];
    }

    // Yeni parola kontrolü
    if (empty($_POST["yeni_parola"])) {
        $parola_err = "Yeni parola alanı boş geçilemez.";
    } else {
        $new_password = $_POST["yeni_parola"];
    }

    // Parola tekrar kontrolü
    if (empty($_POST["yeni_parolatkr"])) {
        $parolatkr_err = "Parola tekrar alanı boş geçilemez.";
    } else if ($_POST["yeni_parola"] !== $_POST["yeni_parolatkr"]) {
        $parolatkr_err = "Parolalar aynı değil!";
    }

    // Eğer tüm veriler geçerliyse devam et
    if (empty($username_err) && empty($parola_err) && empty($parolatkr_err)) {
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
    mysqli_close($baglanti);
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
  <meta charset="UTF-8">
  <title>Şifre Sıfırla</title>
</head>
<body>
  <h2>🔐 Şifre Sıfırlama Formu</h2>

  <?php
  if (!empty($genel_bilgi)) echo $genel_bilgi;
  ?>

  <form action="" method="POST">
    <label for="kullaniciadi">Kullanıcı Adı:</label><br>
    <input type="text" name="kullaniciadi" required><br>
    <span style="color:red;"><?php echo $username_err; ?></span><br><br>

    <label for="yeni_parola">Yeni Parola:</label><br>
    <input type="password" name="yeni_parola" required><br>
    <span style="color:red;"><?php echo $parola_err; ?></span><br><br>

    <label for="yeni_parolatkr">Yeni Parola Tekrar:</label><br>
    <input type="password" name="yeni_parolatkr" required><br>
    <span style="color:red;"><?php echo $parolatkr_err; ?></span><br><br>

    <button type="submit">Şifreyi Güncelle</button>
  </form>
</body>
</html>
