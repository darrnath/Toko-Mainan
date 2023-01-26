<?php
session_start();
//APAKAH SUDAH LOGIN?
if(!$_SESSION['login']){
    header('Location: index.php');
}

require './include/functions.php';
$username = $_SESSION['nama'];
$id_user = $_SESSION['id_user'];

$payments = query("SELECT * FROM kat_pembayaran");
$categories = query("SELECT * FROM kat_produk");

if(isset($_GET['kat'])){
    $id = $_GET['kat'];
    $products = query("SELECT*FROM produk WHERE id_kat_produk='$id' AND status_produk = 'Ready'");
}

if(isset($_GET['btn-search'])){
    $keyword = $_GET['search'];
    $products = query("SELECT * FROM produk WHERE nama_produk LIKE '%$keyword%' AND status_produk = 'Ready'");
}

//TAMPILKAN PEMBAYARAN PUNYA USER
$query = "SELECT gambar_produk,bukti_pembayaran,id_pengiriman,pembayaran.id_kat_pembayaran,pembayaran.id_transaksi,nama_produk,jumlah,harga_produk,jenis_pembayaran,jenis_pengiriman,status_pembayaran,status_pengiriman FROM transaksi,produk,kat_pembayaran,kat_pengiriman,pembayaran,pengiriman WHERE transaksi.id_produk = produk.id_produk AND pembayaran.id_transaksi = transaksi.id_transaksi AND pengiriman.id_transaksi = transaksi.id_transaksi AND pembayaran.id_kat_pembayaran = kat_pembayaran.id_kat_pembayaran AND pengiriman.id_kat_pengiriman = kat_pengiriman.id_kat_pengiriman AND transaksi.id_user = '$id_user' ORDER BY pembayaran.id_transaksi DESC";
$details = query($query);

//KATEGORI PEMESANAN
if(isset($_GET['q'])){
    $id = $_SESSION['id_user'];
    $kategori = $_GET['q'];
    $details = query("SELECT DISTINCT * FROM user NATURAL JOIN produk NATURAL JOIN transaksi NATURAL JOIN kat_pembayaran NATURAL JOIN kat_pengiriman NATURAL JOIN pembayaran NATURAL JOIN pengiriman WHERE transaksi.id_produk=produk.id_produk AND id_kat_produk='$kategori' AND user.id_user = '$id'");
}

$arrayWaiting = array_filter($details , function($k) {
    return $k['bukti_pembayaran'] === null || 
            $k['status_pembayaran'] !== "Approved";
});
$arrayApproved = array_filter($details, function($k) {
    return $k['bukti_pembayaran'] !== null && 
            $k['status_pembayaran'] === "Approved";
});
function trackingPercentage($status_pembayaran , $status_pengiriman) {
    if($status_pembayaran === "Checking" && $status_pengiriman === "Packing") {
        echo("45%");
    } else if($status_pembayaran === "Resubmit" && $status_pengiriman === "Packing") {
        echo("30%");
    } else if($status_pembayaran === "Approved" && $status_pengiriman === "Packing") {
        echo("65%");
    }  else if($status_pembayaran === "Approved" && $status_pengiriman === "Sending") {
        echo("80%") ;
    } else if($status_pembayaran === "Approved" && $status_pengiriman === "Arrived") {
        echo("100%");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="./css/pemesanan.css">
<?php require_once ("include/head.php");?>
<title>Order - tokomainan</title>

<body>
    <?php require_once ("include/userNav2.php");?>
    
    <main id="order-main">
        <!-- PROGRESS SECTION -->
        <section id="order-section-left">
            <h1 class="order-title">On progress</h1>
            <?php if(count($arrayWaiting) === 0 ) : ?>
                <div class="empty-container">
                    <h1>Masih kosong nih, yuk beli mainan hanya di</h1>
                    <p onclick="window.location.href='user.php'">tokomainan.</p>
                </div>
            <?php endif; ?>
            <?php foreach($arrayWaiting as $data) { ?>
                <form action="pembayaran.php" method="post">
                    <input type="hidden" name="id_transaksi" value="<?= $data['id_transaksi'] ?>">
                    <div class="order-box-wrapper">
                        <div>
                            <img src="./img/<?= $data['gambar_produk'] ?>">
                        </div>
                        
                        <div style="width: 18rem;">
                            <h1 class="product-name mt-2"><?= $data['nama_produk'] ?></h1>

                            <div class="mt-2 mb-3">
                                <h2 class="order-progress-title">Progress : </h2>
                                <?php if ($data['status_pembayaran'] === "Checking") { ?>
                                    <h1 class="order-progress-desc">Pemeriksaan bukti pembayaran</h1>
                                <?php } else if ($data['status_pembayaran'] === "Resubmit") { ?>
                                    <h1 class="order-progress-desc">Bukti pembayaran di tolak</h1>
                                <?php } else { ?>
                                    <h1 class="order-progress-desc">Menunggu pembayaran</h1>
                                <?php  } ?>
                            </div>

                            <div class="tracking-line-container mt-2">
                                <span style="width:<?php trackingPercentage($data['status_pembayaran'],$data['status_pengiriman'])?>"
                                class="tracking-line"></span>
                            </div>

                            <div class="mt-1" style="text-align: right;">
                                <?php if ($data['status_pembayaran'] === "Checking") { ?>
                                <?php } else if ($data['status_pembayaran'] === "Resubmit") { ?>
                                    <button name="btn-pembayaran" class="btn-primary btn-resubmit">Resubmit</button>
                                <?php } else { ?>
                                    <button name="btn-pembayaran" class="btn-primary btn-pembayaran">Payment</button>
                                <?php  } ?>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>
        </section>
        
        <!-- CONFIRMATION SECTION -->
        <section id="order-section-right">
            <h1 class="order-title">On the way</h1>
            <?php if(count($arrayApproved) === 0 ) : ?>
                <div class="empty-container">
                    <h1>Yang ini juga masih kosong nih, langsung order yuk di</h1>
                    <p onclick="window.location.href='user.php'">tokomainan.</p>
                </div>
            <?php endif; ?>
            <?php foreach($arrayApproved as $data) { ?>
                <form action="pembayaran.php" method="post">
                    <input type="hidden" name="id_transaksi" value="<?= $data['id_transaksi'] ?>">
                    <div class="order-box-wrapper">
                        <div>
                            <img src="./img/<?= $data['gambar_produk'] ?>">
                        </div>
                        
                        <div style="width: 18rem;">
                            <h1 class="product-name mt-2"><?= $data['nama_produk'] ?></h1>

                            <div class="mt-2 mb-3">
                                <h2 class="order-progress-title">Progress : </h2>
                                <?php if ($data['status_pengiriman'] === "Packing") { ?>
                                    <h1 class="order-progress-desc">Barang sedang dipacking</h1>
                                <?php } else if ($data['status_pengiriman'] === "Sending") { ?>
                                    <h1 class="order-progress-desc">Barang sedang dikirim</h1>
                                <?php } else { ?>
                                    <h1 class="order-progress-desc">Transaksi berhasil</h1>
                                <?php  } ?>
                            </div>

                            <div class="tracking-line-container mt-2">
                                <span style="width:<?php trackingPercentage($data['status_pembayaran'],$data['status_pengiriman'])?>"
                                class="tracking-line"></span>
                            </div>

                            <div class="mt-1" style="text-align: right;">
                                <?php 
                                if ($data['status_pembayaran'] === "Approved" && 
                                    $data['status_pengiriman'] === "Sending") { ?>
                                    <a href="include/confirm.php?c=<?= $data['id_pengiriman'] ?>" class="mt-1 btn-primary btn-confirm">Confirmation</a>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </form>
            <?php } ?>
        </section>
    </main>
    <script src="./js/DOMScript.js"></script>
</body>
</html>