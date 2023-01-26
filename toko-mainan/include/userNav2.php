<?php
require_once 'functions.php';
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

        <a href="./pemesanan.php" class="btn-history">
            <i class="fa fa-print"></i>
        </a>

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