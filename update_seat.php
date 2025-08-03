<?php
include("ayarlar.php");
include("banner.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $seat = $_POST['seat'];
    $status = $_POST['status'];  // 1: occupied, 0: available
    $salon = $_POST['salon'];
    $tarih = $_POST['tarih'];
    $saat = $_POST['saat'];  
    $room_type= $_POST['oda'];
    

    // SQL sorgusu ile koltuk durumunu güncelle
    
     $query = $dbgln->prepare("INSERT INTO seatselection SET
seat=?,
status=?,
salon = ?,
tarih = ?,
saat = ?,
room_type=?,
userid=?
");  
$insert = $query->execute(array(
   $seat,
   $status,
   $salon,      
   $tarih,  
   $saat,
   $room_type,
   $user_id
));
    
 
}

?>
