<?php
session_start();
if(!$_SESSION['login']){
    header('Location: index.php');
}

require './include/functions.php';
$username = $_SESSION['nama'];
$id_user = $_SESSION['id_user'];

$categories = query("SELECT * FROM kat_produk");
if(isset($_GET['kat'])){
    $id = $_GET['kat'];
    $products = query("SELECT*FROM produk WHERE id_kat_produk='$id' AND status_produk = 'Ready'");
}

if(isset($_GET['btn-search'])){
    $keyword = $_GET['search'];
    $products = query("SELECT * FROM produk WHERE nama_produk LIKE '%$keyword%' AND status_produk = 'Ready'");
}

$carts = query("SELECT id_transaksi,gambar_produk,nama_produk,harga_produk,jumlah FROM transaksi NATURAL JOIN produk WHERE id_user='$id_user' AND id_transaksi = ANY (SELECT id_transaksi FROM transaksi WHERE id_transaksi NOT IN(SELECT id_transaksi FROM pembayaran)) ORDER BY id_transaksi DESC");

if(isset($_POST['btn-cancel'])){
    if(hapus($_POST) > 0) {
        echo "<script> 
        document.location.href = 'keranjang.php';
        </script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="./css/global.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/keranjang.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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

    <main id="carts-homepage">
        <div class="carts-title-container">
            <i class="fa fa-shopping-cart"></i>
            <h1>Keranjang belanja</h1>
        </div>
        <section id="carts-section">
            <?php if(count($carts) === 0) : ?>
                <div class="empty-container">
                    <h1>Keranjang anda masih kosong.</h1>
                    <p>Yuk belanja di 
                        <b onclick="window.location.href = 'user.php'" 
                        class="tokomainan-logo-empty">tokomainan.</b>
                    </p>
                </div>
            <?php endif; ?>

            <?php foreach($carts as $cart) : ?>
                <div class="carts-box-container mb-3">
                    <div class="carts-img mr-3"?>
                        <img src="./img/<?=$cart['gambar_produk']?>" alt="<?=$cart['gambar_produk']?>">
                    </div>

                    <div style="display: flex; 
                                flex-direction: column; 
                                justify-content: space-between;
                                padding: 0.75rem 0.25rem;">
                        <div style="display: flex; width:fit-content">
                            <div class="carts-content carts-name mt-1 mr-3">
                                <h1>Nama barang</h1>
                                <h3><?= $cart['nama_produk'] ?></h3>
                            </div>
    
                            <div class="carts-content carts-price mt-1 mr-3">
                                <h1>Harga</h1>
                                <h3><?= rupiah($cart['jumlah']*$cart['harga_produk']) ?></h3>
                            </div>
    
                            <div class="carts-content carts-total mt-1 mr-3">
                                <h1>Jumlah</h1>
                                <h3 class="price"><?= $cart['jumlah'] ?></h3>
                            </div> 
                        </div>

                        <div style="display: flex;" class="tool">
                            <form action="konfirmasi.php" method="post">
                                <input type="hidden" name="id_transaksi" value="<?= $cart['id_transaksi'] ?>">
                                <button class="btn-pembayaran btn-primary" name="btn-pembayaran" type="submit">PEMBAYARAN</button>
                            </form>
                            
                            <form action="" method="post">
                                <input type="hidden" name="id_transaksi" value="<?= $cart['id_transaksi'] ?>">
                                <button class="btn-cancel btn-secondary" name="btn-cancel" type="submit">CANCEL</button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach;?>
        </section>
    </main>

    <script src="./js/DOMScript.js"></script>
</body>
</html>