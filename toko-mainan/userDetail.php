<?php
session_start();
//APAKAH SUDAH LOGIN?
if(!$_SESSION['login'] && isset($_GET['p'])) {
    header('Location: index.php');
}
require 'include/functions.php';
$id_user = $_SESSION['id_user'];
// SEMUA KAT_PRODUK YANG ADA
$categories = query("SELECT * FROM kat_produk");

// GABUNGAN PRODUK DENGAN KAT_PRODUK
if(isset($_GET['p'])) {
    $id = $_GET['p'];
    $products = query("SELECT*FROM produk NATURAL JOIN kat_produk WHERE id_produk = '$id'");
}
//SEARCH BERDASARKAN KATEGORI
if(isset($_GET['kat'])){
    $keyword = $_GET['kat'];
    $products = query("SELECT * FROM produk WHERE id_kat_produk='$keyword'");
}
//SEARCH BERDASARKAN NAMA_PRODUK
if(isset($_POST['btn-search'])){
    $keyword = $_POST['search'];
    $products = query("SELECT * FROM produk WHERE nama_produk LIKE '%$keyword%'");
}

//BUTTON BELI PRODUK
if( isset($_POST['btn-beli']) ){
    global $conn;
    if(simpan($_POST) > 0){
        echo '
        <script>
        console.log("Berhasil");
        document.location.href = "keranjang.php";
        </script>
        ';
        exit;
    } else {
        echo mysqli_error($conn);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/global.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/detail.css">
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

    <!-- DETAIL PRODUCT -->
    <main id="detail-body">
        <?php foreach($products as $product) :?>
        <section class="detail-product-pic mt-3">
            <p class="mb-3 ml-3 detail-category">kategori > <?= $product['jenis_produk'] ?></p>
            <img src="./img/<?= $product['gambar_produk'] ?>" alt="<?= $product['gambar_produk'] ?>">
        </section>

        <section class="detail-product-desc">
            <h1 class="detail-product-name"><?= $product['nama_produk'] ?></h1>
            <p><?php echo $product['keterangan_produk'] ?></p>

            <h2 class="detail-harga-title mt-2">Harga Produk </h2> 
            <h3 class="mb-3 detail-product-harga"><?= rupiah($product['harga_produk']) ?></h3>

            <div class="detail-product-info">
                <div>
                    <h2>Stok Produk </h2> 
                    <h3><?= $product['stok'] ?></h3>
                </div>
                <div class="ml-3">
                    <h2>Berat Produk </h2> 
                    <h3><?= $product['berat_produk'].' gram' ?></h3>
                </div>
            </div>
        <?php endforeach;?>
            <form class="mt-3 add-cart-container" action="./userDetail.php" method="post">
                <input type="hidden" name="id_user" value="<?=$id_user?>">
                <input type="hidden" name="id_produk" value="<?=$product['id_produk']?>">
                <div class="input-jumlah-container">
                    <h1>Jumlah</h1>
                    <input class="input-jumlah" type="number" name="jumlah" value="1" autofocus>
                </div>
                <button name="btn-beli" class="btn-buy btn-primary"> Beli </button>
            </form>
        </section>
    </main>

    <script src="./js/DOMScript.js"></script>
</body>
</html>