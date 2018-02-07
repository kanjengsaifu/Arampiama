<?php
 include "../../config/koneksi.php";
 include "../../lib/input.php";

  $id_barang = $_GET['ajax'];
  $query = ("SELECT * FROM barang WHERE id_barang = '$id_barang'");
  $select = mysql_query($query);
  $data = mysql_fetch_array($select);
  $r = $data['id_barang']."@".$data['kode_barang']."@".$data['nama_barang']."@".$data['hpp'];
  echo $r;

?> 