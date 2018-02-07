<script type="text/javascript">
$(document).ready(function(){
  $('#id_akun').multipleSelect({
    filter: true
  });
});
</script>


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
<table style=" width: 100%; text-align:left">

  <tr class="success">
    <td style="width: 50%;"> 
      <div >
          <div class="text-info">
            	<h2><u>Laporan Jurnal Umum</u></h2>
          </div >
      	</div>
    </td>
    <td style="width: 50%;"> 
     <div >
      <div class="text-info">
         	  <h2><u>Laporan Buku Besar</u></h2>
      </div >
    </div>
    </td>
  </tr>
  <tr>
  <td>
    <div class="text-muted">
      <p>Laporan Jurnal umum, laporan yang berisi alur akun yang di dapat dari jurnal voucer dan transaksi</p>
    </div >
  </td>
  <td>
    <div class="text-muted">
      <p>Laporan buku besar, laporan yang meringkas setiap akun sehingga dapt mengetahui setiap saldo dalam akun</p>
    </div >
  </td>
</tr>
  <tr >
  <td > 
              <div >
                          <button type="button" class="btn btn-default" title="Print" data-toggle="modal" data-target="#modal_jurnal_umum"><span class="glyphicon glyphicon-print"> Print</span></button>
                          <!-- Modal -->
                          <div id="modal_jurnal_umum" class="modal fade" role="dialog">
                          <div class="modal-dialog modal-lg ">
                          <!-- Modal content-->
                          <div class="modal-content">
                          <div class="modal-header">
                          <h4 class="modal-title">Pilih Tanggal</h4>
                          </div>
                          <form method="post" action="modul/laporanakuntansi/cetak_jurnal_umum.php">
                          <div class="modal-body">
                          <table class="table">
                          <tr>
                          <td>Pilih Tanggal laporan</td>
                          <td><input type="text" class="datetimepicker form-control" name="tgl_ju_awal" id="tgl_ju_awal"></td>
                           <td><input type="text" class="datetimepicker form-control" name="tgl_ju_akhir" id="tgl_ju_akhir"></td>
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
                </div >
    </td>
    <td> 
               <div >
                          <button type="button" class="btn btn-default " title="Print" data-toggle="modal" data-target="#modal_buku_besar"><span class="glyphicon glyphicon-print"> Print</span></button>
                          <!-- Modal -->
                          <div id="modal_buku_besar" class="modal fade" role="dialog">
                          <div class="modal-dialog modal-lg">
                          <!-- Modal content-->
                          <div class="modal-content">
                          <div class="modal-header">
                          <h4 class="modal-title">Pilih Tanggal</h4>
                          </div>
                          <form method="post" action="modul/laporanakuntansi/cetak_buku_besar.php">
                          <div class="modal-body">
                          <table class="table">
                          <tr>
                          <td>
                          Pilih Tanggal laporan
                          </td>
                          <td>
                          <input type="text" class="datetimepicker form-control" name="buku_besar_awal" id="buku_besar_awal">
                          </td>

                          <td>
                          <input type="text" class="datetimepicker form-control" name="buku_besar_akhir" id="buku_besar_akhir">
                          </td>
                          </tr>
                          <tr><td>Akun Kas</td>
                               <td colspan="2" align="left">';
                          echo "<select id=id_akun name=id_pegawai[] multiple=multiple style=width:100%>";
                            $sql='SELECT * FROM akun_kas_perkiraan';
                            $query=mysql_query($sql);
                            while ( $p = mysql_fetch_array($query)) {
                              echo "<option value='$p[id_akunkasperkiraan]'>$p[nama_akunkasperkiraan]</option>";
                            }
                            echo '</select>
                          </td></tr>
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
           </div >
    </td>
  </tr>
  <tr class="success">
    <td style="width: 50%;"> 
        <div >
          <div class="text-info">
             <h2><u>Laporan Harian Kas Bank</u></h2>
          </div >
        </div>
    </td>
    <td style="width: 50%;"> 
       <div >
          <div class="text-info">
        <h2><u>Laporan Arus bank</u></h2>
          </div >
        </div>
    </td>
  </tr>
   <tr>
  <td>
       <div class="text-muted">
         <p>1. Laporan Transaksi Haria => Akun Yang Mempengaruhi Akun : Kas, Giro, dan Bank</p>
      </div >
  </td>
  <td>
     <div class="text-muted">
        <p>1. Laporan Arus Bank => Pengunaan Dan Penerimaan dana yang mempengaruhi Akun Bank</p>
     </div >
  </td>
</tr>
  <tr class="success">
  <td> 
              <div >
                          <button type="button" class="btn btn-default" title="Print" data-toggle="modal" data-target="#modalharian"><span class="glyphicon glyphicon-print"> Print</span></button>
                          <!-- Modal -->
                          <div id="modalharian" class="modal fade" role="dialog">
                          <div class="modal-dialog modal-lg ">
                          <!-- Modal content-->
                          <div class="modal-content">
                          <div class="modal-header">
                          <h4 class="modal-title">Pilih Tanggal</h4>
                          </div>
                          <form method="post" action="modul/laporan/cetakharian.php">
                          <div class="modal-body">
                          <table class="table">
                          <tr>
                          <td>
                          Pilih Tanggal laporan
                          </td>
                          <td>
                          <input type="text" class="datetimepicker form-control" name="awal_tanggal_harian" id="awal_tanggal_harian">
                          </td>
                           <td>
                          <input type="text" class="datetimepicker form-control" name="akhir_tanggal_harian" id="akhir_tanggal_harian">
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
             </div >
           </td>
    <td> 
              <div >
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
                </div >
    </td>
  </tr>
   <tr class="success">
    <td> 
      <div >
          <div class="text-info">
              <h2><u>Kartu Hutang Cetak</u></h2>
          </div >
        </div>
    </td>
    <td > 
        <div >
          <div class="text-info">
              <h2><u>Kartu Piutang Cetak</u></h2>
            </div >
        </div>
    </td>
  </tr>
 <tr>
  <td>
    <div class="text-muted">
      <p>Kartu Hutang =></br>
      1. Arus Hutang</br>
      2. Arus Pelunasan Hutang</p>
    </div >
  </td>
  <td>
      <div class="text-muted">
      <p>Kartu Piutang =></br>
      1. Arus Piutang</br>
      2. Arus Pelunasan Piutang</p>
    </div >
  </td>
</tr>
  <tr class="success">
    <td> 
             <div >
                          <button type="button" class="btn btn-default " title="Print" data-toggle="modal" data-target="#kartuhutang"><span class="glyphicon glyphicon-print"> Print</span></button>
                          <!-- Modal -->
                          <div id="kartuhutang" class="modal fade" role="dialog">
                          <div class="modal-dialog modal-lg ">
                          <!-- Modal content-->
                          <div class="modal-content">
                          <div class="modal-header">
                          <h4 class="modal-title">Pilih Tanggal</h4>
                          </div>
                          <form method="post" action="modul/Laporan/cetakkartuhutang.php">
                          <div class="modal-body">
                          <table class="table">
                          <tr>
                          <td>
                          Pilih Nama Supplier :
                          </td>
                          <td colspan="2">';
                          echo combobox('id_supplierkartuhutang','Supplier','id_supplier','nama_supplier',null,'Supplier','required');
                          echo '
                          </td>
                          </tr>
                          <tr>
                          <td>
                          Pilih Tanggal laporan
                          </td>
                          <td>
                          <input type="text" class="datetimepicker form-control" name="kartuhutangawal" id="kartuhutangawal">
                          </td>
                          <td>
                          <input type="text" class="datetimepicker form-control" name="kartuhutangakhir" id="kartuhutangakhir">
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
               </div >
    </td>
    <td> 
            <div >
                          <button type="button" class="btn btn-default " title="Print" data-toggle="modal" data-target="#kartupiutang"><span class="glyphicon glyphicon-print"> Print</span></button>
                          <!-- Modal -->
                          <div id="kartupiutang" class="modal fade" role="dialog">
                          <div class="modal-dialog modal-lg ">
                          <!-- Modal content-->
                          <div class="modal-content">
                          <div class="modal-header">
                          <h4 class="modal-title">Pilih Tanggal</h4>
                          </div>
                          <form method="post" action="modul/Laporan/cetakkartupiutang.php">
                          <div class="modal-body">
                          <table class="table">
                          <tr>
                          <td>
                          Pilih Nama Customer :
                          </td>
                          <td colspan="2">';
                          echo combobox('id_customerkartupiutang','Customer','id_customer','nama_customer',null,'Customer','required');
                          echo '
                          </td>
                          </tr>
                          <tr>
                          <td>
                          Pilih Tanggal laporan
                          </td>
                          <td>
                          <input type="text" class="datetimepicker form-control" name="kartupiutangawal" id="kartupiutangawal">
                          </td>
                          <td>
                          <input type="text" class="datetimepicker form-control" name="kartupiutangakhir" id="kartupiutangakhir">
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
               </div >
    </td>
  </tr>
</table>
  ';

}
}
?>
<script type="text/javascript">
datetimepiker();

add_newitemcombobox('id_supplierkartuhutang','Supplier');
add_newitemcombobox('id_customerkartupiutang','Customer');
$("#id_barangkartubarang").change(function(){
   var datastring = 'data='+$("#id_barangkartubarang").val();
  $.ajax({  
        url: "modul/laporan/ajax_kartubarang.php",             
        data: datastring, 
        success: function(response){                    
            $("#tsatuan").html(response); 
            //alert(response);
        }

    });
});
</script>


