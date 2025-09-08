<?php
$db_host = "127.0.0.1";
$db_user = "root";
$db_pass = "1234";
$db_name = "newsql";

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);

if (!$conn) {
    die("Kết nối thất bại: " . mysqli_connect_error());
}
?>