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
$categories = query("SELECT * FROM kat_produk");
$addresses = query("SELECT * FROM alamat LIMIT 5");
if(isset($_POST['btn-daftar'])){
    if(signup($_POST) > 0){
        echo "
        <script> 
            alert('Registrasi berhasil');
            document.location.href = 'index.php';
        </script>";
    } else {
        echo "
        <script> 
            alert('Registrasi gagal');
            document.location.href = 'signup.php';
        </script>";
    }
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
<link rel="stylesheet" href="./css/signup.css">
<?php require_once ("include/head.php");?>
<title>Sign Up - tokomainan</title>

<body>
    <?php require_once ("include/nav.php");?>

    <main id="register-body">
        <h1 class="signup-title">Create a new member!</h1>
        <form action="./signup.php" method="post">
            <div class="signup-section">
                <div class="mr-3">
                    <label for="user">Username</label>
                    <input type="text" name="user" id="user" 
                            placeholder="Masukan username..." required>
                </div>

                <div>
                    <label for="password">Password</label>
                    <input type="password" name="password" id="new-password" 
                            placeholder="Masukan password..." required>
                </div>
            </div>

            <div class="signup-section mt-3">
                <div class="mr-3">
                    <label for="nama_depan">Nama depan </label>
                    <input type="text" name="nama-depan" id="nama-depan" 
                            placeholder="Masukan nama depan anda..." required>
                </div>

                <div>
                    <label for="nama_belakang">Nama belakang</label> 
                    <input type="text" name="nama-belakang" id="nama-belakang" 
                            placeholder="Masukan nama belakang anda..." required>
                </div>
            </div>

            <div class="signup-section mt-3">
                <div class="mr-3">
                    <label for="nama_belakang">Jenis kelamin</label> 
                    <select name="jk" id="jk" required>
                        <option value="Laki-laki">Laki-Laki</option>
                        <option value="Perempuan">Perempuan</option>
                    </select>
                </div>

                <div>
                    <label for="tgl_lahir">Tanggal lahir</label> 
                    <input type="date" name="tgl-lahir" id="tgl-lahir" required>
                </div>
            </div>

            <div class="signup-section mt-3">
                <div class="mr-3">
                    <label for="email">Email</label> 
                    <input type="email" name="email" id="email" 
                            placeholder="Masukan alamat email..." required>
                </div>

                <div>
                    <label for="tgl_lahir">Nomor telepon</label> 
                    <input type="number" name="no-tlp" id="no-tlp" 
                            placeholder="Masukan nomor telepon..." required>
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
                            <input type="radio" name="alamat" value="<?=$address['id_alamat']?>" required> 
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <div class="btn-daftar-wrapper">
                <button class="btn-primary mt-2 mb-3" id='btn-daftar' name="btn-daftar" type="submit">Daftar</button>
            </div>
        </form>
    </main>

    <script src="./js/script.js"></script>
    <script src="./js/DOMScript.js"></script>
</body>
</html>