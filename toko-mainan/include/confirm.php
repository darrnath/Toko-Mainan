<?php
require 'functions.php';
if(isset($_GET['c'])){
    global $conn;
    $id_pengiriman = $_GET['c'];
    $query = "UPDATE pengiriman SET status_pengiriman = 'Arrived' WHERE id_pengiriman = '$id_pengiriman'";
    mysqli_query($conn,$query);
    if(mysqli_affected_rows($conn) > 0){
        echo "<script> 
        alert('Terima kasih telah mengkonfirmasi');
        document.location.href = '../pemesanan.php';
        </script>";
    }
} else {
    header('Location: ../pemesanan.php');
}

