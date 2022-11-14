<?php
session_start();
//APAKAH SUDAH LOGIN?
if(!$_SESSION['login']){
    header('Location: index.php');
}
if(!$_POST['id_transaksi']){
    header('Location: keranjang.php');
}
require 'include/functions.php';
$categories = query("SELECT * FROM kat_produk");
if(isset($_GET['kat'])){
    $id = $_GET['kat'];
    $products = query("SELECT*FROM produk WHERE id_kat_produk='$id' AND status_produk = 'Ready'");
}

if(isset($_GET['btn-search'])){
    $keyword = $_GET['search'];
    $products = query("SELECT * FROM produk WHERE nama_produk LIKE '%$keyword%' AND status_produk = 'Ready'");
}

//PEMBAYARAN
$payments = query("SELECT * FROM kat_pembayaran");
//PENGIRIMAN
$senders = query("SELECT * FROM kat_pengiriman");
$tanggal_sekarang = date('Y-m-d');
$username = $_SESSION['nama'];
$id = $_SESSION['id_user'];

$id_transaksi = $_POST['id_transaksi'];
//DETAIL YANG INGIN DITAMPILKAN
$details = query("SELECT CONCAT(nama_depan,' ',nama_belakang) AS nama,id_transaksi,gambar_produk,nama_produk,jumlah,harga_produk,stok,produk.id_produk FROM transaksi,produk,user WHERE transaksi.id_produk=produk.id_produk AND transaksi.id_user=user.id_user AND transaksi.id_user='$id' AND id_transaksi='$id_transaksi'");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/pembayaran.css">
    <link rel="stylesheet" href="./css/global.css">
    <link rel="stylesheet" href="./css/header.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko mainan</title>
</head>
<body>
   <!-- HOMEPAGE HEADER -->
    <header> 
        <marquee>Mainan Anak - Toko Mainan - Jual Mainan - Alat Peraga Edukatif - Mainan Bayi - Mainan Kayu - Grosir Mainan - Wooden Toys</marquee>
        <nav>
            <h1 class="tokomainan-logo mr-2" 
                onclick="window.location.href='./user.php'"> tokomainan </h1>
            <div class="category-wrapper">
                <a class="btn-category btn-secondary"> Kategori </a>
                <div class="category-overlay">
                    <?php foreach($categories as $categorie) : ?>
                        <a class="category-link" 
                            href="user.php?kat=<?= $categorie['id_kat_produk']?>"> 
                            <?= $categorie['jenis_produk'] ?> 
                        </a>
                    <?php endforeach;?>
                </div>
            </div>
            <form action="./user.php" method="get">
                <input name="search" id="search-input-product" type="text" 
                placeholder="Cari barang disini" required></input>
                <button class="ml-2" name="btn-search" id="btn-search" type="submit">
                    <i class="fa fa-search mr-2"></i>
                </button>
            </form>

            <a href="./pemesanan.php" class="btn-history">
                <i class="fa fa-print"></i>
            </a>

            <a href="./keranjang.php" class="ml-2 mr-2 btn-cart">
                <i class="fa fa-shopping-cart"></i>
            </a>

            <a href="./profile.php" class="mr-2 btn-profile">
                <i class="fa fa-user"></i>
            </a>
            <a class="btn-secondary" id="btn-logout" href="./include/logout.php" >Logout</a>
        </nav>
    </header>


    <main id="confirmation-body">
        <section id="confirmation-section">
            <form action="./include/metode.php" method="post">
                <input type="hidden" name="id_produk" id="id_produk" 
                        value="<?=$details[0]['id_produk']?>">    
                <input type="hidden" name="stok" id="stok" 
                        value="<?=$details[0]['stok']?>">
                <input type="hidden" name="jumlah" id="jumlah" 
                        value="<?=$details[0]['jumlah']?>">

                <div class="confirmation-group">
                    <div style="display: flex; flex-direction: column;">
                        <h1 class="confirmation-title ml-2 mt-2">Konfirmasi pembelian</h1>
                        <div class="confirmation-desc">
                            <div class="mt-3 ml-3">
                                <h4>Nama produk </h4>
                                <?=$details[0]['nama_produk'] ?>
                            </div>

                            <div class="mt-3 ml-3">
                                <h4>Jumlah</h4>
                                <?=$details[0]['jumlah'] ?>
                            </div>
                        </div>

                        <div class="confirmation-input">
                            <div class="mt-3 ml-3">
                                <h4>Jenis pengiriman : </h4>
                                <select class="select-pengiriman" name="pengiriman" id="jenis">
                                <?php foreach($senders as $sender) : ?>
                                    <option value="<?=$sender['id_kat_pengiriman']?>">
                                            <?=$sender['jenis_pengiriman']?>
                                    </option>
                                <?php endforeach;?>
                                </select>
                            </div>

                            <div class="mt-3 ml-3">
                                <h4>Jenis pembayaran : </h4>
                                <select class="select-pembayaran" name="metode" id="jenis">
                                    <?php foreach($payments as $payment) : ?>
                                        <option value="<?=$payment['id_kat_pembayaran']?>">
                                                <?=$payment['jenis_pembayaran']?>
                                        </option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>

                        <div class="confirmation-wrapper-footer mt-3 ml-3">
                            <h4>Detail alamat : </h4>
                            <textarea name="alamat" id="alamat" cols="50" rows="5" 
                                        placeholder="Masukan nama jalan, nomor rumah, dst" 
                                        required></textarea>

                            <h4 class="mt-3" style="text-align: right;">Total harga</h4>
                            <p class="mb-1" style="text-align: right;"> <?=rupiah($details[0]['jumlah']*$details[0]['harga_produk'])?> </p>

                            <input type="hidden" name="id_transaksi" 
                                    value="<?=$details[0]['id_transaksi']?>">

                            <button class="btn-primary" type="submit" name="btn-confirm" id="btn-confirm" > 
                                Confirm
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </section>
    </main>
    <script src="./js/DOMScript.js"></script>
</body>
</html>