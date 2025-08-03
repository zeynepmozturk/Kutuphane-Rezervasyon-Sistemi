<?php
include("baglanti.php");
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "error" => "Yetkisiz erişim!"]);
    exit;
}

$seats = isset($_POST['seats']) ? $_POST['seats'] : [];
$floor = isset($_POST['floor']) ? (int)$_POST['floor'] : null;
$room_type = isset($_POST['room_type']) ? $_POST['room_type'] : null;
$date = isset($_POST['date']) ? $_POST['date'] : null;
$time_slot = isset($_POST['time_slot']) ? $_POST['time_slot'] : null;

if (empty($seats) || !$floor || !$room_type || !$date || !$time_slot) {
    echo json_encode(["success" => false, "error" => "Eksik veri gönderildi."]);
    exit;
}

mysqli_begin_transaction($baglanti);

try {
    foreach ($seats as $seat) {
        // Koltuk dolu mu kontrolü
        $check_sql = "SELECT status FROM seats WHERE room = ? AND status = 1 FOR UPDATE";
        $stmt_check = mysqli_prepare($baglanti, $check_sql);
        mysqli_stmt_bind_param($stmt_check, "s", $seat);
        mysqli_stmt_execute($stmt_check);
        mysqli_stmt_store_result($stmt_check);

        if (mysqli_stmt_num_rows($stmt_check) > 0) {
            throw new Exception("Koltuk zaten dolu: " . $seat);
        }

        // Rezervasyon kaydı
        $reservation_sql = "INSERT INTO reservations (user_id, floor, room_type, date, time_slot, status, room) 
                            VALUES (?, ?, ?, ?, ?, 1, ?)";
        $stmt_reservation = mysqli_prepare($baglanti, $reservation_sql);
        mysqli_stmt_bind_param($stmt_reservation, "iissss", $_SESSION['user_id'], $floor, $room_type, $date, $time_slot, $seat);

        if (!mysqli_stmt_execute($stmt_reservation)) {
            throw new Exception("Rezervasyon kaydedilemedi: " . $seat);
        }

        // seats tablosunda koltuğu güncelle
        $update_sql = "UPDATE seats SET status = 1, user_id = ?, date = ?, time = ? WHERE room = ?";
        $stmt_update = mysqli_prepare($baglanti, $update_sql);
        $time = explode("-", $time_slot)[0] . ":00"; // Saat formatı ayarlanıyor (örn: "08:00-12:00" ➔ "08:00:00")
        mysqli_stmt_bind_param($stmt_update, "isss", $_SESSION['user_id'], $date, $time, $seat);

        if (!mysqli_stmt_execute($stmt_update)) {
            throw new Exception("Koltuk güncellenemedi: " . $seat);
        }
    }

    mysqli_commit($baglanti);
    echo json_encode(["success" => true, "message" => "Tüm rezervasyonlar başarıyla yapıldı."]);

} catch (Exception $e) {
    mysqli_rollback($baglanti);
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
} finally {
    mysqli_close($baglanti);
}
?>
