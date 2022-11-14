<?php
session_start();
//APAKAH SUDAH LOGIN?
if(!$_SESSION['masuk']){
    header('Location: index.php');
}
require '../include/functions.php';
$username = $_SESSION['nama'];
$id = $_SESSION['id_user'];

$categories = query("SELECT * FROM kat_produk");
$addresses = query("SELECT * FROM alamat LIMIT 5");
$userData = query("SELECT * FROM user WHERE id_user = '$id'");
$passwordHash = $userData [0]['password'];
$id_alamat_user = $userData [0]['id_alamat'];

if(isset($_POST['btn-save'])){
    $old_pass = $_POST['old-password'];
    $new_pass =  $_POST['new-password'];
    $address = $_POST['alamat'];
    $username =  $_POST['user'];
    // CHANGES PROFILE WITH PASSWORD
    if(password_verify($old_pass, $passwordHash) && strlen($new_pass) != 0){
        if(strlen($address) == 0){
            updateProfilePass($_POST);
            $_SESSION['nama'] = $username;
            echo '<script> 
            alert("Data berhasil disimpan");
            document.location.href = "admin.php";
            </script>';
            exit;
        } else {
            updateProfilePassAndAddress($_POST);
            $_SESSION['nama']=$username;
            echo '<script> 
            alert("Data berhasil disimpan");
            document.location.href = "admin.php";
            </script>';
            exit;
        }
        exit;
    }

    // CHANGES PROFILE ONLY
    if(strlen($old_pass) == 0 && strlen($new_pass) == 0) {
        if(strlen($address) == 0) {
            updateProfileOnly($_POST);
            $_SESSION['nama'] = $username;
            echo '<script> 
            alert("Data berhasil disimpan");
            document.location.href = "admin.php";
            </script>';
            exit;
        } else {
            updateProfileWithAddress($_POST);
            $_SESSION['nama'] = $username;
            echo '<script> 
            alert("Data berhasil disimpan");
            document.location.href = "admin.php";
            </script>';
            exit;
        }
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../css/global.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/signup.css">
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

            <div class="setting-wrapper">
                <a class="btn-setting mr-2 btn-cart">
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

    <main id="register-body">
        <h1 class="signup-title">Update you profile!</h1>
        <form action="./adminProfile.php" method="post">
            <input type="hidden" name="id_user" value=<?= $userData [0]['id_user']?>>
            <div class="signup-section signup-section-address mt-3 mb-2">
                <div class="mr-3">
                    <label for="user">Username</label> 
                    <input type="text" name="user" id="user" 
                            placeholder="Masukan username..."
                            value=<?= $userData [0]['username']?> >  
                </div>
            </div>      
            <div class="signup-section">
                <div class="mr-3">
                    <label for="old-password">Old password</label>
                    <input type="password" name="old-password" id="old-password" 
                            placeholder="Masukan password lama..." >
                </div>

                <div>
                    <label for="new-password">New password</label>
                    <input type="password" name="new-password" id="new-password" 
                            placeholder="Masukan password baru..." >
                </div>
            </div>

            <div class="signup-section mt-3">
                <div class="mr-3">
                    <label for="nama_depan">Nama depan </label>
                    <input type="text" name="nama-depan" id="nama-depan" 
                            placeholder="Masukan nama depan anda..." 
                            value=<?= $userData [0]['nama_depan']?>
                            >
                </div>

                <div>
                    <label for="nama_belakang">Nama belakang</label> 
                    <input type="text" name="nama-belakang" id="nama-belakang" 
                            placeholder="Masukan nama belakang anda..." 
                            value=<?= $userData [0]['nama_belakang']?>
                            >
                </div>
            </div>

            <div class="signup-section mt-3">
                <div class="mr-3">
                    <label for="nama_belakang">Jenis kelamin</label> 
                    <select name="jk" id="jk" required>
                        <?php if($userData [0]['jk_user'] == "Laki-laki" ) {?>
                        <option value="Laki-laki" selected>Laki-Laki</option>
                        <option value="Perempuan">Perempuan</option>
                        <?php } else { ?>
                            <option value="Laki-laki">Laki-Laki</option>
                            <option value="Perempuan" selected>Perempuan</option>
                        <?php } ?>
                    </select>
                </div>

                <div>
                    <label for="tgl_lahir">Tanggal lahir</label> 
                    <input type="date" name="tgl-lahir" id="tgl-lahir" 
                            value=<?= $userData [0]['tgl_lhr_user']?>
                            >
                </div>
            </div>

            <div class="signup-section mt-3">
                <div class="mr-3">
                    <label for="email">Email</label> 
                    <input type="email" name="email" id="email" 
                            placeholder="Masukan alamat email..." 
                            value=<?= $userData [0]['email'] ?>
                            >
                </div>

                <div>
                    <label for="tgl_lahir">Nomor telepon</label> 
                    <input type="number" name="no-tlp" id="no-tlp" 
                            placeholder="Masukan nomor telepon..."
                            value=<?= $userData [0]['no_hp_user'] ?>
                            >
                </div>
            </div>

            <div class="signup-section signup-section-address mt-3">
                <div class="mr-3">
                    <label for="tgl_lahir">Cari alamat</label> 
                    <input type="text" name="alamat" id="alamatInput" 
                            placeholder="Cari alamat anda">
                </div>
            </div>
            <div class="mt-3" id="table-address">
                <table>
                    <tr class="table-address-head">
                        <th>Kelurahan</th>
                        <th>Kabupaten</th>
                        <th>Provinsi</th>
                        <th>Pilih</th>
                    </tr>

                    <?php foreach($addresses as $address) : ?>
                    <tr>
                        <td> <?= $address['kelurahan'] ?> </td>
                        <td> <?= $address['kabupaten'] ?> </td>
                        <td> <?= $address['provinsi'] ?> </td>
                        <td>
                            <input type="radio" name="alamat" value="<?=$address['id_alamat']?>"> 
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <div class="btn-daftar-wrapper">
                <button class="btn-primary mt-2 mb-3" id='btn-save' name="btn-save" type="submit">Save</button>
            </div>
        </form>
    </main>

    <script src="../js/scriptAdmin.js"></script>
    <script src="../js/DOMScript.js"></script>
</body>
</html>