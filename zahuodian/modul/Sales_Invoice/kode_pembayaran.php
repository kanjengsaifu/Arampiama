<?php
 include "../../config/koneksi.php";
  $id_bank = $_GET['id'];
  $kode = $_GET['kode'];
 $query=mysql_query("SELECT id_pembayaran FROM `trans_pembayaran` where id_pembayaran like '$kode%' and id_masterbank='$id_bank' order by id desc limit 1 ");
  $kode=mysql_fetch_array($query);
  $romawi=array("","I","II","III","IV","V","VI","VII","VIII","IX","X","XI","XII");
  $kode_bukti=explode(" - ",$kodesurat);
  $kode_urut=explode("/",$kode_bukti[1]);
  $thn = $kode_urut[2];
  $bln_sekarang = date("m");
  $thn_sekarang = date("Y");
   if ($kode_urut[1]== $romawi[$bln_sekarang] && $thn==substr($thn_sekarang,2) ){
     $a = $kode_urut[0]+1+10000 ;
  }
  else{
    $a = 1+10000 ;
  };
  $C=  $kode." - ". substr($a,1) ."/".$romawi[$bln_sekarang]."/".substr($thn_sekarang, 2);
  echo $C;
?>