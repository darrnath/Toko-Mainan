<?php
session_start();
//APAKAH SUDAH LOGIN?
if (!$_SESSION['masuk']) {
    header('Location: ../index.php');
}
require '../include/functions.php';
$username = $_SESSION['nama'];
$id_user = $_SESSION['id_user'];
$id_transaksi = $_GET['p'];

$query = "SELECT bukti_pembayaran,transaksi.id_produk, id_pembayaran, pembayaran.id_transaksi,CONCAT(nama_depan,' ',nama_belakang) AS nama,nama_produk,jumlah,harga_produk,id_pengiriman,jenis_pengiriman,jenis_pembayaran,status_pembayaran FROM transaksi,pengiriman,kat_pengiriman,produk,pembayaran,kat_pembayaran,user WHERE transaksi.id_transaksi = pembayaran.id_transaksi AND transaksi.id_produk=produk.id_produk AND transaksi.id_user = user.id_user AND transaksi.id_transaksi=pengiriman.id_transaksi AND pengiriman.id_kat_pengiriman = kat_pengiriman.id_kat_pengiriman AND pembayaran.id_kat_pembayaran = kat_pembayaran.id_kat_pembayaran AND pembayaran.id_transaksi = '$id_transaksi'";
$details = query($query);
$id_pembayaran = $details[0]['id_pembayaran'];

if(isset($_POST['approved'])){
    if($details[0]['status_pembayaran'] == "Approved"){
    echo "<script> alert('Pembayaran sudah di approved');
    document.location.href = 'adminCheck.php'; </script>";
    } else {
    global $conn;
    mysqli_query($conn,"UPDATE pembayaran SET status_pembayaran = 'Approved' WHERE id_pembayaran ='$id_pembayaran'");
    echo "<script> alert('Pembayaran berhasil di approved');
    document.location.href = 'adminCheck.php'; </script>";
    }
    exit;
}

if(isset($_POST['resubmit'])){
    if($details[0]['status_pembayaran'] == "Resubmit"){
    echo "<script> alert('Permintaan Resubmit Telah dilakukan');
    document.location.href = 'adminCheck.php'; </script>";
    } else {
    global $conn;
    mysqli_query($conn,"UPDATE pembayaran SET status_pembayaran = 'Resubmit' WHERE id_pembayaran ='$id_pembayaran'");
    echo "<script> alert('Permintaan Resubmit berhasil');
    document.location.href = 'adminCheck.php'; </script>";
    }
    exit;
}

if(isset($_POST['canceled'])){
    global $conn ,$id_transaksi;
    $id_produk = $details [0]['id_produk'];
    $jumlah = $details[0]['jumlah'];
    $query = "UPDATE produk SET stok = stok + '$jumlah' WHERE id_produk = '$id_produk' ;";
    $query .= "DELETE FROM pembayaran WHERE id_transaksi ='$id_transaksi';";
    $query .= "DELETE FROM pengiriman WHERE id_transaksi ='$id_transaksi';";
    mysqli_multi_query($conn,$query);
    echo "<script> alert('Transaksi dibatalkan');
    document.location.href = 'adminCheck.php'; </script>";
    exit;
}

//SEARCH
$categories = query("SELECT * FROM kat_produk");
if(isset($_POST['btn-search'])){
    $keyword = $_POST['search'];
    $products = query("SELECT * FROM produk WHERE nama_produk LIKE '%$keyword%' ORDER BY id_produk DESC");
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/adminApprove.css">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/header.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko mainan</title>
</head>
<body>
    <header> 
        <marquee>Mainan Anak - Toko Mainan - Jual Mainan - Alat Peraga Edukatif - Mainan Bayi - Mainan Kayu - Grosir Mainan - Wooden Toys</marquee>
        <nav>
            <h1 class="tokomainan-logo mr-2" 
                onclick="window.location.href='./admin.php'"> tokomainan </h1>
            <div class="category-wrapper">
                <a class="btn-category btn-secondary"> Kategori </a>
                <div class="category-overlay">
                    <?php foreach($categories as $categorie) : ?>
                        <a class="category-link" 
                            href="admin.php?kat=<?= $categorie['id_kat_produk']?>"> 
                            <?= $categorie['jenis_produk'] ?> 
                        </a>
                    <?php endforeach;?>
                </div>
            </div>
            <form action="./admin.php" method="get">
                <input name="search" id="search-input-product" type="text" 
                placeholder="Cari barang disini" required></input>
                <button class="ml-2" name="btn-search" id="btn-search" type="submit">
                    <i class="fa fa-search mr-2"></i>
                </button>
            </form>

            <div class="filter-wrapper">
                <a class="btn-filter">
                    <i class="fa fa-filter"></i>
                </a>
                <div class="filter-overlay mt-2">
                    <h1>Urutkan Berdasarkan : </h1>
                    <a href=<?= filterUrlLink("asc") ?>>Berdasarkan harga terendah</a>
                    <a href=<?= filterUrlLink("desc") ?>>Berdasarkan harga tertinggi</a>
                </div>
            </div>

            <div class="setting-wrapper">
                <a class="btn-setting ml-2 mr-2 btn-cart">
                    <i class="fa fa-cog"></i>
                </a>

                <div class="setting-overlay mt-2">
                    <a href="./adminCheck.php">Cek pemesanan</a>
                    <a href="./adminPengiriman.php">Cek pengiriman</a>
                </div>

            </div>

            <a href="./adminProfile.php" class="mr-2 btn-profile">
                <i class="fa fa-user"></i>
            </a>
            <a class="btn-secondary" id="btn-logout" href="../include/logout.php" >Logout</a>
        </nav>
    </header>

    <main id="admin-approve-main">
        <section id="container-admin">
            <form action="./adminCheck.php" method="post">
                <label class="admin-check-title" for="transaksi">Cek data pemesanan : </label> <br>
                <input type="text" name="transaksi" id="transaksi" placeholder="Masukan no transaksi" required>
                <button class="btn-primary ml-1" type="submit" name="tombol">SEARCH</button>
            </form>

            <div class="admin-approve-detail mt-3" id="ket">
                <?php foreach($details as $detail) : ?>
                    <div style="display: flex;">
                        <div class="admin-approve-section-left">
                            <div class="mb-3">
                                <h1>ID transaksi</h1>
                                <p ><?= $detail['id_transaksi'] ?></p>
                            </div>

                            <div class="mb-3">
                                <h1>Nama pembeli</h1>
                                <p> <?= $detail['nama'] ?> </p>
                            </div>

                            <div class="mb-3">
                                <h1>Jenis pembayaran : </h1>
                                <p> <?= $detail['nama_produk'] ?> </p>
                            </div>
                        </div>

                        <div class="admin-approve-section-right">
                            <div class="mb-2">
                                <h1>Jenis pembayaran</h1>
                                <p> <?= $detail['jenis_pembayaran'] ?> </p>  
                            </div>
                            
                            <div class="mb-3">
                                <h1>Jenis pengiriman</h1>
                                <p> <?= $detail['jenis_pengiriman'] ?> </p>
                            </div>

                            <div class="mb-3">
                                <h1>Total yang harus di bayar : </h1>
                                <p><?= rupiah($detail['jumlah']*$detail['harga_produk']) ?> </p>
                            </div>
                        </div>
                    </div>

                    <div class="admin-approve-section-tool">
                        <form action="" method="post">
                        <?php if(!is_null($detail['bukti_pembayaran'])) : ?>
                            <button type="submit" id="approved" name="approved" class="btn-primary"> APPROVED </button>
                            <button type="submit" id="resubmit" name="resubmit" class="btn-secondary"> RESUBMIT </button>
                        <?php endif; ?>
                            <button type="submit" id="canceled" name="canceled" class="btn-secondary"
                                    onclick="return confirm('Apakah anda ingin membatalkan transaksi?')"> CANCELED </button>
                        </form>
                    </div>
                <?php endforeach; ?>   
            </div>                                                      
        </section>
    </main>

    <script src="../js/DOMScript.js"></script>
</body>
</html>