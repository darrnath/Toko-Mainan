<?php
session_start();
//APAKAH SUDAH LOGIN?
if(!$_SESSION['login']){
    header('Location: index.php');
}

require 'include/functions.php';
$username = $_SESSION['nama'];
$categories = query("SELECT * FROM kat_produk");

if(!isset($_GET['kat']) && !isset($_GET['btn-search']) && !isset($_GET['filter'])){
    $products = query("SELECT*FROM produk WHERE status_produk = 'Ready'");
}

if(isset($_GET['filter'])) {
    $filter = $_GET['filter'];
    $products = query("SELECT*FROM produk WHERE status_produk = 'Ready' ORDER BY harga_produk $filter");
}

if(isset($_GET['kat'])){
    $id = $_GET['kat'];
    $products = query("SELECT*FROM produk WHERE id_kat_produk='$id' AND status_produk = 'Ready'");
}

if(isset($_GET['btn-search'])){
    $keyword = $_GET['search'];
    $products = query("SELECT * FROM produk WHERE nama_produk LIKE '%$keyword%' AND status_produk = 'Ready'");
}

if(isset($_GET['kat']) && isset($_GET['filter'])) {
    $order = strtoupper($_GET['filter']);
    $kat = $_GET['kat'];
    $products = query("SELECT*FROM produk WHERE id_kat_produk='$kat' AND status_produk = 'Ready' ORDER BY harga_produk $order");
}

if(isset($_GET['search']) && isset($_GET['filter'])) {
    $keyword = $_GET['search'];
    $order = $_GET['filter'];
    $products = query("SELECT * FROM produk WHERE nama_produk LIKE '%$keyword%' AND status_produk = 'Ready' ORDER BY harga_produk $order");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="./css/global.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/user.css">
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

            <a href="./keranjang.php" class="ml-2 mr-2 btn-cart">
                <i class="fa fa-shopping-cart"></i>
            </a>

            <a href="./profile.php" class="mr-2 btn-profile">
                <i class="fa fa-user"></i>
            </a>
            <a class="btn-secondary" id="btn-logout" href="./include/logout.php" >Logout</a>
        </nav>
    </header>

    <main class="mt-3" id="user-homepage">
        <section class="mb-3" id="product-section">
            <h1 class="product-title mb-1">Our catalog</h1>
            <div class="product-box-container">
                <?php if(count($products) === 0 && isset($_GET['search'])) : ?>
                    <div class="search-empty-container">
                        <h1>Maaf kami belum bisa menemukan '<?=$_GET['search']?>'</h1>
                        <p>Yuk cari mainan lainnya di
                            <b onclick="window.location.href = 'user.php'" 
                            class="tokomainan-logo-empty">tokomainan.</b>
                        </p>
                    </div>
                <?php endif; ?>

                <?php if(count($products) === 0 && !isset($_GET['search'])) : ?>
                    <div class="search-empty-container">
                        <h1>Maaf produk kami belum tersedia</h1>
                        <p>Yuk cari mainan lainnya di
                            <b onclick="window.location.href = 'user.php'" 
                            class="tokomainan-logo-empty">tokomainan.</b>
                        </p>
                    </div>
                <?php endif; ?>
                <?php foreach($products as $product) : ?>
                <div class="product-box mr-3 mb-3" 
                        onclick="window.location.href='userDetail.php?p=<?=$product['id_produk'] ?>'">
                    <div class="product-pic">
                        <img src="img/<?= $product['gambar_produk'] ?>">
                    </div>
                    <div class="product-caption">
                        <h1 class="product-name"><?= formatString($product['nama_produk']) ?></h1>
                        <p class="product-price"><?= rupiah($product['harga_produk']) ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <script src="./js/DOMScript.js"></script>
</body>
</html>