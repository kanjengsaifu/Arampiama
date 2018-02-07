<?php
 include "../../config/koneksi.php";
 include "../../lib/input.php";
  if(isset($_GET['id_akun'])){
    $id_akun = $_GET['id_akun'];
    $aksi ="modul/setting_akun/aksi_setting_akun.php";
    $edit = mysql_query("SELECT * FROM setting_akun WHERE id='$id_akun'");
    $v    = mysql_fetch_array($edit);
    echo"
    <form method='post' action='$aksi?module=setting_akun&act=update'>
    <table class='table table-striped'>
    <input type='hidden' id='id' name='id' value='$id_akun'>";
    echo checkbox('akun_kas_perkiraan',' where is_void= 0 order by kode_akun_header ','id_akunkasperkiraan','nama_akunkasperkiraan',$v['akses']);
   echo "
 
            
          </table>
          <div class='modal-footer'>
            <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
            <input class='btn btn-sm btn-success' type=submit value=Simpan>
          </div>
  </tr>
      </table>
      </from>";
      ;
    }
  ?>
