<?php
session_start();
// LOGIN SESSION VERIFICATION
if(isset($_SESSION['login'])){
    header('Location: user.php');
} else if (isset($_SESSION['masuk'])) {
    header('Location: admin/admin.php');
}

require 'include/functions.php';

// SEARHCING BY PRODUCT NAME FUNC
if(isset($_POST['btn-search'])){
    $keyword = $_POST['search'];
    $products = query("SELECT * FROM produk WHERE nama_produk LIKE '%$keyword%' AND status_produk = 'Ready'");
}

// DISPLAY ALL KATEGORI PRODUK
$categories = query("SELECT * FROM kat_produk");
// DISPLAY ALL PRODUCT BY ID_KAT_PRODUK
if(isset($_GET['kat'])){
    $keyword = $_GET['kat'];
    $products = query("SELECT*FROM produk WHERE id_kat_produk='$keyword' AND status_produk = 'Ready'");
}

// DISPLAY ALL READY STOCK PRODUCT
if(!isset($_GET['kat']) && !isset($_POST['btn-search'])){
    $products = query("SELECT*FROM produk WHERE status_produk = 'Ready' LIMIT 10");
}

//LOGIN SYSTEM
if(isset($_POST['login'])){
    global $conn;
    $username = $_POST['username'];
    $password = $_POST['password'];
    $result = mysqli_query($conn, "SELECT * FROM user WHERE username ='$username'");
    if(mysqli_num_rows($result)==1){
        $user = mysqli_fetch_assoc($result);
        if(password_verify($password,$user['password'])){
          if($user['id_kat_user']==1){
            $_SESSION['nama'] = $user['username'];
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['masuk'] = true;
            echo "
            <script> 
              alert('login berhasil');
              document.location.href = 'admin/admin.php';
            </script>
            ";
          } else {
            $_SESSION['nama'] = $user['username'];
            $_SESSION['id_user'] = $user['id_user'];
            $_SESSION['login'] = true;
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
?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="./css/index.css">
<?php require_once ("include/head.php");?>
<title>Home - tokomainan</title>

<body>
    <?php require_once ("include/nav.php");?>

    <main class="mt-3">
        <!-- ADS SECTION -->
        <section id="ads-section">
            <img src="./img//ads.jpg">
        </section>

        <!-- PRODUCT SLIDER -->
        <section class="mb-3" id="product-section">
            <h1 class="product-title mb-2 mt-3">Our catalog</h1>
            <div class="product-body">
                <?php foreach($products as $product) : ?>
                    <div class="product-box mr-3 mb-3" 
                            onclick="window.location.href='detail.php?p=<?=$product['id_produk'] ?>'">
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