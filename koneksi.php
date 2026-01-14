<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);


$servername = "sql110.infinityfree.com"; 
$username   = "if0_40823136";           
$password   = "5tjdBYy0l0Lh8S";         
$database   = "if0_40823136_cling_laundry";   


$conn = mysqli_connect($servername, $username, $password, $database);

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error());
} else {

}
?>