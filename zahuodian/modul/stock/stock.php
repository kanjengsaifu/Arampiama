<?php
 include "config/koneksi.php";
 error_reporting(E_ALL ^ E_NOTICE);
 session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
  echo "
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{

  $_ck = (array_search("1",$_SESSION['lvl'], true))?'true':'false';
  if ($_ck==''){
echo "Modul tidak boleh diakses anda";
}else{
$aksi="modul/stok/aksi_stok.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
echo '<script src="modul/stok/jquery.dataTables.yadcf.js"></script>';
switch($_GET['act']){
  // Tampil Modul
  default:
   echo "<h2>Laporan Stock</h2>
   <div class='row'>
   <div class='col-md-6'>

   </div>
   <div class='col-md-6' id='search'>
   <b style='float:right;''>
Search Berdasarkan Nama Barang:   <input type='text' id='search_kode'><button id='search_btn'><span class='glyphicon glyphicon-search'></span></button></b>
</div>
</div>
          <div class='table-responsive' id='stock'>
    <table id='stockbarang' class='table table-hover'>
    <thead>
    <tr style='background-color:#F5F5F5;'>
     <th id='tablenumber'>No</th>
      <th>Nama Barang</th>";
      $query3 = mysql_query( " SELECT * FROM gudang WHERE status_gudang=0 and is_void=0");
      $gudang_aktif="";
      $no_alias=1;
while ( $p = mysql_fetch_array($query3))
{
  $gudang_aktif.= "GROUP_CONCAT(if(s.id_gudang = $p[id_gudang], Stok_sekarang, NULL)) AS s$no_alias,";
  echo"<th>$p[nama_gudang]</th>";
$no_alias++;
}
$gudang_aktif.='GROUP_CONCAT(if(g.status_gudang = 1 and Stok_sekarang <> 0,concat(g.nama_gudang," : ",Stok_sekarang," "), NULL)) AS s'.$no_alias ;
      echo"
      <input  type=hidden id='query_gudang' value='$gudang_aktif'>
       <input type=hidden id='no_alias' value='$no_alias'>

     <th>Gudang External</th>
       <th>Jumlah Barang</th>
        <th>Jumlah Dalam Rupiah</th>
    </tr>
    </thead>   
    <tbody id=tampil>";

echo "
    </tbody>
    </table>
                <div id='pagingtable' style='margin: auto;''>
</div>
  </div>";
  break;

}}}


?>
<style type="text/css">
  #stockbarang_filter{
    visibility: hidden;
  }
</style>
<script type="text/javascript">
function ser(){
   var kata= $('#search_kode').val();
    var no_alias= $('#no_alias').val();
    var gudang_aktif= $('#query_gudang').val();
    var dataString = 'kata='+kata+'&no_alias='+no_alias+'&gudang_aktif='+gudang_aktif;
    $.ajax({
      url: 'modul/stock/tampil.php',
   data: dataString,
   cache: false,
   success: function(r)
    {
       $("#tampil").html(r);
    }
    });
}
  $('#search_kode').keyup(function()
  {
   ser();
  })
</script>



