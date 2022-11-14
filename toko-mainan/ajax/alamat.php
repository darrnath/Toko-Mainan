<?php  
    require '../include/functions.php';
    $keyAlmat = $_GET['a'];
    $addresses = query("SELECT * FROM alamat WHERE kelurahan LIKE '%$keyAlmat%' OR kabupaten LIKE '%$keyAlmat%' OR provinsi LIKE '%$keyAlmat%' LIMIT 5");
?>

<table style="text-align: center;" cellpadding='3' >
<tr>
    <th>Kelurahan</th>
    <th>Kabupaten</th>
    <th>Provinsi</th>
    <th>Pilih</th>
</tr>
<?php foreach($addresses as $address) : ?>
    <tr>
        <td><?=$address['kelurahan']?></td>
        <td><?=$address['kabupaten']?></td>
        <td><?=$address['provinsi']?></td>
        <td><input type="radio" name="alamat" id="alamat" value="<?=$address['id_alamat']?>"></td>
    </tr>
<?php endforeach; ?>
</table>