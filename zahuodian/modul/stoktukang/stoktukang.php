 <?php
 include "config/koneksi.php";
 error_reporting(E_ALL ^ E_NOTICE);
 session_start();
 $tgl_awal = $_POST['tgl_awal'];
 $tgl_akhir = $_POST['tgl_akhir'];
 if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
  echo "
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
$aksi="modul/stoktukang/stoktukang.php";
switch($_GET['act']){

// Tampil Modul
  default:
    echo "<h2>Laporan Stok Tukang</h2>

<div class='table-responsive'>
	<table class='table table-hover'>
		<form method='post' action='?module=stoktukang'>
			<input type=hidden name='save' value='save'>
			";
				if (isset($_POST['save'])){
					echo "<tr><td>Supplier</td><td><strong>:</strong></td> <td>";
					echo '<select  id="supplier" name="supplier" class="chosen-select form-control" tabindex="2" required>';
					$tampil=mysql_query("SELECT  s.nama_supplier AS nama_supplier, s.id_supplier AS id_supplier FROM supplier  s RIGHT JOIN stok_tukang st ON(s.id_supplier = st.id_supplier) GROUP BY st.id_supplier");
					        echo "<option value='' selected>- Pilih Supplier -</option>";
					        while($w=mysql_fetch_array($tampil)){
					         	if ($_POST['supplier']==$w[id_supplier]) {
					         		echo "<option value=$w[id_supplier] selected>$w[nama_supplier]</option>";
					         	}else {
					            	echo "<option value=$w[id_supplier]>$w[nama_supplier]</option>";
					         	}
					        }
					echo '</select></td>
          <td>Nama Barang</td><td><strong>:</strong></td><input type="hidden" name="id_barang" id="id_barang" value="'.$_POST['id_barang'].'">
<td><input  name="nama_barang" id ="nama_barang" value="'.$_POST['nama_barang'].'" data-toggle="modal" class="form-control" data-target="#myModal" readonly/>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Nama Barang</h4>
        </div>
        <div class="modal-body">
    <table border="1" class="table table-hover">
    <tr style="background-color:#F5F5F5;">
      <th id="tablenumber">No</th>
      <th>Nama Barang</th>
      <th>Jumlah</th>      
    </tr>

<tbody  id="results" class="update">

</tbody>
    </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>      
    </div>
  </div>
  </td>
  </tr><tr><td>Tanggal Awal </td><td><strong>:</strong></td><td><input class=form-control type=tgl id=tgl1 name=tgl1 readonly size=10 value="'.$_POST['tgl1'].'"></td>';
					echo '<td>Tanggal Akhir </td><td><strong>:</strong></td><td><input class=form-control type=tgl id=tgl2 name=tgl2 readonly size=10 value="'.$_POST['tgl2'].'"></td></tr>';
				} else {
					echo "<tr><td>Supplier </td> <td><strong>:</strong></td><td>";
					echo '<select  id="supplier" name="supplier" class="chosen-select form-control" tabindex="2" required>';
					$tampil=mysql_query("SELECT  s.nama_supplier AS nama_supplier, s.id_supplier AS id_supplier FROM supplier  s RIGHT JOIN stok_tukang st ON(s.id_supplier = st.id_supplier) GROUP BY st.id_supplier");
					            echo "<option value='' selected>- Pilih Supplier -</option>";
					        while($w=mysql_fetch_array($tampil)){
					        	if ($_GET['supplier']==$w[id_supplier]) {
					        		echo "<option value=$w[id_supplier] selected>$w[nama_supplier]</option>";
					        	}else {
					            	echo "<option value=$w[id_supplier]>$w[nama_supplier]</option>";
					        	}
					        }
					echo '</select></td>
          <td>Nama Barang</td><td><strong>:</strong></td><input type="hidden" name="id_barang" id="id_barang">
<td><input  name="nama_barang" id ="nama_barang" data-toggle="modal" class="form-control" data-target="#myModal" readonly/>
<div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Nama Barang</h4>
        </div>
        <div class="modal-body">
    <table border="1" class="table table-hover">
    <tr style="background-color:#F5F5F5;">
      <th id="tablenumber">No</th>
      <th>Nama Barang</th>
      <th>Jumlah</th>      
    </tr>

<tbody  id="results" class="update">

</tbody>
    </table>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>      
    </div>
  </div>
  </td>
  </tr><tr><td>Tanggal Awal </td><td><strong>:</strong></td><td><input class=form-control type=tgl id=tgl1 name=tgl1 readonly size=10 value="'.$_GET['t1'].'"></td>';
					echo '<td>Tanggal Akhir </td><td><strong>:</strong></td><td><input class=form-control type=tgl id=tgl2 name=tgl2 readonly size=10 value="'.$_GET['t2'].'"></td></tr>';
				}
				echo "
				<tr><td colspan=6><input class='btn btn-success' type=submit value=Telusuri></td></tr>
		</form>                
	</table>
</div>";
if (isset($_POST['save'])||isset($_GET['t1'])) {
	echo "
	<div class='table-responsive'>
	    <table class='table table-bordered'>
	        <thead>
	        	<tr>
              <th rowspan='2' id=tablenumber style='vertical-align:middle'>No</th>
              <th rowspan='2' style='vertical-align:middle'>Tanggal Transaksi</th>
              <th rowspan='2' style='vertical-align:middle'>No Transaksi</th>
              <th colspan='3'>Masuk</th>
              <th colspan='3'>Keluar</th>
	          </tr>
            <tr>
              <th>Jumlah</th>
              <th>Harga</th>
              <th>Rupiah</th>
              <th>Jumlah</th>
              <th>Harga</th>
              <th>Rupiah</th>
            </tr>
	        </thead>
	        <tbody>";
	        if (isset($_POST['save'])) {
	        	$sql="SELECT 1 no, th.tgl_trans AS tgl, th.id_beri_tukang AS no_trans, td.jumlah AS mjumlah, td.harga AS mharga, td.total AS mrupiah, 0 AS kjumlah, 0 AS kharga, 0 AS krupiah FROM trans_beri_tukang_header th JOIN trans_beri_tukang_detail td ON th.id_beri_tukang = td.id_beri_tukang WHERE  th.tgl_trans BETWEEN '$_POST[tgl1]' AND '$_POST[tgl2]' AND th.is_void = 0 AND th.id_supplier = '$_POST[supplier]' AND td.id_barang = '$_POST[id_barang]'
            UNION 
            SELECT 2 no, tgl_transfer_tukang AS tgl, no_transfer_tukang AS no_trans, 0 AS mjumlah, 0 AS mharga, 0 AS mrupiah, jumlah AS kjumlah, harga AS kharga, (jumlah * harga) AS krupiah FROM transfer_tukang WHERE tgl_transfer_tukang BETWEEN '$_POST[tgl1]' AND '$_POST[tgl2]' AND is_void = 0 AND id_supplier_dari = '$_POST[supplier]' AND id_barang = '$_POST[id_barang]'
            UNION
            SELECT 3 no, h.tgl_trans AS tgl, h.no_hitung_hpp AS no_trans,  0 AS mjumlah, 0 AS mharga, 0 AS mrupiah, d.jumlah_barang AS kjumlah, d.harga_barang AS kharga, d.total_biaya AS krupiah FROM hitung_hpp_header h, hitung_hpp_detail d WHERE h.no_hitung_hpp = d.no_hitung_hpp AND h.tgl_trans BETWEEN '$_POST[tgl1]' AND '$_POST[tgl2]' AND is_void = 0 AND id_supplier = '$_POST[supplier]' AND nama_barang = '$_POST[nama_barang]' ORDER BY tgl";
	        } 
          //echo $sql;
	        $query = mysql_query($sql);
	        $no = 1;
	        while ($rows = mysql_fetch_array($query)) {
	        	echo "
	        	<tr>
              <td>$no</td>
	        		<td>$rows[tgl]</td>
	        		<td>$rows[no_trans]</td>
	        		<td>$rows[mjumlah]</td>
              <td>$rows[mharga]</td>
              <td>$rows[mrupiah]</td>
              <td>$rows[kjumlah]</td>
              <td>$rows[kharga]</td>
	        		<td>$rows[krupiah]</td>
	        	</tr>
	        	";
          $no++;
	        }
	        echo "
	        </tbody>
	    </table>
	</div>
	";
}
    break;

  }
}
?>
<script type="text/javascript">
    $(document).ready(function (){
    	$("#tgl1").datepicker({
			dateFormat:"yy-mm-dd"        
	    });
		$("#tgl2").datepicker({
				dateFormat:"yy-mm-dd"        
	    });     
    });
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
 
                var t = $('#omm').DataTable({
                    "columns": [
                        { "searchable": false },
                        null,
                        null,
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false }
                      ],
                    "iDisplayLength": 20,
                       "aLengthMenu": [ [20, 50,100],[20,50,100]],
                      "pagingType" : "simple_numbers",
                      "language": {
                            "decimal": ",",
                            "thousands": "."
                          },
                    "processing": true,
                    "serverSide": true,
                    "ajax": "modul/omsetsales/load-data.php",
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
  $(function() {
    $("#myModal").on('shown.bs.modal', function () {
        var searchString    = $("#search_box").val();
        var data            = 'gdg='+$("#supplier").val();;
        // if(searchString) {
            $.ajax({
                type: "GET",
                url: "modul/stoktukang/cari-barang.php",
                data: data,
                beforeSend: function(html) { // this happens before actual call
                    $("#results").html(''); 
                    $("#searchresults").show();
                    $(".word").html(searchString);
               },
               success: function(html){ // this happens after we get results
                    $("#results").show();
                    $("#results").append(html);
              }
            });    
        // }
        // return false;
    });
  });
  function addMore(kode,name) {
    // kode=kode;
    // name=name;
      $("#id_barang").val(kode);
      $("#nama_barang").val(name);
      // $("#no_expedisi").val(kode[1]);
      // $("#total_barang").val(kode[3]*1);
      // $("#total").val(kode[4]*1);
      // $("#id_brg").val(kode[5]);
      // $("#id_tukang").val(kode[6]);
       $("#myModal").modal("toggle");
  };
</script>