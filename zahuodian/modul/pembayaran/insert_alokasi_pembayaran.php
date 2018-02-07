<?php
include "../../config/koneksi.php";
include "../../lib/input.php";
session_start();
$id_bayarbeli   = $_POST['id_bayarbeli'];
$query_status_0 = mysql_query("SELECT * FROM `trans_bayarbeli_detail` WHERE `bukti_bayar`='$_POST[no_bukti]'");
while ($t = mysql_fetch_array($query_status_0)) {
    $invoice[] = $t['nota_invoice'];
}
input_only_log("delete from  trans_bayarbeli_detail where bukti_bayar='$_POST[no_bukti]'");
foreach ($invoice as $key => $value) {
    input_only_log("UPDATE `trans_retur_pembelian` SET `status`='0' where `kode_rbb`='$value'");
    input_only_log("UPDATE `trans_retur_penjualan` SET `status`='0' WHERE `kode_rjb`='$value'");
    Update_invoice($value);
    Update_penotaan($value);

}
input_only_log("delete from  jurnal_umum where kode_nota='$_POST[no_bukti]'");


$query1 = "INSERT INTO trans_bayarbeli_detail(
                                                   bukti_bayar,
                                                   bukti_bayar_dimuka,
                                                   id_akunkasperkiraan_detail,
                                                   nota_invoice, 
                                                   ket,
                                                   sisa_invoice,
                                                   id_supplier,
                                                   nama,
                                                   nominal_alokasi,
                                                   user_update, 
                                                   tgl_update)
                                                   VALUES ";

$itemCount  = count($_POST["id_akunkas"]);
$queryValue = "";
echo $itemCount;
for ($i = 0; $i < $itemCount; $i++) {
    $queryValue = "(
                                   '" . $_POST["no_bukti"] . "',
                                   '" . $_POST["bukti_bayar_dimuka"][$i] . "',
                                   '" . $_POST["id_akunkas"][$i] . "',
                                   '" . $_POST["no_invoice"][$i] . "', 
                                   '" . $_POST["ketdetail"][$i] . "',
                                   '" . $_POST["sisa_invoice"][$i] . "',
                                   '" . $_POST["id_supplier"][$i] . "', 
                                     '" . $_POST["nama_supplier"][$i] . "', 
                                   '" . $_POST["nominal_alokasi"][$i] . "', 
                                   '" . $_SESSION["namauser"] . "',
                                   now() )";
    if ($_POST["status_titipan"] == 'T') {
        $akun_utama = '49';
    } else {
        $akun_utama = $_POST["id_akun"];
    }
    
    $kete = $_POST["no_invoice"][$i] . ' - ' . $_POST["ketdetail"][$i];
    if ($_POST["nominal_alokasi"][$i] <= 0) {
        ####################-- Jurnal Terbalik --#####################################
        input_jurnal_umum($akun_utama, $_POST["id_akunkas"][$i], '1', '', abs($_POST["nominal_alokasi"][$i]), $_POST["no_bukti"], $_POST["tgl"], $_POST['id_supplier'][$i], 'supplier', $kete,$_POST["no_invoice"][$i]);
        #######################################################################
    } else {
        if ($_POST['kode_user'][$i] == 'RJB') {
            input_jurnal_umum($_POST["id_akunkas"][$i], $akun_utama, '', '1', $_POST["nominal_alokasi"][$i], $_POST["no_bukti"], $_POST["tgl"], $_POST['id_supplier'][$i], 'customer', $kete,$_POST["no_invoice"][$i]);
        } else {
            input_jurnal_umum($_POST["id_akunkas"][$i], $akun_utama, '', '1', $_POST["nominal_alokasi"][$i], $_POST["no_bukti"], $_POST["tgl"], $_POST['id_supplier'][$i], 'supplier', $kete,$_POST["no_invoice"][$i]);
        }
    }
    $sql = $query1 . $queryValue;
    input_only_log($sql);
    if (!empty($_POST["no_invoice"][$i])) {
        $no_invoice = $_POST["no_invoice"][$i];
        $c_tipe     = explode('/', $no_invoice);
        $tipe       = $c_tipe[0];
        if ($tipe == 'PI') {
            Update_invoice($no_invoice);
        }elseif ($tipe == 'NTT') {
          Update_penotaan($no_invoice);
        }
        input_only_log("UPDATE `trans_retur_pembelian` SET `status`='1' where `kode_rbb`='" . $_POST["no_invoice"][$i] . "'");
        input_only_log("UPDATE `trans_retur_penjualan` SET `status`='1' WHERE `kode_rjb`='" . $_POST["no_invoice"][$i] . "'");
    }
}

Update_header($id_bayarbeli);
function Update_header($id_bayarbeli)
{
    $query        = "SELECT `nominal`,sum(b.nominal_alokasi)as total_alokasi,(`nominal`-sum(b.nominal_alokasi)) as sisa_alokasi FROM `trans_bayarbeli_header` a left join `trans_bayarbeli_detail` b on (a.`bukti_bayar`=b.`bukti_bayar`) where id_bayarbeli='$id_bayarbeli' group by a.`bukti_bayar` ";
    $result       = mysql_query($query);
    $check_header = mysql_fetch_array($result);
    if ($check_header['nominal'] == $check_header['total_alokasi']) {
        $query = "UPDATE trans_bayarbeli_header SET 
                                               nominal_alokasi = '$check_header[total_alokasi]',
                                               status_bayar = 'Pass',
                                               sisa_alokasi = '$check_header[sisa_alokasi]'
                                                          WHERE id_bayarbeli = '$id_bayarbeli'";
    } else if ($check_header['nominal'] <= $check_header['total_alokasi']) {
        $query = "UPDATE trans_bayarbeli_header SET 
                                               nominal_alokasi = '$check_header[total_alokasi]',
                                               status_bayar = 'Kelebihan',
                                               sisa_alokasi = '$check_header[sisa_alokasi]'
                                                          WHERE id_bayarbeli = '$id_bayarbeli'";
    } else if ($check_header['nominal'] >= $check_header['total_alokasi']) {
        $query = "UPDATE trans_bayarbeli_header SET 
                                               nominal_alokasi = '$check_header[total_alokasi]',
                                               status_bayar = 'Belum Pass',
                                               sisa_alokasi = '$check_header[sisa_alokasi]'
                                                          WHERE id_bayarbeli = '$id_bayarbeli'";
    }
    input_only_log($query);
}
function Update_invoice($no_invoice)
{
    $PI     = "
    SELECT ti.id_invoice,s.id_supplier, s.nama_supplier, (ti.grand_total - if(teralokasi is null,0,teralokasi)) as result ,ti.grand_total,kode_supplier FROM supplier s, trans_invoice ti left join (SELECT nota_invoice,sum(if(nominal_alokasi>=0,nominal_alokasi,0)) as teralokasi FROM trans_bayarbeli_detail WHERE nota_invoice = '$no_invoice') d on (d.nota_invoice=ti.id_invoice) WHERE ti.id_supplier=s.id_supplier and ti.is_void='0' and ti.id_invoice ='$no_invoice'";
    $result = mysql_query($PI);
    $r      = mysql_fetch_array($result);
    if (intval($r['result']) == 0) {
        input_only_log("UPDATE trans_invoice SET  status_lunas = '2'  WHERE id_invoice =  '" . $no_invoice . "'");
    } else if (intval($r['result']) == $r['grand_total']) {
        input_only_log("UPDATE trans_invoice SET  status_lunas = '0'  WHERE id_invoice =  '" . $no_invoice . "'");
    } else if (intval($r['result']) > '0') {
        input_only_log("UPDATE trans_invoice SET  status_lunas = '1'  WHERE id_invoice =  '" . $no_invoice . "'");
    }
}
function Update_penotaan($no_invoice)
{
    $PI     = "
   SELECT `no_totalan_tukang`,`id_supplier`,
(nominal_totalan - if(teralokasi is null,0,teralokasi)) as result ,`nominal_totalan` 
FROM `trans_totalan_tukang`a left join

(SELECT nota_invoice,sum(if(nominal_alokasi>=0,nominal_alokasi,0)) as teralokasi FROM trans_bayarbeli_detail WHERE nota_invoice = '$no_invoice') b on (b.nota_invoice=a.no_totalan_tukang)

WHERE a.is_void='0' and a.no_totalan_tukang='$no_invoice'";
    $result = mysql_query($PI);
    $r      = mysql_fetch_array($result);
    if (intval($r['result']) == 0) {
        input_only_log("UPDATE `trans_totalan_tukang` SET  status_terbayar = '2'  WHERE no_totalan_tukang =  '" . $no_invoice . "'");
    } else if (intval($r['result']) == $r['nominal_totalan']) {
        input_only_log("UPDATE `trans_totalan_tukang` SET  status_terbayar = '0'  WHERE no_totalan_tukang =  '" . $no_invoice . "'");
    } else if (intval($r['result']) > '0') {
        input_only_log("UPDATE `trans_totalan_tukang` SET  status_terbayar = '1'  WHERE no_totalan_tukang =  '" . $no_invoice . "'");
    }
}
?>