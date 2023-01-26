<?php
// KONEKSI DATABASE LOCALHOST
$username = "root";
$password = '';
$host = 'localhost';
$database = 'toko-mainan';
$conn = mysqli_connect($host,$username,$password,$database);
if(!$conn){
    die("Connection error ".mysqli_connect_error());
}

//KONEKSI DATABASE ONLINE
// $username = "epiz_28243947";
// $password = 'QD15Ru8QcGCy';
// $host = 'sql204.epizy.com';
// $database = 'epiz_28243947_toko_mainan_db';
// $port = '3306';
// $conn = mysqli_connect($host,$username,$password,$database,$port);
// if(!$conn){
//     die("Connection error ".mysqli_connect_error());
// }
?>