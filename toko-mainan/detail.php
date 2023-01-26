<?php
session_start();
//APAKAH SUDAH LOGIN?
if(isset($_SESSION['login'])){
    header('Location: user.php');
} 
else if (isset($_SESSION['masuk'])) {
    header('Location: admin/admin.php');
}
require 'include/functions.php';
// SEMUA KAT_PRODUK YANG ADA
$categories = query("SELECT * FROM kat_produk");
// GABUNGAN PRODUK DENGAN KAT_PRODUK
$id = $_GET['p'];
$products = query("SELECT*FROM produk NATURAL JOIN kat_produk WHERE id_produk = '$id'");
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
//Login system
if(isset($_POST['login'])){
    global $conn;
    $username = $_POST['username'];
    $password = $_POST['password'];
    $result = mysqli_query($conn, "SELECT * FROM user WHERE username ='$username'");
    if(mysqli_num_rows($result)==1){
        $user = mysqli_fetch_assoc($result);
        if(password_verify($password,$user['password'])){
          if($user['id_kat_user']==1){
            $_SESSION['masuk'] = true;
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['nama'] = $user['username'];
            echo "
            <script> 
              alert('login berhasil');
              document.location.href = 'admin/admin.php';
            </script>
            ";
          } else {
            $_SESSION['nama'] = $user['username'];
            $_SESSION['login'] = true;
            $_SESSION['id_user'] = $user['id_user'];
            echo "
            <script> 
              alert('login berhasil');
              document.location.href = './user.php';
            </script>
            ";
          }
        } else {
          echo "<script> alert('Username/Password salah')</script>";
        }
    } else {
      echo "<script> alert('Username/Password salah')</script>";
    }
}

//VIEWS
$views = mysqli_fetch_array(mysqli_query($conn,"SELECT viewcount FROM produk WHERE id_produk = $id"), MYSQLI_ASSOC);
$count = $views['viewcount'];
$count = $count + 1;
$updateviews = mysqli_query($conn,"UPDATE produk SET viewcount = $count WHERE id_produk = $id");

//LIKES
$totallikes = mysqli_fetch_array(mysqli_query($conn,"SELECT COUNT(*) AS num FROM likes NATURAL JOIN produk WHERE likes.id_produk=produk.id_produk AND produk.id_produk = $id"), MYSQLI_ASSOC);

//COMMENTS
$totalcomments = mysqli_fetch_array(mysqli_query($conn,"SELECT COUNT(*) AS num FROM comments NATURAL JOIN produk WHERE comments.id_produk=produk.id_produk AND produk.id_produk = $id"), MYSQLI_ASSOC);
$comments = query("SELECT * FROM comments NATURAL JOIN user WHERE id_produk = $id");
?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="./css/detail.css">
<?php require_once ("include/head.php");?>
<title>Detail - tokomainan</title>

<body>
    <?php require_once ("include/nav.php");?>

    <!-- DETAIL PRODUCT -->
    <main id="detail-body">
        <?php foreach($products as $product) :?>
        <section class="detail-product-pic mt-3">
            <p class="mb-3 ml-3 detail-category">Kategori > <?= $product['jenis_produk'] ?></p>
            <img src="./img/<?= $product['gambar_produk'] ?>" alt="<?= $product['gambar_produk'] ?>">
        </section>
        <section class="detail-product-desc">
            <h1 class="detail-product-name"><?= $product['nama_produk'] ?></h1>
            <div>
                <i id="like" class="fa fa-heart-o mr-1" onclick="login_overlay.classList.add('open-login')"></i> <?php echo $totallikes['num'] ?>
                <i class="<?="fa fa-comments mr-1 ml-2"?>"></i> <?php echo $totalcomments['num'] ?>
            </div>
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
            <button class="btn-buy btn-primary" onclick="login_overlay.classList.add('open-login')"> Beli </button>
            
            </br></br></br>
            <h1>Comments</h1>
            <?php foreach($comments as $comment) :?>
                <div class="comment-list">
                    <h2><?php echo $comment['nama_depan'];?><?php echo $comment['nama_belakang'];?></h2>
                    <h3><?php echo $comment['comment'];?></h3>
                    <p><?php echo $comment['created_at'];?></p>
                </div>
            </br>
            <?php endforeach;?>
            <div>
                <input type="text" name="comment" class="comment" placeholder="Comment here" required>
                <button onclick="login_overlay.classList.add('open-login')" name="btn-send" class="btn-send btn-primary">SEND</button>
            </div>
            </br></br></br>
        </section>
    </main>

    <script src="./js/DOMScript.js"></script>
</body>
</html>