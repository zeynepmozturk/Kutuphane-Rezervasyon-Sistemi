<?php

include("banner.php");
include("baglanti.php"); // Veritabanı bağlantısı
echo  $_SESSION["user_id"];
// Filtreleme parametrelerini al
$floor = isset($_POST['floor']) ? $_POST['floor'] : '';
$room_type = isset($_POST['room_type']) ? $_POST['room_type'] : '';
$time = isset($_POST['time']) ? $_POST['time'] : '';
$date = isset($_POST['date']) ? $_POST['date'] : '';
$status = isset($_POST['status']) ? $_POST['status'] : 0;  // Varsayılan olarak 0



// Rezervasyon parametrelerini al (örneğin, formdan gelen veriler)
$user_id = $_SESSION['user_id']; // Oturumdan kullanıcı ID'sini alıyoruz
$reserve = isset($_POST['reserve']) ? $_POST['reserve'] : ''; // Rezervasyon yap butonunun kontrolü



// SQL Sorgusu (Filtreleme işlemi)
$sql = "SELECT room, status, floor, room_type, time, date FROM seats WHERE status = 1 ";

if ($floor) {
    $sql .= "AND floor = '$floor' ";
}

if ($room_type) {
    $sql .= "AND room_type = '$room_type' ";
}

if ($time) {
    if ($time == "08:00-12:00") {
        $sql .= "AND (time BETWEEN '08:00:00' AND '12:00:00') ";
    } elseif ($time == "12:00-16:00") {
        $sql .= "AND (time BETWEEN '12:00:00' AND '16:00:00') ";
    } elseif ($time == "16:00-20:00") {
        $sql .= "AND (time BETWEEN '16:00:00' AND '20:00:00') ";
    }
}

if ($date) {
    $sql .= "AND date = '$date' ";
}

// Veritabanından koltukları çek
$result = mysqli_query($baglanti, $sql);
$seats = [];
while ($row = mysqli_fetch_assoc($result)) {
    $seats[$row['room']] = $row['status'];  // Koltuk adı => durum
}

// Rezervasyon işlemi yapılacaksa
if ($reserve) {
   
    // Rezervasyon kaydını ekleyelim
    $room = $_POST['room'];  // Seçilen oda
    $status = '0';  // Rezervasyon durumu başlangıçta "pending"

    // 1️⃣ Önce: Bu oda bu tarih ve saatte rezerve edilmiş mi?
    $check_sql = "SELECT COUNT(*) FROM reservations WHERE room = ? AND date = ? AND time_slot = ?";
    $check_stmt = $baglanti->prepare($check_sql);
    $check_stmt->bind_param("sss", $room, $date, $time);
    $check_stmt->execute();
    $check_stmt->bind_result($count);
    $check_stmt->fetch();
    $check_stmt->close();

    if ($count > 0) {
        // Rezervasyon zaten varsa, uyarı ver ve geri dön
        $_SESSION['error'] = "❌ Bu oda $date tarihinde $time saatlerinde zaten rezerve edilmiş.";
        header("Location: ".$_SERVER['PHP_SELF']);
        exit();
    }

    // 2️⃣ Rezervasyon işlemini başlat
    $reservation_query = $baglanti->prepare("INSERT INTO reservations (user_id, floor, room_type, date, time_slot, status, room) 
    VALUES (?, ?, ?, ?, ?, ?, ?)");
    $reservation_query->bind_param("iisssis", $user_id, $floor, $room_type, $date, $time, $status, $room);


    
    // Transaction başlat
    mysqli_begin_transaction($baglanti);
    
try {
    // 1. Rezervasyon kaydı
    if (!$reservation_query->execute()) {
        throw new Exception("Rezervasyon kaydı başarısız: ".$reservation_query->error);
    }
    
    // 2. Koltuk durum güncelleme
    $update_query = $baglanti->prepare("UPDATE seats SET status = 1 WHERE room = ?");
    $update_query->bind_param("s", $_POST['room']);
    if (!$update_query->execute()) {
        throw new Exception("Koltuk güncelleme başarısız: ".$update_query->error);
    }
    
    
    mysqli_commit($baglanti);
    $_SESSION['success'] = "Rezervasyon başarılı!";
} catch (Exception $e) {
    mysqli_rollback($baglanti);
    $_SESSION['error'] = $e->getMessage();
}

header("Location: ".$_SERVER['PHP_SELF']);
exit();

}

// Bağlantıyı kapatıyoruz
mysqli_close($baglanti);
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yandan Açılır Menü</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #B0C4DE;
           
        }
        .container {
            width: 80%;
            margin: auto;
            padding-top: 20px;
            
            
        }
        .title {
            font-size: 24px;
            font-weight: bold;
            color: red;
        }
        .blocks {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }
        .block {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            background: lightgray;
            padding: 10px;
            border-radius: 10px;
        }
        .room {
            width: 80px;
            height: 80px;
            background: white;
            border: 3px solid red;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            position: relative;
        }
        .circle {
            width: 30px;
            height: 30px;
            background: green;
            border-radius: 50%;
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }
        .selected {
            background: red !important;
        }
        .direction {
            font-size: 20px;
            font-weight: bold;
            color: red;
            margin: 0 20px;
        }
        .entrance {
           margin-top: 20px;
            font-size: 18px;
            background: navy;
            color: white;
            padding: 10px;
            display: inline-block;
            border-radius: 5px;
            align-items: center;
        }
    
    </style>

</head>
<body>

<!-- Menü Aç/Kapa İkonu (Banner İçinde) -->
<section id="menu">
    <i class="fa-solid fa-bars menu-icon" onclick="toggleMenu()"></i>
    <a href="anasayfa.php" id="logo">Erzincan Kütüphaneleri</a>
    <nav>
        <a href="rezervasyon.php"><i class="fa-solid fa-chair ikon"></i>Rezervasyon</a>
        <a href="login.php"><i class="fa-solid fa-key ikon"></i>Oturum Aç</a>
        <a href="kayit.php?sekme=kayit"><i class="fa-solid fa-user ikon"></i>Üye Ol</a>
        <a href="logout.php"> <i class="fa-solid fa-circle-xmark"></i> Çıkış</a>
    </nav>
</section>

<!-- Yandan Açılır Menü -->
<div class="sidebar" id="sidebar">
    <form method="POST" action="" id="reservationForm">
        <label for="salon">Salon Seç:</label>
        <select name="floor" id="salon">
            <option value="1">ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / Zemin Kat / Bireysel</option>
            <option value="2">ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / 1. Kat /</option>
            <option value="3">ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / 2. Kat</option>
            <option value="4">ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / 3. Kat / Grup</option>
        </select>
        <input type="hidden" name="reserve" value="1">
        <input type="hidden" name="room" id="selectedRoom" value="">
        <label for="tarih">Tarih Seç:</label>
        <input type="date" name="date" id="tarih" required>

        <label for="saat">Saat Seç:</label>
        <select name="time" id="saat">
            <option value="08:00-12:00">08:00 - 12:00</option>
            <option value="12:00-16:00">12:00 - 16:00</option>
            <option value="16:00-20:00">16:00 - 20:00</option>
        </select>
        <input type="hidden" name="status" value="0"> <!-- Varsayılan değer -->
        <label for="oda">Oda Türü:</label>
        <select name="room_type" id="oda">
            <option value="">Seçiniz...</option>
            <option value="Bireysel">Bireysel</option>
            <option value="Grup">Grup</option>
        </select>

        <div class="button">
            <input type="submit" value="Sorgula">
        </div>
    </form>
</div>
    <div class="container" style="text-align: center;">
        <div class="title" style="text-align: center;">ZEMİN KAT DANIŞMA KAYNAKLARI SALONU</div>
        <div class="blocks">
        <div class="direction">SOL BLOK</div>
        
            <div class="block" id="left-block">            
            <?php
            // Veritabanındaki koltuk durumlarını al
            $seatNames = ["M-1", "M-2", "M-3", "M-4", "M-5", "M-6", "M-7", "M-8", "M-9", "M-10"];
            foreach ($seatNames as $seat) {
                // Eğer o koltuğun durumu "occupied" (dolu) ise kırmızı, "available" (boş) ise yeşil olacak
                $status = isset($seats[$seat]) && $seats[$seat] == 1 ? 'occupied' : 'available';
                echo "<div class='room $status' onclick='openModal(this, \"$seat\")'>$seat</div>";
            }
            ?>

            </div>

            <div class="direction">SAĞ BLOK</div>
            <div class="block" id="right-block">
            <?php
            // Veritabanındaki koltuk durumlarını al
            $seatNames = ["M-11", "M-12", "M-13", "M-14", "M-15", "M-16", "M-17", "M-18", "M-19", "M-20"];
            foreach ($seatNames as $seat) {
                // Eğer o koltuğun durumu "occupied" (dolu) ise kırmızı, "available" (boş) ise yeşil olacak
                $status = isset($seats[$seat]) && $seats[$seat] == 1 ? 'occupied' : 'available';
                echo "<div class='room $status' onclick='openModal(this, \"$seat\")'>$seat</div>";
            }
            ?>

            </div>
        </div>
   
        
        <div class="entrance" style="text-align: center;">Zemin Kat Giriş</div>
        
    </div>

<!-- Modal Penceresi -->
<div id="customModal" class="modal">
    <div class="modal-content">
        <p id="modalMessage"></p>
        <button class="btn btn-success" onclick="confirmSeat(true)" style="background-color: green; color: white;">Evet</button>
        <button class="btn btn-danger" onclick="confirmSeat(false)" style="background-color: red; color: white;">Hayır</button>
    </div>
</div>
<style>
  .seat {
      width: 40px;
      height: 40px;
      margin: 5px;
      display: inline-block;
      border-radius: 5px;
      background-color: green;
      text-align: center;
      line-height: 40px;
      color: white;
      font-weight: bold;
  }
  .occupied {
    background-color: red !important;
    color: white;
}

.available {
    background-color: green !important;
    color: white;
}

  .seat-row {
      margin-bottom: 10px;
  }
  .modal {
    display: none; 
    position: fixed; 
    z-index: 1; 
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal-content {
    background-color: white;
    padding: 20px;
    margin: 15% auto;
    width: 300px;
    text-align: center;
    border-radius: 10px;
}
</style>
<script> 
    function toggleMenu() {
        var sidebar = document.getElementById("sidebar");
        sidebar.classList.toggle("active");
    }
let selectedSeat = null;  // Seçili koltuğu takip etmek için değişken

// Modalı açma fonksiyonu
function openModal(element, seatId) {
    if (element.classList.contains("occupied")) {
        alert("Bu koltuk zaten rezerve edilmiş!");
        return;
    }
    let date = document.getElementById("tarih").value;
    let time = document.getElementById("saat").value;

    document.getElementById("selectedRoom").value = seatId; // Bu satır kritik!
    selectedSeat = element; // Seçilen koltuğu kaydet
    document.getElementById("modalMessage").innerHTML = `
        
        <strong>Adınıza rezervasyon yapılacaktır, onaylıyor musunuz?</strong><br><br>
        <hr> 
        <b>Erzincan Binali Yıldırım Üniversitesi Kütüphaneleri / Zemin Kat / Bireysel</b><br>
        <b>${seatId}</b> / ${date} - ${time}<br><br>
        <hr> 
        <div style="text-align: left;">
        <p>1- Oda tek kişiliktir.</p>
        <p>2- Odalarda pet şişede su hariç herhangi bir katı veya sıvı gıda tüketmeyiniz.</p>
        <p>3- Kurallara uymayanlara bir daha bireysel çalışma odası verilmeyecektir.</p>
        <hr>
        </div>
    `;
    document.getElementById("customModal").style.display = "block";
}


async function confirmSeat(isConfirmed) {
    const modal = document.getElementById("customModal");
    modal.style.display = "none"; // Modalı kapat

    if (!isConfirmed || !selectedSeat) return;

    // 1. Koltuk durumunu UI'da güncelle
    selectedSeat.classList.add("occupied");
    selectedSeat.classList.remove("available");

    // 2. Form verilerini hazırla
    const form = document.getElementById("reservationForm");
    const room = selectedSeat.innerText;
   // form.querySelector("[name=room]").value = room;

    try {
        // 3. Önce update_seat.php'ye istek at
        const updateResponse = await fetch("update_seat.php", {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: `seat=${encodeURIComponent(room)}&status=1`
        });
        const updateResult = await updateResponse.text();

        if (!updateResponse.ok) {
            throw new Error(updateResult);
        }

        // 4. Başarılıysa formu submit et
        form.submit();
    } catch (error) {
        console.error("Hata:", error);
        // Hata durumunda UI'ı eski haline getir
        selectedSeat.classList.remove("occupied");
        selectedSeat.classList.add("available");
        alert("Rezervasyon başarısız: " + error.message);
    }
}

// Seçili koltukları backend'e gönderme
function completeReservation() {
    let selectedSeats = [];

    // Seçili koltukları topla
    document.querySelectorAll('.seat.occupied').forEach(function (seat) {
        selectedSeats.push(seat.innerText);
    });

    if (selectedSeats.length === 0) {
        alert("Lütfen en az bir koltuk seçin!");
        return;
    }

    // Formdaki diğer bilgileri topla
    const floor = document.getElementById("floor").value;
    const roomType = document.getElementById("room_type").value;
    const date = document.getElementById("date").value;
    const timeSlot = document.getElementById("time_slot").value;

    const formData = new URLSearchParams();

    // Koltukları seats[] şeklinde ekle
    selectedSeats.forEach((seat, index) => {
        formData.append(`seats[${index}]`, seat);
    });

    // Diğer bilgileri de ekle
    formData.append("floor", floor);
    formData.append("room_type", roomType);
    formData.append("date", date);
    formData.append("time_slot", timeSlot);

    // Backend'e gönder
    fetch("complete_reservation.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: formData.toString()
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert("Rezervasyon başarıyla tamamlandı!");
            window.location.reload();
        } else {
            alert("Hata: " + data.error);
        }
    })
    .catch(error => {
        console.error("Bir hata oluştu:", error);
        alert("Bir hata oluştu! Lütfen tekrar deneyin.");
    });
}
function redirectPage(e) {
    e.preventDefault(); // Formun normal gönderimini engelle

    const selectedKat = document.getElementById("salon").value;
    const selectedOda = document.getElementById("oda").value;

    const form = document.getElementById("reservationForm");

    // Geçerli seçimler yapılmamışsa uyarı
    if (!selectedKat || !selectedOda) {
        alert("Lütfen geçerli bir salon ve oda türü seçin.");
        return;
    }

    // Salon ve oda türüne göre yönlendirme
    if (selectedKat === "1" && selectedOda === "Bireysel") {
        form.action = "rezervasyon2.php"; // Zemin Kat - Bireysel
    } else if (selectedKat === "2" && selectedOda === "Grup") {
        form.action = "gruprezervasyon.php"; // 3. Kat - Grup
    }else if (selectedKat === "2" && selectedOda === "Bireysel") {
        form.action = "kat2bireysel.php"; // 3. Kat - Grup
    } else if (selectedKat === "3" && selectedOda === "Bireysel") {
        form.action = "kat3bireysel.php"; // 3. Kat - Bireysel
    } else if (selectedKat === "3" && selectedOda === "Grup") {
        form.action = "kat3grup.php"; // 3. Kat - Grup
    } else if (selectedKat === "4" && selectedOda === "Bireysel") {
        form.action = "kat4bireysel.php"; // 3. Kat - Grup
    }else if (selectedKat === "4" && selectedOda === "Grup") {
        form.action = "kat4grup.php"; // 3. Kat - Grup
    }
    

    form.submit(); // Formu gönder
}

// Sayfa yüklendiğinde ayarları yap
document.addEventListener("DOMContentLoaded", function () {
    // updateRoomOptions();

    // document.getElementById("salon").addEventListener("change", updateRoomOptions);
    document.getElementById("reservationForm").addEventListener("submit", redirectPage);
});


</script>

</body>
</html>
