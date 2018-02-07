 <?php
include "../../config/koneksi.php";
include "../../lib/input.php";
session_start();
$id_bayarjual = $_POST['id_bayarjual'];
$query_status_0 = mysql_query("SELECT * FROM `trans_bayarjual_detail` WHERE `bukti_bayarjual`='$_POST[no_bukti]'");
while ($t = mysql_fetch_array($query_status_0)) {
    $invoice[] = $t['nota_invoice'];
}
input_only_log("delete from  trans_bayarjual_detail where bukti_bayarjual='$_POST[no_bukti]'");
foreach ($invoice as $key => $value) {
    input_only_log("UPDATE `trans_retur_pembelian` SET `status`='0' where `kode_rbb`='$value'");
    input_only_log("UPDATE `trans_retur_penjualan` SET `status`='0' WHERE `kode_rjb`='$value'");
    Update_invoice($value);
}
input_only_log("delete from  jurnal_umum where kode_nota='$_POST[no_bukti]'");


$query1 = "INSERT INTO trans_bayarjual_detail(
                                                bukti_bayarjual,
                                                bukti_bayar_dimuka,
                                                id_akunkasperkiraan_detail,
                                                nota_invoice, 
                                                ket_detail_jual,
                                                sisa_invoice_detail_jual,
                                                id_customer,
                                                nama,
                                                nominal_alokasi_detail_jual,
                                                user_update, 
                                                tgl_update)
                                                VALUES ";

$itemCount  = count($_POST["id_akunkas"]);
$queryValue = "";
for ($i = 0; $i < $itemCount; $i++) {
    $queryValue = "(
                                '" . $_POST["no_bukti"] . "',
                                '" . $_POST["bukti_bayar_dimuka"][$i] . "',
                                '" . $_POST["id_akunkas"][$i] . "',
                                '" . $_POST["no_invoice"][$i] . "', 
                                '" . $_POST["ketdetail"][$i] . "',
                                '" . $_POST["sisa_invoice"][$i] . "',
                                '" . $_POST["id_customer"][$i] . "', 
                                  '" . $_POST["nama_customer"][$i] . "', 
                                '" . $_POST["nominal_alokasi_detail_jual"][$i] . "', 
                                '" . $_SESSION["namauser"] . "',
                                now() )";
    if ($_POST["status_titipan"] == 'T') {
        $akun_utama = '75';
    } else {
        $akun_utama = $_POST["id_akun"];
    }
    
    $kete = $_POST["no_invoice"][$i] . ' - ' . $_POST["ketdetail"][$i];
    if ($_POST["nominal_alokasi_detail_jual"][$i] <= 0) {
        ####################-- Jurnal Terbalik --#####################################
        input_jurnal_umum($_POST["id_akunkas"][$i],$akun_utama, '1', '', abs($_POST["nominal_alokasi_detail_jual"][$i]), $_POST["no_bukti"], $_POST["tgl"], $_POST['id_customer'][$i], 'customer', $kete,$_POST["no_invoice"][$i]);
        #######################################################################
    } else {
        if ($_POST['kode_user'][$i] == 'RJB') {
            input_jurnal_umum($akun_utama,$_POST["id_akunkas"][$i], '', '1', $_POST["nominal_alokasi_detail_jual"][$i], $_POST["no_bukti"], $_POST["tgl"], $_POST['id_customer'][$i], 'customer', $kete,$_POST["no_invoice"][$i]);
        } else {
            input_jurnal_umum($akun_utama,$_POST["id_akunkas"][$i],'', '1', $_POST["nominal_alokasi_detail_jual"][$i], $_POST["no_bukti"], $_POST["tgl"], $_POST['id_customer'][$i], 'customer', $kete,$_POST["no_invoice"][$i]);
        }
    }
    $sql = $query1 . $queryValue;
    input_only_log($sql);
    if (!empty($_POST["no_invoice"][$i])) {
        $no_invoice = $_POST["no_invoice"][$i];
        $c_tipe     = explode('/', $no_invoice);
        $tipe       = $c_tipe[0];
        if ($tipe == 'SI') {
            Update_invoice($no_invoice);
        }
        input_only_log("UPDATE `trans_retur_pembelian` SET `status`='1' where `kode_rbb`='" . $_POST["no_invoice"][$i] . "'");
        input_only_log("UPDATE `trans_retur_penjualan` SET `status`='1' WHERE `kode_rjb`='" . $_POST["no_invoice"][$i] . "'");
    }
}

Update_header($id_bayarjual);
function Update_header($id_bayarjual)
{
    $query        = "SELECT `nominaljual`,sum(b.nominal_alokasi_detail_jual)as total_alokasi,(`nominaljual`-sum(b.nominal_alokasi_detail_jual)) as sisa_alokasi FROM `trans_bayarjual_header` a left join `trans_bayarjual_detail` b on (a.`bukti_bayarjual`=b.`bukti_bayarjual`) where id_bayarjual='$id_bayarjual' group by a.`bukti_bayarjual` ";
    $result       = mysql_query($query);
    $check_header = mysql_fetch_array($result);
    if ($check_header['nominaljual'] == $check_header['total_alokasi']) {
        $query = "UPDATE trans_bayarjual_header SET 
                                            nominaljual_alokasi = '$check_header[total_alokasi]',
                                            status_bayar_jual = 'Pass',
                                            sisa_alokasi = '$check_header[sisa_alokasi]'
                                                       WHERE id_bayarjual = '$id_bayarjual'";
    } else if ($check_header['nominaljual'] <= $check_header['total_alokasi']) {
        $query = "UPDATE trans_bayarjual_header SET 
                                            nominaljual_alokasi = '$check_header[total_alokasi]',
                                            status_bayar_jual = 'Kelebihan',
                                            sisa_alokasi = '$check_header[sisa_alokasi]'
                                                       WHERE id_bayarjual = '$id_bayarjual'";
    } else if ($check_header['nominaljual'] >= $check_header['total_alokasi']) {
        $query = "UPDATE trans_bayarjual_header SET 
                                            nominaljual_alokasi = '$check_header[total_alokasi]',
                                            status_bayar_jual = 'Belum Pass',
                                            sisa_alokasi = '$check_header[sisa_alokasi]'
                                                       WHERE id_bayarjual = '$id_bayarjual'";
    }
}
function Update_invoice($no_invoice)
{
    $SI     = "SELECT ti.id_invoice,s.id_customer, s.nama_customer, (ti.grand_total - teralokasi) as result, ti.grand_total,kode_customer 
FROM trans_sales_invoice ti ,customer s,(SELECT nota_invoice,sum(nominal_alokasi_detail_jual) as teralokasi FROM trans_bayarjual_detail WHERE nota_invoice = '$no_invoice') d
WHERE  ti.id_customer=s.id_customer and ti.is_void='0' and d.nota_invoice=ti.id_invoice and ti.id_invoice ='$no_invoice'";
    $result = mysql_query($SI);
    $r      = mysql_fetch_array($result);
    echo 'aa' . $r['result'] . 'aa';
    if ($r['result'] == '0') {
        input_only_log("UPDATE trans_sales_invoice SET  status_lunas = '2'  WHERE id_invoice =  '" . $no_invoice . "'");
    } else if ($r['result'] == $r['grand_total']) {
        input_only_log("UPDATE trans_sales_invoice SET  status_lunas = '0'  WHERE id_invoice =  '" . $no_invoice . "'");
    } else if ($r['result'] > '0') {
        input_only_log("UPDATE trans_sales_invoice SET  status_lunas = '1'  WHERE id_invoice =  '" . $no_invoice . "'");
    }
}
?> 