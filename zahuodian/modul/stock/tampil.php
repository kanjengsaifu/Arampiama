<?php
 include "../../config/koneksi.php";
  include "../../lib/input.php";
$kata=$_GET['kata'];
$gudang_aktif=$_GET['gudang_aktif'];
$no_alias= $_GET['no_alias'];
   $quer = mysql_query("SELECT * FROM barang where is_void='0' and nama_barang like '%$kata%' limit 10 ");
    $no =1;
    while ($c=mysql_fetch_array($quer)) {
    $query2 = "SELECT  $gudang_aktif FROM Stok s,gudang  g where s.id_gudang=g.id_gudang and id_barang='$c[id_barang]'";
    echo $query2;
    $sumall = mysql_fetch_array(mysql_query("SELECT  SUM(Stok_sekarang) as sumall FROM Stok s where id_barang='$c[id_barang]'"));
    $sum=0;
echo $query2;
$query3 = mysql_query($query2);
$r = mysql_fetch_array($query3);
echo "
<tr>
        <td>$no</td>
        <td>$c[nama_barang]</td>";
      for ($i=1; $i <=$no_alias ; $i++) { 
        $alias = "s".$i;
        $hasil=$r[$alias];
        if ( $i <=$no_alias-1){
        echo " <td>";
         convert_satuan($hasil,$c['kali5'],$c['kali4'],$c['kali3'],$c['kali2'],$c['kali1'],$c['satuan5'],$c['satuan4'],$c['satuan3'],$c['satuan2'],$c['satuan1']);
       "</td>";
        }
        else{
            echo " <td>";
            $var_ex=explode(',', $r[$alias]);
            echo $r[$alias];
            echo "</td>";
        }
     

      }
        echo "
        <td> ";
         convert_satuan($sumall['sumall'],$c['kali5'],$c['kali4'],$c['kali3'],$c['kali2'],$c['kali1'],$c['satuan5'],$c['satuan4'],$c['satuan3'],$c['satuan2'],$c['satuan1']);
        echo "</td><td>"; 
        echo $sumall['sumall']*$c['hpp'];
        echo "</td></tr>";
$no++;
}

?>
