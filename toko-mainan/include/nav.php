<?php
require_once 'functions.php';
?>
<!-- HOMEPAGE HEADER -->
<header> 
    <marquee>Mainan Anak - Toko Mainan - Jual Mainan - Alat Peraga Edukatif - Mainan Bayi - Mainan Kayu - Grosir Mainan - Wooden Toys</marquee>
    <nav>
        <h1 class="tokomainan-logo mr-2" 
            onclick="window.location.href='./index.php'"> tokomainan </h1>
        <div class="category-wrapper">
            <a class="btn-category btn-secondary"> Kategori </a>
            <div class="category-overlay">
                <?php foreach($categories as $categorie) : ?>
                    <a class="category-link" 
                        href="index.php?kat=<?= $categorie['id_kat_produk']?>"> 
                        <?= $categorie['jenis_produk'] ?> 
                    </a>
                <?php endforeach;?>
            </div>
        </div>
        <form action="./" method="post">
            <input name="search" id="search-input-product" type="text" 
                placeholder="Cari barang disini" required></input>
            <button class="ml-2" name="btn-search" id="btn-search" type="submit">
                <i class="fa fa-search mr-2"></i>
            </button>
        </form>
        <a class="btn-register btn-primary" href="signup.php"> Register </a>
        <div class="login-wrapper">
            <a class="btn-login btn-secondary bold mr-3"> Login </a>
            <div class="login-overlay">
                <form action="" method="post">
                    <h1 class="login-username mt-1"> Username </h1>
                    <input placeholder="Enter your username..." class="login-input mb-1" type="text" name="username" id="username">
                    <h1 class="login-password"> Password </h1>
                    <input placeholder="Enter your password..." class="login-input mb-1" type="password" name="password" id="password"> <br>
                    <button class="login-submit mb-2" type="submit" name="login" id="login"> Login! </button>
                </form>
            </div>
        </div>

        <!-- MOBILE -->
        <!-- <div class="col"> -->
  
            <!-- searching-->
            <!-- <form action="index.php" method="post"> -->
                <!-- <input name="searchm" id="searchm" type="text" placeholder="search keyword..." required></input> -->
                <!-- <button name="btn-searchm" id="searchm" type="submit"><i class="fa fa-search"></i></button> -->
            <!-- </form> -->
            <!-- <div class="menu-toggle"> -->
                <!-- <input type="checkbox"/> -->
                <!-- <span></span> -->
                <!-- <span></span> -->
                <!-- <span></span> -->
            <!-- </div> -->
        <!-- </div> -->
    </nav>
</footer>

<script src="./js/DOMScript.js"></script>
<script src="./js/script.js"></script>