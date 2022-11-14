<?php
session_start();
//APAKAH SUDAH LOGIN?
if(!$_SESSION['login']){
    header('Location: index.php');
}

if(!isset($_POST['id_transaksi'])){
    header('Location: pemesanan.php');
}

require 'include/functions.php';

$tanggal_sekarang = date('Y-m-d');
$username = $_SESSION['nama'];
$id = $_SESSION['id_user'];
$id_transaksi = $_POST['id_transaksi'];
$categories = query("SELECT * FROM kat_produk");

if(isset($_GET['btn-search'])){
    $keyword = $_GET['search'];
    $products = query("SELECT * FROM produk WHERE nama_produk LIKE '%$keyword%' AND status_produk = 'Ready'");
}

$userData = query("SELECT CONCAT(nama_depan,' ',nama_belakang) AS nama,nama_produk,jenis_pembayaran,jumlah,pembayaran.id_transaksi,harga_produk FROM user,produk,kat_pembayaran,transaksi,pembayaran WHERE transaksi.id_user=user.id_user AND transaksi.id_produk = produk.id_produk AND kat_pembayaran.id_kat_pembayaran = pembayaran.id_kat_pembayaran AND pembayaran.id_transaksi = transaksi.id_transaksi AND pembayaran.id_transaksi = '$id_transaksi'");

if(isset($_POST["btn-confirm"])){
    global $conn;
    if(updatePembayaran($_POST) > 0){
        echo '<script> 
        alert("Terima kasih atas pembayaran, admin kami akan segera memproses");
        document.location.href = "pemesanan.php";
        </script>';
    } else {
        echo mysqli_error($conn);
    }
}

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

    <main id="pembayaran-homepage-main">
        <section id="payment-form-wrapper">
            <h1 class="pembayaran-homepage-title">Konfirmasi pembayaran</h1>
            <form class="mt-3 pembayaran-form" action="./pembayaran.php" method="post" enctype="multipart/form-data">
                <div class="pembayaran-form-desc">
                    <div>
                        <div class="mb-3">
                            <input type="hidden" name="id" value="<?=$id_transaksi?>">
                            <label for="id">ID transaksi</label> <br>
                            <p><?=$id_transaksi?></p>
                        </div>

                        <div class="mb-3">
                        <input type="hidden" name="nama_user" value="<?=$userData[0]['nama']?>">
                            <label for="nama_user">Nama pembeli</label> <br>
                            <p><?= $userData[0]['nama'] ?></p>
                        </div>

                        <div class="mb-3">
                            <input type="hidden" name="metode" value="<?=$userData[0]['jenis_pembayaran']?>">
                            <label for="metode">Jenis pembayaran : </label> <br>
                            <p><?= $userData[0]['jenis_pembayaran'] ?></p>
                        </div>
                    </div>

                    <div class="ml-3">
                        <div class="mb-2">
                            <label for="tgl_pembayaran">Tanggal pembayaran</label> <br>
                            <input type="hidden" name="tgl_pembayaran" value="<?= $tanggal_sekarang ?>">
                            <p><?= $tanggal_sekarang ?></p>
                        </div>
                        
                        <div class="mb-3">
                            <label for="nama_produk">Nama produk</label> <br>
                            <input type="hidden" name="nama_produk" value="<?= $userData[0]['nama_produk'] ?>">
                            <p><?= $userData[0]['nama_produk'] ?></p>
                        </div>

                        <div class="mb-3">
                            <label for="harga">Total yang harus di bayar : </label> <br>
                            <input type="hidden" name="harga" value="<?= rupiah($userData[0]['jumlah']*$userData[0]['harga_produk']) ?>">
                            <p class="total-harga"><?= rupiah($userData[0]['jumlah']*$userData[0]['harga_produk']) ?></p>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="kategori_produk">Upload bukti pembayaran disini : </label> <br>
                    <div class="picture-wrapper mt-1">
                        <img src="./img/camera placeholder.png">
                        <input onchange="loadFile(event)" id="bukti" name="bukti" type="file">
                    </div>
                </div>

                <button class="mt-3 btn-primary" type="submit" name="btn-confirm" id="btn-confirm" type="submit">KIRIM</button>
            </form>
        </section>

        <section id="tutorial-payment-wrapper">
            <?php if($userData[0]['jenis_pembayaran'] === "TRANSFER") : ?>
                <h1 class="mb-3 mt-1 pembayaran-homepage-title">Cara pembayaran</h1>
                <p class="mb-1">Lakukan pembayaran : </p>
                <p>Bank Central Asia</p>
                <p>a.n Darryl Nathanael</p>
                <p class="mt-2">Nomor rekening : </p>
                <p>0808080000</p>
                <img class="mt-3 BCA-QR-image" src='https://www.unitag.io/qreator/generate?crs=Ppv8rOENN3V1lAwTz82zPpEYgrTpeQPpAxSJGcmyf1yS40m%252F8TYex%252BClEuWu4lenvXZtoPs%252F%252BUrLXgu0YhszNgP%252BKdjyjPlmstXQT%252FaVrtPKhQyftmIdGYv13ikDwALrMbZP22mR79KHkzbFuKXEpiL8j20cuH2aGWOj2IjvLpcUzuo31AnGGGBeZdrGyuu6Mb1zDGpyywrS%252B5yeqbhCDpmv2l%252BiKgxTlFLykvA%252BRNyb1Ckz3oh0SMWmsC7t%252F6sPLUjUwDO6FHYFR6p6LaWzmvzQw2O1HVuk4zF5jcbpHGIg2Ti8MoQUfXI9oPDD35rcRXIqy%252FL6IHrfX5bQ3NpZzA%253D%253D&crd=fhOysE0g3Bah%252BuqXA7NPQ87MoHrnzb%252BauJLKoOEbJsrrrhw6qRw4y4KisrBqnziKULpY69r4q3FbEwwxdRwOaA%253D%253D' alt='QR Code'/>
            <?php endif; ?>

            <?php if($userData[0]['jenis_pembayaran'] === "OVO") : ?>
                <h1 class="mb-3 mt-1 pembayaran-homepage-title">Cara pembayaran</h1>
                <p class="mb-1">Lakukan pembayaran : </p>
                <p>OVO</p>
                <p>a.n Darryl Nathanael</p>
                <p class="mt-2">Nomor handphone : </p>
                <p>082108080808</p>
                <img class="mt-3 BCA-QR-image" src="./img/OVO.jpg">
            <?php endif; ?>
        </section>
    </main>
<script src="./js/DOMScript.js"></script>
</body>
</html>