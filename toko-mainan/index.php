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
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/global.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko mainan</title>
</head>
<body>
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
        </nav>
    </header>

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
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <script src="./js/DOMScript.js"></script>
</body>
</html>