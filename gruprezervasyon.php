<?php
 include("banner.php");
 include("baglanti.php"); // Veritabanı bağlantısı
// Koltuk durumlarını al 
 if(isset($_POST['salon'])){
$salonpost=$_POST['salon'];
$tarihpost=$_POST['tarih'];
$saatpost=$_POST['saat'];
$roompost=$_POST['oda'];
 
$query = "SELECT * FROM seatselection  where salon ='$salonpost' and tarih='$tarihpost'  and saat ='$saatpost' and room_type ='$roompost'";
$result = mysqli_query($baglanti, $query);

$seats = [];// Koltukları bir diziye yerleştir
while ($row = mysqli_fetch_assoc($result)) {
    $seats[$row['seat']] = $row['status'];  // Koltuk adı => durum   
}

mysqli_close($baglanti);
 }
 else
 {
    $salonpost=null;
$tarihpost=null;
$saatpost=null;
$roompost=null;
 }

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
        margin-bottom: 30px; /* Başlık ile bloklar arasına boşluk */
    }
    /* Yeni .floor-layout sınıfı ile genel düzeni kontrol ediyoruz */
    .floor-layout {
        display: flex;
        flex-direction: column; /* Önce dikey sonra yatay bileşenler */
        align-items: center; /* Ortaya hizalama */
        margin-top: 20px;
    }
    /* Üst yatay bloklar için */
    .top-section {
        display: flex;
        justify-content: center; /* Blokları ortaya hizala */
        gap: 30px; /* Bloklar arası boşluk */
        margin-bottom: 20px; /* Dikey bloklarla arasında boşluk */
    }
    /* Dikey yan bloklar için */
    .side-sections {
        display: flex;
        justify-content: space-between; /* Yan blokları kenarlara it */
        width: 100%; /* Konteyner genişliğinde olmalı */
        max-width: 800px; /* Maksimum genişlik ile kontrol */
        padding: 0 50px; /* Kenarlardan boşluk bırak */
        box-sizing: border-box; /* Padding'i genişliğe dahil et */
    }
    .block {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 10px;
        background: lightgray;
        padding: 10px;
        border-radius: 10px;
    }

    /* Tek sütunlu dikey bloklar için (eğer U'nun bacakları tek sütun olacaksa) */
    .block.vertical {
        grid-template-columns: 1fr; /* Tek sütun */
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
        margin-bottom: 10px; /* Yönlendirme yazısı ile blok arasında boşluk */
        text-align: center;
    }
    .entrance {
        margin-top: 40px; /* Giriş butonu için daha fazla boşluk */
        font-size: 18px;
        background: navy;
        color: white;
        padding: 10px 20px; /* Daha geniş padding */
        display: inline-block;
        border-radius: 5px;
        cursor: pointer; /* İşaretçi ekleyelim */
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
<div class="sidebar active" id="sidebar">
      <form  enctype="multipart/form-data" method="post" id="reservationForm" > 
    <label for="salon">Salon Seç:</label>
    <select id="salon" name="salon" onchange="redirectPage()">
        <option value="ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / Zemin Kat / Bireysel">ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / Zemin Kat / Bireysel</option>
        <option value="ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / 1. Kat">ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / 1. Kat</option>
        <option value="ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / 2. Kat">ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / 2. Kat</option>
        <option value="ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / 3. Kat">ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / 3. Kat</option>
    </select>

    <label for="tarih">Tarih Seç:</label>
    <input type="date" id="tarih" name="tarih" value="<?php echo date('Y-m-d'); ?>">
       

    <label for="saat">Saat Seç:</label>
    <select id="saat" name="saat">
        <option value="08:00 - 12:00">08:00 - 12:00</option>
        <option value="12:00 - 16:00">12:00 - 16:00</option>
        <option value="16:00 - 20:00">16:00 - 20:00</option>
    </select>

    <label for="oda">Oda Türü:</label>
    <select id="oda" name="oda">
        <option value="">Seçiniz...</option>
        <option value="bireysel">Bireysel</option>
        <option value="grup">Grup</option>
    </select>
    <button type="submit" class="button"  name="sorgula" id="sorgula"  >Sorgula</button> 
  </form>     
</div>
   <div class="container" style="text-align: center;">
    <div class="title" style="text-align: center;">BİRİNCİ KAT DANIŞMA KAYNAKLARI SALONU</div>

    <div class="floor-layout">

        <div class="direction">ÜST YATAY BLOKLAR</div>
        <div class="top-section">
            <div class="block">
                <?php
                // Üst yatay bloktaki koltuklar (örneğin M-21'den M-24'e)
                $seatNamesTop1 = ["M-1", "M-2", "M-3", "M-4"];
                foreach ($seatNamesTop1 as $seat) {
                    $status = isset($seats[$seat]) && $seats[$seat] == 1 ? 'occupied' : 'available';
                    echo "<div class='room $status' onclick='openModal(this, \"$seat\",\"$tarihpost\",\"$saatpost\",\"$salonpost\",\"$roompost\")'>$seat</div>";
                }
                ?>
            </div>
            <div class="block">
                <?php
                // Üst yatay bloktaki diğer koltuklar (örneğin M-25'ten M-28'e)
                $seatNamesTop2 = ["M-5", "M-6", "M-7", "M-8"];
                foreach ($seatNamesTop2 as $seat) {
                    $status = isset($seats[$seat]) && $seats[$seat] == 1 ? 'occupied' : 'available';
                    echo "<div class='room $status' onclick='openModal(this, \"$seat\",\"$tarihpost\",\"$saatpost\",\"$salonpost\",\"$roompost\")'>$seat</div>";
                }
                ?>
            </div>
        </div>

        <div class="side-sections">
            <div class="column">
                <div class="direction">SOL DİKEY BLOK</div>
                <div class="block vertical"> <?php
                    // Sol dikey bloktaki koltuklar (örneğin M-29'dan M-32'ye)
                    $seatNamesLeft = ["M-9", "M-10", "M-11", "M-12"];
                    foreach ($seatNamesLeft as $seat) {
                        $status = isset($seats[$seat]) && $seats[$seat] == 1 ? 'occupied' : 'available';
                        echo "<div class='room $status' onclick='openModal(this, \"$seat\",\"$tarihpost\",\"$saatpost\",\"$salonpost\",\"$roompost\")'>$seat</div>";
                    }
                    ?>
                </div>
            </div>

            <div class="column">
                <div class="direction">SAĞ DİKEY BLOK</div>
                <div class="block vertical"> <?php
                    // Sağ dikey bloktaki koltuklar (örneğin M-33'ten M-36'ya)
                    $seatNamesRight = ["M-13", "M-14", "M-15", "M-16"];
                    foreach ($seatNamesRight as $seat) {
                        $status = isset($seats[$seat]) && $seats[$seat] == 1 ? 'occupied' : 'available';
                        echo "<div class='room $status' onclick='openModal(this, \"$seat\",\"$tarihpost\",\"$saatpost\",\"$salonpost\",\"$roompost\")'>$seat</div>";
                    }
                    ?>
                </div>
            </div>
        </div>

    </div>

    <div class="entrance">BİRİNCİ KAT GİRİŞ</div>

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
function openModal(element, seatId,tarihpost,saatpost,salonpost,roompost) {
    if (element.classList.contains("occupied")) {
        alert("Bu koltuk zaten rezerve edilmiş!");
        return;
    }

    selectedSeat = element; // Seçilen koltuğu kaydet
    selectedtarih = tarihpost;
    selectedsaat = saatpost;
    selectedsalon = salonpost;
    selectedoda = roompost;  
    document.getElementById("modalMessage").innerHTML = `
        
        <strong>Adınıza rezervasyon yapılacaktır, onaylıyor musunuz?</strong><br><br>
        <hr> 
        <b>${salonpost}</b><br>
        <b>${seatId}</b> / ${tarihpost} <br> ${saatpost} <br><br>
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


// Rezervasyon onay fonksiyonu
function confirmSeat(isConfirmed) {
    document.getElementById("customModal").style.display = "none"; // Modalı kapat

    if (!isConfirmed || !selectedSeat) return; // Kullanıcı "Hayır" dediyse çık

    selectedSeat.classList.add("occupied");
    selectedSeat.classList.remove("available");

    fetch("update_seat.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "seat=" + selectedSeat.innerText + "&status=1" + "&salon="+ selectedsalon  + "&tarih=" + selectedtarih + "&saat=" +selectedsaat + "&oda=" + selectedoda
    })
    .then(response => response.text())
    .then(data => {
        console.log(data);
        alert("Rezervasyon başarıyla tamamlandı!");
    })
    .catch(error => console.error("Hata:", error));
}

// Seçili koltukları backend'e gönderme
function completeReservation() {
    let selectedSeats = [];

    // Seçilen koltukları alıyoruz
    document.querySelectorAll('.seat.occupied').forEach(function (seat) {
        selectedSeats.push(seat.innerText);
    });
     

    if (selectedSeats.length === 0) {
        alert("Lütfen bir koltuk seçin!");
        return;
    }

    fetch("complete_reservation.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "seats=" + JSON.stringify(selectedSeats)
    })
    .then(response => response.text())
    .then(data => {
        console.log(data);
        alert(data);
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
if (selectedKat === "ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / Zemin Kat / Bireysel" && selectedOda === "bireysel") {
    form.action = "rezervasyon2.php"; // Zemin Kat - Bireysel
} else if (selectedKat === "ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / 1. Kat" && selectedOda === "grup") {
    form.action = "gruprezervasyon.php"; // 3. Kat - Grup
}else if (selectedKat === "ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / 1. Kat" && selectedOda === "bireysel") {
    form.action = "kat2bireysel.php"; // 3. Kat - Grup
} else if (selectedKat === "ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / 2. Kat" && selectedOda === "bireysel") {
    form.action = "kat3bireysel.php"; // 3. Kat - Bireysel
} else if (selectedKat === "ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / 2. Kat" && selectedOda === "grup") {
    form.action = "kat3grup.php"; // 3. Kat - Grup
} else if (selectedKat === "ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / 3. Kat" && selectedOda === "bireysel") {
    form.action = "kat4bireysel.php"; // 3. Kat - Grup
}else if (selectedKat === "ERZİNCAN BİNALİ YILDIRIM ÜNİVERSİTESİ / 3. Kat" && selectedOda === "grup") {
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
