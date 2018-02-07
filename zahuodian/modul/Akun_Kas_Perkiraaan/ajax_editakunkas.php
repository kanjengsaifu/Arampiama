<?php
 include "../../config/koneksi.php";
 include "../../lib/input.php";
  if(isset($_GET['id_akun'])){
    $id_akun = $_GET['id_akun'];
    $aksi ="modul/akunkasperkiraan/aksi_akunkasperkiraan.php";
    $edit = mysql_query("SELECT * FROM akun_kas_perkiraan WHERE id_akunkasperkiraan='$id_akun'");
    $v    = mysql_fetch_array($edit);
    echo"
    <form method='post' action='$aksi?module=akunkasperkiraan&act=update'>
    <table class='table table-hover'>
  <tr>
    <tr>
                  <td><b>Kode Header</b></td>
                  <td>";echo '<select class="form-control" id="headeredit" name="headeredit" required>';
                $select = mysql_query("SELECT * FROM akun_header WHERE is_void=0 order by kode_akun_header asc");
                        while($row=mysql_fetch_array($select)){
                          $select2= mysql_query("SELECT max(kode_akunkasperkiraan) as akunmax FROM akun_kas_perkiraan WHERE is_void=0  and kode_akun_header ='$row[kode_akun_header]'  ");
                          $hasil = mysql_fetch_array($select2);
                          if ($v['kode_akun_header']==$row['kode_akun_header']) {
                              echo "<option value='".$row['kode_akun_header'].'#'.$hasil['akunmax']."' selected>$row[nama_akun]</option>";
                            }else{
                              echo "<option value='".$row['kode_akun_header'].'#'.$hasil['akunmax']."'>$row[nama_akun]</option>";
                            }
                             
                             }echo "</select></td>
                </tr>
                <tr>
                  <td><b>Kode Akun Kategori</b></td> 
                  <td><input value='$v[kode_akun]' class='form-control' type='text' id='kodeedit' name='kodeedit' readonly /></td>
              </tr>
                <tr>
                  <td><b>Nama Akun  Kategori</b></td> 
                  <td><input value='$v[nama_akunkasperkiraan]' class='form-control' type='text' name='akun'/></td>
                </tr>
               <tr>
                  <td><b>Keterangan</b></td> 
                  <td><input value='$v[ket]' class='form-control' type='text' name='ket'/></td>
              </tr>            
          </table>
          <div class='modal-footer'>
            <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
            <input class='btn btn-sm btn-success' type=submit value=Simpan>
          </div>
  </tr>
      </table>
      </from>";
      echo '  <script type="text/javascript">
      kodex("headeredit","kodeedit",'$v['kode_akun_header']');
  </script>';
    }
  ?>
