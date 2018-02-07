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
 $_ck = (array_search("2",$_SESSION['lvl'], true));
  if ($_ck==''){
echo "Modul tidak boleh diakses anda";
}else{

  $aksi = "modul/stock/stock.php";
  echo '<link rel="stylesheet" href="asset/css/layout.css">';

class salesorder {
public function display() {
   echo "<h2>Laporan Stock</h2>
   <div class='row'>
   <div class='col-md-6'>
<b>Pilih Nama Barang : </b><select id='stockbarang-md'>";
    $query = "SELECT * FROM barang where is_void='0'";
  $md = mysql_query($query);
  while($w=mysql_fetch_array($md)){
    echo "
    <option value='$w[nama_barang]'>$w[nama_barang]</option>";
  }
    echo"
   </select>
   </div>
   <div class='col-md-6'>
   <b style='float:right;''>
Search Berdasarkan Kode Barang:   <input type id='search_kode'></b>
</div>
</div>
          <div class='table-responsive'>
    <table id='stockbarang' class='table table-hover'>
    <thead>
    <tr style='background-color:#F5F5F5;'>
     <th id='tablenumber'>No</th>
      <th>Kode barang</th>
      <th>Nama Barang</th>
     <th>Letak Gudang</th>
       <th>ket</th>
       <th>edit</th>
    </tr>
    </thead>    
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
public function tambahtrans() {
  echo "ini ini ini ini ";
  }

}

$so = new salesorder();
switch($_GET['act']){
            case 'hapus':
              $so -> delete();
              break;
            default:
                $so-> display();
                break;
        }

}
}
?>
<style type="text/css">
  #stockbarang_filter{
    visibility: hidden;
  }
</style>
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
 
                var t = $('#stockbarang').DataTable({
                    /*"columns": [
                        { "searchable": false, "bSortable": false },
                        null,
                        null,
                        { "searchable": false, "bSortable": false },
                        { "searchable": false, "bSortable": false },
                       null
                      ],*/
                    "iDisplayLength": 20,            
                    "bLengthChange": false,
                       "aLengthMenu": [ [20, 50,100],[20,50,100]],
                      "pagingType" : "simple_numbers",
                      "info":     false,                      
                      "language": {
                            "decimal": ",",
                            "thousands": "."
                          },
                    "processing": true,
                    "serverSide": true,
                    "ajax": "modul/stock/load-data.php",
                    //"order": [[2, 'asc']],
                    "rowCallback": function (row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                        $('td:eq(0)', row).html(index);
                    }
                });
                 var oTable;
      oTable = $('#stockbarang').dataTable();
      $('#stockbarang-md').change( function() { 
                        var i = $(this).val();
                $("#sum").load("modul/stock/input.php?kd="+i+" ", function() {
                $("#sum").append($(this).html());
              }); 
            oTable.fnFilter( $(this).val() ); 
       });
    $('#search_kode').change( function() { 
            oTable.fnFilter( $(this).val() ); 
       });
            });
  $( function() {
    $( "#datepicker" ).datepicker();
  } );  
</script>