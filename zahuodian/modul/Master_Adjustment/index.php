<script>
$(document).ready(function(){
  $('#intro select').zelect({ placeholder:'Pilih Barang...' });
  $('#browse_gudang').dialog({
      autoOpen: false,
      show: "blind",
      hide: "blind",
      width: 450,
      height:500,
      modal : true,
      resizable:false,
  });
  $('#browse_barang').dialog({
      autoOpen: false,
      show: "blind",
      hide: "blind", 
      width: 450,
      height:500,
      modal : true,
      resizable:false,
  });
  $('#browse1').click(function(){
    $('#browse_gudang').dialog('open');
    $("#tampil_gudang").load("modul/adjustment/tampil_gudang.php");     
    $('#cari_gudang').click(function(){
      txt=$('#txt_cari_gudang').val();
      $.ajax({
        type  : "POST",
        url   : "modul/adjustment/tampil_gudang.php",
        data  : "txt_cari_gudang="+txt,
        success : function(data){
          $("#tampil_gudang").html(data);     
        }
      });
    });
  });
  // $('#browse2').click(function(){   
  //   $('#browse_barang').dialog('open');
  //   $("#tampil_barang").load("modul/adjustment/tampil_barang.php");     
  //   $('#cari_barang').click(function(){
  //     txt=$('#txt_cari_barang').val();
  //     $.ajax({
  //       type  : "POST",
  //       url   : "modul/adjustment/tampil_barang.php",
  //       data  : "txt_cari_barang="+txt,
  //       success : function(data){
  //         $("#tampil_barang").html(data);     
  //       }
  //     });
  //   });
  // });
});
</script>
<style type="text/css">
  section { margin-bottom: 40px; }
    section:after { content: "."; display: block; height: 0; clear: both; visibility: hidden; }

    #intro .zelect {
      background-color: white;
      min-width: 45%;
      cursor: pointer;
      line-height: 36px;
      border: 1px solid #dbdece;
      border-radius: 6px;
      position: absolute;
    }
    #intro .zelected {
      font-weight: bold;
      padding-left: 10px;
    }
    #intro .zelected.placeholder {
      color: #999f82;
    }
    #intro .zelected:hover {
      border-color: #c0c4ab;
      box-shadow: inset 0px 5px 8px -6px #dbdece;
    }
    #intro .zelect.open {
      border-bottom-left-radius: 0;
      border-bottom-right-radius: 0;
    }
    #intro .dropdown {
      background-color: white;
      border-bottom-left-radius: 5px;
      border-bottom-right-radius: 5px;
      border: 1px solid #dbdece;
      border-top: none;
      position: absolute;
      left:-1px;
      right:-1px;
      top: 36px;
      z-index: 2;
      padding: 3px 5px 3px 3px;
    }
    #intro .dropdown input {
      font-family: sans-serif;
      outline: none;
      font-size: 14px;
      border-radius: 4px;
      border: 1px solid #dbdece;
      box-sizing: border-box;
      width: 100%;
      padding: 7px 0 7px 10px;
    }
    #intro .dropdown ol {
      padding: 0;
      margin: 3px 0 0 0;
      list-style-type: none;
      max-height: 150px;
      overflow-y: scroll;
    }
    #intro .dropdown li {
      padding-left: 10px;
    }
    #intro .dropdown li.current {
      background-color: #e9ebe1;
    }
    #intro .dropdown .no-results {
      margin-left: 10px;
    }
</style>
<?php
 include "config/coneksi.php";
 error_reporting(E_ALL ^ E_NOTICE);
 session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
  echo "
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
  // security akses link
  $_ck = (array_search("7",$_SESSION['lvl'], true));
  if ($_ck==''){
echo "Modul tidak boleh diakses anda";
}else{
$aksi="modul/adjustment/aksi_adjustment.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';
switch($_GET['act']){
  // Tampil Modul
  default:

  $judul = "Master Adjustment";
  $desk = "Modul untuk melakukan adjustment stok l";
  $button="   <a class='btn btn-info' data-toggle='collapse' data-parent='#show' href='#tambah'>Tambah Adjustment</a>";
  headerDeskripsi($judul,$desk,$button);

    echo "
  <div class='table-responsive'>

 <div class='panel-group' id='show'> <!-- ################## PANEL GROUP ################### -->
  <div class='panel'>

    <div id='tambah' class='panel-collapse collapse'>
      <div class='panel-body'> <!-- ################## PANEL BODY 1 ################### -->
          <table class='table'>
                <form method='post' action='$aksi?module=adjustment&act=input'>
                <input type=hidden id=gudang name=gudang>
                <input type=hidden id=barang name=barang>
                <tr>
                  <td><b>Nama Gudang</b></td> 
                  <td><span id=nama_gudang></span></td>
                  <td><button type='button' class='btn btn-info' id=browse1>Pilih Gudang</button></td>
                </tr>
                <tr>
                  <td><b>Nama Barang</b></td> 
                  <td><span id=nama_barang></span></td>
                  <td>
                    <button class=btn btn-primary type=button id=search-edit data-toggle=modal data-target=#search-md>Pilih Barang <span class=glyphicon glyphicon-plus aria-hidden=true></span></button>
                  </td>
                </tr>
                <tr>
                  <td><b>tanggal</b></td> 
                  <td><input type='date' name='tgl_adjustment' class'datetimepicker' form-control'></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td><b>Plus-minus</b></td> 
                  <td><input type='number' name='plusminus_barang' class=form-control /></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td><b>Keterangan</b></td> 
                  <td><textarea type='text' name='keterangan' class=form-control /></textarea></td>
                  <td>&nbsp;</td>
                </tr>
                <tr>
                  <td colspan=2>&nbsp;</td>
                  <td>
                  <div class='form-group'>
                    <input class='btn btn-success' type=submit value=Simpan>
                    </div>
                  </td>
                </tr>
                </form>                
          </table>
      </div> <!-- ################## END PANEL BODY 1################### -->
    </div>
  </div>
</div>      <!-- ################## END PANEL GROUP ################### -->    
    </div>
  <!--div class='btn btn-primary' data-toggle='modal' data-target='#modalstock' >Tambah <span class='glyphicon glyphicon-plus' aria-hidden='true'></span></div-->";
  echo "<div id='browse_gudang' title='Browse Gudang'>";
      echo "<table>";
      echo "<tr>";
        echo "<td>Search</td>";
        echo "<td>:</td>";
        echo "<td>".generateInputText('txt_cari_gudang','',255,30)."</td>";
        echo "<td>".generateButtonCari2('Cari','cari_gudang')."</td>";
      echo "</tr>";
      echo "</table>";
      echo "<div id=tampil_gudang></div>";
      echo "</div>";
echo'
<div id="search-md" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">cari Item</h4>
      </div>
      <div class="modal-body">
      <table id="tambahitem" class="table table-hover table-bordered" cellspacing="0" style="width: 100%;">
        <thead>
                <tr style="background-color:#F5F5F5;">
                    <th  id="tablenumber">No</th>
                    <th>Kode Barang</th>
                    <th>Nama Barang</th>
                    <th>Stok Sekarang</th>
                    <th>Stok Min</th>
                    <th>Tambah</th>
                </tr>
        </thead>
      </table>

          </div>
      </div>
    </div>

  </div>
</div>
  ';
      // echo "<div id='browse_barang' title='Browse Barang'>";
      // echo "<table>";
      // echo "<tr>";
      //   echo "<td><input type='text' id='txt_cari_barang' name=txt_cari_barang size='30'></td>";
      //   echo "<td>".generateButtonCari2('Cari','cari_barang')."</td>";
      // echo "</tr>";
      // echo "</table>";
      // echo "<div id=tampil_barang></div>";
      // echo "</div>";
//   //### modul tambah
//   echo"
//   <div class='modal fade' id='modalstock' role='dialog'>
//     <div class='modal-dialog'>
//           <div class='modal-content'>
//         <div class='modal-header'>
//           <button type='button' class='close' data-dismiss='modal'>&times;</button>
//           <h4 class='modal-title'><b>Tambah</b> Stock gudang</h4>
//         </div>

//         <div class='modal-body'>";
//         echo"
//  <form method='post' action='$aksi?module=adjustment&act=input'>
//       <table class='table table-hover'>
//         <tr>
//           <td>Gudang</td> 
//           <td>
//            <select class='form-control' id='gudang' name='gudang'>";
// $tampil1=mysql_query("SELECT * FROM gudang where is_void='0'");
//           if ($r[id_merk]==0){
//             echo "<option value=0 selected>- Pilih Gudang -</option>";
//           }   

//           while($w=mysql_fetch_array($tampil1)){
//             if ($r[id_gudang]==$w[id_gudang]){
//               echo "<option value=$w[id_gudang] selected>$w[nama_gudang]</option>";
//             }
//             else{
//               echo "<option value=$w[id_gudang]>$w[nama_gudang]</option>";
//             }
//           }
//           echo "
//           </select>
//           </td>
//         </tr>
//         <tr>
//           <td>Barang</td> 
//           <td> ";
//           echo comboboxeasy('barang',"select *,concat(kode_barang,' - ',nama_barang) as tampil from barang_real where is_void=0",'Pilih Barang','id_barang','tampil',0);
//           echo "<script>
//           add_newitemcombobox('gudang');
//           add_newitemcombobox('barang');</script>
          
// </td>
//         </tr>
    //  <tr>
    // <td>tanggal</td> 
    // <td><input type='date' name='tgl_adjustment' class'datetimepicker form-control'></td>
    // </tr>
    //  <tr>
    // <td>Plus-minus</td> 
    // <td><input type='number' name='plusminus_barang'/></td>
    // </tr>
    // <tr>
    // <td>Keterangan</td> 
    // <td><textarea type='text' name='keterangan'/></textarea></td>
    // </tr>
//     <tr>
//       </table>
//       <div class='form-group'>
//                             <input class='btn btn-success' type=submit value=Simpan>
//                             <button type='button' class='btn btn-default' data-dismiss='modal'>Close</button>
//       </div>
//  </form>
//         </div>
//       </div>
      
//     </div>
//   </div>";


        echo "
 <div class='col-md-12'>
<div class='table-responsive'>
    <table id='adjustment' class='display table table-striped table-bordered table-hover'>
    <thead>
    <tr style='background-color:#F5F5F5;'>
      <th id='tablenumber'>No</th>
      <th>Gudang</th>
      <th>Barang</th>
      <th>Tanggal</th>
      <th>Plus-minus</th>
      <th>keterangan</th>
      <th>Aksi</th>
    </tr>
    </thead>
    </table>
  </div>
  </div>";
    break;

 /* case "tambahmenu":
    echo "<h2>Modul adjustment</h2>
    <form method='post' action='$aksi?module=adjustment&act=input'>
     <div class='table-responsive'>
      <table class='table table-hover'>
        <tr>
          <td>Gudang</td> 
          <td>
           <select name='gudang'>";
$tampil=mysql_query("SELECT * FROM gudang where is_void='0'");
          if ($r[id_merk]==0){
            echo "<option value=0 selected>- Pilih Gudang -</option>";
          }   

          while($w=mysql_fetch_array($tampil)){
            if ($r[id_gudang]==$w[id_gudang]){
              echo "<option value=$w[id_gudang] selected>$w[nama_gudang]</option>";
            }
            else{
              echo "<option value=$w[id_gudang]>$w[nama_gudang]</option>";
            }
          }
          echo "
          </select>
          </td>
        </tr>
        <tr>
          <td>Barang</td> 
          <td> <select name='barang'>";
$tampil=mysql_query("SELECT * FROM barang where is_void='0'");
          if ($r[id_merk]==0){
            echo "<option value=0 selected>- Pilih Gudang -</option>";
          }   

          while($w=mysql_fetch_array($tampil)){
            if ($r[id_barang]==$w[id_barang]){
              echo "<option value=$w[id_barang] selected>$w[nama_barang]</option>";
            }
            else{
              echo "<option value=$w[id_barang]>$w[nama_barang]</option>";
            }
          }
          echo "
          </select>
</td>
        </tr>
     <tr>
    <td>tanggal</td> 
    <td><input type='date' name='tgl_adjustment'></td>
    </tr>
     <tr>
    <td>Plus-minus</td> 
    <td><input type='number' name='plusminus_barang'/></td>
    </tr>
    <tr>
    <td>Keterangan</td> 
    <td><textarea type='text' name='keterangan'/></textarea></td>
    </tr>
    <tr>
      </table>
      <div class='form-group'>
                            <input class='btn btn-success' type=submit value=Simpan>
                            <input class='btn btn-warning' type=button value=Batal onclick=self.history.back()><div>
      </div>
      </from>";
     break;*/
     
  case "editmenu":

    $edit = mysql_query("SELECT * FROM adjustment_stok adj,gudang gdg,barang brg WHERE adj.id_gudang=gdg.id_gudang and adj.id_barang=brg.id_barang and adj.is_void='0'  and id_adjustment='$_GET[id]'");
    $v    = mysql_fetch_array($edit);

    echo "<h2>Modul adjustment</h2>
    <form method='post' action='$aksi?module=adjustment&act=update'>
     <div class='table-responsive'>
      <table class='table table-hover'>
        <tr>
          <td>Gudang</td> <input type='hidden' name='id' value='$v[id_adjustment]'/>
          <td>
           <select class='form-control' name='gudang'>";
$tampil=mysql_query("SELECT * FROM gudang where is_void='0'");
          if ($r[id_merk]==0){
            echo "<option value=$v[id_gudang] selected>$v[nama_gudang]</option>";
          }   

          while($w=mysql_fetch_array($tampil)){
            if ($r[id_gudang]==$w[id_gudang]){
              echo "<option value=$w[id_gudang] selected>$w[nama_gudang]</option>";
            }
            else{
              echo "<option value=$w[id_gudang]>$w[nama_gudang]</option>";
            }
          }
          echo "
          </select>
          </td>
        </tr>
        <tr>
          <td>Barang</td> 
          <td> <select class='form-control' name='barang'>";
$tampil=mysql_query("SELECT * FROM barang where is_void='0'");
          if ($r[id_merk]==0){
            echo "<option value=$v[id_barang] selected>$v[nama_barang]</option>";
          }   

          while($w=mysql_fetch_array($tampil)){
            if ($r[id_barang]==$w[id_barang]){
              echo "<option value=$w[id_barang] selected>$w[nama_barang]</option>";
            }
            else{
              echo "<option value=$w[id_barang]>$w[nama_barang]</option>";
            }
          }
          echo "
          </select>
</td>
        </tr>
     <tr>
    <td>tanggal</td> 
    <td><input class='form-control datetimepicker'  type='date' name='tgl_adjustment' value=$v[tgl_adjustment]></td>
    </tr>
     <tr>
    <td>Plus-minus</td> 
    <td><input type='hidden' class='form-control'  type='number' name='plusminus_barang_awal' value=$v[plusminus_barang]>
    <input class='form-control'  type='number' name='plusminus_barang' value=$v[plusminus_barang]></td>
    </tr>
    <tr>
    <td>Keterangan</td> 
    <td><textarea class='form-control'  type='text' name='keterangan' > $v[keterangan]</textarea></td>
    </tr>
    <tr>
      </table>
      <div class='form-group'>
                            <input class='btn btn-success' type=submit value=Simpan>
                            <input class='btn btn-warning' type=button value=Batal onclick=self.history.back()><div>
      </div>
      </from>";
    break;
  }
}
}
?>
<script>
 function addMore(kode) {
  var id = kode;
  var dataString = 'test='+ id;
        $.ajax
                ({
                url: 'modul/adjustment/catch.php',
                data: dataString,
                cache: false,
                success: function(r)
                          {                                 
                      $tai = r.split(" ## ");
          $('#barang').val($tai[0]);
          $('#nama_barang').text($tai[1]);
                          } 
                });
                $('#search-md').modal('toggle'); 
  // var kd1 = kode;
  // $("<tr>").load("modul/adjustment/catch.php?kd="+kd1+"  ", function() {
  //     $tai = r.split(" ## ");
  //         $('#id_barang').val($tai[0]);
  //         $('#nama_barang').text($tai[1]);
  //   return false;
  // }); 

}
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
 
                var t = $('#adjustment').DataTable({
                    "columns": [
                        { "searchable": false },
                        null,
                        null,
                       null,
                        { "searchable": false },
                        null,
                        { "searchable": false }
                      ],
                    "iDisplayLength": 20,
                       "aLengthMenu": [ [20, 50,100],[20,50,100]],
                    "processing": true,
                    "serverSide": true,
                    "ajax": "modul/adjustment/load-data.php",
                    "order": [[3, 'desc']],
                    "rowCallback": function (row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                        $('td:eq(0)', row).html(index);
                    }
                });

                var u = $('#tambahitem').DataTable({
                    "iDisplayLength": 10,
                       "aLengthMenu": [ [10, 20,50],[10,20,50]],
                      "pagingType" : "simple",
                      "ordering": false,
                      "info":     false,
                      "language": {
                            "decimal": ",",
                            "thousands": "."
                          },
                    "processing": true,
                    "serverSide": true,
                    "ajax": "modul/adjustment/tampil_barang.php",
                    "order": [[1, 'asc']],
                     "columns": [
                        { "searchable": false },
                        null,
                        null,
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false }
                      ],
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