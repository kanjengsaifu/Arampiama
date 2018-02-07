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
$aksi="modul/user/aksi_user.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
switch($_GET['act']){
  // Tampil Modul
  default:
  echo "<h2>Master user</h2>
      <a href='?module=user&act=tambahmenu' class='btn btn-primary' >Tambah<span class='glyphicon glyphicon-plus' aria-hidden='true'></span></a>
  <div class='modal fade' id='myModal' role='dialog'>
    <div class='modal-dialog'>
    

      <div class='modal-content'>
        <div class='modal-header'>
          <button type='button' class='close' data-dismiss='modal'>&times;</button>
          <h4 class='modal-title'>Modal Header</h4>
        </div>

        <div class='modal-body'>
          <p>Some text in the modal.</p>
        </div>

        <div class='modal-footer'>
          <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
        </div>
      </div>
      
    </div>
  </div>


      <br /><br />
          
          <div class='table-responsive'>
    <table id='user' class='table table-hover'>
    <thead>
    <tr style='background-color:#F5F5F5;'>
      <th>No</th>
      <th>Nama User</th>
      <th>Aksi</th>
    </tr></thead><tbody>";
  $tampil=mysql_query("SELECT * FROM users WHERE is_void='0' ");
      while ($r=mysql_fetch_array($tampil)){
echo "
<tr>
   <td><span>$r[id_users]</span></td>
            <td>$r[username]</td>
  <td>
    <a href='?module=user&act=editmenu&id=$r[id_users]' class='btn btn-warning' title='Edit'><span class='glyphicon glyphicon-edit'></span></a>
    <a href='$aksi?module=user&act=hapus&id=$r[id_users]' class='btn btn-danger' title='Delete'><span class='glyphicon glyphicon-trash'></span></a>
  </td>
</tr>"; }
  echo "
  </tbody>
    </table>
  </div>";
    break;
case "tambahmenu":
    echo "<h2>Master user</h2>
    <form method='post' action='$aksi?module=user&act=input' enctype='multipart/form-data'>
     <div class='table-responsive'>
      <table class='table table-hover'>
  <tr>
    <td>Nama</td>
    <td><input type='text' name='username'/></td>
  </tr>
  <tr>
    <td>Password</td>
    <td><input type='password' name='password'  /></td>
  </tr>
</table>

      <table class='table table-hover'>";
      $tampil=mysql_query("SELECT * FROM mainmenu WHERE aktif='Y' ");
      while ($r=mysql_fetch_array($tampil)){
echo "
<tr>
   <td><span>$r[id_mainmenu]</span></td>
   <td><input type='checkbox' name=id_mainmenu_array[] value='$r[id_mainmenu]'  />   $r[nama_mainmenu]</td>
</tr>"; }
echo "
</table>
<td><div class='form-group'>
                            <input class='btn btn-success' type=submit value=Simpan>
                            <input class='btn btn-warning' type=button value=Batal onclick=self.history.back()>
</div>
</form>";
     break;
  case "editmenu":
    $edit = mysql_query("SELECT * FROM users WHERE id_users='$_GET[id]'");
    $r    = mysql_fetch_array($edit);

    echo "<h2>Edit Modul</h2>

 <form method='post' action='$aksi?module=user&act=update'>
  <td><input class='hidden' type='text'  name='id' value='$r[id_users]'/></td>
     <div class='table-responsive'>
      <table class='table table-hover'>
  <tr>
    <td>Nama</td>
    <td><input type='text' name='username' value='$r[username]'/></td>
  </tr>
  <tr>
    <td>Password</td>
    <td><input type='password' name='password'/></td>
  </tr>
</table>

      <table align='left' class='table table-hover'>";
      $tampil=mysql_query("SELECT * FROM mainmenu WHERE aktif='Y' ");
      $d = GetCheckboxes('mainmenu', 'id_mainmenu', 'nama_mainmenu', $r[level]);
      
echo " $d ";


echo "
</table>
<td><div class='form-group'>
                            <input class='btn btn-success' type=submit value=Simpan>
                            <input class='btn btn-warning' type=button value=Batal onclick=self.history.back()>
      </div>
      </from>";
    break;
  }
}
}
function GetCheckboxes($table, $key, $Label, $Nilai='') {
  $s = "select * from $table order by id_mainmenu";
  $d = mysql_query($s);
  $_arrNilai = explode(',', $Nilai);
  $str = '';
  echo "<tr>";
  while ($w = mysql_fetch_array($d)) {
    $_ck = (array_search($w[$key], $_arrNilai) === false)? '' : 'checked';
    $str .= "<tr>
    <td>$w[$key]</td>
              <td align='left'><input type=checkbox name='".$key."_array[]' value='$w[$key]' $_ck>$w[$Label] </td></tr>";
  }
  echo "</tr>";
  return $str;
}
?>
<script type="text/javascript">
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
 
                var t =   $('#user').DataTable({
                      "columns": [
                        { "searchable": false },
                        null,
                        { "searchable": false },
                      ],
                    "iDisplayLength": 20,
                       "aLengthMenu": [ [20, 50,100],[20,50,100]],
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
