<?php
 include "../../config/koneksi.php";
  include "../../lib/input.php";
 error_reporting(E_ALL ^ E_NOTICE);
 session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
  echo "
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
$module=$_GET['module'];
$act=$_GET['act'];


// Hapus modul
if ($module=='returpembelian' AND $act=='hapus'){
$query=mysql_query("SELECT * FROM `trans_retur_pembelian_detail` trpd,trans_retur_pembelian trp  WHERE trp.kode_rbb=trpd.kode_rbb and trp.kode_rbb='$_GET[kode]' ");
 while ( $r=mysql_fetch_array($query)){
      input_only_log("update stok set stok_sekarang= (stok_sekarang +".$r['qty_convert'].") WHERE id_barang = '".$r["id_barang"]."' AND id_gudang =  '".$r['id_gudang']."'",$module); 
      $no_invoice=$r['no_invoice'];
  }
        $delete= "delete from  jurnal_umum where kode_nota='".$_GET['kode']."' ";
        mysql_query($delete);
        Echo "UPDATE `trans_invoice` SET `status_retur`='0' WHERE `id_invoice`='$no_invoice'";
         mysql_query("UPDATE `trans_invoice` SET `status_retur`='0' WHERE `id_invoice`='$no_invoice'");
        input_data("update `trans_retur_pembelian` set is_void='1' WHERE kode_rbb='$_GET[kode]'",$module);
}
// Input menu
elseif ($module=='returpembelian' AND $act=='input'){
  $supplier=explode('@', $_POST['cb_supplier']);
$kode_retur=kode_surat('RBB','trans_retur_pembelian','kode_rbb','id');
 input_only_log("INSERT INTO trans_retur_pembelian(
                                                  kode_rbb,
                                                  id_supplier,
                                                  grandtotal_retur,
                                                  total_retur,
                                                  discpersen,
                                                  discnominal,
                                                  ppnpersen,
                                                  ppnnominal,
                                                  jenis_retur,
                                                  tgl_rbb,
                                                  tgl_update,
                                                  user_update)
                                                  VALUES(
                                                  '$kode_retur',
                                                  '$supplier[0]',
                                                  '$_POST[grandtotal]',
                                                  '$_POST[alltotal]',
                                                  '$_POST[persendisc]',
                                                  '$_POST[discalltotal]',
                                                  '$_POST[persenppn]',
                                                  '$_POST[totalppn]',
                                                  '$_POST[jenis_retur]',
                                                  '$_POST[tgl_retur]',
                                                  now(),
                                                  '$_SESSION[namauser]'
                                                  )",$module);

  $itemCount = count($_POST["id_po_barang"]);
    $itemValues=0;
    $query = "INSERT INTO trans_retur_pembelian_detail(
                                                kode_rbb,
                                                id_barang,
                                                id_gudang,
                                                qty_retur,
                                                satuan,
                                                qty_convert,
                                                stok_sekarang,
                                                harga_retur,
                                                disc1,
                                                disc2,
                                                disc3,
                                                disc4,
                                                disc5,
                                                harga_per_satuan_terkecil,
                                                user_update,
                                                tgl_update)
                                                VALUES ";
    $queryValue = "";
    for($i=0;$i<$itemCount;$i++) {

        $itemValues++;
        if($queryValue!="") {
          $queryValue .= ",";
        }
        $temp= explode('-',$_POST["jenis_satuan"][$i]);
        $gudang=explode('-', $_POST["gudang"][$i]);




################################### Maslah Stok ##############################
        $stok_sekarang="SELECT stok_sekarang from stok where id_barang='".$_POST["id_po_barang"][$i]."' and id_gudang = '" .$gudang[0]."'";
        $stok_sekarang=mysql_query($stok_sekarang);
        $stok_sekarang=mysql_fetch_array($stok_sekarang);
        $stok_sekarang=$stok_sekarang[0]-($_POST["jumlah"][$i]*$temp[2]);
        $qupdate=("UPDATE stok set stok_sekarang=$stok_sekarang where id_barang='".$_POST["id_po_barang"][$i]."' and id_gudang = '" .$gudang[0]."'");
        input_only_log($qupdate,$module);
############################################################################


################################### Maslah Retur ##############################
        $queryValue .= "('" . $kode_retur. "', 
          '" .$_POST["id_po_barang"][$i]  . "', '" . $gudang[0] . "', '" . $_POST['jumlah'][$i] . "', 
          '" . $temp[1]  . "', '" . $_POST['jumlah'][$i] . "', '" .($stok_sekarang). "', '" .$_POST['total'][$i]."','".$_POST['disc1'][$i]."','".$_POST['disc2'][$i]."','".$_POST['disc3'][$i]."','".$_POST['disc4'][$i]."','".$_POST['disc5'][$i]. "','".$_POST['harga_sat1'][$i]. "', '" .$_SESSION['username']. "', now())";
############################################################################
      

################################### Maslah Jurnal ##############################
$akun_barang="Select id_akunkasperkiraan from barang where id_barang='".$_POST["id_po_barang"][$i]."'";
$akun_barang=mysql_query($akun_barang);
$data=mysql_fetch_array($akun_barang);
input_jurnal_umum('95',$data['id_akunkasperkiraan'],'1','',$_POST['total'][$i],$kode_retur,$_POST['tgl_retur'],$supplier[0],'supplier');

############################################################################
      
    }
    $sql = $query.$queryValue;
    if($itemValues!=0) {
      input_data($sql,$module);
    }
    }
}

 function cari_kode(){
    $var = explode('-', date("Y-m-d"));
    $sql_cari="SELECT max(`kode_rbb`) as kode FROM `trans_retur_pembelian` WHERE `kode_rbb` LIKE 'RBB/$var[0]$var[1]%' ";
    $result=mysql_query($sql_cari);
    $hasil=mysql_fetch_array($result);
    $kode = explode('/',$hasil["kode"]);
    $kode_ururt=100001+$kode[2];
   return 'RBB/'.implode('', $var).'/'.substr($kode_ururt,1);
}
?>