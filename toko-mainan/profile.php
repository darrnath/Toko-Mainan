<?php
session_start();
//APAKAH SUDAH LOGIN?
if (!$_SESSION['login']) {
    header('Location: ../index.php');
}
require 'include/functions.php';
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
            document.location.href = "user.php";
            </script>';
            exit;
        } else {
            updateProfilePassAndAddress($_POST);
            $_SESSION['nama']=$username;
            echo '<script> 
            alert("Data berhasil disimpan");
            document.location.href = "user.php";
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
            document.location.href = "user.php";
            </script>';
            exit;
        } else {
            updateProfileWithAddress($_POST);
            $_SESSION['nama'] = $username;
            echo '<script> 
            alert("Data berhasil disimpan");
            document.location.href = "user.php";
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
<link rel="stylesheet" href="./css/signup.css">
<?php require_once ("include/head.php");?>
<title>Profile - tokomainan</title>

</head>
<body>
    <?php require_once ("include/userNav2.php");?>

    <main id="register-body">
        <h1 class="signup-title">Update you profile!</h1>
        <form action="./profile.php" method="post">
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

    <script src="./js/script.js"></script>
    <script src="./js/DOMScript.js"></script>
</body>
</html>