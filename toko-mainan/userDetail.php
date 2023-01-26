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

//VIEWS
$views = mysqli_fetch_array(mysqli_query($conn,"SELECT viewcount FROM produk WHERE id_produk = $id"), MYSQLI_ASSOC);
$count = $views['viewcount'];
$count = $count + 1;
$updateviews = mysqli_query($conn,"UPDATE produk SET viewcount = $count WHERE id_produk = $id");

//LIKES
$totallikes = mysqli_fetch_array(mysqli_query($conn,"SELECT COUNT(*) AS num FROM likes NATURAL JOIN produk WHERE likes.id_produk=produk.id_produk AND produk.id_produk = $id"), MYSQLI_ASSOC);
$liked = mysqli_fetch_array(mysqli_query($conn,"SELECT COUNT(*) AS num FROM likes NATURAL JOIN produk NATURAL JOIN user WHERE likes.id_produk=produk.id_produk AND likes.id_user=user.id_user AND produk.id_produk = $id AND user.id_user = $id_user"), MYSQLI_ASSOC);
if($liked['num']>0){
    $icon = 'fa fa-heart mr-1';
}else{
    $icon = 'fa fa-heart-o mr-1';
}
if(isset($_POST['btn-like']) ){
    global $conn;
    if($liked['num']<1){
        if(createlike($_POST) > 0){
            echo '
            <script>
            history.go(-1);
            history.go(1);
            </script>
            ';
            exit;
        } else {
            echo mysqli_error($conn);
            exit;
        }
    }else{
        if(deletelike($_POST) > 0){
            echo '
            <script>
            history.go(-1);
            history.go(1);
            </script>
            ';
            exit;
        } else {
            echo mysqli_error($conn);
            exit;
        }
    }
}

//COMMENTS
$totalcomments = mysqli_fetch_array(mysqli_query($conn,"SELECT COUNT(*) AS num FROM comments NATURAL JOIN produk WHERE comments.id_produk=produk.id_produk AND produk.id_produk = $id"), MYSQLI_ASSOC);
$comments = query("SELECT * FROM comments NATURAL JOIN user WHERE id_produk = $id");
if(isset($_POST['btn-send']) ){
    global $conn;
    if(createcomment($_POST) > 0){
        echo '
        <script>
        history.go(-1);
        history.go(1);
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
<link rel="stylesheet" href="./css/detail.css">
<?php require_once ("include/head.php");?>
<title>Detail - tokomainan</title>

<body>
    <?php require_once ("include/userNav.php");?>
        
    <!-- DETAIL PRODUCT -->
    <main id="detail-body">
        <?php foreach($products as $product) :?>
        <section class="detail-product-pic mt-3">
            <p class="mb-3 ml-3 detail-category">Kategori > <?= $product['jenis_produk'] ?></p>
            <img src="./img/<?= $product['gambar_produk'] ?>" alt="<?= $product['gambar_produk'] ?>">
        </section>

        <section class="detail-product-desc">
            <h1 class="detail-product-name"><?= $product['nama_produk'] ?></h1>
            <form class="like" action="" method="post">
                <input type="hidden" name="id_user" value="<?=$id_user;?>">
                <input type="hidden" name="id_produk" value="<?=$product['id_produk']?>">
                <button name="btn-like" class="btn-like">
                    <i class="<?= $icon ?>"></i>
                </button>
                <?php echo $totallikes['num'] ?>
                <i class="<?="fa fa-comments mr-1 ml-2"?>"></i> <?php echo $totalcomments['num'] ?>
            </form>
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
            </br></br></br>
            <h1>Comments</h1>
            <?php foreach($comments as $comment) :?>
                <div class="comment-list">
                    <h2><?php echo $comment['nama_depan'];?> <?php echo $comment['nama_belakang'];?></h2>
                    <h3><?php echo $comment['comment'];?></h3>
                    <p><?php echo $comment['created_at'];?></p>
                </div>
            </br>
            <?php endforeach;?>
            <form id="comment" action="" method="post">
                <input type="hidden" name="id_user" value="<?=$id_user;?>">
                <input type="hidden" name="id_produk" value="<?=$product['id_produk']?>">
                <input type="text" name="comment" class="comment" placeholder="Comment here" required>
                <button name="btn-send" class="btn-send btn-primary">SEND</button>
            </form>
            </br></br></br>
        </section>
    </main>

    <script src="./js/DOMScript.js"></script>
</body>
</html>