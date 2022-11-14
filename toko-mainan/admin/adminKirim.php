<?php
session_start();
//APAKAH SUDAH LOGIN?
if (!$_SESSION['masuk']) {
    header('Location: ../index.php');
}
require '../include/functions.php';
$username = $_SESSION['nama'];
$id_user = $_SESSION['id_user'];
$id_pengiriman = $_GET['id'];

$query = "SELECT CONCAT(nama_depan,' ',nama_belakang) AS nama,no_hp_user, bukti_pembayaran,nama_produk,berat_produk,jumlah,kelurahan,kecamatan,kabupaten,provinsi,jenis_pengiriman,status_pembayaran,status_pengiriman,keterangan,pembayaran.id_transaksi FROM produk,user,pembayaran,pengiriman,kat_pengiriman,transaksi,alamat WHERE produk.id_produk = transaksi.id_produk AND user.id_user = transaksi.id_user AND user.id_alamat = alamat.id_alamat AND transaksi.id_transaksi = pengiriman.id_transaksi AND kat_pengiriman.id_kat_pengiriman = pengiriman.id_kat_pengiriman AND transaksi.id_transaksi = pembayaran.id_transaksi AND id_pengiriman = '$id_pengiriman'";
$details = query($query);

if(isset($_POST['sending'])){
    if($details[0]['status_pengiriman'] == "Sending"){
    echo "<script> alert('Barang sedang dikirim');
    document.location.href = 'adminPengiriman.php'; </script>";
    } else {
    global $conn;
    mysqli_query($conn,"UPDATE pengiriman SET status_pengiriman = 'Sending' WHERE id_pengiriman ='$id_pengiriman'");
    echo "<script> alert('Barang dikirim');
    document.location.href = 'adminPengiriman.php'; </script>";
    }
    exit;
}

if(isset($_POST['tombol']) && isset($_POST['transaksi'])){
    $id_transaksi = $_POST['transaksi'];
    $query = "SELECT CONCAT(nama_depan,' ',nama_belakang) AS nama,id_pengiriman,id_pembayaran,pembayaran.id_transaksi,nama_produk,jumlah,jenis_pengiriman,status_pengiriman,status_pembayaran FROM transaksi,user,produk,pengiriman,kat_pengiriman,pembayaran WHERE transaksi.id_transaksi = pembayaran.id_transaksi AND transaksi.id_transaksi = pengiriman.id_transaksi AND transaksi.id_user = user.id_user AND transaksi.id_produk = produk.id_produk AND pengiriman.id_kat_pengiriman = kat_pengiriman.id_kat_pengiriman AND status_pembayaran = 'Approved' AND pembayaran.id_transaksi = '$id_transaksi' ORDER BY id_pembayaran DESC";
    $details = query($query);
    $halaman = count($details);
}

if(isset($_POST['canceled'])){
    global $conn;
    $jumlah = $details[0]['jumlah'];
    $id_produk = $details[0]['id_produk'];
    $query = "UPDATE produk SET stok = stok + '$jumlah' WHERE id_produk = '$id_produk' ;";
    $query .= "DELETE FROM pembayaran WHERE id_pembayaran ='$id_pembayaran';";
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
            <form action="./adminPengiriman.php" method="post">
                <label class="admin-check-title" for="transaksi">Cek data pengiriman : </label> <br>
                <input type="number" name="transaksi" id="transaksi" placeholder="Masukan no transaksi" required>
                <button class="btn-primary ml-1" type="submit" name="tombol">SEARCH</button>
            </form>

            <div class="admin-approve-detail mt-3" id="ket">
                <?php foreach($details as $detail) : ?>
                    <div style="display: flex;">
                        <div class="admin-approve-section-left">
                            <div class="mb-3">
                                <h1>ID transaksi</h1>
                                <p> <?= $detail['id_transaksi'] ?> </p>
                            </div>

                            <div class="mb-3">
                                <h1>Nama pembeli</h1>
                                <p> <?= $detail['nama'] ?> </p>
                            </div>

                            <div class="mb-3">
                                <h1>Jumlah produk </h1>
                                <p> <?= $detail['jumlah'] ?> </p>
                            </div>

                            <div class="mb-3">
                                <h1>Alamat </h1>
                                <p> <?= $detail['provinsi'] ?>, <?= $detail['kabupaten'] ?>, <?= $detail['kecamatan'] ?>, <?= $detail['kelurahan'] ?>  </p>
                                <p> <?= $detail['keterangan'] ?> </p>
                            </div>
                        </div>

                        <div class="admin-approve-section-right">
                            <div class="mb-2">
                                <h1>Nama produk</h1>
                                <p> <?= $detail['nama_produk'] ?> </p>
                            </div>
                            
                            <div class="mb-3">
                                <h1>No telepon</h1>
                                <p> <?= $detail['no_hp_user'] ?> </p>
                            </div>

                            <div class="mb-3">
                                <h1>Berat produk </h1>
                                <p><?= $detail['berat_produk'] . " gram" ?></p>
                            </div>

                            <div class="mb-3">
                                <h1>Bukti pembayaran </h1>
                                <a id="btn-download" href="../pembayaran/<?= $detail['bukti_pembayaran'];?>"target="_blank" download="">download</a>
                            </div>
                        </div>
                    </div>

                    <div class="admin-approve-section-tool">
                        <?php if($detail['status_pengiriman'] == "Packing") : ?>
                        <form action="" method="post">
                            <button class="btn-primary" type="submit" id="sending" name="sending"> SENDING </button>
                        </form>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>   
            </div>
        </section>
    </main>

    <script src="../js/DOMScript.js"></script>
</body>
</html>