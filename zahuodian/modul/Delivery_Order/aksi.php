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
      if ($module=='laporankeluarbarang' AND $act=='hapus'){
        $select = mysql_query("SELECT d.*, h.id AS head, h.id_lkb, h.is_void FROM trans_lkb h, trans_lkb_detail d  WHERE h.id_lkb = d.id_lkb and  h.id = '$_GET[id]'");
        while ($detail = mysql_fetch_array($select)) {
          $melati = "UPDATE stok SET stok_sekarang = (stok_sekarang+$detail[qty_diterima_convert]) WHERE id_barang = '$detail[id_barang]' AND id_gudang = '$detail[id_gudang]'";
          input_only_log($melati, $module);
          $id_lkb=$detail[id_lkb];
          $id_sales_order=$detail[id_sales_order];
        }
        input_only_log("DELETE FROM `trans_lkb` WHERE `id_lkb`='$id_lkb'");
        input_only_log("DELETE FROM `trans_lkb_detail` WHERE `id_lkb`='$id_lkb'");
        $select = mysql_query("SELECT * FROM `trans_lkb` a,trans_sales_order b WHERE a.`id_sales_order`=b.`id_sales_order` and a.id_sales_order= '$id_sales_order'");
        if (mysql_num_rows($select)>=1) {
          $query ="UPDATE trans_sales_order SET status_trans = '1'  WHERE  id_sales_order='$id_sales_order'";
          input_data($query,$module);
        }else{
          $query ="UPDATE trans_sales_order SET status_trans = '0'  WHERE  id_sales_order='$id_sales_order'";
          input_data($query,$module);
        }
      }



      //*********************************** Input menu
      elseif ($module=='laporankeluarbarang' AND $act=='input'){
        $id_lkb=kode_surat('LKB','trans_lkb','id_lkb','id');
      $query="INSERT INTO trans_lkb(
                                                        id_lkb,
                                                        id_sales_order,
                                                        id_customer,
                                                        tgl_lkb,
                                                        no_nota_customer,
                                                        no_expedisi,
                                                        user_update,
                                                        tgl_update)
                                                        VALUES(
                                                          '$id_lkb',
                                                          '$_POST[no_so]',
                                                          '$_POST[customer]',
                                                          '$_POST[tgl_lkb]',
                                                          '$_POST[no_nota_customer]',
                                                             '$_POST[no_expedisi]',
                                                          '$_SESSION[namauser]',
                                                          now()
                                                        )";
      input_only_log($query, $module);



        $itemCount = count($_POST["id_lkb"]);
          $itemValues=0; 
          $query = "INSERT INTO trans_lkb_detail(
                                                        id_lkb,
                                                        id_sales_order,
                                                        id_barang,
                                                        id_gudang,
                                                        qty,
                                                        qty_diterima,
                                                        qty_convert,
                                                        qty_diterima_convert,
                                                        qty_satuan,
                                                        qty_diterima_satuan,
                                                        kode_barang_so,
                                                        user,
                                                        tgl_update)
                                                      VALUES ";
          $queryValue = "";
          $counter = 0;
          for($i=0;$i<$itemCount;$i++) {
                      if(!empty($id_lkb) || !empty($_POST["id_barang"][$i]) || !empty($_POST["lbr_gudang"][$i]) || !empty($_POST["jumlah_diminta"][$i])  || !empty($_POST["selisih"][$i])) {
                                            $itemValues++;
                                            if($queryValue!="") {
                                                      $queryValue .= ",";
                                            }

                                            $stok_sekarang=("SELECT stok_sekarang from stok where id_barang='".$_POST["id_barang"][$i]."' and id_gudang = '" . $_POST["gudang_lkb"][$i]."'");
                                            $stok_sekarang=mysql_query($stok_sekarang);
                                            $stok_sekarang=mysql_fetch_array($stok_sekarang);
                                            $jum_sat1=explode('-', $_POST["jenis_satuan"][$i]);
                                            $jum_sat= $jum_sat1[2] * $_POST["selisih"][$i] ;
                                            $stok_sekarang=$stok_sekarang[0]-$jum_sat;
                                            $qupdate=("UPDATE stok set stok_sekarang=$stok_sekarang where id_barang='".$_POST["id_barang"][$i]."' and id_gudang = '" . $_POST["gudang_lkb"][$i]."'");
                                          
                                          input_only_log($qupdate,$module);
                                          $qty_so_convert= $_POST["jumlah_diminta"][$i]*$_POST["qty_convert"][$i] ;
                                            $queryValue .= "('" . $id_lkb . "', '" . $_POST["no_so"] . "', '" . $_POST["id_barang"][$i] . "', '" . $_POST["gudang_lkb"][$i] . "', '" . $_POST["jumlah_diminta"][$i] . "', '" . $_POST["selisih"][$i] . "', '" .$qty_so_convert. "', '" . $jum_sat . "', '" . $_POST["qty_satuan"][$i]. "', '" . $jum_sat1[1] . "', '" . $_POST["id_lkb" ] [$i] . "', '" .$_SESSION["namauser"]. "', now())";
                                               echo $qty_so_convert.'?'.$jum_sat.'='.$jum_sat1[2].'x'.$_POST["selisih"][$i];
                                            if (($qty_so_convert >$jum_sat) ) {
                                            $counter=$counter+1;
                                            }

                        }
          }
                $sql = $query.$queryValue;
                if ($counter==0) {
                $query2 = ("UPDATE trans_sales_order SET status_trans = '2'  WHERE  id_sales_order='$_POST[no_so]'");
                input_only_log($query2,$module);
                }else{
                 $query2 = ("UPDATE trans_sales_order SET status_trans = '1'  WHERE  id_sales_order='$_POST[no_so]'");
                input_only_log($query2,$module);
                }
                if($itemValues!=0) {
               input_data($sql,$module);
                }
                $sel=mysql_query("SELECT * FROM trans_lkb WHERE id_lkb='$id_lkb '");
                $ect=mysql_fetch_array($sel);
                $id_header=$ect[id];
                echo "<script>window.open('cetak.php?id=$id_header')</script>";
        }

          //###### UPDATE 
      elseif ($module=='laporankeluarbarang' AND $act=='update'){
        $query = "UPDATE trans_lkb SET 
                                                        id_sales_order = '$_POST[no_so]',
                                                        id_customer = '$_POST[customer]',
                                                        tgl_lkb = '$_POST[tgl_lkb]',
                                                        no_nota_customer = '$_POST[no_nota_customer]',
                                                        no_expedisi = '$_POST[no_expedisi]',
                                                        user_update ='$_SESSION[namauser]',
                                                        tgl_update = now()
                                                        WHERE  id_lkb = '$_POST[no_lkb]' ";
      input_only_log($query,$module);

        $itemCount = count($_POST["id_lkb"]);
          $itemValues=0;
          $query = "INSERT INTO trans_lkb_detail(
                                                        id,
                                                        id_lkb,
                                                        id_barang,
                                                        id_gudang,
                                                        qty_diterima,
                                                        qty_diterima_convert,
                                                        qty_diterima_satuan)
                                                      VALUES ";
          $queryValue = "";
          for($i=0;$i<$itemCount;$i++) {
                  if(!empty($_POST["id_lkb"][$i]) || !empty($_POST["id_barang"][$i]) || !empty($_POST["lbr_gudang"][$i]) || !empty($_POST["jumlah_diminta"][$i])  || !empty($_POST["selisih"][$i])) {
                            $itemValues++;

                                  $qty = explode('-',$_POST["jumlah_diminta"][$i] );
                                  $jum_sat1=explode('-', $_POST["jenis_satuan"][$i]);
                                  $jum_sat= $jum_sat1[2] * $_POST["selisih"][$i] ;
                                  echo $jum_sat."<br>";
                                         $stok_sekarang=("SELECT stok_sekarang from stok where id_barang='".$_POST["id_barang"][$i]."' and id_gudang = '" . $_POST["gudang_lkb"][$i]."'");
                                           $stok_sekarang=mysql_query($stok_sekarang);
                                            $stok_sekarang=mysql_fetch_array($stok_sekarang);
                                            $stok_sekarang=$stok_sekarang[0]-$jum_sat+$_POST["selisih2"][$i];
                                            $qupdate=("UPDATE stok set stok_sekarang=$stok_sekarang where id_barang='".$_POST["id_barang"][$i]."' and id_gudang = '" . $_POST["gudang_lkb"][$i]."'");
                                          input_only_log($qupdate,$module);

                                if($queryValue!="") {
                                     $queryValue .= ",( '" . $_POST["id_lkb"][$i] . "','" . $_POST["no_lkb"] . "', '" . $_POST["id_barang"][$i] . "', '" . $_POST["gudang_lkb"][$i] . "', '" . $_POST["selisih"][$i] ."', '" . $jum_sat ."', '" . $jum_sat1[1] ."')";
                                  } else {
                                     $queryValue .= "( '" . $_POST["id_lkb"][$i] . "','" . $_POST["no_lkb"] . "', '" . $_POST["id_barang"][$i] . "', '" . $_POST["gudang_lkb"][$i] . "', '" . $_POST["selisih"][$i] ."', '" . $jum_sat ."', '" . $jum_sat1[1] ."')";
                                  }
                          
                }
          }
           $queryEnd = "ON DUPLICATE KEY UPDATE id= VALUES(id), id_lkb = VALUES(id_lkb), id_barang = VALUES(id_barang), id_gudang = VALUES(id_gudang), qty_diterima = VALUES(qty_diterima), qty_diterima_convert = VALUES(qty_diterima_convert), qty_diterima_satuan = VALUES(qty_diterima_satuan)";
         $sql = $query.$queryValue.$queryEnd;
          if($itemValues!=0) {
              input_data($sql,$module);
          }       
          $cek1 = mysql_query("SELECT SUM(d.jumlah) AS pesan FROM trans_sales_order_detail d JOIN trans_sales_order h ON h.id_sales_order=d.id_sales_order WHERE h.is_void = 0 AND d.id_sales_order = '$_POST[no_so]'");
          $pesan = mysql_fetch_array($cek1);
          $cek2 = mysql_query("SELECT SUM(d.qty_diterima) AS terima FROM trans_lkb_detail d JOIN trans_lkb h ON h.id_lkb=d.id_lkb WHERE h.is_void = 0 AND d.id_sales_order = '$_POST[no_so]'");
          $terima = mysql_fetch_array($cek2);
          if ($terima['terima'] >= $pesan['pesan']) {
            $query2 = ("UPDATE trans_sales_order SET status_trans = '2'  WHERE  id_sales_order='$_POST[no_so]'");
            input_only_log($query2,$module);
          } else {
            $query2 = ("UPDATE trans_sales_order SET status_trans = '1'  WHERE  id_sales_order='$_POST[no_so]'");
            input_only_log($query2,$module);
          }
      }

      elseif ($module=='laporankeluarbarang' AND $act=='pengiriman'){
      $query="INSERT INTO trans_lkb(
                                                        id_lkb,
                                                        id_sales_order,
                                                        id_customer,
                                                        tgl_lkb,
                                                        no_nota_customer,
                                                        status_nota,
                                                        user_update,
                                                        tgl_update)
                                                        VALUES(
                                                          '$_POST[no_lkb]',
                                                          '$_POST[no_so]',
                                                          '$_POST[customer]',
                                                          '$_POST[tgl_lkb]',
                                                          '$_POST[no_nota_customer]','1',
                                                          '$_SESSION[namauser]',
                                                          now()
                                                        )";
      input_only_log($query);
      $query="UPDATE trans_sales_order SET status_lkb = '1'  WHERE  id_sales_order='$_POST[no_so]'";
      input_only_log($query);

        $itemCount = count($_POST["id_lkb"]);
        echo $itemCount;
          $itemValues=0;
          $query = "INSERT INTO trans_lkb_detail(
                                                        id_lkb,
                                                        id_sales_order,
                                                        id_barang,
                                                        id_gudang,
                                                        qty,
                                                        qty_diterima,
                                                        qty_convert,
                                                        qty_diterima_convert,
                                                        qty_satuan,
                                                        qty_diterima_satuan,
                                                        kode_barang_so,
                                                        user,
                                                        tgl_update)
                                                      VALUES ";
          $queryValue = "";
          for($i=0;$i<$itemCount;$i++) {
            if(!empty($_POST["id_lkb"]) || !empty($_POST["id_barang"][$i]) || !empty($_POST["lbr_gudang"][$i]) || !empty($_POST["jumlah_diminta"][$i])  || !empty($_POST["selisih"][$i])) {
              $itemValues++;
              if($queryValue!="") {
                $queryValue .= ",";
              }
             $stok_sekarang=("SELECT stok_sekarang from stok where id_barang='".$_POST["id_barang"][$i]."' and id_gudang = '" . $_POST["gudang_lkb"][$i]."'");
              $stok_sekarang=mysql_query($stok_sekarang);
              $stok_sekarang=mysql_fetch_array($stok_sekarang);

               $jum_sat1=explode('-', $_POST["jenis_satuan"][$i]);
                $qty = explode('-',$_POST["jumlah_diminta"][$i] );
                $jum_sat= $jum_sat1[2] * $_POST["selisih"][$i] ;

              $stok_sekarang=$stok_sekarang[0]-$jum_sat;
             
              $qupdate=("UPDATE stok set stok_sekarang=$stok_sekarang where id_barang='".$_POST["id_barang"][$i]."' and id_gudang = '" . $_POST["gudang_lkb"][$i]."'");
            input_only_log($qupdate,$module);
               $queryValue .= "('" . $_POST["no_lkb"] . "', '" . $_POST["no_so"] . "', '" . $_POST["id_barang"][$i] . "', '" . $_POST["gudang_lkb"][$i] . "', '" . $qty[0] . "', '" . $_POST["selisih"][$i] . "', '" . $_POST["qty_convert"][$i] . "', '" . $jum_sat . "', '" . $_POST["qty_satuan"][$i]. "', '" . $jum_sat1[1] . "', '" . $_POST["id_lkb"][$i] . "','" .$_SESSION["namauser"]. "', now())";
             
            }
          }
          $sql = $query.$queryValue;
          echo $sql;
          if($itemValues!=0) {
          input_data($sql,$module);
            
          }
       
        }
      }
?>