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
 $_ck = (array_search("6",$_SESSION['lvl'], true));
  if ($_ck==''){
echo "Modul tidak boleh diakses anda";
}else{
$aksi="modul/pencairangiro/aksi_pencairangiro.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
switch($_GET['act']){
  // Tampil Modul
  default:
  echo "<h2>Master user</h2>
 
          <div class='table-responsive'>
    <table id='user' class='table table-hover'>
    <thead>
    <tr style='background-color:#F5F5F5;'>
      <th>No</th>
      <th>Kode Pembayaran</th>
      <th>Nama Supplier</th>
      <th>Jumlah Pembayaran</th>
      <th>Jatuh Tempo</th>
      <th>Rekening Perusahaan</th>
      <th>Aksi</th>
    </tr></thead><tbody>";
  $tampil=mysql_query("SELECT *,m.ac as AN,t.id as id_pembayaran FROM trans_pembayaran t,supplier s, master_bank m WHERE m.id=t.id_masterbank and  t.id_supplier=s.id_supplier and t.is_void='0' and status_giro='1' and giro_ditolak='0' ");
      $no=1;
      while ($r=mysql_fetch_array($tampil)){
echo "
<tr><td>$no</td>
   <td><span>$r[id_pembayaran]</span></td>
            <td>$r[nama_supplier]</td>
             <td>$r[jumlah_pembayaran]</td>
              <td>$r[jatuh_tempo]</td>
               <td>$r[AN]</td>
  <td>
<!-- Tombol Untuk menampilkan modal -->
<div class='tombolpay' style='float:right;padding-right: 15px;padding-left: 15px;'> <a class='btn btn-success' data-toggle='modal' data-target='#myModal' > Pay 
      <span class='glyphicon glyphicon-usd' aria-hidden='true'></span></a>
      </div>
</button>
  <form method='post' action='$aksi?module=pencairangiro&act=input'>
<!-- Dialog Modal -->
<div class='modal fade' id='myModal' tabindex='-1' role='dialog' aria-labelledby='myModalLabel' aria-hidden='true'>
  <div class='modal-dialog'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>
        <h4 class='modal-title' id='myModalLabel'>Modal Judul</h4>
      </div>
      <div class='modal-body'>
       <table class='table table-hover'>
  <input type='hidden' name='id_pembayaran' value='$r[id_pembayaran]'>
       <tr><td>Tanggal</td><td><input name='tgl' class='datepicker'></td></tr>
       <tr><td>Proses Giro</td><td> <form>
      <input type='radio' name='status' value='status_giro=' checked> Dicairan
      <input type='radio' name='status' value='giro_ditolak='> Ditolak
      </form> </td></tr>
        <tr><td>Keterangan</td><td><textarea name='ket'></textarea></td></tr>";


     
      echo"</table></div>
      <div class='modal-footer'>
       <button type='button' class'btn btn-default' data-dismiss='modal'>Close</button>
          <button class='btn btn-success'  type='submit'  name='save' value='Save' >Save </button>
      </div>
    </div>
  </div>
</div>
</form>
  </td>
</tr>"; 
$no++;}
  echo "
  </tbody>
    </table>
  </div>
";
    break;
  }
}
}

?>
<script type="text/javascript">
   $( function() {
    $( ".datepicker" ).datepicker({
        dateFormat:"yy-mm-dd",
      changeMonth:true,
    changeYear:true}
      );
  } );

</script>
