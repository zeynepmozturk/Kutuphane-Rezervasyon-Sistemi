<?php
include("baglanti.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $seat = $_POST["seat"]; // oda adı gibi düşünülebilir
    $user_id = $_POST["user_id"];
    $floor = $_POST["floor"];
    $room_type = $_POST["room_type"];
    $date = $_POST["date"];
    $time_slot = $_POST["time_slot"];

    $query = "INSERT INTO reservations (user_id, floor, room_type, date, time_slot, room) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($baglanti, $query);
    mysqli_stmt_bind_param($stmt, "iissss", $user_id, $floor, $room_type, $date, $time_slot, $seat);

    if (mysqli_stmt_execute($stmt)) {
        echo "Rezervasyon başarıyla kaydedildi.";
    } else {
        echo "Rezervasyon kaydedilemedi!";
    }

    mysqli_stmt_close($stmt);
    mysqli_close($baglanti);
}
?>