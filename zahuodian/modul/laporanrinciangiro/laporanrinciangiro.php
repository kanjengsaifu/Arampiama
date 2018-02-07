
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
    $judul = "Rincian Giro";
    $desk = "Laporan Yang digunakan Untuk melihat Rincian Giro Dibuka ataupun Diterima";
    headerDeskripsi($judul,$desk);

	echo '
<table style=" width: 100%; text-align:left" >

  <tr class="success">
  <!--################################################  giro dibuka yg belum cair ################################################-->
    <td style="width: 50%;"> 
    <div >
    <div class="text-info">
       		<h2><u>Rincian Giro Dibuka </u></h2>
    </div >
    <div class="text-muted">
    <p>Rincian Giro Dibuka yang belum Cair pada Semua Supplier Berdasarkan Tanggal Periode</p>
    </div >
      	</div>
    </td>
    <!--################################################ end giro dibuka yg belum cair ################################################-->
    <!--################################################  giro diterima yg belum cair ################################################-->
    <td style="width: 50%;"> 
     <div >
    <div class="text-info">
       	  <h2><u>Rincian Giro Diterima</u></h2>
    </div >
    <div class="text-muted">
    <p>Rincian Giro Diterima yang belum Cair Semua Customer Berdasarkan Tanggal Periode</p>
   </div >
      	</div>
    </td>
    <!--################################################ end giro diterima yg belum cair ################################################-->
  </tr>
  <tr class="success">
  <!--################################################ modal giro dibuka yg belum cair ################################################-->
    <td style="width: 50%;"> 
    <div >
    <button type="button" class="btn btn-default" title="Print" data-toggle="modal" data-target="#bukalomcair"><span class="glyphicon glyphicon-print"> Print</span></button>
<!-- Modal -->
<div id="bukalomcair" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title">Pilih Tanggal</h4>
      </div>
      <form method="post" action="modul/laporanrinciangiro/cetakrinciangirodibuka.php">
          <div class="modal-body">
            <table class="table">
                            <tr>
                <td>
                Pilih FIlter
                </td>
                <td>';
                 echo combobox('id_supplier','supplier','id_supplier','nama_supplier',null,'Semua Supplier');
                 echo '
                </td>
                 <td>
                <select  id="ket" name="ket" class="chosen-select1 form-control">
                      <option value="0">Tampilkan Semua </option>
                      <option value="1">Giro Belum Cair</option>
                      <option value="2">Giro Sudah Cair</option>
                      <option value="3">Giro Di tolak</option>
                </select>
                <script>
                add_newitemcombobox("ket","Customer");
                </script>
                </td>
                </tr>
                <tr>
                <td>
                Pilih Tanggal laporan
                </td>
                <td>
                <input type="text" class="datetimepicker form-control" name="tglawalbuka1" id="tglawalbuka1">
                </td>
                 <td>
                <input type="text" class="datetimepicker form-control" name="tglakhirbuka1" id="tglakhirbuka1">
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
      <form method="post" action="modul/laporanrinciangiro/cetakrinciangiroditerima.php">
          <div class="modal-body">
            <table class="table">
                            <tr>
                <td>
                Pilih FIlter
                </td>
                <td>';
                 echo combobox('id_customer','customer','id_customer','nama_customer',null,'Semua Customer','required');
                 echo '
                </td>
                 <td>
                <select  id="ket2" name="ket2" class="chosen-select1 form-control">
                      <option value="0">Tampilkan Semua </option>
                      <option value="1">Giro Belum Cair</option>
                      <option value="2">Giro Sudah Cair</option>
                      <option value="3">Giro Di tolak</option>
                </select>
               <script>
                add_newitemcombobox("ket2","Customer");
                </script>
                </td>
                </tr>
                <tr>
                <td>
                Pilih Tanggal laporan
                </td>
                <td>
                <input type="text" class="datetimepicker form-control" name="tglawal1" id="tglawal1">
                </td>
                 <td>
                <input type="text" class="datetimepicker form-control" name="tglakhir1" id="tglakhir1">
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
       		<h2><u>Laporan Purchase Order Sisa</u></h2>
    </div >
    <div class="text-muted">
    <p>Laporan Yang berisi sisa Pemesanan yang belum diterima oleh UD. Melati</p>
    </div >
      	</div>
    </td>
    <td style="width: 50%;"> 
     <div >
    <div class="text-info">
       		<h2><u>Laporan Sales Order Sisa</u></h2>
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
<a type="button" class="btn btn-default " title="Print" href="modul/laporanrinciangiro/cetak_laporan_po_sisa.php"><span class="glyphicon glyphicon-print"> Print</span></a>
    </td>
    <!--################################################+++++++ modal untuk rincian giro diterima yg belom cair per cuss ################################################+++++++-->
<td>
<a type="button" class="btn btn-default " title="Print" href="modul/laporanrinciangiro/cetak_laporan_so_sisa.php"><span class="glyphicon glyphicon-print"> Print</span></a>
<!--################################################+++++++ end modal untuk rincian giro diterima yg belom cair per cuss ################################################+++++++-->
   </div >
    </td>
  </tr>
   <tr class="success">
    <td style="width: 50%;"> 
     <div >
    <div class="text-info">
          <h2><u>Laporan Daftar Tagihan Supplier</u></h2>
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
     <!--################################################+++++++ modal untuk rincian giro ditolak per customer ################################################+++++++-->
<button type="button" class="btn btn-default " title="Print" data-toggle="modal" data-target="#trimatolak"><span class="glyphicon glyphicon-print"> Print</span></button>
<!-- Modal -->
<div id="tolakpercus" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title">Pilih Tanggal</h4>
      </div>
      <form method="post" action="modul/laporanrinciangiro/cetakdaftarpenagihancustomer.php">
          <div class="modal-body">
            <table class="table">
            <tr>
            <td>
            Pilih Nama Customer :
            </td>
            <td>';
            echo combobox('id_customerdpc','customer','id_customer','nama_customer','-');
echo '
            </td>
              <td>
                <select  id="ketdpc" name="ket2dpc" class="chosen-select1 form-control">
                      <option value="0">Penagihan Piutang</option>
                      <option value="1">Penagihan Titipan</option>
                      <option value="2">Penagihan Retur</option>
                </select>
               <script>
                add_newitemcombobox("ketdpc","Customer");
                </script>
            </tr>
                <tr>
                <td>
                Pilih Tanggal laporan
                </td>
                <td>
                <input type="text" class="datetimepicker form-control" name="dpcawal" id="tolakpercusawal">
                </td>
                 <td>
                <input type="text" class="datetimepicker form-control" name="dpcakhir" id="tolakpercusakhir">
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
    <button type="button" class="btn btn-default " title="Print" data-toggle="modal" data-target="#tolakpercus"><span class="glyphicon glyphicon-print"> Print</span></button>
<!-- Modal -->
<div id="trimatolak" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
      <h4 class="modal-title">Pilih Tanggal</h4>
      </div>
      <form method="post" action="modul/rinciangiro/cetaktolakall.php">
          <div class="modal-body">
            <table class="table">
                <tr>
                <td>
                Pilih Tanggal laporan
                </td>
                <td>
                <input type="text" class="datetimepicker form-control" name="tolakawal" id="tolakawal">
                </td>
                 <td>
                <input type="text" class="datetimepicker form-control" name="tolakakhir" id="tolakakhir">
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
add_newitemcombobox('id_customer','Customer');
add_newitemcombobox('id_supplier','Supplier');
add_newitemcombobox('id_customerdpc','Supplier');
add_newitemcombobox('id_customerkp','Customer');


</script>
