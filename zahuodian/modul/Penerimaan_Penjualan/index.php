<?php
  include "config/coneksi.php";
  error_reporting(E_ALL ^ E_NOTICE);
  session_start();
  if (empty($_SESSION['username']) AND empty($_SESSION['password'])) {
      echo "
   <center>Untuk mengakses modul, Anda harus login <br>";
      echo "<a href=../../index.php><b>LOGIN</b></a></center>";
  } else {
      $_ck = (array_search("4", $_SESSION['lvl'], true));
      if ($_ck == '') {
          echo "Modul tidak boleh diakses anda";
      } else {
          $aksi = "modul/pembayaran_penjualan/aksi_pembayaranpenjualan.php";
          echo '<link rel="stylesheet" href="asset/css/layout.css">';
          switch ($_GET['act']) {
              
              default:
                  $judul  = "Master Pembayaran Penjualan";
                  $desk   = "List Nota Penerimaan Pembayaran dari Customer yang telah di buat l";
                  $button = "
    <a href='?module=pembayaranpenjualan&act=tambah' class='btn btn-primary' >Buat Transaksi <span class='glyphicon glyphicon-plus' aria-hidden='true'></span></a><span class='success'>";
                  headerDeskripsi($judul, $desk, $button);
                  echo '
     <div class="table-responsive">
  <table id="pay_tampil" class="table table-hover table-bordered" cellspacing="0">
          <thead>
    <tr style="background-color:#F5F5F5;">
   <th id="tablenumber">No</th>
            <th>Nama Customer</th>
            <th>No. Bukti Pembayaran</th>
            <th>Nominal Pembayaran</th>
            <th title="correct">akun kas</th>
            <th>Ket</th>
            <th>Status</th>
            <th style="width:70px;">Alokasi</th>
            <th style="width:70px;">hapus</th>
            </tr>
          </thead>
      </table>
    </div>';
                  break;
              
              case "tambah";
                  $judul  = "Master Pembayaran Penjualan";
                  $desk   = "ini adalah modul berisi List Invoice yang belum dibayar l";
                  $button = '  ';
                  headerDeskripsi($judul, $desk, $button);
                  echo '
  
        <div class="panel-group" id="accordion">
          <div class="panel panel-default"  style="border-radius: 0px 0px 25px 25px;">
                       <div class="panel-heading">
                       <div class="row">
                            <div class="col-md-2 tombol-header">
                                  <a data-toggle="collapse" data-parent="#accordion" href="#bkm" class="btn btn-sm btn-primary"><b>Bukti Kas Masuk</b></a>
                            </div>
                            <div class="col-md-2 tombol-header">
                                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="btn btn-sm btn-info"><b>Bukti Giro Masuk</b></a>
                            </div>
                            <div class="col-md-2 tombol-header">
                                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" class="btn btn-sm btn-second"><b>Bukti Bank Masuk</b></a>
                            </div>
                        </div>
                        </div>
                          <!--<div id="bk," class="panel-collapse collapse in"> ## otomatis open-->
                          <div id="bkm" class="panel-collapse collapse in">
                          <div class="panel-body" style="border-top: 15px solid #275E8E;"> 
                          <!--  ################### BKM ###################  -->
                          <p class="deskripsi" style="color:#000;">Form pembuatan laporan <b>Bukti Kas Masuk</b>.</p>
                          <hr class="deskripsihr" style="margin-bottom:0px;">
                          <form class="submit_data_header">
                                        <table class="table table-hover table_without_top" border=0 id=tambah>
                                                  <tr style="border-bottom:1px solid #ddd;">
                                        <td class="kop_td">Akun Kas</td>
                                        <td id="sup" colspan="3">';
                  $selectakun = mysql_query("Select * from setting_akun where id=2");
                  $o          = mysql_fetch_array($selectakun);
                  echo comboboxextra('akun_kas', '*,concat(kode_akun," - ",nama_akunkasperkiraan)  as tampil', 'akun_kas_perkiraan', 'is_void=0', 'id_akunkasperkiraan', 'tampil', $o['akses'], '0');
                  echo '  <script>add_newitemcombobox("akun_kas","akun_kas");</script>
                                                </td>
                                                 <td colspan="2"></td>
                                       </tr>
                                         <tr>
                                                <td class="kop_td">No. Bukti </td>';
                  $query2 = mysql_query("SELECT bukti_bayarjual FROM `trans_bayarjual_header` where bukti_bayarjual like 'BKM%' order by id_bayarjual desc limit 1 ");
                  $kode   = mysql_fetch_array($query2);
                  $kode   = kode_pembayaran($kode['bukti_bayarjual'], "BKM", null);
                  
                  echo '
                                                <td class="batas_header_form"><input name="no_bukti"  id="no_bukti" class="form-control" value="' . $kode . '"  required></td>
                                                 <td class="kop_td">Tanggal bayar</td>
                                                <td><input name="tgl" value="' . date("Y-m-d") . '" class="datetimepicker form-control"></td>
                                        </tr>
                                          <tr>
                                                 <td class="kop_td">Nominal</td>
                                                 <td class="batas_header_form" id="sup">
                                                 <input name="nominal" class="form-control numberhit"  required>
                                                  <td  class="kop_td">Titipan <span><input value="T" type="checkbox" id="titipancheckbox" name="titipancheckbox"/></span></td>
                                                 <td id="ttitipancheckbox" name= "ttitipancheckbox" ></td>';
                  
                  echo '
                                       </tr>
                                        <tr>
                                                  <td colspan="4">
                                                        <label>Keterangan</label>
                                                        <textarea id="ket" name="ket" class="form-control"></textarea>
                                                  </td>
                                        </tr>
                              </table>
                            <input id="simpan" class="btn btn-success" type="submit" value=Simpan>
                          </form>
                      </div><!-- ######## end panel body ######## -->
                  </div>
          </div>
          
          <div class="panel panel-default" style="border-radius: 0px 0px 25px 25px;">
              <div id="collapseTwo" class="panel-collapse collapse">
                  <div class="panel-body" style="border-top: 15px solid #40B5D7;">
                  <!--  ################### BGM ###################  -->
                         <p class="deskripsi" style="color:#000;">Form pembuatan laporan <b>Bukti Giro Masuk</b>.</p>
                          <hr class="deskripsihr" style="margin-bottom:0px;">
                          <form class="submit_data_header">
                                        <table class="table table-hover table_without_top" border=0 id=tambah>
                                                                              <tr><td> Akun Kas </td> <td colspan="3">';
                  $selectakun = mysql_query("Select * from setting_akun where id=4");
                  $o          = mysql_fetch_array($selectakun);
                  echo comboboxextra('akun_kas', '*,concat(kode_akun," - ",nama_akunkasperkiraan)  as tampil', 'akun_kas_perkiraan', 'is_void=0', 'id_akunkasperkiraan', 'tampil', $o['akses'], '0', '1');
                  echo '
                                                </td>
                                       </tr>
                                         <tr>
                                                <td class="kop_td">No. Bukti </td>';
                  $query2 = mysql_query("SELECT bukti_bayarjual FROM `trans_bayarjual_header` where bukti_bayarjual like 'BGM%' order by id_bayarjual desc limit 1 ");
                  $kode   = mysql_fetch_array($query2);
                  $kode   = kode_pembayaran($kode['bukti_bayarjual'], "BGM", null);
                  
                  echo '
                                                <td class="batas_header_form"><input name="no_bukti"  id="no_bukti" class="form-control" value="' . $kode . '"  required></td>
                                                 <td class="kop_td">Tanggal bayar</td>
                                                <td><input name="tgl" value="' . date("Y-m-d") . '" class="datetimepicker form-control"></td>
                                        </tr>';
                  echo '
                                          <tr>
                                                 <td class="kop_td">No Giro</td>
                                                <td><input id="no_giro" name="no_giro" class="form-control" required></td>
                                                 <td class="kop_td">Tanggal jatuh tempo</td>
                                                <td><input id="jatuh_tempo" name="jatuh_tempo" class="datetimepicker form-control" required>
                                                <input type="hidden" value="1" id="status_giro" name="status_giro" class="datetimepicker form-control"></td>
                                       </tr>
                                        <tr>
                                                 <td class="kop_td">Nama Bank Giro</br>
                                                 Tersebut</td>
                                                <td><input id="nama_bank" name="nama_bank" class="form-control" required></td>
                                                 <td class="kop_td">Nama Customer </br> Penanggung Jawab</td>
                                                <td>';
                  echo comboboxeasy('id_customer', ' Select *,concat(nama_customer,"(",telp_customer,")") as nama from customer where is_void=0', 'Pilih Customer', 'id_customer', 'nama', null);
                  
                  
                  echo '
                                                </td>
                                       </tr>
                                       <tr>
                                        <td class="kop_td">Nominal</td>
                                                 <td class="batas_header_form">
                                                 <input name="nominal" class="form-control numberhit"  required>
                                        </td>
                                        <td  class="kop_td">Titipan <span><input value="T" type="checkbox" id="titipancheckbox" name="titipancheckbox"/></span></td>
                                      
                                       </tr>
                                        <tr>
                                                  <td colspan="4">
                                                        <label>Keterangan</label>
                                                        <textarea id="ket" name="ket" class="form-control"></textarea>
                                                  </td>
                                        </tr>
                              </table>
  <input id="simpan" class="btn btn-success" type="submit" value=Simpan>
                          </form>
                  </div><!-- ######## end panel body ######## -->
              </div>
          </div>
          <div class="panel panel-default" style="border-radius: 0px 0px 25px 25px;">
              <div id="collapseThree" class="panel-collapse collapse">
                  <div class="panel-body" style="border-top: 15px solid #9933CC;"><!--  ################### BBM ###################  -->
                        <p class="deskripsi" style="color:#000;">Form pembuatan laporan <b>Bukti Bank Masuk</b>.</p>
                          <hr class="deskripsihr" style="margin-bottom:0px;">
                          <form class="submit_data_header">
                                        <table class="table table-hover table_without_top" border=0 id=tambah>
                                    <tr style="border-bottom:1px solid #ddd;">
                                        <td class="kop_td">Akun Kas</td>
                                        <td id="sup" colspan="3">';
                  $selectakun = mysql_query("Select * from setting_akun where id=3");
                  $o          = mysql_fetch_array($selectakun);
                  echo comboboxextra('akun_kas', '*,concat(kode_akun," - ",nama_akunkasperkiraan)  as tampil', 'akun_kas_perkiraan', 'is_void=0', 'id_akunkasperkiraan', 'tampil', $o['akses'], '0', '1');
                  echo '
                                                </td> 
                                                 <td colspan="2"></td>
                                       </tr>
                                         <tr>
                                                <td class="kop_td">No. Bukti </td>';
                  $query2 = mysql_query("SELECT bukti_bayarjual FROM `trans_bayarjual_header` where bukti_bayarjual like 'BBM%' order by id_bayarjual desc limit 1 ");
                  $kode   = mysql_fetch_array($query2);
                  $kode   = kode_pembayaran($kode['bukti_bayarjual'], "BBM", null);
                  
                  echo '
                                                <td class="batas_header_form"><input name="no_bukti"  id="no_bukti" class="form-control" value="' . $kode . '"  required></td>
                                                 <td class="kop_td">Tanggal bayar</td>
                                                <td><input name="tgl" value="' . date("Y-m-d") . '" class="datetimepicker form-control"></td>
                                        </tr>
                                        <tr>
                                                <td class="kop_td">Rek. Asal </td>
                                                <td class="batas_header_form"><input id="rek_tujuan" name="rek_tujuan" class="form-control" required></td>
                                                <td class="kop_td">Atas Nama</td>
                                                 <td><input id="ac_tujuan" name="ac_tujuan" class="form-control" required></td>
                                        </tr>
                                          <tr>
                                                 <td class="kop_td">Nominal</td>
                                                 <td class="batas_header_form">
                                                 <input name="nominal" class="form-control numberhit"  required>
                                               <td  class="kop_td">Titipan <span><input value="T" type="checkbox" id="titipancheckbox2" name="titipancheckbox"/></span>
                                                </td><td id="ttitipancheckbox2" name= "ttitipancheckbox2" ></td>';
                  
                  echo '
                                       </tr>
                                        <tr>
                                                  <td colspan="4">
                                                        <label>Keterangan</label>
                                                        <textarea id="ket" name="ket" class="form-control"></textarea>
                                                  </td>
                                        </tr>
                              </table>
  <input id="simpan" class="btn btn-success" type="submit" value=Simpan>
                          </form>
                  </div><!-- ######## end panel body ######## -->
              </div>
          </div>
      </div>';
                  
                  echo "<script>
      add_newitemcombobox('id_customer','Customer');
    </script>";
                  break;
              
              
              
              
              
              //#################################  Alokasi
              //
              //
              //
              //
              case "alokasi":
                  if (isset($_GET['id'])) {
                      $id_pay = $_GET['id'];
                  }
                  ///////////////////////////////////////////////////////////////////////////////////////////////////////////  49 UANG MUKA penjualan AKTIVA
                  $query67    = mysql_query("SELECT *,ket_jual as ket, tb.nominal_alokasi_jual as total_alokasi,if(`status_titipan`='T','49',tb.`id_akunkasperkiraan`) as id_akun_header FROM trans_bayarjual_header tb left join akun_kas_perkiraan ak on(tb.id_akunkasperkiraan=ak.id_akunkasperkiraan) where id_bayarjual='$id_pay'");
                  $my         = mysql_fetch_array($query67);
                  $jenisbayar = explode(" - ", $my['bukti_bayarjual']);
                  echo ' 
  <div class="row">
    <div class="col-md-6">
        <h2>Alokasi Pembayaran</h2>
         <p class="deskripsi"> List alokasi pembayaran</p>
    </div>
  </div>
  <form id="submit_data">
  <input type="hidden" name="id_bayarjual" value="' . $my['id_bayarjual'] . '" id="id_bayarjual">
  <input type="hidden" id="status_titipan" name="status_titipan" value="' . $my['status_titipan'] . '" >
  <hr class="deskripsihr" style="margin-bottom:0px;">
  <table id="tableheader" class="table table-hover table_without_top" border=0>
  <tr>
        <td class="kop_td">No. Bukti </td>
        <td><strong>:</strong></td>
        <td class="batas_header_form"><strong>' . $my['bukti_bayarjual'] . '</strong>
        <input name="no_bukti"  id="no_bukti" class="form-control" value="' . $my['bukti_bayarjual'] . '"  type="hidden" required readonly></td>
        <td class="kop_td">Akun Kas</td>
              <td><strong>:</strong></td>
        <td><strong>' . $my['kode_akun'] . ' - ' . $my['nama_akunkasperkiraan'] . '</strong>
        <input class="form-control" name="id_akun" value="' . $my['id_akun_header'] . '" readonly type="hidden"></td>
  </tr>
  <tr>
         <td class="kop_td">Tanggal bayar</td>
         <td><strong>:</strong></td>
        <td class="batas_header_form"><strong>' . tanggalan($my['tgl_pembayaranjual']) . '</strong>
        <input type="hidden" name="tgl" value="' . $my['tgl_pembayaranjual'] . '"  class="datetimepicker form-control" readonly></td>
        <td class="kop_td">Nominal</td>
        <td><strong>:</strong></td>
        <td ><strong>' . format_rupiah($my['nominaljual']) . '</strong>
        <input name="nominal" class="numberhit"  id="total_pembayaran"  type="hidden" value="' . $my['nominaljual'] . '" readonly></ td>
  </tr>';
                  if ($jenisbayar[0] == 'BGM') {
                      echo '
  <tr>
        <td class="kop_td">Nama Bank</td>
        <td><strong>:</strong></td>
        <td class="batas_header_form"><strong>' . $my['nama_bank'] . '</strong>
           <input id="ac_tujuan" type="hidden"  name="ac_tujuan" class="form-control" value="' . $my['nama_bank'] . '" required readonly></td>
            <td class="kop_td">No Giro</td>
            <td><strong>:</strong></td>
        <td><strong>' . $my['no_giro_jual'] . '</strong>
       <input id="no_giro" type="hidden" name="no_giro" class="form-control" value="' . $my['no_giro_jual'] . '" required readonly></td>
  </tr>
  <tr>
          <td class="kop_td">Tanggal jatuh tempo</td>
          <td><strong>:</strong></td>
        <td><strong>' . tanggalan($my['jatuh_tempo_jual']) . '</strong>
        <input type="hidden" name="jatuh_tempo" class="form-control" value="' . $my['jatuh_tempo_jual'] . '" required readonly>
        <input type="hidden" value="1" id="status_giro" name="status_giro" class="datetimepicker form-control"></td>
  </tr>';
                  } elseif ($jenisbayar[0] == 'BBM') {
                      echo '
  <tr>
      <td class="kop_td">No. Rekening Pengirim </td>
      <td><strong>:</strong></td>
      <td class="batas_header_form"><strong>' . $my['rek_asal'] . '</strong>
      <input id="rek_tujuan" type="hidden" name="rek_tujuan" class="form-control" value="' . $my['rek_asal'] . '" required readonly></td>
      <td class="kop_td">Atas Nama</td>
      <td><strong>:</strong></td>
       <td><strong>' . $my['ac_asal'] . '</strong>
       <input id="ac_tujuan" name="ac_tujuan" type="hidden" class="form-control" value="' . $my['ac_asal'] . '" required readonly></td>
  </tr> ';
                  }
                  echo '
  <tr>
        <td class="kop_td">Keteranga</td>
     <td><strong>:</strong></td>
        <td>' . $my['ket'] . '
        <input type="hidden" value="' . $my['ket'] . '" name="ket"  readonly></td>
  </tr>
  </table>
     <a class="btn btn-success" title="menambahkam invoice" onclick="detail()"><span class="glyphicon glyphicon-plus"></span>Tambah Invoice</a>
     <a class="btn btn-success" title="menambahkam akun" onclick="detail_akun()"><span class="glyphicon glyphicon-plus"></span>Tambah Akun</a>';
                  //####################################################  ALOKAISI DETAIL ##########
                  echo '
  <table id="alokasi_tampil" class="table table-hover table-bordered" cellspacing="0">
  <thead>
    <tr style="background-color:#F5F5F5;">
      <th id="tablenumber">No</th>
      <th>Akun Kas Perkiraan</th>
        <th>Nama customer</th>
      <th>No. Invoice</th>
      <th>Nominal Invoice</th>
      <th>Sisa Invoice</th>
      <th>Total</th>
      <th id="tablenumber">Aksi</th>
    </tr>
  </thead>
  <tbody id="tambahrow">';
                  $detail_alokasi = ("
            SELECT (count(*)) as nomor FROM `trans_bayarjual_detail` a  where  `bukti_bayarjual`='" . $my['bukti_bayarjual'] . "'
            ");
                  $result         = mysql_query($detail_alokasi);
                  $nomor          = mysql_fetch_array($result);
                  
                  echo '
  </tbody>
  <input type="hidden"    id="counter" value="' . $nomor['nomor'] . '" >
  <tfood id="noborder" style="border-top:1px solid #000;">
        <tr>
          <td colspan="4"></td>
          <td><b>Total :</b></td>
          <td><input type="text" name="sisa_totalinvoice"   id="total_sisainvoice"  class="form-control hitung" readonly></td>
          <td colspan="1"><input type="text" name="total_alokasi"  value="' . $my['total_alokasi'] . '"  id="total_alokasi"  class="form-control hitung numberhit" readonly></td>
        </tr>
        <tr>
          <td colspan="5"></td>
          <td><b>Sisa Alokasi :</b></td>
          <td colspan="1"><input type="text" name="sisa_alokasi"  value="' . $my['sisa_alokasi'] . '"  id="sisa_alokasi"  class="form-control hitung numberhit" readonly></td>
        </tr>
  </tfood>
  </table>
      <div style="float: right;">
              <button class="btn btn-success" data-toggle="modal"  id="simpan">Simpan</button>
              <a class="btn btn-warning" type="button" href="media.php?module=pembayaranpenjualan" >Batal</a>
      </div>';
                  break;
          }
  ?>
<!-- //////////////////////////Modal /////////////////////////////////-->
<!-- //////////////////////////////// /////////////////////////////////-->
<div class="modal fade" id="modalinvoice" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" style="color:red;" data-dismiss="modal">Batal &times;</button>
        <h4 class="modal-title"><b>Pilih No Invoice</b></h4>
      </div>
      <div class="modal-body">
        <table id="modalnoinvoice" border="1" class="table table-hover" style="width: 100%;">
          <thead>
            <tr style="background-color:#F5F5F5;">
              <th id="tablenumber">No</th>
              <th>customer</th>
              <th>No Invoice</th>
              <th>Tanggal</th>
              <th>Grand Total</th>
              <th>Aksi</th>
            </tr>
          </thead>
        </table>
      </div>
      <!-- ############## end Modal body -->
    </div>
    <!-- ############## end Modal content -->      
  </div>
  <!-- ############## end Modal dialog -->    
</div>
<!-- ############## end Modal fade-->
<div class="modal fade" id="modalakun" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" style="color:red;" data-dismiss="modal">Batal &times;</button>
        <h4 class="modal-title"><b>Pilih No Invoice</b></h4>
      </div>
      <div class="modal-body">
        <table id="modalnoakun" border="1" class="table table-hover" style="width: 100%;">
          <thead>
            <tr style="background-color:#F5F5F5;">
              <th id="tablenumber">No</th>
              <th>Kode Akun</th>
              <th>Nama Akun</th>
              <th>Aksi</th>
            </tr>
          </thead>
        </table>
      </div>
      <!-- ############## end Modal body -->
    </div>
    <!-- ############## end Modal content -->      
  </div>
  <!-- ############## end Modal dialog -->    
</div>
<!-- ############## end Modal fade-->

 <div class="modal fade" id="modalakuninvoice" role="dialog">
          <div class="modal-dialog modal-lg">                                                  
            <!-- Modal content-->
            <div class="modal-content">
                  <div class="modal-header">
                        <button type="button" class="close" style="color:red;" data-dismiss="modal">Batal &times;</button>
                        <h4 class="modal-title"><b>Pilih No Invoice</b></h4>
                  </div>
                  <div class="modal-body">
                  <table id="modalnoakuninvoice" border="1" class="table table-hover" style="width: 100%;">
                      <thead>
                            <tr style="background-color:#F5F5F5;">
                                    <th id="tablenumber">No</th>
                                    <th>Kode Akun</th>
                                    <th>Nama Akun</th>
                                    <th>Aksi</th>
                            </tr>
                      </thead>
    
                  </table>
                  </div><!-- ############## end Modal body -->
            </div><!-- ############## end Modal content -->      
          </div><!-- ############## end Modal dialog -->    
    </div><!-- ############## end Modal fade-->
<?php
  }
  }
  ?>
<script type="text/javascript">
  $('#titipancheckbox').click(function() {
    if ((this.checked)== true) {
        var datastring="data=customer";
     $.ajax({  
          url: "modul/pembayaran_penjualan/ajax_titipan.php",            
          data: datastring, 
          success: function(response){                    
              $("#ttitipancheckbox").html(response); 
          }
      });
    }else{ 
      $("#hapus").remove();
    };
  });
  
  $('#titipancheckbox2').click(function() {
    if ((this.checked)== true) {
        var datastring2="data=customer2";
     $.ajax({  
          url: "modul/pembayaran_penjualan/ajax_titipan.php",            
          data: datastring2, 
          success: function(response){                    
              $("#ttitipancheckbox2").html(response); 
          }
      });
    }else{ 
      $("#hapus2").remove();
    };
  });
      var count=1;
   $(document).ready(function() {
    datetimepiker();
          ajax_check("no_bukti",'trans_bayarjual_header','bukti_bayarjual');
         $("#sup").change(function() { 
              var id = $("#akun_kas").find(":selected").attr("data");
              $('#kode_kas :selected').text(id);
          });
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
                  var t = $('#pay_tampil').DataTable({
                      "iDisplayLength": 25,
                         "aLengthMenu": [ [25, 50,100],[25,50,100]],
                        "pagingType" : "simple",
                        "ordering": false,
                        "info":     false,
                        "language": {
                              "decimal": ",",
                              "thousands": "."
                            },
                      "processing": true,
                      "serverSide": true,
                      "ajax": "modul/pembayaran_penjualan/load-data_paypenjualan.php",
                      "order": [[1, 'asc']],
                      "columns": [
                          { "searchable": false },
                          null,
                           null,
                          { "searchable": false },
                          { "searchable": false },
                          { "searchable": false },
                          null,
                          { "searchable": false },
                          { "searchable": false }
                        ],
                        "destroy": true,
                      "rowCallback": function (row, data, iDisplayIndex) {
                          var info = this.fnPagingInfo();
                          var page = info.iPage;
                          var length = info.iLength;
                          var index = page * length + (iDisplayIndex + 1);
                          $('td:eq(0)', row).html(index);
                      }
                  });
  });
  
  function detail(rt){
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
                  var t = $('#modalnoinvoice').DataTable({
                      "iDisplayLength": 10,
                         "aLengthMenu": [ [10, 20,50],[10,20,50]],
                        "pagingType" : "simple",
                        "ordering": false,
                        "info":     false,
                      "processing": true,
                      "serverSide": true,
                      "ajax": "modul/pembayaran_penjualan/load-data_sales_invoice.php",
                      "order": [[1, 'asc']],
                       "columns": [
                          { "searchable": false },
                          null,
                          null,
                          { "searchable": false },
                          { "searchable": false },
                          { "searchable": false }
                        ],
                        "destroy": true,
                      "rowCallback": function (row, data, iDisplayIndex) {
                          var info = this.fnPagingInfo();
                          var page = info.iPage;
                          var length = info.iLength;
                          var index = page * length + (iDisplayIndex + 1);
                          $('td:eq(0)', row).html(index);
                      }
                  });  
              $('#modalinvoice').modal('show');
            };
    function detail_akun(rt){
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
                  var t = $('#modalnoakun').DataTable({
                      "iDisplayLength": 10,
                         "aLengthMenu": [ [10, 20,50],[10,20,50]],
                        "pagingType" : "simple",
                        "ordering": false,
                        "info":     false,
                      "processing": true,
                      "serverSide": true,
                      "ajax": "modul/pembayaran_penjualan/load-data_akun.php",
                      "order": [[1, 'asc']],
                       "columns": [
                          { "searchable": false },
                          null,
                          null,
                          { "searchable": false }
                        ],
                        "destroy": true,
                      "rowCallback": function (row, data, iDisplayIndex) {
                          var info = this.fnPagingInfo();
                          var page = info.iPage;
                          var length = info.iLength;
                          var index = page * length + (iDisplayIndex + 1);
                          $('td:eq(0)', row).html(index);
                      }
                  });  
              $('#modalakun').modal('show');
            };

function akun_invoice(id_invoice){
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
               var t = $('#modalnoakuninvoice').DataTable({
                    "iDisplayLength": 25,
                       "aLengthMenu": [ [25, 50,100],[25,50,100]],
                      "pagingType" : "simple",
                      "ordering": false,
                      "info":     false,
                      "processing": true,
                      "serverSide": true,
         "ajax": {
      "url": "modul/pembayaran/load-data_akun_invoice.php",
      "cache": false,
      "type": "GET",
      "data": {"id_invoice": id_invoice }
    },
                    "order": [[1, 'asc']],
                     "columns": [
                        { "searchable": false },
                        null,
                        null,
                        { "searchable": false }
                      ],
                       "destroy": true,
                    "rowCallback": function (row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                    $('td:eq(0)', row).html(index);
                    }
                });   
                 $('#modalakuninvoice').modal('show');       
}
            add_invoice('Pembayaran/@'+$('#no_bukti').val());
           var count=parseInt($('#counter').val())+1;
  function add_invoice(kode_invoice){
    alert(kode_invoice);
     data='kode='+kode_invoice+'&no='+count;
     count=count + 1
      $.ajax({
        url: 'modul/pembayaran_penjualan/ajax_add_invoice_penjualan.php',
        type: 'POST',
        dataType: 'html',
        data: data,
      })
      .done(function(data) {
          $("#tambahrow").append(data);
              hitung();
      })
      .fail(function() {
        console.log("error");
      })
      .always(function() {
        console.log("complete");
      });
  
  
  }
  function deleteRow(r) {
      var i = r.parentNode.parentNode.rowIndex;
      document.getElementById("alokasi_tampil").deleteRow(i);
      hitung();
      }
  
      $(document).on('keyup', '.hitung', function() {
      var aydi = $(this).attr('id'),
      berhitung = aydi.split('-');
      var sisa = parseFloat($('#sisa_invoice-'+berhitung[1]).val());
      var alokasi = parseFloat($('#nominal_alokasi-'+berhitung[1]).val());
     if(sisa < alokasi ){
        $('#nominal_alokasi-'+berhitung[1]).val('');
     };
          hitung();
    });
      var row_terakhir='';
      function hitung() {
           var alltotalalokasi = 0;
                   $('.alokasi').each(function(){
                      alltotalalokasi += parseFloat($(this).val() != '' ? $(this).val() : 0);
                      row_terakhir=$(this);
                  });
                   t_pembayaran= $('#total_pembayaran').val();
                   if(t_pembayaran <alltotalalokasi){
                            row_terakhir.val('');
                            alert('Nominal Yang Di Alokasikan Terlalu Besar');
                   }else{
                        $('#total_alokasi').val(alltotalalokasi);
                         $('#sisa_alokasi').val(t_pembayaran-alltotalalokasi);
                   }
      }
  
  $('#submit_data').submit(function() {
     document.getElementById("simpan").style.visibility = "hidden";
   if ( ($('#status_titipan').val() !='T'  )  ) {
        if ( ($('#sisa_alokasi').val() == 0 )  ) {
            $.ajax({
                type: 'POST',
                url: 'modul/pembayaran_penjualan/insert_alokasi_pembayaran.php',
                data: $(this).serialize(),
                success: function(data) { 
                  alert('data masuk');
                 location.reload(); 
                }
              })
      }else{
        alert('Nominal Alokasi Harus Sesuai dengan Nominal Header');
         document.getElementById("simpan").style.visibility = "visible";
      }
   }else{
          $.ajax({
                type: 'POST',
                url: 'modul/pembayaran_penjualan/insert_alokasi_pembayaran.php',
                data: $(this).serialize(),
                success: function(data) { 
                  alert(data);
                  location.reload(); 
                }
              })
   }
  
      return false;
      })
  
  $('.submit_data_header').submit(function() {
       document.getElementById("simpan").style.visibility = "hidden";
            $.ajax({
                type: 'POST',
                url: 'modul/pembayaran_penjualan/insert_header_pembayaran.php',
                data: $(this).serialize(),
                success: function(data) { 
                  alert(data);
                  location.reload(); 
                }
              })
      return false;
      })
</script>