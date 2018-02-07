
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
  $_ck = (array_search("1",$_SESSION['lvl'], true))?'true':'false';
  if ($_ck==''){
echo "Modul tidak boleh diakses anda";
}else{
    $judul = "Laporan Cetak";
    $desk = "Laporan Yang digunakan Untuk melihat hasil transaksi";
    headerDeskripsi($judul,$desk);

	echo '
<table style=" width: 100%; text-align:left" >

  <tr class="success">
  <!--   ################################################ Laporan Harian #############################################  -->
    <td style="width: 50%;"> 
    <div >
    <div class="text-info">
       		<h2><u>Laporan Penjualan Nota Per Sales</u></h2>
    </div >
    <div class="text-muted">
    <p>Laporan Penjualn Persales untuk melihat penjalan dari sales berdasarkan invoice yang dibuat berdasarkan periode yang ditentukan</p>
    </div >
      	</div>
    </td>
    <!--   ################################################ Laporan Harian #############################################  -->
    <!--   ################################################ Laporan Arus Bank #############################################  -->
    <td style="width: 50%;"> 
     <div >
    <div class="text-info">
       	  <h2><u>Laporan Arus bank</u></h2>
    </div >
    <div class="text-muted">
    <p>Laporan Arus Bank adalah, laporan pengeluaran dan penerimaan dana pada setiap akun bank yang disamakan dengan buku tabungan dari setiap rekening</p>
   </div >
      	</div>
    </td>
    <!--   ################################################ Laporan Arus Bank #############################################  -->
  </tr>
  <tr class="success">
  <!--   ################################################ Modal Untuk Laporan Harian #############################################  -->
    <td style="width: 50%;"> 
    <div >
    <button type="button" class="btn btn-default" title="Print" data-toggle="modal" data-target="#modalharian"><span class="glyphicon glyphicon-print"> Print</span></button>
<!-- Modal -->
<div id="modalharian" class="modal fade" role="dialog">
  <div class="modal-dialog ">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title">Pilih Tanggal</h4>
      </div>
      <form method="post" action="modul/laporansales/cetakpenjualansales.php">
          <div class="modal-body">
            <table class="table">
                <tr>
                <td>
                Pilih Supplier
                </td>
                <td colspan="2">
                ';
                echo combobox('id_sales','sales','id_sales','nama_sales',null,'semua');
                echo '
                </td>
                </tr>
                <tr>
                <td>
                Pilih Tanggal laporan
                </td>
                <td>
                <input type="text" class="datetimepicker form-control" name="tanggal_awalsales" id="tanggal_awalsales">
                </td>
                   <td>
                <input type="text" class="datetimepicker form-control" name="tanggal_akhirsales" id="tanggal_akhirsales">
                </td>
                </tr>
            </table>
          </div>
          <div class="modal-footer">
                <button  class="btn btn-default" ><span class="glyphicon glyphicon-print"> Print</span></button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
      </form>
    </div>
  </div>
</div>
<!-- ##########################################################################################################  -->
      </div >
    </td>
    <td style="width: 50%;"> 
    <div >
   <!--   ################################################ Modal Untuk Laporan Arus Bank #############################################  -->
<button type="button" class="btn btn-default " title="Print" data-toggle="modal" data-target="#modalarusbank"><span class="glyphicon glyphicon-print"> Print</span></button>
<!-- Modal -->
<div id="modalarusbank" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title">Pilih Tanggal</h4>
      </div>
      <form method="post" action="modul/Laporan/cetakarusbank.php">
          <div class="modal-body">
            <table class="table">
                <tr>
                <td>
                Pilih Tanggal laporan
                </td>
                <td>
                <input type="text" class="datetimepicker form-control" name="arusbankawal" id="arusbankawal">
                </td>
                
                 <td>
                <input type="text" class="datetimepicker form-control" name="arusbankakhir" id="arusbankakhir">
                </td>
                </tr>
            </table>
          </div>
          <div class="modal-footer">
                <button  class="btn btn-default" ><span class="glyphicon glyphicon-print"> Print</span></button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
      </form>
    </div>
   
  </div>
</div>
<!-- ##########################################################################################################  -->
    </div >
    </td>
  </tr>
<!-- ##########################################################################################################  -->
 


 
</table>
	';

}
}
?>
<script type="text/javascript">
datetimepiker();
add_newitemcombobox('id_sales','Sales');
/*add_newitemcombobox('id_supplier','Supplier');
add_newitemcombobox('id_supplierkh','Supplier');
add_newitemcombobox('id_customerkp','Customer');*/
</script>
