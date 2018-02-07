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
$aksi="modul/jenispembayaran/jenispembayaran.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">
<div class="body" id="jenispembayaran">';

class jenispembayaran {
public function display() {
   echo "<h2>Modul Jenis pembayaran</h2>
   <div class='row'>
              <div id='jenispembayaran1'>";
              echo $this -> aksiact(null, null, null, 'aksi&act1=input', 'simpan', null);
              echo "
              </div>
   </div>

    <div class='table-responsive'>
    <table id='jenispembayaran' class='table table-hover'>
    <thead>
    <tr style='background-color:#F5F5F5;'>
     <th id='tablenumber'>No</th>
      <th>Jenis Pembayaran</th>
      <th>Keterangan</th>
     <th>Is Void</th>
       <th>Aksi</th>
    </tr>
    </thead>
    <tbody>";
   $querytable = "SELECT * FROM jenis_pembayaran";
  $tbl = mysql_query($querytable);
  $no = 1;
  while($a=mysql_fetch_array($tbl)){
    echo "
          <tr bgcolor='#F6F29B'>
                <td>$no</td>
                <td>$a[jenis_pembayaran]</td>
                <td>$a[ket]</td>
                <td>$a[is_void]</td>
                <td>
        <a href='?module=jenispembayaran&act=aksi&act1=hapus&id=$a[id]' class='btn btn-danger' title='hapus'><span class='glyphicon glyphicon-trash'></span></a>
                </td>
          </tr>
          <tr id='editjenis' >
          <td colspan='5'>";
             echo $this -> aksiact($a[jenis_pembayaran], $a[ket], $a[is_void], 'aksi&act1=update&id='.$a[id], 'Update', '<tr><td>id</td><td><input type="text" name="id" value='.$a[id].'></td></tr>');
              echo "
          </td>
          </tr>
          ";
          $no++;
        }
          echo"
    </tbody>    
    </table>
  </div>";
}

public function delete() {
  $query = "DELETE FROM stok  WHERE id_stok='$_GET[id]'";
  $del = mysql_query($query);
  $page = "media.php?module=stock";
echo '<meta http-equiv="Refresh" content="0;' . $page . '">';

echo '<script type="text/javascript">
function myFunction() {
    location.reload();
}
</script>';
  }
public function aksi() {
  $module=$_GET['module'];
  $act1=$_GET['act1'];

if ($module=='jenispembayaran' AND $act1=='hapus'){
    mysql_query("DELETE FROM jenis_pembayaran WHERE id='$_GET[id]'");
}
elseif ($module=='jenispembayaran' AND $act1=='input'){
mysql_query("INSERT INTO jenis_pembayaran(
          jenis_pembayaran,
          ket,
          is_void)
          VALUES(
          '$_POST[jenis]',
          '$_POST[ket]',
          '$_POST[is_void]'
          )"
          );
    }
elseif ($module=='jenispembayaran' AND $act1=='update'){
mysql_query("UPDATE jenis_pembayaran SET 
                              id = '$_POST[id]',
                              jenis_pembayaran = '$_POST[jenis]',
                              ket = '$_POST[ket]',
                              is_void = '$_POST[is_void]'
                                  where id='$_GET[id]'");
}  
echo '
<script type="text/javascript">
$(document).ready(
  function () {
    window.history.back();
  }
  );
</script>';
  }

  public function aksiact($valuejenis, $valueket, $valueisvois,$action, $jenis, $inputid) {
  echo "
  <form method='post' action='$aksi?module=jenispembayaran&act=$action'>
     <div class='table-responsive'>
    <table class='table table-hover'>
    $inputid
    <tr>
          <td>Nama Jenis Pembayaran </td> 
          <td><input type='text' name='jenis' value='$valuejenis'/></td>
    </tr>
    <tr>
          <td>Keterangan</td>
          <td><input type='text' name='ket' value='$valueket'/></td>
    </tr>
    <tr>
          <td>Is Void</td>
          <td><input type='text' name='is_void' value='$valueisvois' /></td>
    </tr>
    <tr>
    <td colspan='2'>
        <div class='form-group'>
                            <input class='btn btn-success' type=submit value='$jenis'>           
        </td>
    </tr>
    </table>
    </div>
    </form>";
  }

}

$so = new jenispembayaran();
switch($_GET['act']){
            case 'hapus':
              $so -> delete();
              break;
             case 'aksi':
              $so -> aksi();
              break;
            default:
                $so-> display();
                break;
        }

echo "</div>";
}
?>