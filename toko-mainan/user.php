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
<link rel="stylesheet" href="./css/user.css">
<?php require_once ("include/head.php");?>
<title>Home - tokomainan</title>

<body>
    <?php require_once ("include/userNav.php");?>

    <main class="mt-3" id="user-homepage">
        <section id="ads-section">
            <img src="./img//ads.jpg">
        </section>
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
                    <div class="product-reaction">
                        <i class="fa fa-eye mr-1" aria-hidden="true"></i> <?= $product['viewcount'] ?>
                        <i class="fa fa-heart mr-1 ml-2" aria-hidden="true"></i> <?= mysqli_fetch_array(mysqli_query($conn,"SELECT COUNT(*) AS num FROM likes NATURAL JOIN produk WHERE likes.id_produk=produk.id_produk AND produk.id_produk = ". $product['id_produk']), MYSQLI_ASSOC)['num'] ?>
                        <i class="<?="fa fa-comments mr-1 ml-2"?>"></i> <?= mysqli_fetch_array(mysqli_query($conn,"SELECT COUNT(*) AS num FROM comments NATURAL JOIN produk WHERE comments.id_produk=produk.id_produk AND produk.id_produk = ". $product['id_produk']), MYSQLI_ASSOC)['num'] ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <script src="./js/DOMScript.js"></script>
</body>
</html>