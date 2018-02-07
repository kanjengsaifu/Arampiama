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
   $_ck = (array_search("5",$_SESSION['lvl'], true));
  if ($_ck==''){
echo "Modul tidak boleh diakses anda";
}else{
switch($_GET['act']){
  // Tampil Modul
  default:
  echo ButtonCase($_GET['module'],$tambah,'');
?>
<div class="rows">
  <div class="well col-md-6">
    <div class="col-md-3"><?= $tambah ?> Merk</div>
      <div class="col-md-9">
        <?= GenerateInput('merk','merk','text','','full-input','Tambah Merk')?>
    </div>

    <hr>
     <table class='tb_merk display table table-striped table-bordered table-hover'>
    <thead>
    <tr style='background-color:#F5F5F5;'>
      <th id='tablenumber'>No.</th>
      <th>Merk</th>
      <th class='tablenumber'>Aksi</th>
      <th class='tablenumber'>Aksi</th>
    </tr> </thead>
    </table>

  </div>
  <div class="well col-md-6">
        <div class="col-md-3"><?= $tambah ?> Kategorial</div>
      <div class="col-md-9">
      <?= GenerateInput('kategori','kategori','text','','full-input','Tambah Kategori')?>
    </div>

    <hr>
     <table class='tb_kategori display table table-striped table-bordered table-hover'>
    <thead>
    <tr style='background-color:#F5F5F5;'>
      <th id='tablenumber'>No.</th>
      <th>Merk</th>
      <th class='tablenumber'>Aksi</th>
      <th class='tablenumber'>Aksi</th>
    </tr> </thead>
    </table>
  </div>
</div>

<?php
    } 
}
}
?>
<script>
$('#merk').keyup(function(event) {
  Cari_Merk($('#merk').val());

});
$('#kategori').keyup(function(event) {
  Cari_Kategori($('#kategori').val());
});
   $(document).ready(function () {
                Cari_Merk('');
                Cari_Kategori('');
                 });
 function Cari_Merk(merk) {
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
                  var t = $('.tb_merk').DataTable({
                    "columns": [
                        { "searchable": false },
                        null,
                        { "searchable": false },
                         { "searchable": false }
                      ],
                    "iDisplayLength": 5,
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                    "url": "json/load-data-merk.php",
                    "cache": false,
                    "type": "GET",
                    "data": {"merk": merk }
                    },
                    "order": [[1, 'asc']],
                     "destroy": true,
                    "rowCallback": function (row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                        $('td:eq(0)', row).html(index);
                    }
                });
 }
  function Cari_Kategori(kategori) {
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
                  var h = $('.tb_kategori').DataTable({
                    "columns": [
                        { "searchable": false },
                        null,
                        { "searchable": false },
                         { "searchable": false }
                      ],
                    "iDisplayLength": 5,
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                    "url": "json/load-data-kategori.php",
                    "cache": false,
                    "type": "GET",
                    "data": {"kategori": kategori }
                    },
                    "order": [[1, 'asc']],
                     "destroy": true,
                    "rowCallback": function (row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                        $('td:eq(0)', row).html(index);
                    }
                });
 }

           
        </script>