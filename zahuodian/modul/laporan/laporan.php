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
    <td style="width: 50%;"> 
      <div>
        <div class="text-info">
          <h2><u>Laporan Rekapitulasi Penjualan Per Customer</u></h2>
        </div>
        <div class="text-muted">
          <p>Laporan Rekapitulasi Penjualan adalah laporan yang berisi data Penjualan pada Customer secara detail dengan menampilkan data barang harga dan nota invoice</p>
        </div>
      </div>
    </td>
    <td style="width: 50%;"> 
      <div>
        <div class="text-info">
           		<h2><u>Laporan Rekapitulasi Pembelian Per Supplier</u></h2>
        </div>
        <div class="text-muted">
          <p>Laporan Rekapitulasi Pembelian adalah laporan yang berisi data pembelian pada Supplier secara detail dengan menampilkan data barang harga dan nota invoice</p>
        </div>
      </div>
    </td>
  </tr>
  <tr class="success">
    <td style="width: 50%;"> 
      <div >
    <!--   ################################################ Modal Untuk Laporan Rekapitulasi Penjualan #############################################  -->
                          <button type="button" class="btn btn-default " title="Print" data-toggle="modal" data-target="#rekapitulasipenjualan"><span class="glyphicon glyphicon-print"> Print</span></button>
                          <!-- Modal -->
                          <div id="rekapitulasipenjualan" class="modal fade" role="dialog">
                          <div class="modal-dialog modal-lg ">
                          <!-- Modal content-->
                          <div class="modal-content">
                          <div class="modal-header">
                          <h4 class="modal-title">Pilih Tanggal</h4>
                          </div>
                          <form method="post" action="modul/Laporan/cetakrekapitulasipenjualan.php">
                          <div class="modal-body">
                          <table class="table">
                          <tr>
                          <td>
                          Pilih Nama Customer :
                          </td>
                          <td colspan="2">';
                          echo combobox('id_customerrekapitulasi','customer','id_customer','nama_customer',null,'Customer','required');
                          echo '
                          </td>
                          </tr>
                          <tr>
                          <td>
                          Pilih Tanggal laporan
                          </td>
                          <td>
                          <input type="text" class="datetimepicker form-control" name="rekapitulasipenjualanawal" id="rekapitulasipenjualanawal">
                          </td>
                          <td>
                          <input type="text" class="datetimepicker form-control" name="rekapitulasipenjualanakhir" id=rekapitulasipenjualanakhir">
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
<!-- ############################################################################################  -->
      </div >
    </td>
    <td style="width: 50%;"> 
      <div>
    <!--   ################################################ Modal Untuk Laporan Rekapitulasi Pembelian #############################################  -->
                          <button type="button" class="btn btn-default " title="Print" data-toggle="modal" data-target="#rekapitulasipembelian"><span class="glyphicon glyphicon-print"> Print</span></button>
                          <!-- Modal -->
                          <div id="rekapitulasipembelian" class="modal fade" role="dialog">
                          <div class="modal-dialog modal-lg ">
                          <!-- Modal content-->
                          <div class="modal-content">
                          <div class="modal-header">
                          <h4 class="modal-title">Pilih Tanggal</h4>
                          </div>
                          <form method="post" action="modul/Laporan/cetakrekapitulasipembelian.php">
                          <div class="modal-body">
                          <table class="table">
                          <tr>
                          <td>
                          Pilih Nama Customer :
                          </td>
                          <td colspan="2">';
                          echo combobox('id_supplierrekapitulasi','Supplier','id_supplier','nama_supplier',null,'Supplier','required');
                          echo '
                          </td>
                          </tr>
                          <tr>
                          <td>
                          Pilih Tanggal laporan
                          </td>
                          <td>
                          <input type="text" class="datetimepicker form-control" name="rekapitulasipembelianawal" id="rekapitulasipembelianawal">
                          </td>
                          <td>
                          <input type="text" class="datetimepicker form-control" name="rekapitulasipembelianakhir" id=rekapitulasipembelianakhir">
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
      </div>
    </td>
  </tr>
  <tr class="success">
  <!--################################################  giro dibuka yg belum cair ################################################-->
    <td style="width: 50%;"> 
      <div>
        <div class="text-info">
          <h2><u>Rincian Giro Dibuka </u></h2>
        </div>
        <div class="text-muted">
          <p>Rincian Giro Dibuka yang belum Cair pada Semua Supplier Berdasarkan Tanggal Periode</p>
        </div>
      </div>
    </td>
    <!--################################################ end giro dibuka yg belum cair ################################################-->
    <!--################################################  giro diterima yg belum cair ################################################-->
    <td style="width: 50%;"> 
      <div>
        <div class="text-info">
          <h2><u>Rincian Giro Diterima</u></h2>
        </div>
        <div class="text-muted">
          <p>Rincian Giro Diterima yang belum Cair Semua Customer Berdasarkan Tanggal Periode</p>
        </div>
      </div>
    </td>
    <!--################################################ end giro diterima yg belum cair ################################################-->
  </tr>
  <tr class="success">
  <!--################################################ modal giro dibuka yg belum cair ################################################-->
    <td style="width: 50%;"> 
    <div>
                                      <button type="button" class="btn btn-default" title="Print" data-toggle="modal" data-target="#bukalomcair"><span class="glyphicon glyphicon-print"> Print</span></button>
                                      <!-- Modal -->
                                      <div id="bukalomcair" class="modal fade" role="dialog">
                                      <div class="modal-dialog modal-lg">
                                      <!-- Modal content-->
                                      <div class="modal-content">
                                      <div class="modal-header">
                                      <h4 class="modal-title">Pilih Tanggal</h4>
                                      </div>
                                      <form method="post" action="modul/laporan/cetakrinciangirodibuka.php">
                                      <div class="modal-body">
                                      <table class="table">
                                      <tr>
                                      <td>
                                      Pilih FIlter
                                      </td>
                                      <td>';
                                      echo combobox('id_suppliergirobuka','supplier','id_supplier','nama_supplier',null,'Semua Supplier');
                                      echo '
                                      </td>
                                      <td>
                                      <select  id="ketgirodibuka" name="ketgirodibuka" class="chosen-select1 form-control">
                                      <option value="0">Tampilkan Semua </option>
                                      <option value="1">Giro Belum Cair</option>
                                      <option value="2">Giro Sudah Cair</option>
                                      <option value="3">Giro Di tolak</option>
                                      </select>
                                      <script>
                                      add_newitemcombobox("ketgirodibuka","Customer");
                                      </script>
                                      </td>
                                      </tr>
                                      <tr>
                                      <td>
                                      Pilih Tanggal laporan
                                      </td>
                                      <td>
                                      <input type="text" class="datetimepicker form-control" name="awalgirodibuka" id="awalgirodibuka">
                                      </td>
                                      <td>
                                      <input type="text" class="datetimepicker form-control" name="akhirgirodibuka" id="akhirgirodibuka">
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
<!--################################################ modal giro dibuka yg belum cair ################################################-->
      </div >
    </td>
    <td style="width: 50%;"> 
    <div >
   <!--################################################ modal giro diterima yg belum cair ################################################-->
                                      <button type="button" class="btn btn-default " title="Print" data-toggle="modal" data-target="#trimalomcair"><span class="glyphicon glyphicon-print"> Print</span></button>
                                      <!-- Modal -->
                                      <div id="trimalomcair" class="modal fade" role="dialog">
                                      <div class="modal-dialog modal-lg">
                                      <!-- Modal content-->
                                      <div class="modal-content">
                                      <div class="modal-header">
                                      <h4 class="modal-title">Pilih Tanggal</h4>
                                      </div>
                                      <form method="post" action="modul/laporan/cetakrinciangiroditerima.php">
                                      <div class="modal-body">
                                      <table class="table">
                                      <tr>
                                      <td>
                                      Pilih FIlter
                                      </td>
                                      <td>';
                                      echo combobox('id_customergiroterima','customer','id_customer','nama_customer',null,'Semua Customer');
                                      echo '
                                      </td>
                                      <td>
                                      <select  id="ketgiroditerima" name="ketgiroditerima" class="chosen-select1 form-control">
                                      <option value="0">Tampilkan Semua </option>
                                      <option value="1">Giro Belum Cair</option>
                                      <option value="2">Giro Sudah Cair</option>
                                      <option value="3">Giro Di tolak</option>
                                      </select>
                                      <script>
                                      add_newitemcombobox("ketgiroditerima","Customer");
                                      </script>
                                      </td>
                                      </tr>
                                      <tr>
                                      <td>
                                      Pilih Tanggal laporan
                                      </td>
                                      <td>
                                      <input type="text" class="datetimepicker form-control" name="awalgiroditerima" id="awalgiroditerima">
                                      </td>
                                      <td>
                                      <input type="text" class="datetimepicker form-control" name="akhirgiroditerima" id="akhirgiroditerima">
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
<!--################################################ end modal giro diterima yg belum cair ################################################-->
   <tr class="success">
    <td style="width: 50%;"> 
    <div >
    <div class="text-info">
          <h2><u>Laporan Sisa Purchase Order</u></h2>
    </div >
    <div class="text-muted">
    <p>Laporan Yang berisi sisa Pemesanan yang belum diterima oleh UD. Melati</p>
    </div >
        </div>
    </td>
    <td style="width: 50%;"> 
     <div >
    <div class="text-info">
          <h2><u>Laporan Sisa Sales Order</u></h2>
    </div >
    <div class="text-muted">
   <p>Laporan Yang berisi sisa Pemesanan yang belum dikirim oleh UD. Melati</p>
    </div >
        </div>
    </td>
  </tr>
  <tr class="success">
    <td style="width: 50%;"> 
    <div >
    <!--################################################+++++++ modal untuk rincian giro dibuka yg belom cair per supp ################################################+++++++-->
<a type="button" class="btn btn-default " title="Print" href="modul/laporan/cetak_laporan_po_sisa.php"><span class="glyphicon glyphicon-print"> Print</span></a>
    </td>
    <!--################################################+++++++ modal untuk rincian giro diterima yg belom cair per cuss ################################################+++++++-->
<td>
<a type="button" class="btn btn-default " title="Print" href="modul/laporan/cetak_laporan_so_sisa.php"><span class="glyphicon glyphicon-print"> Print</span></a>
<!--################################################+++++++ end modal untuk rincian giro diterima yg belom cair per cuss ################################################+++++++-->
   </div >
    </td>
  </tr>
   <tr class="success">
    <td style="width: 50%;"> 
     <div >
    <div class="text-info">
          <h2><u>Laporan Daftar Tagihan Supplier  </u></h2>
    </div >
    <div class="text-muted">
    <p>Rincian Giro Yang Ditolak Dari Semua Customer Berdasarkan Tanggal Periode</p>
     </div >
        </div>
    </td>
    <td style="width: 50%;"> 
    <div >
    <div class="text-info">
          <h2><u>Laporan Daftar Tagihan Customer</u></h2>
    </div >
    <div class="text-muted">
      <p>Rincian Giro Yang Ditolak per Customer Berdasarkan Tanggal Periode</p>
    </div >
        </div>
    </td>
  </tr>
  <tr class="success">
    <td style="width: 50%;"> 
    <div >
     <!--################################################+++++++ modal untuk Daftar Tagihan Supplier################################################+++++++-->
                                 <button type="button" class="btn btn-default " title="Print" data-toggle="modal" data-target="#modaldps"><span class="glyphicon glyphicon-print"> Print</span></button>
                                      <!-- Modal -->
                                      <div id="modaldps" class="modal fade" role="dialog">
                                      <div class="modal-dialog modal-lg">
                                      <!-- Modal content-->
                                      <div class="modal-content">
                                      <div class="modal-header">
                                      <h4 class="modal-title">Pilih Tanggal</h4>
                                      </div>
                                      <form method="post" action="modul/laporan/cetakdaftarpenagihansupplier.php">
                                      <div class="modal-body">
                                      <table class="table">
                                      <tr>
                                      <td>
                                      Pilih Nama supplier :
                                      </td>
                                      <td>';
                                      echo combobox('id_supplierdps','supplier','id_supplier','nama_supplier',null,'supplier','required');
                                      echo '
                                      </td>
                                      <td>
                                      <select  id="ketdps" name="ketdps" class="chosen-select1 form-control">
                                      <option value="0">Penagihan Hutang</option>
                                      <option value="1">Penagihan Titipan</option>
                                      <option value="2">Penagihan Retur</option>
                                      <option value="3">Penagihan Semua</option>
                                      </select>
                                      <script>
                                      add_newitemcombobox("ketdps","supplier");
                                      </script>
                                      </tr>
                                      <tr>
                                      <td>
                                      Pilih Tanggal laporan
                                      </td>
                                      <td>
                                      <input type="text" class="datetimepicker form-control" name="dpsawal" id="dpsawal">
                                      </td>
                                      <td>
                                      <input type="text" class="datetimepicker form-control" name="dpsakhir" id="dpsakhir">
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
              <button type="button" class="btn btn-default " title="Print" data-toggle="modal" data-target="#modaldpc"><span class="glyphicon glyphicon-print"> Print</span></button>
                                      <!-- Modal -->
                                      <div id="modaldpc" class="modal fade" role="dialog">
                                      <div class="modal-dialog modal-lg">
                                      <!-- Modal content-->
                                      <div class="modal-content">
                                      <div class="modal-header">
                                      <h4 class="modal-title">Pilih Tanggal</h4>
                                      </div>
                                      <form method="post" action="modul/laporan/cetakdaftarpenagihancustomer.php">
                                      <div class="modal-body">
                                      <table class="table">
                                      <tr>
                                      <td>
                                      Pilih Nama Customer :
                                      </td>
                                      <td>';
                                      echo combobox('id_customerdpc','customer','id_customer','nama_customer',null,'customer','required');
                                      echo '
                                      </td>
                                      <td>
                                        <select  id="ketdpc" name="ketdpc" class="chosen-select form-control">
                                        <option value="0">Penagihan Piutang</option>
                                        <option value="1">Penagihan Titipan</option>
                                        <option value="2">Penagihan Retur</option>
                                        <option value="3">Penagihan Semua</option>
                                        </select>
                                      </td>
                                      </tr>
                                      <tr>
                                      <td>
                                      Pilih Tanggal laporan
                                      </td>
                                      <td>
                                      <input type="text" class="datetimepicker form-control" name="dpcawal" id="dpcawal" required>
                                      </td>
                                      <td>
                                      <input type="text" class="datetimepicker form-control" name="dpcakhir" id="dpcakhir" required>
                                      </td>
                                      </tr>
                                      <tr>
                                      <td>
                                      Pilih Sales
                                      </td>
                                      <td colspan=2>';
                                      echo combobox('id_salesdpc','sales','id_sales','nama_sales',null,'sales','');
                                      echo '
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
  <!--   ################################################ Laporan Harian #############################################  -->
    <td style="width: 50%;"> 
    <div >
    <div class="text-info">
          <h2><u>Laporan Penjualan Nota Per Sales</u></h2>
    </div >
    <div class="text-muted">
    <p>1. Memilih Semua Sales => Mencetak Semua Penjulan yang dilakukan oleh Setiap Salse</br>
    2. Memilih Satu Sales => Mencetak Penjualan Sales Secara Individu</p>
    </div >
        </div>
    </td>
    <!--   ################################################ Laporan Harian #############################################  -->
    <!--   ################################################ Laporan Arus Bank #############################################  -->
    <td style="width: 50%;"> 
     <div >
    <div class="text-info">
          <h2><u>Laporan Kartu Barang</u></h2>
    </div >
    <div class="text-muted">
    <p>1. Cetak Kartu Barang Tanpa Memilih Barang => Mencetak semua saldo barang selama periode tersebut</br>
    2. Cetak Kartu Barang Dengan Memilih Barang => Mencetak Arus Barang Yang telah di Pilih</p>
   </div >
        </div>
    </td>
    <!--   ####### Laporan Arus Bank ##############  -->
  </tr>
  <tr class="success">
  <!--   ####### Modal Untuk Laporan Harian #########  -->
    <td style="width: 50%;"> 
    <div >
                                                    <button type="button" class="btn btn-default" title="Print" data-toggle="modal" data-target="#modalsales"><span class="glyphicon glyphicon-print"> Print</span></button>
                                                    <!-- Modal -->
                                                    <div id="modalsales" class="modal fade" role="dialog">
                                                    <div class="modal-dialog ">
                                                    <!-- Modal content-->
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                    <h4 class="modal-title">Pilih Tanggal</h4>
                                                    </div>
                                                    <form method="post" action="modul/laporan/cetakpenjualansales.php">
                                                    <div class="modal-body">
                                                    <table class="table">
                                                     <tr>
                                                    <td>
                                                    Pilih Laporan
                                                    </td>
                                                    <td colspan="2">
                                                    <select  id="jenis-laporan-sales" name="jenis-laporan-sales" class="chosen-select form-control">
                                                    <option value="0">Laporan Penjualan</option>
                                                    <option value="1">Laporan Komisi</option>
                                                    </select>
                                                    </td>
                                                    </tr>
                                                    <tr>
                                                    <td>
                                                    Pilih Sales
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
   <!--   ########## Modal Untuk Laporan Arus Bank ##########  -->
<button type="button" class="btn btn-default " title="Print" data-toggle="modal" data-target="#modalkartubarang"><span class="glyphicon glyphicon-print"> Print</span></button>

<div id="modalkartubarang" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

                                              <div class="modal-content">
                                              <div class="modal-header">
                                              <h4 class="modal-title">Pilih Tanggal</h4>
                                              </div>
                                              <form method="post" action="modul/Laporan/cetakkartubarang.php">
                                              <div class="modal-body">
                                              <table class="table">
                                              <tr>
                                              <td>
                                              Pilih Barang
                                              </td>
                                                   <td colspan="2">
                                                    <select class="chosen-select" id="jenis_laporan" name="jenis_laporan" >
                                                    <option value="general">Laporan Semua Barang</option>
                                                    <option value="barang_kantor">Laporan Barang Kantor</option>
                                                    <option value="barang_gudang">Laporan Barang Gudang</option>
                                                    </select>
                                              </tr>
                                              <tr>
                                              <td>
                                              Pilih Tanggal laporan
                                              </td>
                                              <td>
                                              <input type="text" class="datetimepicker form-control" name="awalkartubarang" id="awalkartubarang">
                                              </td>

                                              <td>
                                              <input type="text" class="datetimepicker form-control" name="akhirkartubarang" id="akhirkartubarang">
                                              </td>
                                              </tr>
<tr>
  <td>Filter</td><td colspan="2"><input type=hidden id=id_barang name=id_barang>
                                <span id="nama_barang" >Semua Barang</span>
                            </td>
</tr>
<tr>
<td></td>
<td colspan=2> <select class="chosen-select" id="satuan" name="satuan" >
        <option value="12@LS"> LS (12)</option>
        <option value="20@DS"> DS (20)</option>
        </select></td>
</tr>
<tr>
<td></td>
<td colspan=2> <select class="chosen-select" id="merk_kartu_barang" name="merk_kartu_barang" >
        <option value=""> Pilih Merk</option>';
$query="Select * from merk where is_void=0";
$result=mysql_query($query);
while ($r=mysql_fetch_array($result)) {
echo '<option value="'.$r[id_merk].'@'.$r[merk].'">'.$r[merk].'</option>';
}
echo '</select></td>
</tr>
<tr>
<td></td>
<td colspan=2> <select class="chosen-select" id="kategori_kartu_barang" name="kategori_kartu_barang" >
        <option value=""> Pilih Kategori</option>';
$query="Select * from kategori where is_void=0";
$result=mysql_query($query);
while ($r=mysql_fetch_array($result)) {
echo '<option value="'.$r[id_kategori].'@'.$r[kategori].'">'.$r[kategori].'</option>';
}
  echo' </select></td>
</tr>
<tr>
<td></td>
<td colspan=2> <select class="chosen-select" id="kode_supplier_kartu_barang" name="kode_supplier_kartu_barang" >
        <option value=""> Pilih Supplier</option>';
$query="Select * from Supplier where is_void=0";
$result=mysql_query($query);
while ($r=mysql_fetch_array($result)) {
echo '<option value="'.$r[kode_supplier].'@'.$r[nama_supplier].'">'.$r[nama_supplier].'</option>';
}
  echo' </select></td>
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
echo'
<div id="search-md" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

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
                    <th>Pilih</th>
                </tr>
        </thead>
      </table>

          </div>
      </div>
    </div>

  </div>
</div>
  ';
}
}
?>
<script type="text/javascript">
$("#jenis_laporan").change(function() {
if (($(this).val())!='general') {
  $('#search-md').modal(); 
}else{
   $('#id_barang').val('');
    $('#nama_barang').text('Semua Barang');
}
});

function addMore(kode) {
  var id = kode;
  var dataString = 'test='+ id;
        $.ajax
                ({
                url: 'modul/laporan/catch.php',
                data: dataString,
                cache: false,
                success: function(r)
                          {                                 
                      $y = r.split(" ## ");
          $('#id_barang').val($y[0]);
          $('#nama_barang').text($y[1]);
              } 
                });
                $('#search-md').modal('toggle'); 
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
                    "ajax": "modul/laporan/tampil_barang.php",
                    "order": [[1, 'asc']],
                     "columns": [
                        { "searchable": false },
                        null,
                        null,
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
datetimepiker();

</script>


