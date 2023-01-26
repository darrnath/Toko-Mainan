<?php
require 'connection.php';
//SELECT AND FETCH DATA
function query ($sql){
    global $conn;
    $result=mysqli_query($conn,$sql);
    $output=[];
    while($data = mysqli_fetch_assoc($result)){
        $output[]=$data;
    }
    return $output;
}

//COMMENTS
function createcomment($data){
    global $conn;
    $id_user = $data['id_user'];
    $id_produk = $data['id_produk'];
    $comment = $data['comment'];
    $date = date('Y-m-d');
    $query = "INSERT INTO comments(id_user, id_produk, comment, created_at) VALUES ('$id_user', '$id_produk', '$comment', '$date')";
    $result = mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}

//LIKE
function createlike($data){
    global $conn;
    $id_user = $data['id_user'];
    $id_produk = $data['id_produk'];
    $date = date('Y-m-d');
    $query = "INSERT INTO likes(id_user, id_produk, created_at) VALUES ('$id_user', '$id_produk', '$date')";
    $result = mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}

//UNLIKE
function deletelike($data){
    global $conn;
    $id_user = $data['id_user'];
    $id_produk = $data['id_produk'];
    $query = "DELETE FROM likes WHERE id_produk = $id_produk AND id_user = $id_user";
    $result = mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}

// UPDATE DATA
function updateProfilePass($data){
    global $conn;
    $username = htmlspecialchars(strtolower(stripslashes($data['user'])));
    $password = mysqli_real_escape_string($conn,$data['new-password']);
    $password= password_hash($password,PASSWORD_DEFAULT);
    $email = htmlspecialchars($data['email']);
    $nama_depan= htmlspecialchars($data['nama-depan']);
    $nama_belakang= htmlspecialchars($data['nama-belakang']);
    $jenis_kelamin= $data['jk'];
    $no_tlp= htmlspecialchars($data['no-tlp']);
    $tanggal_lhr = $data['tgl-lahir'];
    $id_user = $data['id_user'];
    $query = "UPDATE user SET username = '$username', password = '$password' , email = '$email', nama_depan = '$nama_depan', nama_belakang = '$nama_belakang', jk_user = '$jenis_kelamin', no_hp_user = '$no_tlp' , tgl_lhr_user = STR_TO_DATE('$tanggal_lhr','%Y-%m-%d') WHERE id_user = '$id_user'";
    $result = mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}

function updateProfilePassAndAddress($data){
    global $conn;
    $alamat = $data['alamat'];
    $username = htmlspecialchars(strtolower(stripslashes($data['user'])));
    $password = mysqli_real_escape_string($conn,$data['new-password']);
    $password= password_hash($password,PASSWORD_DEFAULT);
    $email = htmlspecialchars($data['email']);
    $nama_depan= htmlspecialchars($data['nama-depan']);
    $nama_belakang= htmlspecialchars($data['nama-belakang']);
    $jenis_kelamin= $data['jk'];
    $no_tlp= htmlspecialchars($data['no-tlp']);
    $tanggal_lhr = $data['tgl-lahir'];
    $id_user = $data['id_user'];
    $query = "UPDATE user SET username = '$username', password = '$password' , email = '$email', nama_depan = '$nama_depan', nama_belakang = '$nama_belakang', jk_user = '$jenis_kelamin', no_hp_user = '$no_tlp' , tgl_lhr_user = STR_TO_DATE('$tanggal_lhr','%Y-%m-%d'), id_alamat = '$alamat' WHERE id_user = '$id_user'";
    $result = mysqli_query($conn, $query);
    return mysqli_affected_rows($conn);
}

function updateProfileWithAddress($data){
    global $conn;
    $alamat1 = $data['alamat'];
    $username1 = htmlspecialchars(strtolower(stripslashes($data['user'])));
    $email1 = htmlspecialchars($data['email']);
    $nama_depan1= htmlspecialchars($data['nama-depan']);
    $nama_belakang1= htmlspecialchars($data['nama-belakang']);
    $jenis_kelamin1= $data['jk'];
    $no_tlp1= htmlspecialchars($data['no-tlp']);
    $tanggal_lhr1 = $data['tgl-lahir'];
    $id_user1 = $data['id_user'];
    $query1 = "UPDATE user SET username = '$username1', email = '$email1', nama_depan = '$nama_depan1', nama_belakang = '$nama_belakang1', jk_user = '$jenis_kelamin1', no_hp_user = '$no_tlp1', tgl_lhr_user = STR_TO_DATE('$tanggal_lhr1','%Y-%m-%d'), id_alamat = '$alamat1' WHERE id_user = '$id_user1'";
    $result = mysqli_query($conn, $query1);
    return mysqli_affected_rows($conn);
}

function updateProfileOnly($data){
    global $conn;
    $username1 = htmlspecialchars(strtolower(stripslashes($data['user'])));
    $email1 = htmlspecialchars($data['email']);
    $nama_depan1= htmlspecialchars($data['nama-depan']);
    $nama_belakang1= htmlspecialchars($data['nama-belakang']);
    $jenis_kelamin1= $data['jk'];
    $no_tlp1= htmlspecialchars($data['no-tlp']);
    $tanggal_lhr1 = $data['tgl-lahir'];
    $id_user1 = $data['id_user'];
    $query1 = "UPDATE user SET username = '$username1', email = '$email1', nama_depan = '$nama_depan1', nama_belakang = '$nama_belakang1', jk_user = '$jenis_kelamin1', no_hp_user = '$no_tlp1', tgl_lhr_user = STR_TO_DATE('$tanggal_lhr1','%Y-%m-%d') WHERE id_user = '$id_user1'";
    $result = mysqli_query($conn, $query1);
    return mysqli_affected_rows($conn);
}

function updateProduk($data){
    global $conn;
    $nama_produk = $data['nama_produk'];
    $id_produk = $data['id_produk'];
    $stok = $data['stok'];
    $harga_produk = $data['harga_produk'];
    $kategori = $data['kategori_produk'];
    $status = $data['status_produk'];
    $berat_produk=$data['berat_produk'];
    $gambar_produk = uploadGambarProduk();
    if($gambar_produk === 0){
        $keterangan_produk = $data['keterangan_produk'];
        $query = "UPDATE produk SET nama_produk = '$nama_produk' , stok='$stok', harga_produk='$harga_produk', id_kat_produk='$kategori',status_produk = '$status',berat_produk='$berat_produk', keterangan_produk = '$keterangan_produk' WHERE id_produk='$id_produk'";
        mysqli_query($conn,$query);
    } 
    if($gambar_produk !== 0) {
        $keterangan_produk = $data['keterangan_produk'];
        $query = "UPDATE produk SET nama_produk = '$nama_produk' , stok='$stok', harga_produk='$harga_produk', id_kat_produk='$kategori',status_produk = '$status',berat_produk='$berat_produk',gambar_produk='$gambar_produk', keterangan_produk = '$keterangan_produk' WHERE id_produk='$id_produk'";
        mysqli_query($conn,$query);
    }
    return mysqli_affected_rows($conn);
}

function uploadGambarProduk(){
    $namaGambar = $_FILES['gambar_produk']['name'];
    $ukuranGambar = $_FILES['gambar_produk']['size'];
    $error = $_FILES['gambar_produk']['error'];
    $pathGambar = $_FILES['gambar_produk']['tmp_name'];

    //FILE SUDAH DIMASUKAN ATAU BELUM
    if($error === 4){
        return 0;
    }

    //CEK EKTERNSI FILE
    $formatGambar=['jpg','jpeg','png'];
    $formatBukti=explode('.',$namaGambar);
    $formatBukti = strtolower(end($formatBukti));
    if(!in_array($formatBukti,$formatGambar)){
        echo '<script>
        alert("Yang anda upload bukan gambar");
        </script>';
        return 0;
    }

    //KALAU FILE BESAR BANGET
    $ukuranFileByte = 30000000; //30MB
    if($ukuranGambar > $ukuranFileByte){
        echo '<script>
        alert("Ukuran file terlalu besar");
        </script>';
        return false;
    }
    move_uploaded_file($pathGambar,'../img/'.$namaGambar);
    return $namaGambar;
}

//HAPUS DATA
function hapus($data){
    global $conn;
    $id_transaksi=htmlspecialchars($data['id_transaksi']);
    $query="DELETE FROM transaksi WHERE id_transaksi = $id_transaksi";
    mysqli_query($conn,$query);
    return mysqli_affected_rows($conn);
}
function hapusProduct($data){
    global $conn;
    $id_produk=htmlspecialchars($data['id_produk']);
    $query="DELETE FROM produk WHERE id_produk = $id_produk";
    mysqli_query($conn,$query);
    return mysqli_affected_rows($conn);
}
//UPDATE AUTO UPDATE STOK
function updateStok($data){
global $conn;
$stok = $data['stok'];
$jumlah = $data['jumlah'];
$stokBaru = $stok-$jumlah;
if($stokBaru <= 0){
    return false;
} else {
    $query = "UPDATE produk SET stok = '$stokBaru'";
    mysqli_query($conn,$query);
    return mysqli_affected_rows($conn);
}
}

//INPUTAN DATA KE PRODUK
function input($data){
global $conn;
$kategori = $data['kategori_produk'];
$nama_produk = $data['nama_produk'];
$stok = $data['stok'];
$harga_produk = $data['harga_produk'];
$berat_produk = $data['berat_produk'];
$gambar_produk = uploadGambar();
if(!$gambar_produk){
    return false;
}
$keterangan_produk = $data['keterangan_produk'];
$query = "INSERT INTO produk VALUES('','$kategori','$nama_produk','$stok','$harga_produk','$berat_produk','$gambar_produk','Ready','$keterangan_produk', 0)";
$result = mysqli_query($conn,$query);
return mysqli_affected_rows($conn);
}

function uploadGambar(){
    $namaGambar = $_FILES['gambar_produk']['name'];
    $ukuranGambar = $_FILES['gambar_produk']['size'];
    $error = $_FILES['gambar_produk']['error'];
    $pathGambar = $_FILES['gambar_produk']['tmp_name'];

    //FILE EXSIST
    if($error === 4){
        echo '<script>
        alert("Masukan gambar terlebih dahulu");
        </script>';
        return false;
    }

    //CEK EKTERNSI FILE
    $formatGambar=['jpg','jpeg','png'];
    $formatBukti=explode('.',$namaGambar);
    $formatBukti = strtolower(end($formatBukti));
    if(!in_array($formatBukti,$formatGambar)){
        echo '<script>
        alert("Yang anda upload bukan gambar");
        </script>';
        return false;
    }

    //KALAU FILE SIZE
    $ukuranFileByte = 30000000; //30MB
    if($ukuranGambar > $ukuranFileByte){
        echo '<script>
        alert("Ukuran file terlalu besar");
        </script>';
        return false;
    }
    move_uploaded_file($pathGambar,'../img/'.$namaGambar);
    return $namaGambar;
}

//UPDATE PEMBAYARAN
function updatePembayaran($data){
global $conn;
$id_transaksi = $data['id'];
$tanggal_pembayaran = $data['tgl_pembayaran'];
$bukti = upload();
if(!$bukti){
    return false;
}
var_dump($bukti);
$query = "UPDATE pembayaran SET tgl_pembayaran = '$tanggal_pembayaran', bukti_pembayaran = '$bukti', status_pembayaran = 'Checking' WHERE id_transaksi = '$id_transaksi'";
mysqli_query($conn,$query);
return mysqli_affected_rows($conn);
}

function upload(){
    $namaGambar = $_FILES['bukti']['name'];
    $ukuranGambar = $_FILES['bukti']['size'];
    $error = $_FILES['bukti']['error'];
    $pathGambar = $_FILES['bukti']['tmp_name'];

    //FILE SUDAH DIMASUKAN ATAU BELUM
    if($error === 4){
        echo '<script>
        alert("Masukan bukti pembayaran");
        </script>';
        return false;
    }

    //CEK EKTERNSI FILE
    $formatGambar=['jpg','jpeg','png'];
    $formatBukti=explode('.',$namaGambar);
    $formatBukti = strtolower(end($formatBukti));
    if(!in_array($formatBukti,$formatGambar)){
        echo '<script>
        alert("Yang anda upload bukan gambar");
        </script>';
        return false;
    }

    //KALAU FILE BESAR BANGET
    $ukuranFileByte = 1000000; //1MB
    if($ukuranGambar > $ukuranFileByte){
        echo '<script>
        alert("Ukuran file terlalu besar");
        </script>';
        return false;
    }
    move_uploaded_file($pathGambar,'pembayaran/'.$namaGambar);
    return $namaGambar;
}

//INPUTAN DATA
function simpan($data){
    global $conn;
    $id_user=htmlspecialchars($data['id_user']);
    $id_produk= $data['id_produk'];
    $jumlah= $data['jumlah'];
    $query="INSERT INTO transaksi(id_user,id_produk,jumlah) VALUES('$id_user','$id_produk','$jumlah')";
    mysqli_query($conn,$query);
    return mysqli_affected_rows($conn);
}

// FORMAT RUPIAH
function rupiah($angka){
	$hasil_rupiah = "Rp " . number_format($angka,2,',','.');
	return $hasil_rupiah;
}
// FORMAT STRING
function formatString($string) {
    if(strlen($string) > 15) {
        return substr($string, 0, 13) . "...";
    }
    return $string;
}
// FILTER URL BY PRICE
function filterUrlLink($order) {
    $url = $_SERVER['REQUEST_URI'];

    if(strpos($url , "?search=") && isset($_GET['search'])) {
        $key = $_GET['search'];
        if (strpos($url , "user.php")) {
            return "/toko-mainan/user.php?search=$key&filter=$order";
        } else {
            return "/toko-mainan/admin/admin.php?search=$key&filter=$order";
        }
    }
    if(strpos($url , "?kat=") && isset($_GET['kat'])) {
        $kat = $_GET['kat'];
        if (strpos($url , "user.php")) {
            return "/toko-mainan/user.php?kat=$kat&filter=$order";
        } else {
            return "/toko-mainan/admin/admin.php?kat=$kat&filter=$order";
        }
    } else {
        if (strpos($url , "user.php")) {
            return "/toko-mainan/user.php?filter=$order";
        } else {
            return "/toko-mainan/admin/admin.php?filter=$order";
        }
    }
}

//SIGN UP
function signup($data){
    global $conn;
    $alamat = $data['alamat'];
    $username = htmlspecialchars(strtolower(stripslashes($data['user'])));
    $password = mysqli_real_escape_string($conn,$data['password']);
    $password= password_hash($password,PASSWORD_DEFAULT);
    $email = htmlspecialchars($data['email']);
    $nama_depan= htmlspecialchars($data['nama-depan']);
    $nama_belakang= htmlspecialchars($data['nama-belakang']);
    $jenis_kelamin= $data['jk'];
    $no_tlp= htmlspecialchars($data['no-tlp']);
    $tanggal_lhr = $data['tgl-lahir'];

    $cekQuery = "SELECT * FROM user WHERE username='$username'";
    $result = mysqli_query($conn,$cekQuery);
    if($username=='' && $password=='' && $nama_depan=='' || $nama_belakang=='' 
    || $no_tlp=='' || $tanggal_lhr=='' || $jenis_kelamin=='' || $email=='' || $alamat==''){
        echo "<script>
        alert('Data belum lengkap');
        </script>";
        return false;
    }

    if(mysqli_affected_rows($conn) == 1 ){
        echo "<script>
        alert('Username sudah dipakai');
        document.location.href = 'signup.php';
        </script>";
        return false;
    } else {
        mysqli_query($conn,"INSERT INTO user(id_alamat,username,password,email,nama_depan,nama_belakang,tgl_lhr_user,jk_user,no_hp_user) VALUES ('$alamat','$username','$password','$email','$nama_depan','$nama_belakang',STR_TO_DATE('$tanggal_lhr','%Y-%m-%d'),'$jenis_kelamin','$no_tlp')");
        return mysqli_affected_rows($conn);
    }
}

?>