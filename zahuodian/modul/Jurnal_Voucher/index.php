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
 $_ck = (array_search("4",$_SESSION['lvl'], true));
  if ($_ck==''){
echo "Modul tidak boleh diakses anda";
}else{
$aksi="modul/jurnalvoucer/aksi_jurnalvoucer.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
switch($_GET['act']){

    // Tampil Modul
  default:
    echo "<h2>Jurnal Voucer</h2>
<div class='table-responsive'>

 <div class='panel-group' id='kategoricollapse'>
  <div class='panel'>
      <h4 class='panel-title'>
        <a class='btn btn-info' data-toggle='modal' data-target='#jurnal_voucer'> Tambah Jurnal Voucer</a>
      </h4>
    <div id='jurnal_voucer' class='modal fade' role='dialog'>
      <div class='modal-dialog'>
          <!-- Modal content-->
      <form method='post' action='$aksi?module=jurnalvoucer&act=voucer'>";
      date_default_timezone_set("Asia/Jakarta");
      $tgl = date("Y-m-d");
      $arrtgl=explode('-',$tgl);
      $tgl=($arrtgl[0]-2000)*100+($arrtgl[1]);
      $sql="SELECT MAX(kode_nota) jum FROM jurnal_umum WHERE kode_nota LIKE 'JV-$tgl%'";
      $jum_kode=mysql_fetch_array(mysql_query($sql));
      $sub_kode=substr($jum_kode['jum'], 8);
      $nourut=$sub_kode+1;
      if ($nourut<10)
        $strurut='0000'.$nourut;
      else if ($nourut<100)
        $strurut='000'.$nourut;
      else if ($nourut<1000)
        $strurut='00'.$nourut;
      else if ($nourut<10000)
        $strurut='0'.$nourut;
      else
        $strurut=$nourut;
      $nojurnal="JV-".$tgl."-".$strurut;
    echo "
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal'>&times;</button>
        <h4 class='modal-title'>Tambah Jurnal Voucer</h4>
      </div>
      <div class='modal-body'>
       <table class='table table-hover'>
       <thead>
       <th>Nama Akun</th>
       <th>Debet</th>
       <th>Kredit</th>
       </thead>
        <tr><td>
               ";
           echo '<select class="form-control" id="akun_d_voucer" name="akun_d_vaoucer" required>';
                $tampil=mysql_query("SELECT * FROM akun_kas_perkiraan WHERE is_void=0 order by id_akunkasperkiraan asc");
                           echo "<option value='' selected>- Pilih Akun -</option>";
                        while($w=mysql_fetch_array($tampil)){
                             echo "<option value=$w[id_akunkasperkiraan]>$w[nama_akunkasperkiraan]</option>";
                             }echo "</select>
                </td><td><input id='qty_saldo_d' name='qty_saldo_d' ></td><td><input readonly></td></tr>
                <tr><td>";
              echo '<select class="form-control" id="akun_k_voucer" name="akun_k_vaoucer" required>';
                $tampil=mysql_query("SELECT * FROM akun_kas_perkiraan WHERE is_void=0 order by id_akunkasperkiraan asc");
                           echo "<option value='' selected>- Pilih Akun -</option>";
                        while($w=mysql_fetch_array($tampil)){
                             echo "<option value=$w[id_akunkasperkiraan]>$w[nama_akunkasperkiraan]</option>";
                             }echo "</select>
                </td><td><input readonly></td><td><input id='qty_saldo_k' name='qty_saldo_k' readonly></td></tr>      
          </table>
          <input type=hidden id=nojurnal name=nojurnal value=$nojurnal>
      </div>
      <div class='modal-footer'>
        <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
        <input class='btn btn-sm btn-success' type=submit value=Simpan>
      </div>
    </div>
     </form> 
      </div>
    </div>

    <div id='edit_voucer' class='modal fade' role='dialog'>
      <div class='modal-dialog'>
          <!-- Modal content-->
      <form method='post' action='$aksi?module=jurnalvoucer&act=update'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal'>&times;</button>
        <h4 class='modal-title'>Edit Jurnal Voucer</h4>
      </div>
      <div class='modal-body'>
       <table class='table table-hover'>
       <thead>
       <th>Nama Akun</th>
       <th>Debet</th>
       <th>Kredit</th>
       </thead>
        <tr><td>
               ";
           echo '<select class="form-control" id="akun_d_voucer" name="akun_d_vaoucer" required>';
                $tampil=mysql_query("SELECT * FROM akun_kas_perkiraan WHERE is_void=0 order by id_akunkasperkiraan asc");
                           echo "<option value='' selected>- Pilih Akun -</option>";
                        while($w=mysql_fetch_array($tampil)){
                             echo "<option value=$w[id_akunkasperkiraan]>$w[nama_akunkasperkiraan]</option>";
                             }echo "</select>
                </td><td><input id='edt_saldo_d' name='edt_saldo_d' ></td><td><input readonly></td></tr>
                <tr><td>";
              echo '<select class="form-control" id="akun_k_voucer" name="akun_k_vaoucer" required>';
                $tampil=mysql_query("SELECT * FROM akun_kas_perkiraan WHERE is_void=0 order by id_akunkasperkiraan asc");
                           echo "<option value='' selected>- Pilih Akun -</option>";
                        while($w=mysql_fetch_array($tampil)){
                             echo "<option value=$w[id_akunkasperkiraan]>$w[nama_akunkasperkiraan]</option>";
                             }echo "</select>
                </td><td><input readonly></td><td><input id='edt_saldo_k' name='edt_saldo_k' readonly></td></tr>      
          </table>
          <input type=hidden id=nojurnal name=nojurnal value=''>
      </div>
      <div class='modal-footer'>
        <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
        <input class='btn btn-sm btn-warning' type=submit value=Edit>
      </div>
    </div>
     </form> 
      </div>
    </div>

    <div id='hapus_voucer' class='modal fade' role='dialog'>
      <div class='modal-dialog'>
          <!-- Modal content-->
      <form method='post' action='$aksi?module=jurnalvoucer&act=hapus'>
    <div class='modal-content'>
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal'>&times;</button>
        <h4 class='modal-title'>Hapus Jurnal Voucer</h4>
      </div>
      <div class='modal-body'>
        <h3>Apakah Anda yakin ingin Menghapus ?.</h3>
          <input type=hidden id=nojurnal name=nojurnal value=''>
      </div>
      <div class='modal-footer'>
        <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
        <input class='btn btn-sm btn-danger' type=submit value=Hapus>
      </div>
    </div>
     </form> 
      </div>
    </div>

  </div>
 </div>         
</div>

<div class='table-responsive'>
  <table id='kategori' class='table table-hover'>
  <thead>
    <tr style='background-color:#F5F5F5;'>
      <th id='tablenumber'>No.</th>
      <th>Nama Akun Akuntasi</th>
      <th>Debet / Keredit</th>
      <th>Nominal</th>
     <th>Keterangan</th>
      <th>Aksi</th>
    </tr>
    </thead>
    <tbody> ";
     $tampil=mysql_query("SELECT * FROM jurnal_umum WHERE kode_nota LIKE 'jv-%'");
    $no=1;
      while ($r=mysql_fetch_array($tampil)){
          echo "
        <tr>
           <td class='tablenumber'>$no</td>";
           $id=$r['id_akun_kas_perkiraan'];
           $sel=mysql_query("SELECT * FROM akun_kas_perkiraan WHERE id_akunkasperkiraan = '$id'");
           $ect=mysql_fetch_array($sel);
           echo "
            <td>$ect[nama_akunkasperkiraan]</td>";
            if ($r['debet_kredit']=='D') {
              echo "
              <td>Debet</td>
              ";
            }else if ($r['debet_kredit']=='K') {
              echo "
              <td>Kredit</td>
              ";
            }
            echo "            
            <td>".number_format($r['nominal'])."</td>
            <td>$r[kode_nota]</td>
        <td>
          <a data-toggle='modal' data-voucer-id=$r[kode_nota] data-target='#edit_voucer' title='Edit'>Edit <span class='fa fa-edit'></span></a> | 
          <a data-toggle='modal' data-delete-id=$r[kode_nota] data-target='#hapus_voucer' title='Hapus'> Hapus <span class='glyphicon glyphicon-trash'></span></a>
        </td>
      </tr>";
 $no++;}

  echo "
    </tbody>
  </table>
</div>";
  break;
 }
}
}
?>
<script>
        $("#qty_saldo_d").keydown(function(){
          setTimeout(function() {
          var tempt=($("#qty_saldo_d").val());
          $("#qty_saldo_k").val(tempt);
          }, 0);
        });

        $("#edt_saldo_d").keydown(function(){
          setTimeout(function() {
          var tempt=($("#edt_saldo_d").val());
          $("#edt_saldo_k").val(tempt);
          }, 0);
        });

kodex('header','kode')
function kodex(id_combo,id_text){
$("#"+id_combo).change(function()
 { 
  var id = $("#"+id_combo).find(":selected").val();
  var id = id.split("#");
  if (id[1]=="") {
    var jum = 0;
  }else{
    var jum = id[1];
  };
  var jum = parseInt(jum) + 1;
  jum = jum.toString()
  if (jum.length == 1){
    $("#"+id_text).val(id[0]+" - 0"+jum);
  }else{
    $("#"+id_text).val(id[0]+" - "+jum);
  }
 })
        }



</script>
<script type="text/javascript">
          $(document).ready(function () {
            $('#edit_voucer').on('show.bs.modal', function(e) {
                var noId = $(e.relatedTarget).data('voucer-id');
                $(e.currentTarget).find('input[name="nojurnal"]').val(noId);
            });
            $('#hapus_voucer').on('show.bs.modal', function(e) {
                var noId = $(e.relatedTarget).data('delete-id');
                $(e.currentTarget).find('input[name="nojurnal"]').val(noId);
            });

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
 
                var t = $('#kategori').DataTable({
                    "columns": [
                        { "searchable": false },
                        null,
                        null,
                        { "searchable": false },
                        null,
                        { "searchable": false }
                      ],
                    "iDisplayLength": 20,
                       "aLengthMenu": [ [20, 50,100],[20,50,100]],
                    "order": [[0, 'desc']],
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
