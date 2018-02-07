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
$aksi="modul/laporanbahanketukang/laporanbahanketukang.php";
switch($_GET['act']){

// Tampil Modul
  default:
    echo "<h2>Laporan Bahan Baku ke Tukang</h2>

<div class='table-responsive'>
	<table class='table table-hover'>
		<form method='post' action='?module=laporanbahanketukang'>
			<input type=hidden name='save' value='save'>
			<tr>";
				if (isset($_POST['save'])){
					echo '<td>Tanggal Awal :</td><td><input class=form-control type=tgl id=tgl1 name=tgl1 readonly size=10 value="'.$_POST['tgl1'].'"></td>';
					echo '<td>Tanggal Akhir :</td><td><input class=form-control type=tgl id=tgl2 name=tgl2 readonly size=10 value="'.$_POST['tgl2'].'"></td>';
				} else {
					echo '<td>Tanggal Awal :</td><td><input class=form-control type=tgl id=tgl1 name=tgl1 readonly size=10 value="'.$_GET['t1'].'"></td>';
					echo '<td>Tanggal Akhir :</td><td><input class=form-control type=tgl id=tgl2 name=tgl2 readonly size=10 value="'.$_GET['t2'].'"></td>';
				}
				echo "
				<td><input class='btn btn-success' type=submit value=Telusuri></td>
			</tr>
		</form>                
	</table>
</div>";
if (isset($_POST['save'])||isset($_GET['t1'])) {
	echo "
	<div class='table-responsive'>
	    <table class='table table-bordered'>
	        <thead>
	        	<tr>
              <th id=tablenumber>No</th>
	            <th>No NPB</th>
	            <th>No Nota</th>
	            <th>Nama Tukang</th>
	            <th>Tanggal</th>
	            <th>Nominal</th>
              <th>Detail</th>
	          </tr>
	        </thead>
	        <tbody>";
	        $sql = "SELECT * FROM trans_beri_tukang_header t, supplier s WHERE t.id_supplier = s.id_supplier AND t.is_void = 0";
	        if (isset($_POST['save'])) {
	        	$sql.=" AND tgl_trans BETWEEN '$_POST[tgl1]' AND '$_POST[tgl2]'";
	        } else {
	        	$sql.=" AND tgl_trans BETWEEN '$_GET[t1]' AND '$_GET[t2]'";
	        }
	        $sql.=" ORDER BY id_trans_beri_tukang_header";
	        //echo $sql;
	        $query = mysql_query($sql);
	        $no = 1;
	        while ($rows = mysql_fetch_array($query)) {
	        	echo "
	        	<tr>
              <td>$no</td>
	        		<td>$rows[id_beri_tukang]</td>
	        		<td>$rows[nonota_beri_tukang]</td>
	        		<td>$rows[nama_supplier]</td>
	        		<td>".tgl_indo($rows['tgl_trans'])."</td>
	        		<td>".number_format($rows['grand_total'])."</td>
              <td>";
              if (isset($_POST['save']))
                echo "
                <a href='?module=laporanbahanketukang&act=detail&id=$rows[id_beri_tukang]&t1=$_POST[tgl1]&t2=$_POST[tgl2]'class='btn btn-success'  title='detail'target='_blank'><span class='glyphicon glyphicon-calendar' aria-hidden='true'></span></a>";
              else
                echo "
                <a href='?module=laporanbahanketukang&act=detail&id=$rows[id_beri_tukang]&t1=$_GET[t1]&t2=$_GET[t2]'class='btn btn-success'  title='detail'target='_blank'><span class='glyphicon glyphicon-calendar' aria-hidden='true'></span></a></td>";
            echo "
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

  case 'detail':
    $tgl1=$_GET['t1'];
    $tgl2=$_GET['t2'];
    echo "
    <script>
      
    </script>
    <h2>Detail Laporan Bahan Baku ke Tukang</h2>
    <div class='table-responsive' id='unstyled'>
      <table class='table table-hover' border=0 id=tambah>
  <tr>
    <td>No PO</td>
    <td>";
 $tampil44=mysql_query("SELECT * FROM `trans_beri_tukang_header`tpr,supplier s   WHERE tpr.id_supplier=s.id_supplier and id_beri_tukang = '$_GET[id]'");
  $r    = mysql_fetch_array($tampil44);

  echo "$r[id_beri_tukang]";
   echo " </td>
    <td>Tanggal</td>
    <td>".tgl_indo($r[tgl_trans])."</td>
  </tr>
  <tr>
   <td>Supplier</td> 
   <td>$r[nama_supplier]</td>
  <td>No Nota</td>
  <td>$r[nonota_beri_tukang]</td>
  </tr>";
  echo '<tr id="txtHint">
<td> Alamat </td>
    <td>'.$r[alamat_supplier].'</td>
    <td> No tlp </td>
    <td>'.$r[telp1_supplier].'</td>
  </tr>';
  echo "</table> ";

echo '
<div class="btn-action float-clear">

<!--div class="btn btn-primary" type="button" id="search-edit" data-toggle="modal" data-target="#search-md">Tambah Item <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
 </div-->

</div>
<table id="header" class="display table table-striped table-bordered table-hover" cellspacing="0">
        <thead>
  <tr style="background-color:#F5F5F5;">
    <th id=tablenumber>No</th>
      <th colspan="2">Nama barang - Kode barang</th>
      <th width="10%"">Gudang</th>
      <th width="10%">Satuan</th>
      <th width="15%">Harga</th>
      <th width="15%">Qty</th>
      <!--th>Disc 1 (%)</th>
      <th>Disc 2 (%)</th>
      <th>Disc 3 (%)</th>
      <th>Disc 4 (%)</th>
      <th>Pembulatan <br> (Rp.)</th-->
      <th>Total</th>
      
      </tr>
        </thead>
 
        <tbody id="product">';
$tampiltable=mysql_query("SELECT * FROM `trans_beri_tukang_detail` t, gudang g WHERE g.id_gudang = t.id_gudang AND id_beri_tukang = '$r[id_beri_tukang]'  ");
$noz = 1;
$rst_jumlah = mysql_num_rows($tampiltable);
while ($rst = mysql_fetch_array($tampiltable)){
  $sql= mysql_query("SELECT * FROM barang WHERE id_barang = '$rst[id_barang]' ");
  $data = mysql_fetch_array($sql);
  echo '
  <tr>
    <td>'.$noz.'</td>
  <td colspan="2">
   '.$data['nama_barang'].'
   </br>
       '.$data['kode_barang'].'
    </td>
    <td>
      '.$rst['nama_gudang'].'
    </td>
   <td>
      '.$rst['satuan'].'</td>
   <td>
       '.$rst['harga'].'
    </td>
    
   <td>'.$rst[jumlah].'</td>
           
    <td>'.$rst[total].'</td>
  
</tr>
';
$noz++;
}

if ($r['all_discpersen']==0) {
$discper="";
}else{
  $discper=$r['all_discpersen'];
}
if ($r['all_ppnpersen']==0) {
$ppnper="";
}else{
  $ppnper=$r['all_ppnpersen'];
}
echo'
        </tbody>
        <tfoot>
        <tr>
    <td rowspan="4"> 
          </td>

    <td colspan="6" style="text-align:right;" ><p><b>Total </b></p></td>
    <td align="center"> Rp. '.number_format($r['grand_total']).'</td>
  </tr>
                </tfoot>
          </table>
  </div> ';
  
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
</script>