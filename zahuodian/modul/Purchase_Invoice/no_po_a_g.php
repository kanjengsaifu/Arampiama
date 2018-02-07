<?php
echo '<script type="text/javascript" src="http://code.jquery.com/jquery-1.7.2.min.js"></script>';
 include "koneksi.php";
   $tampil=mysql_query("SELECT * FROM `trans_pur_order` WHERE id_pur_order LIKE '%20161108%' order by id desc limit 1 ");
  $r    = mysql_fetch_array($tampil);
  $no = explode("/",$r['id_pur_order']);
  $a = $no[2]+1+100000 ;
  $C = "PO/". date("Ymd") ."/".substr($a,1);
  $b = "<input  value='$C' id='id_pur_order' disabled/>";
  echo $b;


