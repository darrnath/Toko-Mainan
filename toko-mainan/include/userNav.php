<?php
require_once 'functions.php';

//SORT
if(isset($_GET['s'])){
    $sort = $_GET['s'];
    if($sort == 1){
        $products = query("SELECT COUNT(*) AS num, produk.id_produk, id_kat_produk, nama_produk, stok, harga_produk, berat_produk, gambar_produk, status_produk, keterangan_produk, viewcount FROM likes NATURAL JOIN produk WHERE likes.id_produk=produk.id_produk GROUP BY id_produk ORDER BY num DESC");
    } else if($sort == 2){
        $products = query("SELECT COUNT(*) AS num, produk.id_produk, id_kat_produk, nama_produk, stok, harga_produk, berat_produk, gambar_produk, status_produk, keterangan_produk, viewcount FROM likes NATURAL JOIN produk WHERE likes.id_produk=produk.id_produk GROUP BY id_produk ORDER BY num ASC");
    } else if($sort == 3){
        $products = query("SELECT * FROM produk WHERE status_produk = 'Ready' ORDER BY viewcount DESC");
    } else if($sort == 4){
        $products = query("SELECT * FROM produk WHERE status_produk = 'Ready' ORDER BY viewcount ASC");
    } else{
        exit;
    }
}
?>
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
                <a href=<?= filterUrlLink("asc") ?>>Harga Terendah</a></br>
                <a href=<?= filterUrlLink("desc") ?>>Harga Tertinggi</a></br>
                <a href="user.php?s=1">Paling Banyak Disukai</a></br>
                <a href="user.php?s=2">Paling Sedikit Disukai</a></br>
                <a href="user.php?s=3">Paling Banyak Dilihat</a></br>
                <a href="user.php?s=4">Paling Sedikit Dilihat</a></br>
            </div>
        </div>

        <a href="./keranjang.php" class="ml-2 mr-2 btn-cart">
            <i class="fa fa-shopping-cart"></i>
        </a>

        <a href="./wishlist.php" class="mr-2 btn-wish">
            <i class="fa fa-heart"></i>
        </a>

        <a href="./profile.php" class="mr-2 btn-profile">
            <i class="fa fa-user"></i>
        </a>
        <a class="btn-secondary" id="btn-logout" href="./include/logout.php" >Logout</a>
        
        <!-- MOBILE -->
        <!-- <div class="col"> -->
  
            <!-- searching-->
            <!-- <form action="user.php" method="post"> -->
                <!-- <input name="searchm" id="searchm" type="text" required placeholder="search keyword..."> </input> -->
                <!-- <button name="btn-searchm" id="searchm" type="submit"><i class="fa fa-search"></i> </button> -->
            <!-- </form> -->
            <!-- <div class="menu-toggle"> -->
                <!-- <input type="checkbox"/> -->
                <!-- <span></span> -->
                <!-- <span></span> -->
                <!-- <span></span> -->
            <!-- </div> -->
        <!-- </div> -->
    </nav>
</header>