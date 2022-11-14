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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./css/global.css">
    <link rel="stylesheet" href="./css/header.css">
    <link rel="stylesheet" href="./css/detail.css">
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

    <!-- DETAIL PRODUCT -->
    <main id="detail-body">
        <?php foreach($products as $product) :?>
        <section class="detail-product-pic mt-3">
            <p class="mb-3 ml-3 detail-category">kategori > <?= $product['jenis_produk'] ?></p>
            <img src="./img/<?= $product['gambar_produk'] ?>" alt="<?= $product['gambar_produk'] ?>">
        </section>

        <section class="detail-product-desc">
            <h1 class="detail-product-name"><?= $product['nama_produk'] ?></h1>
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

            <button class="btn-buy btn-primary" 
                    onclick="login_overlay.classList.add('open-login')"> Beli </button>
        </section>
    </main>

    <script src="./js/DOMScript.js"></script>
</body>
</html>