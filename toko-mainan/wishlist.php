<?php
session_start();
if(!$_SESSION['login']){
    header('Location: index.php');
}

require './include/functions.php';
$username = $_SESSION['nama'];
$id_user = $_SESSION['id_user'];

$categories = query("SELECT * FROM kat_produk");
$wishlist = query("SELECT * FROM likes NATURAL JOIN produk WHERE id_user='$id_user' ORDER BY id_like DESC");
if(isset($_GET['kat'])){
    $id = $_GET['kat'];
    $products = query("SELECT*FROM produk WHERE id_kat_produk='$id' AND status_produk = 'Ready'");
}

if(isset($_GET['btn-search'])){
    $keyword = $_GET['search'];
    $products = query("SELECT * FROM produk WHERE nama_produk LIKE '%$keyword%' AND status_produk = 'Ready'");
}
?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="./css/wishlist.css">
<?php require_once ("include/head.php");?>
<title>Wishlist - tokomainan</title>

<body>
    <?php require_once ("include/userNav2.php");?>

    <main id="carts-homepage">
        <div class="carts-title-container">
            <i class="fa fa-heart"></i>
            <h1>Wishlist</h1>
        </div>
        <section id="carts-section">
            <?php if(count($wishlist) === 0) : ?>
                <div class="empty-container">
                    <h1>Wishlist anda masih kosong.</h1>
                    <p>Yuk belanja di 
                        <b onclick="window.location.href = 'user.php'" 
                        class="tokomainan-logo-empty">tokomainan.</b>
                    </p>
                </div>
            <?php endif; ?>

            <?php foreach($wishlist as $wishes) : ?>
                <a href="userDetail.php?p=<?=$wishes['id_produk'] ?>">
                    <div class="carts-box-container mb-3">
                        <div class="carts-img mr-3">
                            <img src="./img/<?=$wishes['gambar_produk']?>" alt="<?=$wishes['gambar_produk']?>">
                        </div>

                        <div class="product-description">
                            <div class="product-info">
                                <div class="carts-content carts-name mt-1 mr-3">
                                    <h1>Nama barang</h1>
                                    <h3><?= $wishes['nama_produk'] ?></h3>
                                </div>

                                <div class="carts-content carts-price mt-1 mr-3">
                                    <h1>Harga</h1>
                                    <h3><?= rupiah($wishes['harga_produk']) ?></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach;?>
        </section>
    </main>

    <script src="./js/DOMScript.js"></script>
</body>
</html>