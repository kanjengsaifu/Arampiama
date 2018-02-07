<?php 
 include "../../config/koneksi.php";
 include "../../lib/input.php";
    $id_BGK = $_GET['ajaxmanagiro'];
        $buktibayar = $_GET['buktibayar'];
        $bayarkode= explode(" - ", $buktibayar);
        $kodebuktibayar = $bayarkode[0];
$aksi="modul/konfirmasi-giro/aksi_girojatuhtempo.php";
        if($kodebuktibayar == 'BGK'){ //###################### BGK
      $query67 = mysql_query("SELECT *, tb.ket as ket, tb.nominal_alokasi as total_alokasi FROM trans_bayarbeli_header tb left join akun_kas_perkiraan ak on(tb.id_akunkasperkiraan=ak.id_akunkasperkiraan) where id_bayarbeli='$id_BGK' ");
        $my = mysql_fetch_array($query67);
      echo '
            <p class="deskripsi"> List alokasi pembayaran <b>BGK</b></p>
            <hr class="deskripsihr" style="margin-bottom:0px;"><br>
        <table id="tableheadergirodetail" class="table table-hover table_without_top" border=0>
              <tr style="text-align:left;border-bottom:1px solid #ddd;">
                      <td class="kop_td">Akun Kas</td>
                      <td>';
                               echo $my['kode_akunkasperkiraan'].'-'.$my['nama_akunkasperkiraan'].'
                              </td>
                              <td colspan="2"></td>';
                    echo'
              </tr>
           <tr style="text-align:left;border-bottom:1px solid #ddd;">
                    <td class="kop_td">No. Bukti </td>
                    <td>'.$my['bukti_bayar'].'</td>
                     <td class="kop_td">Tanggal bayar</td>
                    <td>'.date("d M Y", strtotime($my['tgl_pembayaran'])).'</td>
            </tr>
             <tr style="text-align:left;border-bottom:1px solid #ddd;">
                      <td class="kop_td">Transfer Dari </td>
                      <td>
                      </td>
                      <td class="kop_td">No Giro</td>
                       <td>'.$my['no_giro'].'</td>
              </tr>
              <tr style="text-align:left;border-bottom:1px solid #ddd;">
                      <td class="kop_td">No. Rekening Tujuan </td>
                      <td>'.$my['rek_tujuan'].'</td>
                      <td class="kop_td">Atas Nama</td>
                       <td>'.$my['ac_tujuan'].'</td>
              </tr>
               <tr style="text-align:left;border-bottom:1px solid #ddd;">
                       <td class="kop_td">Nominal</td>
                       <td>'.format_rupiah($my['nominal']).'
                       <td class="kop_td">Tanggal jatuh tempo</td>
                      <td>'.date("d M Y", strtotime($my['jatuh_tempo'])).'</td>
             </tr>
            <tr>
                      <td colspan="4">
                            <b> Keterangan : </b> '.$my['ket'].'
                      </td>
            </tr>
  </table>
   <div class="table-responsive">';
   $query23 =( "SELECT *,(trans_bayarbeli_detail.ket) as ket1, CONCAT(akun_kas_perkiraan.kode_akunkasperkiraan,' - ', akun_kas_perkiraan.nama_akunkasperkiraan) as kode FROM trans_bayarbeli_detail left join akun_kas_perkiraan on(trans_bayarbeli_detail.id_akunkasperkiraan_detail=akun_kas_perkiraan.id_akunkasperkiraan) where trans_bayarbeli_detail.bukti_bayar = '$my[bukti_bayar]' AND trans_bayarbeli_detail.is_void='0' ");
        $tampil23=mysql_query($query23);
if(!empty($tampil23)){
   echo'
<table id="tabledetailgirodetail" class="table table-hover table-bordered" cellspacing="0">
        <thead>
  <tr style="background-color:#F5F5F5;">
          <th id="tablenumber">No</th>
          <th>Akun Kas Perkiraan</th>
          <th>No. Invoice</th>
          <th>Keterangan</th>
          <th>Kurangan tagihan Invoice</th>
          <th>Total</th>
          </tr>
        </thead>
        <tbody id="tambahrow">';

           #### JIKA ALOKASI ADA isi
        $no = 1;
          while($wp=mysql_fetch_array($tampil23)){
            $query32 =mysql_query( "SELECT s.nama_supplier, ts.grand_total FROM trans_invoice ts LEFT JOIN supplier s ON(ts.id_supplier=s.id_supplier) where ts.id_invoice = '$wp[nota_invoice]'");
            $gr = mysql_fetch_array($query32);
    echo '<tr>
        <td>'.$no.'</td> <input type="hidden" name="id_bayarbeli_detail[]" value="'.$wp['id_bayarbeli_detail'].'">
        <td>'.$wp['kode'].'</td>
      <td> '.$wp['nota_invoice'].'<input type="hidden" name="no_invoice[]" value="'.$wp['nota_invoice'].'">';
      echo '
             <b>Nominal Invoice : <br></b>'.format_rupiah($gr['grand_total']).'<br>
             <b>'.$gr['nama_supplier'].'</b>
      </td>
      <td>'.$wp['ket1'].'
      </td>
      <td>'.format_rupiah($wp['sisa_invoice']).'<span id="jikaadainvoice-'.$no.'">
              </span>
      </td>      
      <td> '.format_rupiah($wp['nominal_alokasi']).'<input type="hidden" name="nominal_alokasi[]" value="'.$wp['nominal_alokasi'].'">
      </td>             
  </tr>'; 
      $no++;
    }
      echo'
        </tbody>
        <tfood id="noborder" style="border-top:1px solid #000;">
            <tr>
                    <td colspan="3"></td>
                    <td><b>Total :</b></td>
                    <td colspan="2">'.format_rupiah($my['total_alokasi']).'</td>
            </tr>
              <tr>
                    <td colspan="3"></td>
                    <td><b>Sisa Alokasi :</b></td>
                    <td colspan="2">'.format_rupiah($my['sisa_alokasi']).'</td>
            </tr>
        </tfood>
    </table>';
  }
    echo'
        </div>
         <div class="modal-footer">';
         echo '         <form method="post" action="'.$aksi.'?module=girojatuhtempo&act=terimagiro" id="confirmgirotrima">
    <div class="modal fade" id="confModal" role="dialog">
          <div class="modal-dialog">                                                  
            <!-- Modal content-->
            <div class="modal-content">
                  <div class="modal-body" id="confirmgirodetail">';
                  echo '
                                <label>No Bukti Giro Cair</label>
<input type="text" name="no_giro_cair"  id="no_girocair"  class="form-control" >
                                <label>Tanggal dicairkan</label>
<input type="text" name="tgl_giro_cair"  id="tgl_giro_cair"  class="form-control datepicker" >   
<input type="hidden" name="nominal"  id="nominal"  value="'.$my['nominal'].'" class="form-control" >
<input type="hidden" name="nota"  id="nota"  value="'.$my['bukti_bayar'].'" class="form-control" >
    <input type="hidden" name="akun_kas"  id="akun_kas"  value="'.$my['id_akunkasperkiraan'].'" class="form-control" >';
                  echo '
                  </div> 
                  <div class="modal-footer">                  
                                <button  type="submit" class="btn btn-success"  id="hppOnly" style="float:left;margin:0px 5px;">Giro Terima </button> 
                                 <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                  </div>
          </div><!-- ############## end Modal content -->      
        </div><!-- ############## end Modal dialog -->    
      </div><!-- ############## end Modal fade-->';
         echo'
             <input type="hidden" name="id_bgk"  id="id_bgk"  value='.$id_BGK.'>
            </form>
              <button class="btn btn-success" href="#kategoricollapse" style="float:left;margin:0px 5px;" data-toggle="modal" data-target="#confModal"> Giro diterim</button>
              
                  <form method="post" action="'.$aksi.'?module=girojatuhtempo&act=tolakgiro" id="confirmgiro">
<input type="hidden" name="nominal"  id="nominal"  value="'.$my['nominal'].'" class="form-control" >
<input type="hidden" name="nota"  id="nota"  value="'.$my['bukti_bayar'].'" class="form-control" >
<input type="hidden" name="tgl_jt"  id="tgl_jt"  value="'.$my['jatuh_tempo'].'" class="form-control" >
              <input type="hidden" name="id_bgk"  id="id_bgktolak"  value='.$id_BGK.'>
              <input type="hidden" name="akun_kas"  id="akun_kas"  value="'.$my['id_akunkasperkiraan'].'" class="form-control" >
                    <button class="btn btn-danger"  id="hppOnly" style="float:left;margin:0px 5px;" type="submit">Giro Tolak </button>
                  </form>
              <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
           </div>'; 
  echo ' <script type="text/javascript">
  $( function() {
    $( ".datepicker" ).datepicker({
        dateFormat:"yy-mm-dd",
      changeMonth:true,
    changeYear:true}
      );
  } );
</script>';

} else if ($kodebuktibayar == "BGM") {            //################ Konfirmasi dari Giro masuk
       $query67 = mysql_query("SELECT *, tb.ket_jual as ket, tb.nominal_alokasi_jual as total_alokasi FROM trans_bayarjual_header tb left join akun_kas_perkiraan ak on(tb.id_akunkasperkiraan=ak.id_akunkasperkiraan) where id_bayarjual='$id_BGK' ");
        $my = mysql_fetch_array($query67);
      echo '
            <p class="deskripsi"> List alokasi pembayaran <b>BGM</b></p>
            <hr class="deskripsihr" style="margin-bottom:0px;"><br>
        <table id="tableheadergirodetail" class="table table-hover table_without_top" border=0>
              <tr style="text-align:left;border-bottom:1px solid #ddd;">
                      <td class="kop_td">Akun Kas</td>
                      <td>';
                               echo $my['kode_akunkasperkiraan'].'-'.$my['nama_akunkasperkiraan'].'
                              </td>
                              <td colspan="2"></td>';
                    echo'
              </tr>
           <tr style="text-align:left;border-bottom:1px solid #ddd;">
                    <td class="kop_td">No. Bukti </td>
                    <td>'.$my['bukti_bayarjual'].'</td>
                     <td class="kop_td">Tanggal bayar</td>
                    <td>'.date("d M Y", strtotime($my['tgl_pembayaranjual'])).'</td>
            </tr>
             <tr style="text-align:left;border-bottom:1px solid #ddd;">
                      <td class="kop_td">Transfer Dari </td>
                      <td>';
                       $tampil_bank=mysql_query("SELECT id,concat(rek,'  <br>  ',ac,'  (',nama_bank,')') as ket FROM master_bank where is_void=0 AND id='$my[id_masterbank]' ");
                       $rec_bank = mysql_fetch_array($tampil_bank);
                              echo $rec_bank['ket'];

                      echo '
                      </td>
                      <td class="kop_td">No Giro</td>
                       <td>'.$my['no_giro_jual'].'</td>
              </tr>
              <tr style="text-align:left;border-bottom:1px solid #ddd;">
                      <td class="kop_td">No. Rekening Tujuan </td>
                      <td>'.$my['rek_asal'].'</td>
                      <td class="kop_td">Atas Nama</td>
                       <td>'.$my['ac_asal'].'</td>
              </tr>
               <tr style="text-align:left;border-bottom:1px solid #ddd;">
                       <td class="kop_td">Nominal</td>
                       <td>'.format_rupiah($my['nominaljual']).'
                       <td class="kop_td">Tanggal jatuh tempo</td>
                      <td>'.date("d M Y", strtotime($my['jatuh_tempo_jual'])).'</td>
             </tr>
            <tr>
                      <td colspan="4">
                            <b> Keterangan : </b> '.$my['ket_jual'].'
                      </td>
            </tr>
  </table>
   <div class="table-responsive">';
   $query23 =( "SELECT *,(trans_bayarjual_detail.ket_detail_jual) as ket1, CONCAT(akun_kas_perkiraan.kode_akunkasperkiraan,' - ', akun_kas_perkiraan.nama_akunkasperkiraan) as kode FROM trans_bayarjual_detail left join akun_kas_perkiraan on(trans_bayarjual_detail.id_akunkasperkiraan_detail=akun_kas_perkiraan.id_akunkasperkiraan) where trans_bayarjual_detail.bukti_bayarjual = '$my[bukti_bayarjual]' AND trans_bayarjual_detail.is_void='0' ");
        $tampil23=mysql_query($query23);
if(!empty($tampil23)){
   echo'
<table id="tabledetailgirodetail" class="table table-hover table-bordered" cellspacing="0">
        <thead>
  <tr style="background-color:#F5F5F5;">
          <th id="tablenumber">No</th>
          <th>Akun Kas Perkiraan</th>
          <th>No. Invoice</th>
          <th>Keterangan</th>
          <th>Kurangan tagihan Invoice</th>
          <th>Total</th>
          </tr>
        </thead>
        <tbody id="tambahrow">';

           #### JIKA ALOKASI ADA isi
        $no = 1;
          while($wp=mysql_fetch_array($tampil23)){
            $query32 =mysql_query( "SELECT c.nama_customer, ts.grand_total FROM trans_sales_invoice ts LEFT JOIN customer c ON(ts.id_customer = c.id_customer) WHERE ts.id_invoice = '$wp[nota_invoice]'");
            $gr = mysql_fetch_array($query32);
    echo '<tr>
        <td>'.$no.'</td> <input type="hidden" name="id_bayarbeli_detail[]" value="'.$wp['id_bayarjual_detail'].'">
        <td>'.$wp['kode'].'</td>
      <td> '.$wp['nota_invoice'].' <input type="hidden" name="no_invoice[]" value="'.$wp['nota_invoice'].'">';
      echo '
             <b>Nominal Invoice : <br></b>'.format_rupiah($gr['grand_total']).' <br>
             <b>'.$gr['nama_customer'].'</b>

      </td>
      <td>'.$wp['ket1'].'
      </td>
      <td>'.format_rupiah($wp['sisa_invoice_detail_jual']).'<span id="jikaadainvoice-'.$no.'">
              </span>
      </td>      
      <td> '.format_rupiah($wp['nominal_alokasi_detail_jual']).' <input type="hidden" name="nominal_alokasi[]" value="'.$wp['nominal_alokasi_detail_jual'].'">
      </td>             
  </tr>'; 
      $no++;
    }
      echo'
        </tbody>
        <tfood id="noborder" style="border-top:1px solid #000;">
            <tr>
                    <td colspan="3"></td>
                    <td><b>Total :</b></td>
                    <td colspan="2">'.format_rupiah($my['total_alokasi']).'</td>
            </tr>
              <tr>
                    <td colspan="3"></td>
                    <td><b>Sisa Alokasi :</b></td>
                    <td colspan="2">'.format_rupiah($my['sisa_alokasi_jual']).'</td>
            </tr>
        </tfood>
    </table>';
  }
    echo'
        </div>
         <div class="modal-footer">';
                  echo '         <form method="post" action="'.$aksi.'?module=girojatuhtempo&act=terimagiromasuk" id="confirmgiroterima">
    <div class="modal fade" id="confModal" role="dialog">
          <div class="modal-dialog">                                                  
            <!-- Modal content-->
            <div class="modal-content">
                  <div class="modal-body" id="confirmgirodetail">';
                  echo '
                                  <label>Masuk ke akun kas</label>
                                      <input type="hidden" name="akun_kas"  id="akun_kas"  value="'.$my['id_akunkasperkiraan'].'" class="form-control" >
                                <label>No Bukti Giro Cair</label>
                                <input type="text" name="no_giro_cair"  id="no_girocair"  class="form-control" >
                                <label>Tanggal dicair</label>
<input type="text" name="tgl_giro_cair"  id="tgl_giro_cair"  class="form-control datepicker" >
<input type="hidden" name="nominal"  id="nominal"  value="'.$my['nominaljual'].'" class="form-control" >
<input type="hidden" name="nota"  id="nota"  value="'.$my['bukti_bayarjual'].'" class="form-control" >
                    


                                ';
                  echo '
                  </div> 
                  <div class="modal-footer">
                   <button class="btn btn-success"  id="hppOnly" style="float:left;margin:0px 5px;" type="submit">Giro Terima </button> 
                   <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                  </div>
          </div><!-- ############## end Modal content -->      
        </div><!-- ############## end Modal dialog -->    
      </div><!-- ############## end Modal fade-->';
         echo'
             <input type="hidden" name="id_bgk"  id="id_bgk"  value='.$id_BGK.'>
        </form>
              <button class="btn btn-success" style="float:left;margin:0px 5px;" data-toggle="modal" data-target="#confModal"> Giro diterim</button>

              
                  <form method="post" action="'.$aksi.'?module=girojatuhtempo&act=tolakgiromasuk" id="confirmgiro">
                    <input type="hidden" name="nominal"  id="nominal"  value="'.$my['nominaljual'].'" class="form-control" >
                    <input type="hidden" name="akun_kas"  id="akun_kas"  value="'.$my['id_akunkasperkiraan'].'" class="form-control" >
                                       <input type="hidden" name="nota"  id="nota"  value="'.$my['bukti_bayarjual'].'" class="form-control" >
                                             <input type="hidden" name="tgl_jt"  id="tgl_jt"  value="'.$my['jatuh_tempo_jual'].'" class="form-control" >
              <input type="hidden" name="id_bgk"  id="id_bgktolak" value='.$id_BGK.'>
                    <button class="btn btn-danger"  id="hppOnly" style="float:left;margin:0px 5px;" type="submit">Giro Tolak </button>
                  </form>
              <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
           </div>';
             echo ' <script type="text/javascript">
  $( function() {
    $( ".datepicker" ).datepicker({
        dateFormat:"yy-mm-dd",
      changeMonth:true,
    changeYear:true
  });
      $(function() {
        $(".chosen-select").chosen();
        $(".chosen-select-deselect").chosen({ allow_single_deselect: true });
      }); 
  } );
               $("#akun_kas_chosen").css("width","100%");
</script>'; 
}
?>