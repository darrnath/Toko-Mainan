<?php
session_start();
//APAKAH SUDAH LOGIN?
if (!$_SESSION['masuk']) {
    header('Location: ../index.php');
}
require '../include/functions.php';
$username = $_SESSION['nama'];
$id_user = $_SESSION['id_user'];

if(isset($_POST['tombol']) && isset($_POST['transaksi'])){
    $id_transaksi = $_POST['transaksi'];
    $query = "SELECT id_pembayaran,pembayaran.id_transaksi,CONCAT(nama_depan,' ',nama_belakang) AS nama,nama_produk,jumlah,harga_produk,jenis_pembayaran,status_pembayaran FROM transaksi,produk,pembayaran,kat_pembayaran,user WHERE transaksi.id_transaksi = pembayaran.id_transaksi AND transaksi.id_produk=produk.id_produk AND transaksi.id_user = user.id_user AND pembayaran.id_kat_pembayaran = kat_pembayaran.id_kat_pembayaran AND (pembayaran.id_transaksi = '$id_transaksi' OR user.nama_depan LIKE '%$id_transaksi%' OR user.nama_belakang LIKE '%$id_transaksi%') ORDER BY id_pembayaran DESC";
    $details = query($query);
} else {
    $query = "SELECT id_pembayaran,pembayaran.id_transaksi,CONCAT(nama_depan,' ',nama_belakang) AS nama,nama_produk,jumlah,harga_produk,jenis_pembayaran,status_pembayaran FROM transaksi,produk,pembayaran,kat_pembayaran,user WHERE transaksi.id_transaksi = pembayaran.id_transaksi AND transaksi.id_produk=produk.id_produk AND transaksi.id_user = user.id_user AND pembayaran.id_kat_pembayaran = kat_pembayaran.id_kat_pembayaran ORDER BY id_pembayaran DESC";
    $details = query($query);
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
    <link rel="stylesheet" href="../css/adminCheck.css">
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

    <main id="admin-check-wrapper">
        <section id="container-admin">
            <form action="./adminCheck.php" method="post">
                <label class="admin-check-title" for="transaksi">Cek data pemesanan : </label> <br>
                <input type="text" name="transaksi" id="transaksi" placeholder="Masukan no transaksi" required>
                <button class="btn-primary ml-1" type="submit" name="tombol">SEARCH</button>
            </form>

            <table>
                <thead>
                    <th>ID Transaksi</th>
                    <th>Nama Lengkap</th>
                    <th>Nama Produk</th>
                    <th>Total Harga</th>
                    <th>Jenis Pembayaran</th>
                    <th>Status Pembayaran</th>
                </thead>

                <tbody>
                    <?php if(count($details) === 0 && isset($_POST['transaksi'])) { ?>
                        <tr>
                            <td style="text-align: center;" class="empty-data" colspan="6">
                                <h1 class="empty-text">ID transaksi '<?=$_POST['transaksi']?>' tidak ditemukan</h1>
                                <a class="btn-show-empty" href="./adminCheck.php">Lihat semua pemesanan</a>
                            </td>
                        </tr>
                    <?php } else if(count($details) === 0 && !isset($_POST['transaksi'])) { ?>
                        <tr>
                            <td style="text-align: center;" class="empty-data" colspan="6">
                                <h1 class="empty-text">Belum ada pemesanan</h1>
                            </td>
                        </tr>
                    <?php } else { ?>
                        <?php foreach($details as $detail) : ?>
                            <tr class="data-row" onclick="
                                window.location.href = 'adminApprove.php?p=<?= $detail['id_transaksi']?>'">
                                <td class="data-id" ><?= $detail['id_transaksi'] ?></td>
                                <td> <?= $detail['nama'] ?> </td>
                                <td> <?= $detail['nama_produk'] ?> </td>
                                <td style="width: 150px"> <?= rupiah($detail['jumlah']*$detail['harga_produk']) ?> </td>
                                <td> <?= $detail['jenis_pembayaran'] ?> </td>
                                <td class="data-status"> <?= $detail['status_pembayaran'] ?> </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php } ?>
                </tbody>
            </table>                                                                   
        </section>
    </main>
    <script src="../js/DOMScript.js"></script>
</body>
</html>