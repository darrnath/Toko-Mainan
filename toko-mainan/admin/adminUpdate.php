<?php
session_start();
//APAKAH SUDAH LOGIN?
if (!$_SESSION['masuk']) {
    header('Location: ../index.php');
}

require '../include/functions.php';
$username = $_SESSION['nama'];
if(isset($_GET['p'])) {
    $id_produk = $_GET['p'];
    $items = query("SELECT * FROM produk WHERE id_produk='$id_produk'");
}

if(isset($_POST['btn-update'])){
    global $conn;
    if(updateProduk($_POST)>0){
        echo "<script> alert('Data berhasil diupdate'); 
        document.location.href = 'admin.php';
        </script>";
        exit;
    } else {
        echo mysqli_error($conn);
    }
}

if(isset($_POST['btn-delete'])){
    if(hapusProduct($_POST)>0){
        echo "<script> alert('Data berhasil dihapus'); 
        document.location.href = 'admin.php';
        </script>";
    } else {
        echo mysqli_error($conn);
    }
}

//SEARCH
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/admin.css">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/header.css">
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

    <main id="update-admin-wrapper">
        <section id="container-admin">
            <h1 class="product-title">Update product</h1>
            <section id="create-product-tools">
            <form class="update-form-data" action="./adminUpdate.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id_produk" value="<?= $items[0]['id_produk']?>"/>
            <input type="hidden" name="status_produk" value="<?= $items[0]['status_produk']?>"/>
                <div class="picture-wrapper mr-3">
                    <img src="../img/<?= $items[0]['gambar_produk'] ?>">
                    <input onchange="loadFile(event)" id="gambar_produk" name="gambar_produk" type="file">
                </div>

                <div class="product-detail-input">
                    <div style="display: flex;">
                        <div class="mr-3">
                            <label for="nama_produk">Nama produk</label> <br>
                            <input value="<?= $items[0]['nama_produk'] ?>" placeholder="Masukan nama produk" id="nama_produk" name="nama_produk" type="text">
                        </div>
                        <div>
                            <label for="harga_produk">Harga produk</label> <br>
                            <input value="<?= $items[0]['harga_produk'] ?>" placeholder="Masukan harga produk" id="harga_produk" name="harga_produk" type="number">
                        </div>
                    </div>

                    <div style="display: flex;">
                        <div class="mr-3">
                            <label for="stok">Stok</label> <br>
                            <input value="<?= $items[0]['stok'] ?>" placeholder="Masukan jumlah stok" id="stok" name="stok" type="number">
                        </div>
                        <div>
                            <label for="kategori_produk">Kategori produk</label> <br>
                            <select name="kategori_produk" id="kategori_produk">
                                <?php foreach($categories as $categorie) : ?>
                                    <?php if($items[0]['id_kat_produk']==$categorie['id_kat_produk']) { ?>
                                    <option value="<?= $categorie['id_kat_produk']?>" selected><?= $categorie['jenis_produk']?></option>
                                        <?php } else { ?>
                                    <option value="<?= $categorie['id_kat_produk']?>"><?= $categorie['jenis_produk']?></option> 
                                    <?php } ?>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <label for="berat_produk">Berat produk</label>
                    <span>
                        <input value=<?= $items[0]['berat_produk'] ?> placeholder="Masukan berat produk" id="berat_produk" name="berat_produk" type="number"> gram
                    </span>
                    <label for="keterangan_produk">Keterangan produk</label>
                    <textarea name="keterangan_produk" id="keterangan_produk" style="resize: none" placeholder="Masukan keterangan dari produk"><?= $items[0]['keterangan_produk'] ?></textarea> 
                    <div>
                        <button onclick="return confirm('Apakah anda ingin mengupdate produk?');" name="btn-update" class="mr-1 btn-primary mt-3" type="submit">Simpan</button>
                        <button class="btn-secondary" onclick="return confirm('Apakah anda ingin mengapus produk?'); " type="submit" name="btn-delete">Hapus</button>
                    </div>
                </div>
            </form>
        </section>
    </main>
    <script src="../js/DOMScript.js"></script>
</body>
</html>