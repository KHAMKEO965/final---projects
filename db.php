<?php
$host = "localhost";
$user = "root";
$pass = "";
$db_name = "lao_air_cargo_db";

$conn = mysqli_connect($host, $user, $pass, $db_name);
mysqli_set_charset($conn, "utf8mb4");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>