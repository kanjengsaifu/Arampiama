<?php
 error_reporting(E_ALL ^ E_NOTICE);
 session_start();

switch($_GET['act']){
  // Tampil Modul
  default:
echo '
    <ul class="nav nav-tabs">
      <li class="active"><a data-toggle="tab" href="#all">Supplier</a></li>
      <li><a data-toggle="tab" href="#list-barang">Daftar Barang</a></li>
    </ul>

    <div class="tab-content">
      <div id="all" class="tab-pane fade in active">
        <div class="table-responsive">
          <table class="tb_supplier display table table-striped table-bordered table-hover">
          <thead>
          <tr style="background-color:#F5F5F5;"">
            <th id="tablenumber">No</th>
            <th>Kode Supplier</th>
            <th>Nama Supplier</th>
            <th>Region</th>
            <th>Telp Supplier</th>
            <th>Telp Sales</th>
            <th>Aksi</th>
          </tr></thead>
          </table>
        </div>
      </div>
      <div id="list-barang" class="tab-pane fade">
        <div class="table-responsive">
          <table class="tb_barang display table table-striped table-bordered table-hover" width="100%" >
          <thead>
          <tr style="background-color:#F5F5F5;"">
                 <th id="tablenumber">No</th>
                 <th>kode Barang </th>
                 <th>Nama Barang</th>
                 <th>Merk</th>
                 <th>Kategori</th>
                 <th>Akun</th>
                 <th>Harga</th>
                 <th>Limit Stok</th>
                 <th>Delete</th>
          </tr></thead>
          </table>
        </div>
      </div>
    </div>
    ';
    break;
case "tambah":
echo ButtonAksi('Tambah Barang');
$id_supplier =$_GET['id'];
$r = Select_database("Select s.id_supplier,nama_supplier,kode_supplier,right(kode_supplier_barang,4) as no_terakhir,kode_supplier_barang,kode_supplier From Master_supplier s , `master_barang` b where s.id_supplier=b.id_supplier and s.id_supplier='$id_supplier' order by kode_supplier_barang desc limit 1");
?>
<div class="well">
<div class="row">
    <div class="col-md-4"><strong>Nama Supplier</strong> : <br><?php echo $r['nama_supplier'] ?> kode Terakhir ( <?= $r['kode_supplier_barang'] ?> )
<?= GenerateInput("id_supplier",'id_supplier','text',$id_supplier,'','')?></div>   
    <div class="col-md-4"><strong>Merk Barang </strong>
                          <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#tambah_merk">+</button> :
                          <?= GenerateInput("merk",'merk','text','','','Merk')?> <br>
<?= GenerateInput("id_merk",'id_merk','text','','','')?>
                          <div id="ket_merk"></div>   </div>
    <div class="col-md-4"><strong>Kategori Barang </strong>
                          <button class="btn btn-xs btn-primary" data-toggle="modal" data-target="#tambah_kategori">+</button> : 
                          <?= GenerateInput("kategori",'kategori','text','','','Kategori')?><br>
<?= GenerateInput("id_kategori",'id_kategori','text','','','')?>
                          <div id="ket_kategori"></div></div>
</div>
</div>
  <table id="tb_fixed_columns" class="stripe row-border order-column" width="100%" cellspacing="0">
      <thead>
        <tr>
              <th rowspan="2">Nama Barang</th>
              <th rowspan="2">Jenis Barang</th>
              <th colspan="3">Barang Satun 1</th>
              <th colspan="3">Barang Satun 2</th>
              <th colspan="3">Barang Satun 3</th>
              <th colspan="3">Barang Satun 4</th>
              <th colspan="3">Barang Satun 5</th>
              <th>Kode </th>
              <th>kode </th>
              <th>kode </th>
         </tr>
         <tr>
              <th>Harga Jual</th>
              <th>Sat   </th>
              <th>Isi</th>
              <th>Harga Jual</th>
              <th>Sat   </th>
              <th>Isi</th>
              <th>Harga Jual</th>
              <th>Sat   </th>
              <th>Isi</th>
              <th>Harga Jual</th>
              <th>Sat   </th>
              <th>Isi</th>
              <th>Harga Jual</th>
              <th>Sat   </th>
              <th>Isi</th>
              <th>supplier</th>
              <th>merk</th>
              <th>kategori</th>
         </tr>
      </thead>

      <tbody>
      <?php 
        $option="SELECT id_akun,concat(kode_akun,' - ',akun) as nama_akun FROM `master_akun2` WHERE `id_tipe_akun`='5' and is_void='0'";
        $options='';
        $result=mysql_query($option);
        while ($o=mysql_fetch_array($result)) {
          $options .= " <option value='$o[id_akun]'>$o[nama_akun]</option>";
        }
       ?>
      <?php for ($i=1; $i <= 20 ; $i++) :?>
          <tr>
            <td><?= GenerateInput('nama_barang-'.$i,'nama_barang[]','text','','','Nama Barang')  ?></td>
            <td><select id="id_akun-<?php echo $i ?>" name="id_akun[]">
              <?php echo $options; ?>
            </select></td>
            <td><?= InputInteger('harga_sat1-'.$i,'harga_sat1[][]','text','','decimal','10.000')  ?></td>
            <td><?= GenerateInput('satuan1-'.$i,'satuan1[]','text','','mini','PCS')  ?></td>
            <td><?= InputInteger('kali1-'.$i,'kali1[]','text','1','nominal mini','','readonly')  ?></td>
            
            <td><?= InputInteger('harga_sat2-'.$i,'harga_sat2[]','text','','decimal','')  ?></td>
            <td><?= GenerateInput('satuan2-'.$i,'satuan2[]','text','','mini','')  ?></td>
            <td><?= InputInteger('kali2-'.$i,'kali2[]','text','','mini','')  ?></td>
            
            <td><?= InputInteger('harga_sat3-'.$i,'harga_sat3[]','text','','decimal','')  ?></td>
            <td><?= GenerateInput('satuan3-'.$i,'satuan3[]','text','','mini','')  ?></td>
            <td><?= InputInteger('kali3-'.$i,'kali3[]','text','','mini','')  ?></td>
            
            <td><?= InputInteger('harga_sat4-'.$i,'harga_sat4[]','text','','decimal','')  ?></td>
            <td><?= GenerateInput('satuan4-'.$i,'satuan4[]','text','','mini','')  ?></td>
            <td><?= InputInteger('kali4-'.$i,'kali4[]','text','','mini','')  ?></td>
            
            <td><?= InputInteger('harga_sat5-'.$i,'harga_sat5[]','text','','decimal','')  ?></td>
            <td><?= GenerateInput('satuan5-'.$i,'satuan5[]','text','','mini','')  ?></td>
            <td><?= InputInteger('kali5-'.$i,'kali5[]','text','','nominal mini','')  ?></td>
            
            <td>
            <?php
            $kode_supplier_barang =$r['kode_supplier'].'-'.substr(($r['no_terakhir']+$i+10000), 1);
            echo GenerateInput('kode_supplier_barang','kode_supplier_barang','text',$kode_supplier_barang,'','Kode Supplier Barang','')  ?>
            </td>
            <td><?= GenerateInput('kode_merk_barang','kode_merk_barang','text','','','Kode Merk Barang')  ?></td>
            <td><?= GenerateInput('kode_kategori_barang','kode_kategori_barang','text','','','Kode Kategori Barang')  ?></td>
            </tr>

      <?php endfor; ?> 
      </tbody>
    </table>

<?php
$header_merk=array('No.','Kode Merk','Merk','Proses');
GenerateModal('cari_merk','Cari Merk','lg',$header_merk);
GenerateModal('tambah_merk','Tambah Merk','lg'); ?>
<?php
$header_kategori=array('No.','Kategori','Proses');
GenerateModal('cari_kategori','Cari Kategori','lg',$header_kategori);
GenerateModal('tambah_kategori','Tambah Kategori','sm'); ?>

<?php
break;
  }
?>


  <script type="text/javascript">
        $('#merk').keyup(function(event) {
          if (event.keyCode==13) {
            cari_merk($(this).val());
            $('#cari_merk').modal();
          }
        })
        $('#kategori').keyup(function(event) {
          if (event.keyCode==13) {
            $('#cari_kategori').modal();
          }
        })



            $(document).ready(function () {
                  var table = $('#tb_fixed_columns').DataTable( {
        scrollY:        "330px",
        scrollX:        true,

        bInfo : false,
        searching : false,
        paging:         false,
        fixedColumns:   {
            leftColumns: 2,
        }
    } );

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


             var h = $('.tb_barang').DataTable({
                      "columns": [
                        { "searchable": false },
                        { "width":"10" },
                         { class:"dt-left" },
                        { "searchable": false },
                        { "searchable": false },
                         { "searchable": false },
                          { "searchable": false },
                           { "searchable": false },
                        {width:"10px" }
                      ],
                    "iDisplayLength": 10,
                    "processing": true,
                    "serverSide": true,
                    "ajax": "json/load-data-barang.php",
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

function cari_merk(val) {
      var m = $('#tb_cari_merk').DataTable({
        "columns": [
          { "width":"10" },
          { "width":"10" },
          { "width":"10" },
          { "width":"10" }
        ],
      "iDisplayLength": 10,
      "processing": true,
      "serverSide": true,
        bInfo : false,
        searching : false,
        paging:         false,
        "ajax": {
                  "url": "json/load-data-merk-barang.php",
                  "cache": false,
                  "type": "GET",
                  "data": {"merk": val}
                  },
      "order": [[1, 'asc']],
      "destroy" :true,
      "rowCallback": function (row, data, iDisplayIndex) {
          var info = this.fnPagingInfo();
          var page = info.iPage;
          var length = info.iLength;
          var index = page * length + (iDisplayIndex + 1);
          $('td:eq(0)', row).html(index);
      }
  });
}
function pilih_merk(id,ket_merk) {
  $('#id_merk').val(id);
  $('#ket_merk').html(ket_merk);
}
            function tampiledit(d){
            var dataString = 'data='+ d;
                $.ajax({
                      url: "modul/barang/ajax_barang.php",
                     data: dataString,
                     cache: false,
                     success: function(r){
                          $("#showedit").html(r);
                     } 
              });
              $('#editbarang').modal('show');
            }
        </script>