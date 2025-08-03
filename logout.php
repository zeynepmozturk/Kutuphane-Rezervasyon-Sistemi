<?php
session_start();
ob_start();
session_destroy();
echo "<center>Cikis yaptiniz. Ana sayfaya yönlendiriliyorsunuz.</center>";
if($_SERVER ['SERVER_NAME']!='127.0.0.1')
{
    header("Refresh: 2; url=anasayfa.php");
}
else
{
    header("Refresh:2; url=anasayfa.php");
}

ob_end_flush();
?>