<?php
 include "config/coneksi.php";
 error_reporting(E_ALL ^ E_NOTICE);
 session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
  echo "
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
 $_ck = (array_search("4",$_SESSION['lvl'], true));
  if ($_ck==''){
echo "Modul tidak boleh diakses anda";
}else{
$aksi="modul/pembayaran/aksi_pembayaranpembelian.php";
echo '<link rel="stylesheet" href="asset/css/layout.css">';

switch($_GET['act']){ 
  // Tampil Modul
  default:
    $judul = "Master Pembayaran Pembelian";
  $desk = "List Nota Pembayaran untuk Supplier yang telah di buat l";
  $button="
  <a href='?module=pembayaranpembelian&act=tambah' class='btn btn-primary' >Buat Transaksi <span class='glyphicon glyphicon-plus' aria-hidden='true'></span></a><span class='success'>";
  headerDeskripsi($judul,$desk,$button);
      echo ' <div class="table-responsive">
<table id="pay_tampil" class="table table-hover table-bordered" cellspacing="0">
        <thead>
  <tr style="background-color:#F5F5F5;">
          <th id="tablenumber">No</th>
           <th>Nama Supplier</th>
          <th>No. Bukti Pembayaran</th>
          <th>Nominal Pembayaran</th>
          <th title="correct">akun kas</th>
          <th>Ket</th>
          <th>Status</th>
          <th style="width:70px;">Alokasi</th>
          <th style="width:70px;">hapus</th>
          <th style="width:70px;">Edit</th>
          </tr>
        </thead>
    </table>
  </div>';
?>

    <?php
  break;
  case 'tambah';
    $judul = "Master Pembayaran Pembelian";
  $desk = "ini adalah modul berisi List Invoice yang belum dibayar l";
  $button='  ';
  headerDeskripsi($judul,$desk,$button);
    echo '
          
      <!-- #### acording-->
      <div class="panel-group" id="accordion">
        <div class="panel panel-default"  style="border-radius: 0px 0px 25px 25px;">
                     <div class="panel-heading">
                     <div class="row">
                          <div class="col-md-2 tombol-header">
                                <a data-toggle="collapse" data-parent="#accordion" href="#bkk" class="btn btn-sm btn-success"><b>Bukti Kas Keluar</b></a>
                          </div>
                          <div class="col-md-2 tombol-header">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="btn btn-sm btn-warning"><b>Bukti Giro Keluar</b></a>
                          </div>
                          <div class="col-md-2 tombol-header">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" class="btn btn-sm btn-danger"><b>Bukti Bank Keluar</b></a>
                          </div>
                      </div>
                      </div>
                        
                        <div id="bkk" class="panel-collapse collapse in">
                        <div class="panel-body" style="border-top: 15px solid #449a44;"> <!--  ################### BKK ###################  -->
                        <p class="deskripsi" style="color:#000;">Form pembuatan laporan <b>Bukti Kas Keluar</b>.</p>
                        <hr class="deskripsihr" style="margin-bottom:0px;">
                        <form class="submit_data_header">
                                      <table class="table table-hover table_without_top" border=0 id=tambah>
                                                <tr style="border-bottom:1px solid #ddd;">
                                      <td class="kop_td">Akun Kas</td>
                                      <input type="hidden" value="0" id="status_giro" name="status_giro" class="datetimepicker form-control">
                                      <td  colspan=3 id="sup">';
        $selectakun=mysql_query("Select * from setting_akun where id=2");
        $o= mysql_fetch_array($selectakun);
        echo comboboxextra('akun_kas','*,concat(kode_akun," - ",nama_akunkasperkiraan)  as tampil',
                            'akun_kas_perkiraan','is_void=0','id_akunkasperkiraan','tampil',$o['akses'],'0');
                                              echo '
                                              </td>
                                            
                                     </tr>
                                       <tr>
                                              <td class="kop_td">No. Bukti </td>';
                                                $query2=mysql_query("SELECT bukti_bayar FROM `trans_bayarbeli_header` where bukti_bayar like 'BKK%' order by id_bayarbeli desc limit 1 ");
                                                  $kode=mysql_fetch_array($query2);
                                                  $kode = kode_pembayaran($kode['bukti_bayar'],"BKK",null);

                                              echo'
                                              <td class="batas_header_form"><input name="no_bukti"  id="no_bukti" class="form-control" value="'.$kode.'"  required></td>
                                               <td class="kop_td">Tanggal bayar</td>
                                              <td><input name="tgl" value="'.date("Y-m-d").'" class="datetimepicker form-control"></td>
                                      </tr>
                                        <tr>
                                               <td class="kop_td">Nominal</td>
                                               <td class="batas_header_form" id="sup">
                                               <input name="nominal" class="form-control numberhit"  required>
                                               <td  class="kop_td">Titipan <span><input value="T" type="checkbox" id="titipancheckbox" name="titipancheckbox"/></span></td>
                                               <td id="ttitipancheckbox" name= "ttitipancheckbox" ></td>';

                                              echo'
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
                <div class="panel-body" style="border-top: 15px solid #EB951B;"><!--  ################### BGK ###################  -->
                       <p class="deskripsi" style="color:#000;">Form pembuatan laporan <b>Bukti Giro Keluar</b>.</p>
                        <hr class="deskripsihr" style="margin-bottom:0px;">
                        <form  class="submit_data_header">
                                      <table class="table table-hover table_without_top" border=0 id=tambah>
                                       <tr>
                                              <td class="kop_td">No. Bukti </td>';
                                                $query2=mysql_query("SELECT bukti_bayar FROM `trans_bayarbeli_header` where bukti_bayar like 'BGK%' order by id_bayarbeli desc limit 1 ");
                                                  $kode=mysql_fetch_array($query2);
                                                  $kode = kode_pembayaran($kode['bukti_bayar'],"BGK",null);

                                              echo'
                                              <td class="batas_header_form"><input name="no_bukti"  id="no_bukti" class="form-control" value="'.$kode.'"  required></td>
                                               <td class="kop_td">Tanggal bayar</td>
                                              <td><input name="tgl" value="'.date("Y-m-d").'" class="datetimepicker form-control"></td>
                                      </tr>
                                      <tr>
                                               <td class="kop_td">Untuk Supplier</td>
                                               <td>';
                                               echo comboboxeasy('id_supplier','select *,concat(kode_supplier," - ",nama_supplier) as tampil from supplier where is_void=0','Pilih Supplier','id_supplier','tampil');
                                               echo '</td>
                                               <td class="kop_td">Nominal</td>
                                               <td class="batas_header_form">
                                               <input id="total_giro" name="nominal" class="form-control numberhit" readonly  required></td>
                                     </tr>
                                    <tr>    <td> <label>Keterangan</label> <br>
                                      <input id="simpan" class="btn btn-success" type="submit" value=Simpan></td>
                                                <td>
                                                     <input type="hidden" value="1" id="status_giro" name="status_giro" class="datetimepicker form-control">
                                                      <textarea id="ket" name="ket" class="form-control"></textarea>
                                                </td>
                                  <td  class="kop_td">Titipan <span><input value="T" type="checkbox"  name="titipancheckbox"  /></span></td>
                                               <td id="ttitipancheckbox" name= "ttitipancheckbox" ></td>
                                      </tr>
                            </table>
                            <table class="table table-hover">
											<thead>
											   <tr>
											   <th width="30%">Akun Kas</th>
											   <th>No Giro</th>
											   <th>Atas Nama</th>
											   <th>Nama Bank</th>
											   <th>Tgl. JT</th>
											   <th>Nominal</th>
												</tr>
											</thead>
                            <tbody>
                            ';
                            $selectakun=mysql_query("Select * from setting_akun where id=4");
        $o= mysql_fetch_array($selectakun);
        for ($i = 0; $i <=20 ; $i++) {
        	 echo '<tr><td>';
echo comboboxextra('akun_kas[]','*,concat(kode_akun," - ",nama_akunkasperkiraan)  as tampil','akun_kas_perkiraan','is_void=0','id_akunkasperkiraan','tampil',$o['akses'],'0','1');echo '</td>
                            	<td><input id="no_giro-'.$i.'" name="nomor_giro[]" class="form-control no_giro"></td>
										<td><input name="an_giro[]" class="form-control"></td>
										<td><input name="nb_giro[]" class="form-control"></td>
										<td><input name="jt_giro[]" value="'.date("Y-m-d").'" class="datetimepicker form-control"></td>
										<td> <input id="'.$i.'" name="nominal_giro[]" class="form-control numberhit nominal_giro"></td>
                            </tr>';
        }
                           echo'
                            </tbody>
                            </table>
                        </form>
                </div><!-- ######## end panel body ######## -->
            </div>
        </div>
        <div class="panel panel-default" style="border-radius: 0px 0px 25px 25px;">
            <div id="collapseThree" class="panel-collapse collapse">
                <div class="panel-body" style="border-top: 15px solid #C4322E;">
                <!--  ################### BBK ###################  -->
                      <p class="deskripsi" style="color:#000;">Form pembuatan laporan <b>Bukti Bank Keluar</b>.</p>
                        <hr class="deskripsihr" style="margin-bottom:0px;">
                        <form  class="submit_data_header">
                                      <table class="table table-hover table_without_top" border=0 id=tambah>
                                  <tr style="border-bottom:1px solid #ddd;">
                                      <td class="kop_td">Akun Kas</td>
                                      <input type="hidden" value="2" id="status_giro" name="status_giro" class="datetimepicker form-control">
                                      <td id="sup" colspan=3>';
                                                      $selectakun=mysql_query("Select * from setting_akun where id=3");
        $o= mysql_fetch_array($selectakun);
        echo comboboxextra('akun_kas','*,concat(kode_akun," - ",nama_akunkasperkiraan)  as tampil',
                            'akun_kas_perkiraan','is_void=0','id_akunkasperkiraan','tampil',$o['akses'],'0','2');
                                              echo '
                                              </td> 
                                    
                                     </tr>
                                       <tr>
                                              <td class="kop_td">No. Bukti </td>';
                                                $query2=mysql_query("SELECT bukti_bayar FROM `trans_bayarbeli_header` where bukti_bayar like 'BBK%' order by id_bayarbeli desc limit 1 ");
                                                  $kode=mysql_fetch_array($query2);
                                                  $kode = kode_pembayaran($kode['bukti_bayar'],"BBK",null);

                                              echo'
                                              <td class="batas_header_form"><input name="no_bukti"  id="no_bukti" class="form-control" value="'.$kode.'"  required></td>
                                               <td class="kop_td">Tanggal bayar</td>
                                              <td><input name="tgl" value="'.date("Y-m-d").'" class="datetimepicker form-control"></td>
                                      </tr>';

                                              echo'
                                      <tr>
                                              <td class="kop_td">No. Rek Tujuan</td>
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

                                              echo'
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
    break;
 

  case 'edit_header';
    $judul = "Master Pembayaran Pembelian";
  $desk = "ini adalah modul berisi List Invoice yang belum dibayar l";
  $button='  ';
  headerDeskripsi($judul,$desk,$button);
  $select ="SELECT * FROM `trans_bayarbeli_header` WHERE `id_bayarbeli`='$_GET[id]'";
  $query=mysql_query($select);
  $r=mysql_fetch_array($query);
  $status_giro=$r['status_giro'];
  if ($r['status_titipan']=='T') {
    $checked='checked';
  }else{
    $checked='';
  }

if ($status_giro=='0') {
  echo '   <form class="submit_data_header">
                                      <table class="table table-hover table_without_top" border=0 id=tambah>
                                                <tr style="border-bottom:1px solid #ddd;">
                                      <td class="kop_td">Akun Kas</td>
                                      <input type="hidden" value="0" id="status_giro" name="status_giro" class="datetimepicker form-control">
                                      <td  colspan=3 id="sup">';
        $selectakun=mysql_query("Select * from setting_akun where id=2");
        $o= mysql_fetch_array($selectakun);
        echo comboboxextra('akun_kas','*,concat(kode_akun," - ",nama_akunkasperkiraan)  as tampil',
                            'akun_kas_perkiraan','is_void=0','id_akunkasperkiraan','tampil',$o['akses'],$r['id_akunkasperkiraan']);
                                              echo '
                                              </td>
                                            
                                     </tr>
                                       <tr>
                                              <td class="kop_td">No. Bukti </td>
                                              <td class="batas_header_form">'.$r['bukti_bayar'].'<input type="hidden" name="no_bukti" class="form-control" value="'.$r['bukti_bayar'].'"  required></td>
                                               <td class="kop_td">Tanggal bayar</td>
                                              <td><input name="tgl" value="'.$r['tgl_pembayaran'].'" class="datetimepicker form-control"></td>
                                      </tr>
                                        <tr>
                                               <td class="kop_td">Nominal</td>
                                               <td class="batas_header_form" id="sup">
                                               <input name="nominal" class="form-control numberhit" value="'.$r['nominal'].'"  required>
                                               <td  class="kop_td">Titipan <span><input value="T" type="checkbox" id="titipancheckbox" name="titipancheckbox" '.$checked.' /></span></td>
                                               <td id="ttitipancheckbox" name= "ttitipancheckbox" >
                                               <div id="hapus">';
                                                     if ($checked=='checked') {
                                                 echo comboboxeasy('id_supplier','select *,concat(kode_supplier," - ",nama_supplier) as tampil from supplier where is_void=0','Pilih Supplier','id_supplier','tampil',$r['id_supplier']);
                                              }

                                               echo '</div></td>
                                     </tr>
                                      <tr>
                                                <td colspan="4">
                                                      <label>Keterangan</label>
                                                      <textarea id="ket" name="ket" class="form-control">'.$r['ket'].'</textarea>
                                                </td>
                                      </tr>
                            </table>
                            <input type="hidden" name="update" value="1">
                            <input id="simpan" class="btn btn-success" type="submit" value=Simpan>
                        </form>';
}elseif ($status_giro=='1') {
  echo '  <form  class="submit_data_header">
                                      <table class="table table-hover table_without_top" border=0 id=tambah>
                                       <tr>
                                              <td class="kop_td">No. Bukti </td>
                                              <td class="batas_header_form">'.$r['bukti_bayar'].'<input type="hidden" name="no_bukti"   class="form-control" value="'.$r['bukti_bayar'].'"  required></td>
                                               <td class="kop_td">Tanggal bayar</td>
                                              <td><input name="tgl" value="'.$r['tgl_pembayaran'].'" class="datetimepicker form-control"></td>
                                      </tr>
                                      <tr>
                                               <td class="kop_td">Untuk Supplier</td>
                                               <td>';
                                               echo comboboxeasy('id_supplier','select *,concat(kode_supplier," - ",nama_supplier) as tampil from supplier where is_void=0','Pilih Supplier','id_supplier','tampil',$r['id_supplier']);
                                               echo '</td>
                                               <td class="kop_td">Nominal</td>
                                               <td class="batas_header_form">
                                               <input id="total_giro" name="nominal" class="form-control numberhit" readonly value="'.$r['nominal'].'"  required></td>
                                     </tr>
                                      <tr>    <td> <label>Keterangan</label> <br>
<input type="hidden" name="update" value="1">
                                      <input id="simpan" class="btn btn-success" type="submit" value=Simpan></td>
                                                <td>
                                                     <input type="hidden" value="1" id="status_giro" name="status_giro" class="datetimepicker form-control">
                                                      <textarea id="ket" name="ket" class="form-control">'.$r['ket'].'</textarea>
                                                </td>
                                  <td  class="kop_td">Titipan <span><input value="T" type="checkbox"  name="titipancheckbox" '.$checked.' /></span></td>
                                               <td id="ttitipancheckbox3" name= "ttitipancheckbox" ></td>
                                      </tr>
                            </table>
                            <table class="table table-hover">
                      <thead>
                         <tr>
                         <th width="30%">Akun Kas</th>
                         <th>No Giro</th>
                         <th>Atas Nama</th>
                         <th>Nama Bank</th>
                         <th>Tgl. JT</th>
                         <th>Nominal</th>
                        </tr>
                      </thead>
                            <tbody>
                            ';
                            $selectakun=mysql_query("Select * from setting_akun where id=4");
        $o= mysql_fetch_array($selectakun);
$select_giro="SELECT * FROM `giro_keluar` WHERE `bukti_bayar`='$r[bukti_bayar]'";
$select_giro=mysql_query($select_giro);
$no=0;
while ($sg=mysql_fetch_array($select_giro)) {
      echo '<tr><td>';
  if ($sg['konfirmasi_giro']!=0) {
    $readonly='readonly';
    $date='';
    echo "Giro Telah di Konfirmasi
    <input type='hidden'  name='akun_kas[]' value='".$sg['id_akun_kas_perkiraan']."' ".$readonly.">";

  }else{
    $readonly='';
    $date='datetimepicker';
    echo comboboxextra('akun_kas[]','*,concat(kode_akun," - ",nama_akunkasperkiraan)  as tampil','akun_kas_perkiraan','is_void=0','id_akunkasperkiraan','tampil',$o['akses'],$sg['id_akun_kas_perkiraan'],$no);
  }
echo '</td>
                              <td><input id="no_giro-'.$no.'" name="nomor_giro[]" class="form-control no_giro" value="'.$sg['no_giro'].'"  '.$readonly.'></td>
                    <td><input name="an_giro[]" class="form-control" value="'.$sg['an_giro'].'" '.$readonly.'></td>
                    <td><input name="nb_giro[]" class="form-control" value="'.$sg['nb_giro'].'" '.$readonly.'></td>
                    <td><input name="jt_giro[]" class="'.$date.' form-control" value="'.$sg['jt_giro'].'" '.$readonly.'></td>
                    <td> <input id="'.$no.'" name="nominal_giro[]" class="form-control numberhit nominal_giro" value="'.$sg['nominal_giro'].'" '.$readonly.'>
                    <input  name="konfirmasi_giro[]"  value="'.$sg['konfirmasi_giro'].'" '.$readonly.'></td>
                            </tr>';
$no++;}


for ($i = $no; $i <=20 ; $i++) {
           echo '<tr><td>';
echo comboboxextra('akun_kas[]','*,concat(kode_akun," - ",nama_akunkasperkiraan)  as tampil','akun_kas_perkiraan','is_void=0','id_akunkasperkiraan','tampil',$o['akses'],'','1');echo '</td>
                              <td><input id="no_giro-'.$i.'" name="nomor_giro[]" class="form-control no_giro"></td>
                    <td><input name="an_giro[]" class="form-control"></td>
                    <td><input name="nb_giro[]" class="form-control"></td>
                    <td><input name="jt_giro[]" value="'.date("Y-m-d").'" class="datetimepicker form-control"></td>
                    <td> <input id="'.$i.'" name="nominal_giro[]" class="form-control numberhit nominal_giro"></td>
                            </tr>';
        }
                           echo'
                            </tbody>
                            </table>
                        </form>';
}elseif ($status_giro=='2') {
  echo'   <form  class="submit_data_header">
                                      <table class="table table-hover table_without_top" border=0 id=tambah>
                                  <tr style="border-bottom:1px solid #ddd;">
                                      <td class="kop_td">Akun Kas</td>
                                      <input type="hidden" value="2" id="status_giro" name="status_giro" class="datetimepicker form-control">
                                      <td id="sup" colspan=3>';
                                                      $selectakun=mysql_query("Select * from setting_akun where id=3");
        $o= mysql_fetch_array($selectakun);
        echo comboboxextra('akun_kas','*,concat(kode_akun," - ",nama_akunkasperkiraan)  as tampil',
                            'akun_kas_perkiraan','is_void=0','id_akunkasperkiraan','tampil',$o['akses'],$r['id_akunkasperkiraan'],'2');
                                              echo '
                                              </td> 
                                    
                                     </tr>
                                       <tr>
                                              <td class="kop_td">No. Bukti </td>
                                              <td class="batas_header_form"><input type="hidden" name="no_bukti"   class="form-control" value="'.$r['bukti_bayar'].'"  required>'.$r['bukti_bayar'].'</td>
                                               <td class="kop_td">Tanggal bayar</td>
                                              <td><input name="tgl" value="'.$r['tgl_pembayaran'].'" class="datetimepicker form-control"></td>
                                      </tr>';

                                              echo'
                                      <tr>
                                              <td class="kop_td">No. Rek Tujuan</td>
                                              <td class="batas_header_form"><input value="'.$r['rek_tujuan'].'" id="rek_tujuan" name="rek_tujuan" class="form-control" required></td>
                                              <td class="kop_td">Atas Nama</td>
                                               <td><input id="ac_tujuan" name="ac_tujuan" class="form-control" value="'.$r['ac_tujuan'].'" required></td>
                                      </tr>
                                        <tr>
                                               <td class="kop_td">Nominal</td>
                                               <td class="batas_header_form">
                                               <input name="nominal" class="form-control numberhit" value="'.$r['nominal'].'"   required>
                                              <td  class="kop_td">Titipan <span><input value="T" type="checkbox" id="titipancheckbox2" name="titipancheckbox" '.$checked.'/></span>
                                              </td><td id="ttitipancheckbox2" name= "ttitipancheckbox2" >
                                               <div id="hapus2">';
                                              if ($checked=='checked') {
                                                 echo comboboxeasy('id_supplier','select *,concat(kode_supplier," - ",nama_supplier) as tampil from supplier where is_void=0','Pilih Supplier','id_supplier','tampil',$r['id_supplier']);
                                              }
                                           
                                              echo'</div> </td>
                                     </tr>
                                      <tr>
                                                <td colspan="4">
                                                      <label>Keterangan</label>
                                                      <textarea id="ket" name="ket" class="form-control"></textarea>
                                                </td>
                                      </tr>
                            </table>
                            <input type="hidden" name="update" value="1">
                            <input id="simpan" class="btn btn-success" type="submit" value=Simpan>
                        </form>';
}

  break;







//#################################  Alokasi
//
//
//
//
    case "alokasi":
       if(isset($_GET['id'])){
        $id_pay =$_GET['id'];
      }
      ///////////////////////////////////////////////////////////////////////////////////////////////////////////  49 UANG MUKA PEMBELIAN AKTIVA
      $query67 = mysql_query("SELECT  *,tb.ket as ket, tb.nominal_alokasi as total_alokasi,if(`status_titipan`='T','49',tb.`id_akunkasperkiraan`) as id_akun_header FROM trans_bayarbeli_header tb left join akun_kas_perkiraan ak on(tb.id_akunkasperkiraan=ak.id_akunkasperkiraan) where id_bayarbeli='$id_pay'");
      $my = mysql_fetch_array($query67);
      $jenisbayar= explode(" - ", $my['bukti_bayar']);
      echo ' 
<div class="row">
  <div class="col-md-6">
      <h2>Alokasi Pembayaran</h2>
       <p class="deskripsi"> List alokasi pembayaran</p>
  </div>
</div>
<form id="submit_data">
<input type="hidden" name="id_bayarbeli" value="'.$my['id_bayarbeli'].'" id="id_bayarbeli">
<input type="hidden" id="status_titipan" name="status_titipan" value="'.$my['status_titipan'].'" >
<hr class="deskripsihr" style="margin-bottom:0px;">
<table id="tableheader" class="table table-hover table_without_top" border=0>
<tr>
      <td class="kop_td">No. Bukti </td>
      <td><strong>:</strong></td>
      <td class="batas_header_form"><strong>'.$my['bukti_bayar'].'</strong>
      <input name="no_bukti"  id="no_bukti" class="form-control" value="'.$my['bukti_bayar'].'"  type="hidden" required readonly></td>
      <td class="kop_td">Akun Kas</td>
            <td><strong>:</strong></td>
      <td><strong>'.$my['kode_akun'].' - '.$my['nama_akunkasperkiraan'].'</strong>
      <input class="form-control" name="id_akun" value="'.$my['id_akun_header'].'" readonly type="hidden"></td>
</tr>
<tr>
       <td class="kop_td">Tanggal bayar</td>
       <td><strong>:</strong></td>
      <td class="batas_header_form"><strong>'.tanggalan($my['tgl_pembayaran']).'</strong>
      <input type="hidden" name="tgl" value="'.$my['tgl_pembayaran'].'"  class="form-control" readonly></td>
      <td class="kop_td">Nominal</td>
      <td><strong>:</strong></td>
      <td ><strong>'.format_rupiah($my['nominal']).'</strong>
      <input name="nominal" class="numberhit"  id="total_pembayaran"  type="hidden" value="'.$my['nominal'].'" readonly></ td>
</tr>';
      if ($jenisbayar[0] =='BGK'){
      echo '
<tr>
      <td class="kop_td">Atas Nama</td>
      <td><strong>:</strong></td>
      <td class="batas_header_form"><strong>'.$my['ac_tujuan'].'</strong>
      <input id="ac_tujuan" name="ac_tujuan" type="hidden" value="'.$my['ac_tujuan'].'" required readonly></td>
          <td class="kop_td">No Giro</td>
          <td><strong>:</strong></td>
      <td><strong>'.$my['no_giro'].'</strong>
      <input id="no_giro" type="hidden" name="no_giro" class="form-control" value="'.$my['no_giro'].'" required readonly></td>
</tr>
<tr>
        <td class="kop_td">Tanggal jatuh tempo</td>
        <td><strong>:</strong></td>
      <td><strong>'.tanggalan($my['jatuh_tempo']).'</strong>
      <input type="hidden" name="jatuh_tempo" class="form-control" value="'.$my['jatuh_tempo'].'" required readonly>
      <input type="hidden" value="1" id="status_giro" name="status_giro" class="datetimepicker form-control"></td>
</tr>';
               } elseif ($jenisbayar[0] =='BBK'){
              echo '
<tr>
    <td class="kop_td">No. Rekening Tujuan </td>
    <td><strong>:</strong></td>
    <td class="batas_header_form"><strong>'.$my['rek_tujuan'].'</strong>
    <input type="hidden" id="rek_tujuan" name="rek_tujuan" class="form-control" value="'.$my['rek_tujuan'].'" required readonly></td>
    <td class="kop_td">Atas Nama</td>
    <td><strong>:</strong></td>
     <td><strong>'.$my['ac_tujuan'].'</strong>
     <input id="ac_tujuan" name="ac_tujuan" type="hidden" class="form-control" value="'.$my['ac_tujuan'].'" required readonly></td>
</tr> ';
            } 
    echo'
<tr>
      <td class="kop_td">Keteranga</td>
   <td><strong>:</strong></td>
      <td>'.$my['ket'].'
      <input type="hidden" value="'.$my['ket'].'" name="ket"  readonly></td>
</tr>
</table>
   <a class="btn btn-success" title="menambahkam invoice" onclick="detail()"><span class="glyphicon glyphicon-plus"></span>Tambah Invoice</a>
   <a class="btn btn-success" title="menambahkam akun" onclick="detail_akun()"><span class="glyphicon glyphicon-plus"></span>Tambah Akun</a>';
//####################################################  ALOKAISI DETAIL ##########
   echo'
<table id="alokasi_tampil" class="table table-hover table-bordered" cellspacing="0">
<thead>
  <tr style="background-color:#F5F5F5;">
    <th id="tablenumber">No</th>
    <th>Akun Kas Perkiraan</th>
      <th>Nama Supplier</th>
    <th>No. Invoice</th>
    <th>Nominal Invoice</th>
    <th>Sisa Invoice</th>
    <th>Total</th>
    <th id="tablenumber">Aksi</th>
  </tr>
</thead>
<tbody id="tambahrow">';
        $detail_alokasi = ("
          SELECT (count(*)) as nomor FROM `trans_bayarbeli_detail` a  where  `bukti_bayar`='".$my['bukti_bayar']."'
          ");
      $result = mysql_query($detail_alokasi);
      $nomor=mysql_fetch_array($result);

      echo'
</tbody>
<input type="hidden"    id="counter" value="'.$nomor['nomor'].'" >
<tfood id="noborder" style="border-top:1px solid #000;">
      <tr>
        <td colspan="4"></td>
        <td><b>Total :</b></td>
        <td><input type="text" name="sisa_totalinvoice"   id="total_sisainvoice"  class="form-control hitung" readonly></td>
        <td colspan="1"><input type="text" name="total_alokasi"  value="'.$my['total_alokasi'].'"  id="total_alokasi"  class="form-control hitung numberhit" readonly></td>
      </tr>
      <tr>
        <td colspan="5"></td>
        <td><b>Sisa Alokasi :</b></td>
        <td colspan="1"><input type="text" name="sisa_alokasi"  value="'.$my['sisa_alokasi'].'"  id="sisa_alokasi"  class="form-control hitung numberhit" readonly></td>
      </tr>
</tfood>
</table>
    <div style="float: right;">
            <button class="btn btn-success" data-toggle="modal"  id="simpan">Simpan</button>
            <a class="btn btn-warning" type="button" href="media.php?module=pembayaranpembelian" >Batal</a>
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
                                    <th>Supplier</th>
                                    <th>No Invoice</th>
                                    <th>Tanggal</th>
                                    <th>Grand Total</th>
                                    <th>Aksi</th>
                            </tr>
                      </thead>
    
                  </table>
                  </div><!-- ############## end Modal body -->
            </div><!-- ############## end Modal content -->      
          </div><!-- ############## end Modal dialog -->    
    </div><!-- ############## end Modal fade-->
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
                  </div><!-- ############## end Modal body -->
            </div><!-- ############## end Modal content -->      
          </div><!-- ############## end Modal dialog -->    
    </div><!-- ############## end Modal fade-->

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
	$('.no_giro').keyup(function(event) {
		hitung_giro();
	});
$('.nominal_giro').keyup(function() {
	hitung_giro();
});
function hitung_giro() {
		var total_giro = 0;
     $('.nominal_giro').each(function(){
 			$id=$(this).attr('id');
 			if ($('#no_giro-'+$id).val()!=''){
 				total_giro += parseFloat($(this).val() != '' ? $(this).val() : 0);
 			}
    });
    $('#total_giro').val(total_giro);
}
$('#titipancheckbox').click(function() {
  if ((this.checked)== true) {
      var datastring="data=supplier";
   $.ajax({  
        url: "modul/pembayaran/ajax_titipan.php",             
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
      var datastring2="data=supplier2";
   $.ajax({  
        url: "modul/pembayaran/ajax_titipan.php",             
        data: datastring2, 
        success: function(response){                    
            $("#ttitipancheckbox2").html(response); 
        }
    });
  }else{ 
    $("#hapus2").remove();
  };
});

 $(document).ready(function() {
  datetimepiker();
        ajax_check("no_bukti",'trans_bayarbeli_header','bukti_bayar');
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
                    "ajax": "modul/pembayaran/load-data_paypembelian.php",
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
                    "ajax": "modul/pembayaran/load-data_invoice.php",
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
                    "ajax": "modul/pembayaran/load-data_akun.php",
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
   data='kode='+kode_invoice+'&no='+count;
   count=count + 1
    $.ajax({
      url: 'modul/pembayaran/ajax_add_invoice_pembelian.php',
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
              url: 'modul/pembayaran/insert_alokasi_pembayaran.php',
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
              url: 'modul/pembayaran/insert_alokasi_pembayaran.php',
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
              url: 'modul/pembayaran/insert_header_pembayaran.php',
              data: $(this).serialize(),
              success: function(data) { 
                alert(data);
                //location.reload(); 
              }
            })
    return false;
    })
</script>