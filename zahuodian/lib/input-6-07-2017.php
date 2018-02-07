<?php ob_start();
function generate_barang($awal,$akhir)
{

$query="DELETE from lap_rekap_barang where (tgl_transaksi between '".$awal." ' and  '".$akhir." ') ";
mysql_query($query);
$query="
select * from (
SELECT
tl.tgl_update as tgl_transaksi,
tl.id_lpb as nota,
nama_supplier as keterangan,
id_barang,
qty_pi_convert as masuk,
harga_pi as harga_masuk,
(qty_pi*harga_pi) as rupiah_masuk,
'0' as keluar,
'0' as harga_keluar,
'0' as rupiah_keluar
FROM supplier s,trans_invoice_detail td, trans_invoice t, trans_lpb tl
where td.id_invoice=t.id_invoice and s.id_supplier=t.id_supplier and tl.id_lpb=t.id_lpb and td.is_void=0 and t.is_void=0
union all
SELECT
t2.tgl_update as tgl_transaksi,
t.id_lpb as nota,
nama_supplier as keterangan,
id_barang,
qty_diterima_convert as masuk,
'0' as harga_masuk,
'0' as rupiah_masuk,
'0' as keluar,
'0' as harga_keluar,
'0' as rupiah_keluar
FROM trans_lpb_detail t,trans_lpb t2,supplier s where t2.is_void=0 and t.id_lpb=t2.id_lpb and s.id_supplier=t2.id_supplier and t.id_lpb not in(SELECT t3.id_lpb FROM trans_invoice t3)
union all
SELECT
tgl_update as tgl_transaksi,
'Adjustment' as nota,
'Adjustment' as keterangan,
id_barang ,
'0' as masuk,
'0' as harga_masuk,
'0' as rupiah_masuk,
abs(plusminus_barang) as keluar,
'0' as harga_keluar,
'0' as rupiah_keluar
FROM adjustment_stok where (plusminus_barang like '-%')
union all
SELECT
tgl_update as tgl_transaksi,
'Adjustment' as nota,
'Adjustment' as keterangan,
id_barang ,
abs(plusminus_barang) as masuk,
'0' as harga_masuk,
'0' as rupiah_masuk,
'0' as keluar,
'0' as harga_keluar,
'0' as rupiah_keluar
FROM adjustment_stok where (plusminus_barang not like '-%')
union all
SELECT
tl.tgl_update as tgl_transaksi,
tl.id_lkb as nota,
nama_customer as keterangan,
id_barang,
'0' as masuk,
'0' as harga_masuk,
'0' as rupiah_masuk,
qty_si_convert as keluar,
harga_si as harga_keluar,
(qty_si*harga_si) as rupiah_keluar
FROM customer s,trans_sales_invoice_detail td, trans_sales_invoice t, trans_lkb tl
where td.id_invoice=t.id_invoice and s.id_customer=t.id_customer and tl.id_lkb=t.id_lkb and td.is_void=0 and t.is_void=0
union all
SELECT
t2.tgl_update as tgl_transaksi,
t.id_lkb as nota,
nama_customer as keterangan,
id_barang,
'0' as masuk,
'0' as harga_masuk,
'0' as rupiah_masuk,
qty_diterima_convert as keluar,
'0' as harga_keluar,
'0' as rupiah_keluar
FROM trans_lkb_detail t,trans_lkb t2,customer s where t2.is_void=0 and t.id_lkb=t2.id_lkb and s.id_customer=t2.id_customer and t.id_lkb not in(SELECT t3.id_lkb FROM trans_sales_invoice t3)
union all
SELECT
tl.tgl_update  as tgl_transaksi,
tl.kode_rjb as nota,
nama_customer as keterangan,
1 as id_barang,
qty_convert as masuk,
'0' as harga_masuk,
'0' as rupiah_masuk,
'0' as keluar,
'0' as harga_keluar,
'0' as rupiah_keluar
FROM trans_retur_penjualan_detail tld,trans_retur_penjualan tl,customer c
WHERE c.id_customer=tl.id_customer and tl.kode_rjb=tld.kode_rjb and  tl.is_void=0 and tl.is_void=0 
union all
SELECT
tl.tgl_update  as tgl_transaksi,
tl.kode_rbb as nota,
nama_supplier as keterangan,
id_barang as id_barang,
'0' as masuk,
'0' as harga_masuk,
'0' as rupiah_masuk,
qty_convert as keluar,
'0' as harga_keluar,
'0' as rupiah_keluar
FROM trans_retur_pembelian_detail tld,trans_retur_pembelian tl,supplier s WHERE s.id_supplier=tl.id_supplier and tl.kode_rbb=tld.kode_rbb and  tl.is_void=0
 ) as rekap
where ( date(tgl_transaksi) between '".$awal." ' and  '".$akhir." ') order by tgl_transaksi asc";
$query=mysql_query($query);
while ($r=mysql_fetch_array($query)) {
  $saldo_akhir=mysql_query("SELECT * from lap_rekap_barang where id_barang =".$r['id_barang']." and date(tgl_transaksi) <= date('".$r['tgl_transaksi']."') order by id_lap_rekap_barang desc ,date(tgl_transaksi)  limit 1");
  $s=mysql_fetch_array($saldo_akhir);
 if (empty($s['saldo_akhir'])){
    $qsaldo=0;
  }else{
     $qsaldo=$s['saldo_akhir'];
  }
   if ($r['keluar']=='0' && $r['masuk']=='0') {}else{
  if ($r['keluar']=='0') {
   $qinsert=("Insert Into lap_rekap_barang(nota,id_barang,tgl_transaksi,saldo_awal,masuk,keluar,saldo_akhir,tgl_update,harga_masuk,rupiah_masuk,keterangan) 
    values ('".$r['nota']."',".$r['id_barang'].",'".$r['tgl_transaksi']."',".$qsaldo.",".$r['masuk'].",0,".($s['saldo_akhir']+$r['masuk']).",now(),".$r['harga_masuk'].",".$r['rupiah_masuk'].",'".$r['keterangan']."')");
   mysql_query($qinsert);
}else{  
     $qinsert=("Insert Into lap_rekap_barang(nota,id_barang,tgl_transaksi,saldo_awal,masuk,keluar,saldo_akhir,tgl_update,harga_keluar,rupiah_keluar,keterangan) 
    values ('".$r['nota']."',".$r['id_barang'].",'".$r['tgl_transaksi']."',".$qsaldo.",0,'".$r['keluar']."','".($s['saldo_akhir']-$r['keluar'])."',now(),'".$r['harga_keluar']."','".$r['rupiah_keluar']."','".$r['keterangan']."')");
 mysql_query($qinsert);
  }
}
}

}
function generate_piutang($awal,$akhir,$user){
  mysql_query("DELETE FROM `lap_rekap_piutang` WHERE tgl_transaksi >= '$awal' ");
$select_customer="select * from jurnal_umum, customer where kode_user=kode_customer and tanggal >= '$awal' group by kode_customer";
$result_customer=mysql_query($select_customer);
while ($customer=mysql_fetch_array($result_customer)) {
  $select_ju="
select *,sum(nominal) as nominal_t from jurnal_umum where `kode_user`='".$customer['kode_customer']."' and tanggal >= '$awal' and `id_akun_kas_perkiraan`='36' group by kode_nota,debet_kredit order by tanggal asc 
";

  $result_ju=mysql_query($select_ju);
  while ($ju=mysql_fetch_array($result_ju)) {
                $select_lrp="select * from lap_rekap_piutang where id_customer='".$customer['id_customer']."' order by id_lap_rekap_piutang desc limit 1";
                $result_lrp=mysql_query($select_lrp);
                $lrp=mysql_fetch_array($result_lrp);
                if ($ju['debet_kredit']=='D') {
                          $insert_lrp="INSERT INTO `lap_rekap_piutang`( `id_customer`, `nota`, `tgl_transaksi`, `saldo_awal`, `pembelian`, `pembayaran`, `saldo_akhir`, `user_update`) VALUES 
                ('".$customer['id_customer']."','".$ju['kode_nota']."','".$ju['tanggal']."','".$lrp['saldo_akhir']."',
                '".$ju['nominal_t']."','-','".($lrp['saldo_akhir']+$ju['nominal_t'])."','".$user."')";
                }else {
                          $insert_lrp="INSERT INTO `lap_rekap_piutang`( `id_customer`, `nota`, `tgl_transaksi`, `saldo_awal`, `pembelian`, `pembayaran`, `saldo_akhir`, `user_update`) VALUES 
                ('".$customer['id_customer']."','".$ju['kode_nota']."','".$ju['tanggal']."','".$lrp['saldo_akhir']."','-',
                '".$ju['nominal_t']."','".($lrp['saldo_akhir']-$ju['nominal_t'])."','".$user."')";
                }
                mysql_query($insert_lrp);
                                                                                  }
                                                                }
}
function generate_hutang($awal,$akhir,$user){

  mysql_query("DELETE FROM `lap_rekap_hutang` WHERE tgl_transaksi >= '$awal' ");
$select_supplier="select * from supplier ";
$result_supplier=mysql_query($select_supplier);
while ($supplier=mysql_fetch_array($result_supplier)) {
  $select_ju="
select *,sum(nominal) as nominal_t from jurnal_umum where `kode_user`='".$supplier['kode_supplier']."' and tanggal >= '$awal' and (`id_akun_kas_perkiraan`='71' or `id_akun_kas_perkiraan`='71') group by kode_nota,debet_kredit order by tanggal asc 
";

  $result_ju=mysql_query($select_ju);
  while ($ju=mysql_fetch_array($result_ju)) {
                $select_lrp="select * from lap_rekap_hutang where id_supplier='".$supplier['id_supplier']."' order by id_lap_rekap_hutang desc limit 1";
                $result_lrp=mysql_query($select_lrp);
                $lrp=mysql_fetch_array($result_lrp);
                if ($ju['debet_kredit']=='K') {
                          $insert_lrp="INSERT INTO `lap_rekap_hutang`
( `id_supplier`, `nota`, `tgl_transaksi`, `saldo_awal`, `pembelian`, `pembayaran`, `saldo_akhir`, `user_update`)
 VALUES 
 ('".$supplier['id_supplier']."','".$ju['kode_nota']."','".$ju['tanggal']."','".$lrp['saldo_akhir']."',
                '".$ju['nominal_t']."','-','".($lrp['saldo_akhir']+$ju['nominal_t'])."','".$user."')";
                }else {
                          $insert_lrp="INSERT INTO `lap_rekap_hutang`( `id_supplier`, `nota`, `tgl_transaksi`, `saldo_awal`, `pembelian`, `pembayaran`, `saldo_akhir`, `user_update`) VALUES 
                ('".$supplier['id_supplier']."','".$ju['kode_nota']."','".$ju['tanggal']."','".$lrp['saldo_akhir']."','-',
                '".$ju['nominal_t']."','".($lrp['saldo_akhir']-$ju['nominal_t'])."','".$user."')";
                }
                mysql_query($insert_lrp);
                                                                                  }
                                                                }
}
function generate_buku_besar($awal,$akhir,$user){

  mysql_query("DELETE FROM `buku_besar` WHERE tanggal >= '$awal' ");
$select_ak="select * from akun_kas_perkiraan ";
$result1=mysql_query($select_ak);
while ($a=mysql_fetch_array($result1)) {
  $select_ju="select *,sum(nominal) as nominal_t from jurnal_umum 
  where id_akun_kas_perkiraan='".$a['id_akunkasperkiraan']."'  and tanggal >= '$awal' group by kode_nota,debet_kredit order by tanggal asc";

  $result2=mysql_query($select_ju);
  while ($r=mysql_fetch_array($result2)) {
                $select_bb="select * from buku_besar where id_akunkasperkiraan='".$a['id_akunkasperkiraan']."' order by id_buku_besar desc limit 1";
                $result3=mysql_query($select_bb);
                $t=mysql_fetch_array($result3);
                if ($r['debet_kredit']=='D') {
                          $insert_bb="INSERT INTO `buku_besar`( `id_akunkasperkiraan`, `no_nota`, `tanggal`, `saldo_awal`, `debet`, `kredit`, `saldo_akhir`, `user_update`) VALUES 
                ('".$a['id_akunkasperkiraan']."','".$r['kode_nota']."','".$r['tanggal']."','".$t['saldo_akhir']."',
                '".$r['nominal_t']."','-','".($t['saldo_akhir']+$r['nominal_t'])."','".$user."')";
                }else {
                          $insert_bb="INSERT INTO `buku_besar`( `id_akunkasperkiraan`, `no_nota`, `tanggal`, `saldo_awal`, `debet`, `kredit`, `saldo_akhir`, `user_update`) VALUES 
                ('".$a['id_akunkasperkiraan']."','".$r['kode_nota']."','".$r['tanggal']."','".$t['saldo_akhir']."','-',
                '".$r['nominal_t']."','".($t['saldo_akhir']-$r['nominal_t'])."','".$user."')";
                }
                mysql_query($insert_bb);
                                                                                  }
                                                                }
}
function input_jurnal_umum_tipe_1($id_akun_debet,$id_akun_kredit,$d_header,$k_header,$nominal,$kode_nota,$tanggal,$id_user='',$ket_user='',$keterangan=''){
  ###########################################################
$kode_user=mysql_query("Select kode_".$ket_user." from ".$ket_user." where id_".$ket_user."='".$id_user."'");
$kode_user=mysql_fetch_row($kode_user);
  ###########################################################
   $query_akun_jurnal="INSERT  INTO jurnal_umum 
   (id_akun_kas_perkiraan,
   debet_kredit,
   nominal,
   kode_nota,
   tanggal,
   header,
   id_detail,
   kode_user,
   keterangan,
  user_update)
VALUES ";
if ($id_akun_debet!='') {
   $query_akun_jurnal.="('$id_akun_debet','D','$nominal','$kode_nota','$tanggal','$d_header','$id_akun_kredit','$kode_user[0]','$keterangan','$_SESSION[namauser]')";
}else{
   $query_akun_jurnal.="('$id_akun_kredit','K','$nominal','$kode_nota','$tanggal','$k_header','$id_akun_debet','$kode_user[0]','$keterangan','$_SESSION[namauser]')";
}
    try {
    if ( mysql_query($query_akun_jurnal)!=1 ) {
     throw new Exception('Problem with tableAresults');
     } 
       echo $query_akun_jurnal."succes</br>";
    } catch (Exception $e) {
   echo $query_akun_jurnal. "   " . "error" ;
   echo 'Message: ' .$e->getMessage();
    }
   
}

function input_jurnal_umum($id_akun_debet,$id_akun_kredit,$d_header,$k_header,$nominal,$kode_nota,$tanggal,$id_user='',$ket_user='',$keterangan=''){
  ###########################################################
$kode_user=mysql_query("Select kode_".$ket_user." from ".$ket_user." where id_".$ket_user."='".$id_user."'");
$kode_user=mysql_fetch_row($kode_user);
  ###########################################################
   $query_akun_jurnal="INSERT  INTO jurnal_umum 
   (id_akun_kas_perkiraan,
   debet_kredit,
   nominal,
   kode_nota,
   tanggal,
   header,
   id_detail,
   kode_user,
   keterangan,
  user_update)
VALUES 
('$id_akun_debet','D','$nominal','$kode_nota','$tanggal','$d_header','$id_akun_kredit','$kode_user[0]','$keterangan','$_SESSION[namauser]'),
('$id_akun_kredit','K','$nominal','$kode_nota','$tanggal','$k_header','$id_akun_debet','$kode_user[0]','$keterangan','$_SESSION[namauser]')";
    try {
    if ( mysql_query($query_akun_jurnal)!=1 ) {
     throw new Exception('Problem with tableAresults');
     } 
       echo $query_akun_jurnal."succes</br>";
    } catch (Exception $e) {
   echo $query_akun_jurnal. "   " . "error" ;
   echo 'Message: ' .$e->getMessage();
    }
   
}
function tanda_tangan($nama){
$table =
"
<table border=0 width='100%'>
<thead>
<th width='50%' rowspan=4 colspan=2 align='center'></th>
<th width='20%' colspan=2>Hormat Kami</th>
<th width='10%' colspan=2></th>
<th width='20%' colspan=2>Tanda Terima,</th>

</thead>
<tbody>

<tr>
<td colspan=2 align='center'><a>
Pertokoan SINAR GALAXY
Blok B No. 47 Telp. 3532162, 3337468
</br> Jln. Pasar Turi - Jl. Tembaan (S U R A B A Y A)</a>
</br><a><strong>Perhatian  :</strong>Barang <sup>2</sup> yang sudah dibeli tidak dapat dikembalikan / ditukar</a></td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td>&nbsp;&nbsp;&nbsp;<br> </td>
<td colspan=2>&nbsp;&nbsp;&nbsp;<br> </td>
<td>&nbsp;&nbsp;<br> </td>
<td>&nbsp;&nbsp;<br> </td>

</tr>
<tr>
<td colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td>&nbsp;&nbsp;&nbsp;<br> </td>
<td colspan=2>&nbsp;&nbsp;&nbsp;<br> </td>
<td>&nbsp;&nbsp;<br> </td>
<td>&nbsp;&nbsp;<br> </td>

</tr>
</tbody>
<tfoot>
<tr>
<td colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td  colspan=2 align='center'><a><u>$_SESSION[namauser]<u></a></td>
<td colspan=2>&nbsp;&nbsp;&nbsp;<br> </td>
<td  colspan=2 align='center'><a><u>$nama<u></a></td>
</tr>

</table>


";
return $table;
}
  function combobox($idname,$table,$id_table,$tampiltable,$id_edit,$name='',$req=''){
    $option="";
    $awal="<select id='$idname' name='$idname' class='chosen-select form-control' $req>";
    $comboquery=mysql_query("SELECT * FROM $table where is_void='0'");
          if ($id_edit==null){
             $option.="<option value='' selected>- Pilih $name  -</option>";
          }   
          while($w=mysql_fetch_array($comboquery)){
            if ($w[$id_table]==$id_edit){
              $option.= "<option value=$w[$id_table] selected>$w[$tampiltable]</option>";
            }
            else{
              $option.= "<option value=$w[$id_table]>$w[$tampiltable]</option>";
            }
          }
    $option.="</select>";
    return $awal.$option;
  }
   function comboboxextra($idname,$select,$table,$where,$value,$tampil,$akun="",$id_edit="",$id=""){
    $option="";
    $idname_id=$idname.$id;
    $akun_array= explode(',', $akun);
    $awal="<select id='$idname_id' name='$idname' class='chosen-select form-control' required>";
    $comboquery=mysql_query("SELECT $select FROM $table where $where");
          if ($id_edit==0){
             $option.="<option value=''  selected>- Pilih -</option>";
          }   
          while($w=mysql_fetch_array($comboquery)){
            $_ck = (array_search($w[$value], $akun_array) === false)? '' : 'checked';
            if ($_ck=='checked') {
              if ($w[$value]==$id_edit){
              $option.= "<option value=$w[$value] selected>$w[$tampil]</option>";
            }
            else{
              $option.= "<option value=$w[$value]>$w[$tampil]</option>";
            }
            }
          }
    $option.="</select>";
    return $awal.$option;
  }
     function comboboxeasy($idname,$query,$ket,$value,$tampil,$id_edit=""){
    $option="";
    $akun_array= explode(',', $akun);
    $awal="<select id='$idname' name='$idname' class='chosen-select form-control' required>";
    $comboquery=mysql_query("$query");
          if ($id_edit==0){
             $option.="<option  selected>- $ket -</option>";
          }   
          while($w=mysql_fetch_array($comboquery)){
              if ($w[$value]==$id_edit){
              $option.= "<option value='$w[$value]' selected>$w[$tampil]</option>";
            }
            else{
              $option.= "<option value='$w[$value]'>$w[$tampil]</option>";
            }
          }
    $option.="</select>";
    return $awal.$option;
  }

function check_data($table,$field,$value){
  $select ="SELECT * FROM $table where $field = '$value' and is_void = '0' ";
  echo $select;
  $select = mysql_query("$select ");
  $select = mysql_num_rows($select);
    echo $select;
  if ($select >= '1') {
    return 1;
  }else{
    return 0;
  }

}
function convert_satuan($total,$s5,$s4,$s3,$s2,$s1,$sa5,$sa4,$sa3,$sa2,$sa1){
   if ( ( ($s5!=0 )and floor($total / $s5)!=0)  ){
            echo floor($total / $s5)." - ".$sa5."<br>";
            $total =($total % $s5);
        }
          if ( (($s4!=0 )and floor($total / $s4)!=0)  ){
            echo floor($total / $s4)." - ".$sa4."<br>";
            $total =($total % $s4);
        }
          if ( (($s3!=0 )and floor($total / $s3)!=0)  ){
            echo floor($total / $s3)." - ".$sa3."<br>";
            $total =($total % $s3);
        }
          if ( (($s2!=0 )and floor($total / $s2)!=0)  ){
            echo floor($total / $s2)." - ".$sa2."<br>";
            $total =($total % $s2);
        }
         if ( (($s1!=0 )and floor($total / $s1)!=0)  ){
            echo floor($total / $s1)." - ".$sa1."<br>";
            $total =($total % $s1);
        }
}
function convt_satuan($total,$id_barang){
  $convert = "SELECT * FROM barang where id_barang=".$id_barang;
  $convert =mysql_query($convert);
  $convert =mysql_fetch_array($convert);
  $temp = "";
        if ((($convert['kali5']!=0 ) and floor($total / $convert['kali5'])!=0) ){
            $temp .= floor($total / $convert['kali5'])." ".$convert['satuan5']." ";
            $total =($total % $convert['kali5']);
        }
          if ( (($convert['kali4']!=0 ) and floor($total / $convert['kali4'])!=0)  ){
             $temp .= floor($total / $convert['kali4'])." ".$convert['satuan4']." ";
            $total =($total % $convert['kali4']);
        }
          if ( (($convert['kali3']!=0 )and floor($total / $convert['kali3'])!=0)  ){
             $temp .= floor($total / $convert['kali3'])." ".$convert['satuan3']." ";
            $total =($total % $convert['kali3']);
        }
          if ( (($convert['kali2']!=0 )and floor($total /$convert['kali2'])!=0)  ){
             $temp .= floor($total /$convert['kali2'])." ".$convert['satuan2']." ";
            $total =($total % $convert['kali2']);
        }
         if ( (($convert['kali1']!=0 )and floor($total /$convert['kali1'])!=0)  ){
            $temp .= floor($total /$convert['kali1'])." ".$convert['satuan1']." ";
            $total =($total %$convert['kali1']);
        }
        return $temp;
}
function bulan_hutang($moon){
  $temp="";
  if ($moon==1){
    $bulan=12;
  }else{
    $bulan=$moon-1;
  }
Switch ($bulan){
 case 1 : $bulan=1;
  $temp="<option value='".$bulan."'>Januari</option>";
 Break;
 case 2 : $bulan=2;
  $temp="<option value='".$bulan."'>Febuari</option>";
 Break;
 case 3 : $bulan=3;
  $temp="<option value='".$bulan."'>Maret</option>";
 Break;
 case 4 : $bulan=4;
  $temp="<option value='".$bulan."'>April</option>";
 Break;
 case 5 : $bulan=5;
  $temp="<option value='".$bulan."'>Mei</option>";
 Break;
 case 6 : $bulan=6;
  $temp="<option value='".$bulan."'>Juni</option>";
 Break;
 case 7 : $bulan=7;
  $temp="<option value='".$bulan."'>Juli</option>";
 Break;
 case 8 : $bulan=8;
  $temp="<option value='".$bulan."'>Agustus</option>";
 Break;
 case 9 : $bulan=9;
  $temp="<option value='".$bulan."'>September</option>";
 Break;
 case 10 : $bulan=10;
  $temp="<option value='".$bulan."'>Oktober</option>";
 Break;
 case 11 : $bulan=11;
  $temp="<option value='".$bulan."'>November</option>";
 Break;
 case 12 : $bulan=12;
  $temp="<option value='".$bulan."'>Desember</option>";
 Break;
 }
 echo "<option value='".$moon."'>".date("F")."</option>".$temp;
}
function BS($barang_rusak,$module,$oprator){
  if ($oprator=='tambah') {
    $query = "SELECT stok_sekarang FROM stok WHERE id_barang = '1' AND id_gudang =  '1'";
    $stok_sekarang = mysql_query($query);
    $stok_sekarang = mysql_fetch_array($stok_sekarang);
    $stokBS=$stok_sekarang['stok_sekarang']+$barang_rusak;
    $sql = "UPDATE stok SET 
    stok_sekarang = '$stokBS'                                                 
    WHERE id_barang =  '1' AND id_gudang =  '1' ";
    input_only_log($sql,$module);
  }
    if ($oprator=='kurang') {
    $query = "SELECT stok_sekarang FROM stok WHERE id_barang = '1' AND id_gudang =  '1'";
    $stok_sekarang = mysql_query($query);
    $stok_sekarang = mysql_fetch_array($stok_sekarang);
    $stokBS=$stok_sekarang['stok_sekarang']-$barang_rusak;
    $sql = "UPDATE stok SET 
    stok_sekarang = '$stokBS'                                                 
    WHERE id_barang =  '1' AND id_gudang =  '1' ";
    input_only_log($sql,$module);
  }

}
function checkbox($table,$whereNorderby,$key, $Label, $Nilai='') {
  $s = "select * from $table $whereNorderby";

  $d = mysql_query($s);
  $_arrNilai = explode(',', $Nilai);
  $str = '';
  $str = "<tr>";
      $no=1;
  while ($w = mysql_fetch_array($d)) {

    $_ck = (array_search($w[$key], $_arrNilai) === false)? '' : 'checked';
    if (($no % 3)!=0) {
      $str .= "<td class='left'><label>$no</label>
                </td><td><input type=checkbox name='".$key."_array[]' value='$w[$key]' $_ck></td>
              <td class='left'><label> - $w[$Label]</label></td>";
    }else{
       $str .= "<td class='left'><label>$no</label> </td><td><input type=checkbox name='".$key."_array[]' value='$w[$key]' $_ck></td>
              <td class='left'><label> - $w[$Label]</label></td></tr><tr>";

    }
  $no++;
  }

  $str .= "</tr>";
  return $str;
}

function kode_pembayaran2($kodesurat_asli,$kode1,$link){
  $romawi=array("","I","II","III","IV","V","VI","VII","VIII","IX","X","XI","XII");
  $bln_sekarang = date("m");
  $thn_sekarang = date("Y");
  if(isset($link)){
    $query=mysql_query("SELECT * FROM kodenota where link_modul='$link");
    $r = mysql_fetch_array($query);
    $kode = $r['nama_kodenota'];
  }
  else{
    $kode = $kode1;
    if(empty($kodesurat_asli)){
      $kodesurat = $kode1." - 0000/".$romawi[$bln_sekarang]."/".substr($thn_sekarang, 2);
    } else{
      $kodesurat = $kodesurat_asli;
    }
  }

  $kode_bukti=explode(" - ",$kodesurat);
  $kode_urut=explode("/",$kode_bukti[1]);
  $thn = $kode_urut[2];
   if ($kode_urut[1]== $romawi[$bln_sekarang] && $thn==substr($thn_sekarang,2) ){
     $a = $kode_urut[0]+1+10000 ;
  }
  else{
    $a = 1+10000 ;
  };
  $C=  $kode." - ". substr($a,1) ."/".$romawi[$bln_sekarang]."/".substr($thn_sekarang, 2);
  return $C;
}
function kode_pembayaran($kodesurat_asli,$kode1,$link){
  $romawi=array("","I","II","III","IV","V","VI","VII","VIII","IX","X","XI","XII");
  $bln_sekarang = date("n");
  $thn_sekarang = date("Y");

    $kode = $kode1;
    if(empty($kodesurat_asli)){
      $kodesurat = $kode1." - 0000/".$romawi[$bln_sekarang]."/".substr($thn_sekarang, 2);
    } else{
      $kodesurat = $kodesurat_asli;
    }
  

  $kode_bukti=explode(" - ",$kodesurat);
  $kode_urut=explode("/",$kode_bukti[1]);
  $thn = $kode_urut[2];
   if ($kode_urut[1]== $romawi[$bln_sekarang] && $thn==substr($thn_sekarang,2) ){
     $a = $kode_urut[0]+1+10000 ;
  }
  else{
    $a = 1+10000 ;
  };
  $C=  $kode." - ". substr($a,1) ."/".$romawi[$bln_sekarang]."/".substr($thn_sekarang, 2);
  return $C;
}

function input_data($query,$module){
 try {
    if (mysql_query($query)!=1 ) {
     throw new Exception('Problem with tableAresults');
     } ;
echo $query . "</br>";
   $str_rep= str_replace("'","", $query);
    $query="INSERT INTO `log_transaksi`( `user`, `modul`,`tgl_transaksi`, `perintah`) 
          VALUES ('$_SESSION[namauser]','$module',now(),'$str_rep') ";
  
  mysql_query($query);
  
header('location:../../media.php?module='.$module);

 } catch (Exception $e) {
    echo $query. "   " . "error" ;
   echo 'Message: ' .$e->getMessage();
 }
}

function input_only_log($query,$module=''){
try {
    if (mysql_query($query)!=1 ) {
     throw new Exception('Problem with tableAresults');
     } ;

   $str_rep= str_replace("'","", $query);
    $query="INSERT INTO `log_transaksi`( `user`, `modul`,`tgl_transaksi`, `perintah`) 
          VALUES ('$_SESSION[namauser]','$module',now(),'$str_rep') ";
  mysql_query($query);
 } catch (Exception $e) {
   echo $query. "   " . "error </br>" ;
   echo 'Message: ' .$e->getMessage();
 }
}
function check_query($query){
  try {
     if (mysql_query($query)!=1 ) {
     throw new Exception('Problem with tableAresults');
     } ;
  } catch (Exception $e) {
    echo $query;
  }
}

function input_and_print($query,$module, $ikiid){
try {
    if (mysql_query($query)!=1 ) {
     throw new Exception('Problem with tableAresults');
     } ;
   $str_rep= str_replace("'","", $query);
    $query="INSERT INTO `log_transaksi`( `user`, `modul`,`tgl_transaksi`, `perintah`) 
          VALUES ('$_SESSION[namauser]','$module',now(),'$str_rep') ";
  mysql_query($query);
echo "<script type='text/javascript'>window.open('cetak.php?id=$ikiid'); window.location.replace('../../media.php?module=$module');</script>";


 } catch (Exception $e) {
   echo $query. "   " . "error </br>" ;
   echo 'Message: ' .$e->getMessage();
 }
}
  function kode_checker($sql){
  $query = mysql_query($sql);
  $count = mysql_num_rows($query); 
  if ($count>=1) {
    return True;
  }
  }
function kodesurat($kodesurat, $kode, $nameinput, $idinput, $ikiReadOra = NULL)
{
  $no = explode("/",$kodesurat);
  $tgl_sekarang = date("Ymd");
  $bln_sekarang = date("m");
  $thn_sekarang = date("Y");
  $bln = substr($no[1], "4","2");
  $thn = substr($no[1], "0","4"); 
  if($ikiReadOra == NULL || empty($ikiReadOra)){
    $readOraIki = "readonly='readonly'";
  } else {
    $readOraIki = "";
  }
  if ($bln == $bln_sekarang && $thn==$thn_sekarang ){
     $a = $no[2]+1+100000 ;
  }
  else{
    $a = 1+100000 ;
  }
 
  $C = "$kode/". $tgl_sekarang ."/".substr($a,1);
  $date= date("m/d/Y");
  $b = "<input  name='$nameinput' value='$C' id='$idinput' $readOraIki  class='form-control' required />";
  return $b;
}
function generateButtonAdd($nama,$action,$id='')
{
   $s= "<button type=button class='ui-state-default ui-corner-all' id='$id' onclick=\"$action\">
            <span class='ui-icon ui-icon-circle-plus'></span>$nama
        </button>";   
	return $s;
}

function generateButtonCari($nama)
{
    $s="<button class='ui-state-default ui-corner-all' type='submit' role='button'>
            <span class='ui-icon ui-icon-search'></span>$nama
        </button>";
	return $s;
}
function generateButtonCari2($nama,$id_button='')
{
    $s="<button class='ui-state-default ui-corner-all' id='$id_button' type='button' role='button'>
            <span class='ui-icon ui-icon-search'></span>$nama
        </button>";
	return $s;
}

function generateButton($nama,$action,$id_button='')
{
    $s='<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="button" role="button" aria-disabled="false" onclick="'.$action.'" id="'.$id_button.'"><span class="ui-button-text">'.$nama.'</span></button>';
	return $s;
}

function generateSubmitButton($nama)
{
    $s="<button class='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' type='submit' role='button' aria-disabled='false'><span class='ui-button-text'>$nama</span></button>";
	return $s;
}

function generateInputDecimal($nama,$value='',$maxlength=20,$size=20,$action='')
{
	$caption=$nama.'_caption';
	$s="<input class=input type=text name='$caption' id='$caption' maxlength='$maxlength' size='$size' value='$value' onchange='$action'>";
	$s.="<input type=hidden name='$nama' id='$nama' value='$value'>";
	return $s;
}

function generateInputText($caption,$value='',$maxlength=20,$size=20,$onchange='',$prop='')
{
	$s="<input class=input onchange='$onchange' type=text name='$caption' $prop id='$caption' maxlength='$maxlength' size='$size' value='$value'>";
	return $s;
}
function format_rupiah($angka){
  $rupiah="Rp ".number_format($angka,2,',','.');
  return $rupiah;
}
function format_jumlah($angka){
  $jumlah=number_format($angka,0,',','.');
  if ($jumlah==0) {
    $jumlah="";
  }
  return $jumlah;
}
function format_ribuan($angka){
  $ribuan =" ".number_format($angka,2,',','.');
  return $ribuan ;
}
#### Fungsi CSS ####
function headerDeskripsi($judul,$deskripsi,$button=''){
  echo '  
  <div class="row">
          <div class="col-md-9 col-sm-9 col-xs-9">
                <h2>'.$judul.'</h2>
                 <p class="deskripsi">'.$deskripsi.'</p>
           </div>
           <div align="right" class="col-md-3 col-sm-3 col-xs-3">
              <div style="margin-top: 20px;"> '.$button.'</div>
           </div>
      </div>
            <hr class="deskripsihr" style="margin-bottom:0px;">';
          }

function tanggalan ($tgl){
  if($tgl != ''){
      $newDate = date("d M Y", strtotime($tgl));
    } else{
      $newDate = '-';
    }
      return $newDate;
}