<?php
 include "config/koneksi.php";
 error_reporting(E_ALL ^ E_NOTICE);
 session_start();
 $tgl_awal = $_POST['tgl_awal'];
 $tgl_akhir = $_POST['tgl_akhir'];
 if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
  echo "
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
$aksi="modul/omsetsales/omsetsales.php";
switch($_GET['act']){

// Tampil Modul
  default:
    echo "
            <div class='row'>
                  <div class='col-md-6'>
                        <h2>Modul Omset Sales</h2>
                         <p class='deskripsi'>ini adalah Modul untuk Omset Sales </p>
                   </div>
            </div>

<div class='table-responsive'>
       <table class='table table-hover'>
                 <form method='post' action='?module=omsetsales&act=search'>
                  <tr>
                    <td>Tanggal Awal</td>
                    <td><input id='tgl_awal' name='tgl_awal' required></td>
                    <td>Tanggal Akhir</td>
                    <td><input id='tgl_akhir' name='tgl_akhir' required></td>
                    <td><input class='btn btn-success' type=submit value=Telusuri></td>
                  </tr>
                </form>                
          </table>
</div>                  
      <div class='table-responsive'>
      <table class='table table-bordered'>
              <thead>
          <tr style='background-color:#F5F5F5;'>
            <th rowspan=2 style='vertical-align:middle' class='number' >No</th>
            <th rowspan=2 style='vertical-align:middle'>Nama Sales</th>
            <th rowspan=2 style='vertical-align:middle'>Total Penjualan</th>
            <th colspan=4>Penjualan Terbayar</th>
            <th rowspan=2 style='vertical-align:middle'>Aksi</th>
          </tr>
          <tr>
            <th>Total</th>
            <th>BKM</th>
            <th>BGM</th>
            <th>BBM</th>
          </tr>
                </thead>
                <tbody>";
                  $tampil = mysql_query("SELECT *, sum(tsi.grand_total) as grand_total1 FROM trans_sales_invoice tsi LEFT JOIN trans_lkb lkb ON tsi.id_lkb = lkb.id_lkb left join trans_sales_order tso on lkb.id_sales_order = tso.id_sales_order left join sales sls on tso.id_sales = sls.id_sales where tsi.is_void = 0 group by sls.nama_sales");
                  $tot05=0;
                  $tot1=0;
                  $tot2=0;
                  $tot3=0;
                  $tot4=0;
                  $no = 1;
                  while ($r=mysql_fetch_array($tampil)){

                  echo "
                <tr>
                  <td>$no</td>
                  <td>$r[nama_sales]</td>";
                 $tot1+=$r['grand_total1'];
                    echo "
                  <td style='text-align:right'>".format_rupiah($r[grand_total1])."</td>";
                  $status = mysql_query("SELECT CASE WHEN tbd.bukti_bayarjual LIKE 'BKM%' THEN (sum(tbd.nominal_alokasi_detail_jual)) END AS BKM, CASE WHEN tbd.bukti_bayarjual LIKE 'BGM%' THEN (sum(tbd.nominal_alokasi_detail_jual)) END AS BGM, CASE WHEN tbd.bukti_bayarjual LIKE 'BBM%' THEN (sum(tbd.nominal_alokasi_detail_jual)) END AS BBM FROM trans_bayarjual_detail tbd JOIN trans_sales_invoice tsi ON tbd.nota_invoice = tsi.id_invoice JOIN trans_lkb lkb ON tsi.id_lkb = lkb.id_lkb left join trans_sales_order tso on lkb.id_sales_order = tso.id_sales_order JOIN sales sls ON tso.id_sales = sls.id_sales WHERE tbd.is_void='0' and tso.id_sales='$r[id_sales]' GROUP BY sls.id_sales");
                  //$status = mysql_query("SELECT sum(jumlah_pembayaran) as jumlah from trans_pembayaran tp left join trans_sales_invoice tsi on tsi.id_invoice = tp.id_invoice left join trans_sales_order tso on tsi.id_sales_order = tso.id_sales_order left join sales sls on tso.id_sales = sls.id_sales where tsi.is_void = 0 group by sls.nama_sales");
                  $s=mysql_fetch_array($status);
            $tot05+=($s[BKM]+$s[BGM]+$s[BBM]);
            $tot2+=$s['BKM'];
            $tot3+=$s['BGM'];
            $tot4+=$s['BBM'];
            echo" 
            <td style='text-align:right'>".format_rupiah(($s[BKM]+$s[BGM]+$s[BBM]))."</td>
            <td style='text-align:right'>".format_rupiah($s[BKM])."</td>
            <td style='text-align:right'>".format_rupiah($s[BGM])."</td>
            <td style='text-align:right'>".format_rupiah($s[BBM])."</td>
            <td>          
            <a href='?module=omsetsales&act=detail&id=$r[id_sales]'class='btn btn-success'  title='detail'target='_blank'><span class='glyphicon glyphicon-calendar' aria-hidden='true'></span></a>
            </td>";
                  
                  echo "
                </tr>"; 
                $no++;
                }
                if($no>0)
                {
                  echo "<tr style='background-color:#F5F5F5;'>
                          <td>&nbsp;</td>
                          <td>Total</td>
                          <td style='text-align:right'>".format_rupiah($tot1)."</td>
                          <td style='text-align:right'>".format_rupiah($tot05)."</td>
                          <td style='text-align:right'>".format_rupiah($tot2)."</td>
                          <td style='text-align:right'>".format_rupiah($tot3)."</td>
                          <td style='text-align:right'>".format_rupiah($tot4)."</td><td></td>
                        </tr>";
                }
            echo "
        </tbody>
      </table>
    </div>";
    break;

  case "search":
                echo "
            <div class='row'>
                  <div class='col-md-6'>
                        <h2>Modul Omset Sales</h2>
                         <p class='deskripsi'>ini adalah Modul untuk Omset Sales </p>
                         <p>Pencarian omset berdasarkan tanggal dari <b> $tgl_awal </b>sampai <b> $tgl_akhir </b> </p>   
                   </div>
            </div>

   <div class='table-responsive'>
       <table class='table table-hover'>
                 <form method='post' action='?module=omsetsales&act=search'>
                  <tr>
                    <td>Tanggal Awal</td>
                    <td><input id='tgl_awal' name='tgl_awal' required></td>
                    <td>Tanggal Akhir</td>
                    <td><input id='tgl_akhir' name='tgl_akhir' required></td>
                    <td><input class='btn btn-success' type=submit value=Telusuri></td>
                  </tr>
                </form>                
          </table>
</div>  
      <div class='table-responsive'>
      <table class='table table-bordered'>
              <thead>
          <tr style='background-color:#F5F5F5;'>
            <th rowspan=2 style='vertical-align:middle' class='number' >No</th>
            <th rowspan=2 style='vertical-align:middle'>Nama Sales</th>
            <th rowspan=2 style='vertical-align:middle'>Total Penjualan</th>
            <th colspan=4>Penjualan Terbayar</th>
            <th rowspan=2 style='vertical-align:middle'>Aksi</th>
          </tr>
          <tr>
            <th>Total</th> 
            <th>BKM</th>
            <th>BGM</th>
            <th>BBM</th>
          </tr>
              </thead>
                <tbody>";
                  $query = ("SELECT *, sum(tsi.grand_total) as grand_total1 FROM trans_sales_invoice tsi LEFT JOIN trans_lkb lkb ON tsi.id_lkb = lkb.id_lkb left join trans_sales_order tso on lkb.id_sales_order = tso.id_sales_order left join sales sls on tso.id_sales = sls.id_sales where tsi.tgl BETWEEN  '". $tgl_awal."' AND  '".$tgl_akhir."' group by sls.nama_sales");
                  $tampil = mysql_query($query);
                  $tot05=0;
                  $tot1=0;
                  $tot2=0;
                  $tot3=0;
                  $tot4=0;
                  $no = 1;
                  while ($r=mysql_fetch_array($tampil)){
                    $tot1+=$r['grand_total1'];
                  echo "
                <tr>
                  <td>$no</td>
                  <td>$r[nama_sales]</td>";
                 
                    echo " 
                  <td style='text-align:right'>".format_rupiah($r[grand_total1])."</td>";
                  $status = mysql_query("SELECT CASE WHEN tbd.bukti_bayarjual LIKE 'BKM%' THEN (sum(tbd.nominal_alokasi_detail_jual)) END AS BKM, CASE WHEN tbd.bukti_bayarjual LIKE 'BGM%' THEN (sum(tbd.nominal_alokasi_detail_jual)) END AS BGM, CASE WHEN tbd.bukti_bayarjual LIKE 'BBM%' THEN (sum(tbd.nominal_alokasi_detail_jual)) END AS BBM FROM trans_bayarjual_detail tbd JOIN trans_sales_invoice tsi ON tbd.nota_invoice = tsi.id_invoice JOIN trans_lkb lkb ON tsi.id_lkb = lkb.id_lkb left join trans_sales_order tso on lkb.id_sales_order = tso.id_sales_order JOIN sales sls ON tso.id_sales = sls.id_sales WHERE tbd.is_void='0' AND tsi.tgl BETWEEN  '". $tgl_awal."' AND  '".$tgl_akhir."'  and tso.id_sales='$r[id_sales]' GROUP BY sls.id_sales");
                  //SELECT sum(jumlah_pembayaran) as jumlah from trans_pembayaran tp left join trans_sales_invoice tsi on tsi.id_invoice = tp.id_invoice left join trans_sales_order tso on tsi.id_sales_order = tso.id_sales_order left join sales sls on tso.id_sales = sls.id_sales where tsi.tgl BETWEEN  '". $tgl_awal."' AND  '".$tgl_akhir."' group by sls.nama_sales");
                  $s=mysql_fetch_array($status);
              $tot05+=($s[BKM]+$s[BGM]+$s[BBM]);
              $tot2+=$s['BKM'];
              $tot3+=$s['BGM'];
              $tot4+=$s['BBM'];
            echo"
            <td style='text-align:right'>".format_rupiah(($s[BKM]+$s[BGM]+$s[BBM]))."</td>
            <td style='text-align:right'>".format_rupiah($s[BKM])."</td>
            <td style='text-align:right'>".format_rupiah($s[BGM])."</td>
            <td style='text-align:right'>".format_rupiah($s[BBM])."</td>
            <td>
            <a href='?module=omsetsales&act=detail&id=$r[id_sales]&tglawal=$tgl_awal&tglakhir=$tgl_akhir'class='btn btn-success'  title='detail' target='_blank'><span class='glyphicon glyphicon-calendar' aria-hidden='true'></span></a>
            </td>";
                  
                  echo "
                </tr>"; 
                $no++;
                }
                if($no>0)
                {
                  echo "<tr style='background-color:#F5F5F5;'>
                          <td>&nbsp;</td>
                          <td>Total</td>
                          <td style='text-align:right'>".format_rupiah($tot1)."</td>
                          <td style='text-align:right'>".format_rupiah($tot05)."</td>
                          <td style='text-align:right'>".format_rupiah($tot2)."</td>
                          <td style='text-align:right'>".format_rupiah($tot3)."</td>
                          <td style='text-align:right'>".format_rupiah($tot4)."</td>
                        </tr>";
                }
            echo "
        </tbody>
            </table>
          </div>"; 
          echo $select;   
    break;

  case 'detail':
  if (!isset($_GET[tglawal]) AND !isset($_GET[tglakhir])){
    $id = $_GET['id'];
  $query1 = ("SELECT *, sum(tsi.grand_total) as grand_total1 FROM trans_sales_invoice tsi LEFT JOIN trans_lkb lkb ON tsi.id_lkb = lkb.id_lkb left join trans_sales_order tso on lkb.id_sales_order = tso.id_sales_order left join sales sls on tso.id_sales = sls.id_sales where tsi.is_void = 0 AND sls.id_sales = '$id' group by sls.nama_sales");
  $tampil = mysql_query($query1);
  $row=mysql_fetch_array($tampil);
  $query2 = ("SELECT CASE WHEN tbd.bukti_bayarjual LIKE 'BKM%' THEN (sum(tbd.nominal_alokasi_detail_jual)) END AS BKM, CASE WHEN tbd.bukti_bayarjual LIKE 'BGM%' THEN (sum(tbd.nominal_alokasi_detail_jual)) END AS BGM, CASE WHEN tbd.bukti_bayarjual LIKE 'BBM%' THEN (sum(tbd.nominal_alokasi_detail_jual)) END AS BBM FROM trans_bayarjual_detail tbd JOIN trans_sales_invoice tsi ON tbd.nota_invoice = tsi.id_invoice JOIN trans_lkb lkb ON tsi.id_lkb = lkb.id_lkb left join trans_sales_order tso on lkb.id_sales_order = tso.id_sales_order JOIN sales sls ON tso.id_sales = sls.id_sales WHERE tbd.is_void='0' and tso.id_sales='$id' GROUP BY sls.id_sales");
  $status = mysql_query($query2);
  $s=mysql_fetch_array($status);
  $jumlah = ($s[BKM]+$s[BGM]+$s[BBM]);
  echo "
    <div class='row'>
          <div class='col-md-6'>
                <h2>Modul Omset Sales</h2>
                 <p class='deskripsi'>Nama Sales         : $row[nama_sales]</p>
                 <p class='deskripsi'>Tanggal Laporan    :"; if(isset($tglakhir)){  echo "$tglawal sampai $tglakhir";} echo" </p>
                 <p class='deskripsi'>Total Penjualan    : ".format_rupiah($row[grand_total1])."</p>
                 <p class='deskripsi'>Penjualan Terbayar : ".format_rupiah($jumlah)."</p>
                 <br>
           </div>
    </div>
    <div class='table-responsive'>
      <table class='table table-bordered'>
              <thead>
          <tr style='background-color:#F5F5F5;'>
            <th class='number' >No</th>
            <th>Tanggal</th>
            <th>Nomor Invoice</th>
            <th>Nomor Nota</th>
            <th>Nama Customer</th>
            <th>Total Penjualan</th>
            <th>Total Pembayaran</th>
            <th>Sisa Pembayaran</th>
            <th>Aksi</th>
          </tr>
                </thead>
                <tbody>";
                /*$tampil = mysql_query("SELECT * FROM trans_sales_invoice tsi left join trans_sales_order tso on tsi.id_sales_order = tso.id_sales_order left join sales sls on tso.id_sales = sls.id_sales where tsi.is_void = 0 group by sls.nama_sales");*/

                 $tampil = mysql_query("SELECT *,tsi.no_nota, tsi.grand_total FROM trans_sales_invoice tsi left JOIN trans_lkb lkb ON tsi.id_lkb = lkb.id_lkb left join trans_sales_order tso on lkb.id_sales_order = tso.id_sales_order JOIN customer ctr ON tsi.id_customer = ctr.id_customer where tso.id_sales = '$_GET[id]' order by tsi.id_sales_order");
                  $no = 1;
                  while ($r=mysql_fetch_array($tampil)){
                  echo "
                <tr>
                  <td>$no</td>
                  <td>$r[tgl]</td>
                  <td>$r[id_invoice]</td>
                  <td>$r[no_nota]</td>
                  <td>$r[nama_customer]</td>
                  <td style='text-align:right'>".format_rupiah($r[grand_total])."</td>";
                  $status = mysql_query("SELECT  sum(tbd.nominal_alokasi_detail_jual)  as jumlah, sum(tbh.status_giro_jual) as status_giro ,sum(tbh.giro_ditolak_jual) as giro_ditolak  from trans_bayarjual_detail tbd  LEFT JOIN trans_bayarjual_header tbh ON(tbd.bukti_bayarjual=tbh.bukti_bayarjual) where tbd.is_void='0' and tbd.nota_invoice='$r[id_invoice]' order by tbd.id_bayarjual_detail desc");
                    $s=mysql_fetch_array($status);
                  echo"
                  <td style='text-align:right'>".format_rupiah($s[jumlah])."</td>
                  <td><b>";
                  $sisa = $s[jumlah] - $r[grand_total];
                  if ($s[jumlah] == $r[grand_total]){
                    echo "Lunas";
                  }
                  else if ($s[jumlah] >= $r[grand_total]){
                   echo "Lunas <br> </b><small><i>kelebihan</i></small><br>";
                   echo format_rupiah($sisa);
                   echo "<b>";
                  }
                  else{
                    echo "Belum Lunas <br> </b><small><i>kurang</i></small><br>";
                   echo format_rupiah($sisa);
                   echo "<b>";
                  }
                              echo" </b></td>
                  <td>
                  <a href='?module=omsetsales&act=detaili&id=$r[id_sales_order]'class='btn btn-success'  title='detail' target='_blank'><span class='glyphicon glyphicon-calendar' aria-hidden='true'></span></a>
                  </td>";
                 
                  echo "
                </tr>"; 
                $no++;
                }
            echo "
        </tbody>
      </table>
    </div>";

  }else{
    $tglawal  = $_GET['tglawal'];
    $tglakhir = $_GET['tglakhir'];
    $id       = $_GET['id'];
    $query1 = ("SELECT *, sum(tsi.grand_total) as grand_total1 FROM trans_sales_invoice tsi LEFT JOIN trans_lkb lkb ON tsi.id_lkb = lkb.id_lkb left join trans_sales_order tso on lkb.id_sales_order = tso.id_sales_order left join sales sls on tso.id_sales = sls.id_sales where tsi.tgl BETWEEN  '". $tglawal."' AND  '".$tglakhir."' AND sls.id_sales = '$id' group by sls.nama_sales");
  $tampil = mysql_query($query1);
  $row=mysql_fetch_array($tampil);

  $status = mysql_query("SELECT CASE WHEN tbd.bukti_bayarjual LIKE 'BKM%' THEN (sum(tbd.nominal_alokasi_detail_jual)) END AS BKM, CASE WHEN tbd.bukti_bayarjual LIKE 'BGM%' THEN (sum(tbd.nominal_alokasi_detail_jual)) END AS BGM, CASE WHEN tbd.bukti_bayarjual LIKE 'BBM%' THEN (sum(tbd.nominal_alokasi_detail_jual)) END AS BBM FROM trans_bayarjual_detail tbd JOIN trans_sales_invoice tsi ON tbd.nota_invoice = tsi.id_invoice JOIN trans_lkb lkb ON tsi.id_lkb = lkb.id_lkb left join trans_sales_order tso on lkb.id_sales_order = tso.id_sales_order JOIN sales sls ON tso.id_sales = sls.id_sales WHERE tbd.is_void='0' and tso.id_sales='$id' AND tsi.tgl BETWEEN  '". $tglawal."' AND  '".$tglakhir."' GROUP BY sls.id_sales");
    $s=mysql_fetch_array($status);
    $jumlah = ($s[BKM]+$s[BGM]+$s[BBM]);
    echo "
    <div class='row'>
          <div class='col-md-6'>
                <h2>Modul Omset Sales</h2>
                 <p class='deskripsi'>Nama Sales         : $row[nama_sales]</p>
                 <p class='deskripsi'>Tanggal Laporan    :"; if(isset($tglakhir)){  echo "$tglawal sampai $tglakhir";} echo" </p>
                 <p class='deskripsi'>Total Penjualan    : ".format_rupiah($row[grand_total1])."</p>
                 <p class='deskripsi'>Penjualan Terbayar : ".format_rupiah($jumlah)."</p>
                 <br>
           </div>
    </div>
    <div class='table-responsive'>
      <table class='table table-bordered'>
              <thead>
          <tr style='background-color:#F5F5F5;'>
            <th class='number' >No</th>
            <th>Tanggal</th>
            <th>Nomor Invoice</th>
            <th>Nomor Nota</th>
            <th>Nama Customer</th>
            <th>Total Penjualan</th>
            <th>Total Pembayaran</th>
            <th>Sisa Pembayaran</th>
            <th>Aksi</th>
          </tr>
                </thead>
                <tbody>";
                /*$tampil = mysql_query("SELECT * FROM trans_sales_invoice tsi left join trans_sales_order tso on tsi.id_sales_order = tso.id_sales_order left join sales sls on tso.id_sales = sls.id_sales where tsi.tgl BETWEEN  '". $tglawal."' AND  '".$tglakhir."' AND tso.id_sales = '$_GET[id]' group by sls.nama_sales");*/

                  $tampil = mysql_query("SELECT *,tsi.no_nota, tsi.grand_total FROM trans_sales_invoice tsi left JOIN trans_lkb lkb ON tsi.id_lkb = lkb.id_lkb left join trans_sales_order tso on lkb.id_sales_order = tso.id_sales_order JOIN customer ctr ON tsi.id_customer = ctr.id_customer where tsi.tgl BETWEEN  '". $tglawal."' AND  '".$tglakhir."' AND tso.id_sales = '$_GET[id]' order by tsi.id_sales_order");
                  $no = 1;
                  while ($r=mysql_fetch_array($tampil)){
                  echo "
                <tr>
                  <td>$no</td>
                  <td>$r[tgl]</td>
                  <td>$r[id_invoice]</td>
                  <td>$r[no_nota]</td>
                  <td>$r[nama_customer]</td>
                  <td style='text-align:right'>".format_rupiah($r[grand_total])."</td>";
                  $status = mysql_query("SELECT  sum(tbd.nominal_alokasi_detail_jual)  as jumlah, sum(tbh.status_giro_jual) as status_giro ,sum(tbh.giro_ditolak_jual) as giro_ditolak  from trans_bayarjual_detail tbd  LEFT JOIN trans_bayarjual_header tbh ON(tbd.bukti_bayarjual=tbh.bukti_bayarjual) where tbd.is_void='0' and tbd.nota_invoice='$r[id_invoice]' order by tbd.id_bayarjual_detail desc");
                    $s=mysql_fetch_array($status);
                  echo"
                  <td style='text-align:right'>".format_rupiah($s[jumlah])."</td>
                  <td><b>";
                  $sisa = $s[jumlah] - $r[grand_total];
                  if ($s[jumlah] == $r[grand_total]){
                    echo "Lunas";
                  }
                  else if ($s[jumlah] >= $r[grand_total]){
                   echo "Lunas <br> </b><small><i>kelebihan</i></small><br>";
                   echo format_rupiah($sisa);
                   echo "<b>";
                  }
                  else{
                    echo "Belum Lunas <br> </b><small><i>kurang</i></small><br>";
                   echo format_rupiah($sisa);
                   echo "<b>";
                  }
                              echo" </b></td>
                  <td>
                  <a href='?module=omsetsales&act=detaili&id=$r[id_sales_order]'class='btn btn-success'  title='detail' target='_blank'><span class='glyphicon glyphicon-calendar' aria-hidden='true'></span></a>
                  </td>";
                 
                  echo "
                </tr>"; 
                $no++;
                }
            echo "
        </tbody>
      </table>
    </div>";
  }   
    break;

  case 'detaili':
  $tampil = mysql_query("SELECT *,sum(grand_total) as grand FROM trans_sales_invoice  tsi left JOIN trans_lkb lkb ON tsi.id_lkb = lkb.id_lkb where lkb.id_sales_order = '$_GET[id]'");
  $row=mysql_fetch_array($tampil);
    echo "
    <div class='row'>
          <div class='col-md-6'>
                <h2>Modul Omset Sales</h2>
                 <p class='deskripsi'>Nomor Nota : $row[no_nota]</p>
                 <p class='deskripsi'>Total : ".format_rupiah($row[grand])."</p>
                 <br>
           </div>
    </div>
    <div class='table-responsive'>
      <table class='table table-bordered'>
              <thead>
          <tr style='background-color:#F5F5F5;'>
            <th class='number' >No</th>
            <th>Nama Barang</th>
            <th>jumlah</th>
            <th>Satuan</th>
            <th>Harga</th>
            <th>Total</th>
          </tr>
                </thead>
                <tbody>";
                  $tampil = mysql_query("SELECT * FROM trans_sales_invoice_detail tsid left join trans_sales_invoice tsi on tsid.id_invoice = tsi.id_invoice left join barang b on tsid.id_barang = b.id_barang JOIN trans_lkb lkb ON tsi.id_lkb = lkb.id_lkb left join trans_sales_order tso on lkb.id_sales_order = tso.id_sales_order left join sales sls on tso.id_sales = sls.id_sales where tso.id_sales_order = '$_GET[id]' order by tsid.id_invoice");
                  $no = 1;
                  while ($r=mysql_fetch_array($tampil)){

                  echo "
                <tr>
                  <td>$no</td>
                  <td>$r[nama_barang]</td>
                  <td>$r[qty_si]</td>
                  <td>$r[qty_si_satuan]</td>
                  <td style='text-align:right'>".format_rupiah($r[harga_si])."</td>
                  <td style='text-align:right'>".format_rupiah($r[total])."</td>";
                 
                  echo "
                </tr>"; 
                $no++;
                /*echo "
                <tr>
                  <td></td>
                  <td></td>
                  <td><b>TOTAL<b></td>
                  <tdstyle='text-align:right'>".format_rupiah($r[TOTAL])."</td>
                </tr>
                ";*/
                }
                  echo "
                <tr>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td></td>
                  <td><b>TOTAL</b></td>
                  <td style='text-align:right'><b>".format_rupiah($row[grand])."</b></td>";
                 
                  echo "
                </tr>
        </tbody>
      </table>
    </div>";
    break;
  }
}
?>
<script type="text/javascript">
    $(document).ready(function (){
          $( "#tgl_awal" ).datepicker({
                dateFormat:"yy-mm-dd",
                changeMonth:true,
                changeYear:true
            });
      });
    $(document).ready(function (){
          $( "#tgl_akhir" ).datepicker({
                dateFormat:"yy-mm-dd",
                changeMonth:true,
                changeYear:true
            });
      });
    $(document).ready(function () {
                $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
                {
                    return {
                        "iStart": oSettings._iDisplayStart,
                        "iEnd": oSettings.fnDisplayEnd(),
                        "iLength": oSettings._iDisplayLength,
                        "iTotal": oSettings.fnRecordsTotal(),
                        "iFilteredTotal": oSettings.fnRecordsDisplay(),
                        "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                        "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
                    };
                };
 
                var t = $('#omm').DataTable({
                    "columns": [
                        { "searchable": false },
                        null,
                        null,
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false }
                      ],
                    "iDisplayLength": 20,
                       "aLengthMenu": [ [20, 50,100],[20,50,100]],
                      "pagingType" : "simple_numbers",
                      "language": {
                            "decimal": ",",
                            "thousands": "."
                          },
                    "processing": true,
                    "serverSide": true,
                    "ajax": "modul/omsetsales/load-data.php",
                    "order": [[1, 'asc']],
                    "rowCallback": function (row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                        $('td:eq(0)', row).html(index);
                    }
                });
            });
</script>