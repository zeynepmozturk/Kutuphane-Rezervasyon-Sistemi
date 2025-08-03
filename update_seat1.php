<?php
include("baglanti.php");

header('Content-Type: application/json'); // JSON response için

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $seat = trim($_POST['seat']); // Boşlukları temizle
    $status = (int)$_POST['status']; // Güvenli tür dönüşümü

    // Transaction başlat
    mysqli_begin_transaction($baglanti);

    try {
        // 1. Koltuk durumunu kilitle ve kontrol et
        $checkQuery = "SELECT status FROM seats WHERE room = ? FOR UPDATE";
        $checkStmt = mysqli_prepare($baglanti, $checkQuery);
        mysqli_stmt_bind_param($checkStmt, "s", $seat);
        mysqli_stmt_execute($checkStmt);
        mysqli_stmt_bind_result($checkStmt, $currentStatus);
        mysqli_stmt_fetch($checkStmt);
        mysqli_stmt_close($checkStmt);

        if ($currentStatus == 1) {
            throw new Exception("Bu koltuk zaten dolu!");
        }

        // 2. Koltuk durumunu güncelle
        $updateQuery = "UPDATE seats SET status = ? WHERE room = ?";
        $updateStmt = mysqli_prepare($baglanti, $updateQuery);
        mysqli_stmt_bind_param($updateStmt, "is", $status, $seat);
        mysqli_stmt_execute($updateStmt);

        mysqli_commit($baglanti);
        echo json_encode(["success" => true, "message" => "Koltuk güncellendi"]);
    } catch (Exception $e) {
        mysqli_rollback($baglanti);
        http_response_code(400); // Bad Request
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    } finally {
        mysqli_close($baglanti);
    }
} else {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["success" => false, "error" => "Sadece POST isteği kabul edilir"]);
}
?>