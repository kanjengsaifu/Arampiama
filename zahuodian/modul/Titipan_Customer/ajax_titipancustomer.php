<?php
 include "../../config/koneksi.php";
 include "../../lib/input.php";
 if(isset($_GET['jenisbuktibayar'])){
 	$jenisbuktibayar = $_GET['jenisbuktibayar'];
 	if($jenisbuktibayar == "BGM"){
            $query2=mysql_query("SELECT bukti_bayarjual FROM `trans_bayarjual_header` where bukti_bayarjual like 'BGM%' order by id_bayarjual desc limit 1 ");
              $kode=mysql_fetch_array($query2);
              $kode = kode_pembayaran($kode['bukti_bayarjual'],"BGM",null);
		echo $kode;

	} else if($jenisbuktibayar == "BKM"){
              $query2=mysql_query("SELECT bukti_bayarjual FROM `trans_bayarjual_header` where bukti_bayarjual like 'BKM%' order by id_bayarjual desc limit 1 ");
              $kode=mysql_fetch_array($query2);
              $kode = kode_pembayaran($kode['bukti_bayarjual'],"BKM",null);
		echo $kode;
	} else if($jenisbuktibayar == "BBM"){
                $query2=mysql_query("SELECT bukti_bayarjual FROM `trans_bayarjual_header` where bukti_bayarjual like 'BBM%' order by id_bayarjual desc limit 1 ");
                  $kode=mysql_fetch_array($query2);
                  $kode = kode_pembayaran($kode['bukti_bayarjual'],"BBM",null);
                       echo $kode;
	}
 }  else if(isset($_GET['ajaxmana'])){
  	$no = $_GET['ajaxmana'];

    $nor = $_GET['nor'];
echo '<tr>
	<td>'.$nor.'</td> <input type="hidden" name="id_bayarbeli_detail[]" value="">
	<td>
            <select  id="akun_kasdetail-'.$no.'" name="akun_kasdetail[]" onchange="kode('.$no.')" class="chosen-select form-control" tabindex="2" required>';
            $tampil=mysql_query("SELECT *, CONCAT(kode_akunkasperkiraan,' - ', nama_akunkasperkiraan) as kode FROM akun_kas_perkiraan where is_void=0 ");
            echo "<option value='' selected> - akun kas perkiraan - </option>"; 

            while($w=mysql_fetch_array($tampil)){
              echo "<option value=$w[id_akunkasperkiraan] data=$w[kode_akunkasperkiraan] >$w[kode]</option>";
            }
              echo '</select><br><b>Kode akun : </b><input type="text" title="'.$no.'" id="viewakun-'.$no.'" readonly></td>
        <td>
              <input type="text" name="no_invoice[]"   id="bukti_bayar-'.$no.'"  class="hitung form-control" onclick="detail('.$no.')"><b>Nominal Invoice : </b><input type="text" name="nominal_detail[]"   id="nominal-'.$no.'"  class="form-control numberhit" readonly>
                           <b><input type="text" class="form-control" id="viewakuntext-'.$no.'" style="text-align:center;" readonly>
                                  <input type="hidden"  id="viewakuntextsave-'.$no.'" name="viewakuntextsave[]"></b>
        </td>
        <td>
              <textarea type="text" name="ketdetail[]"   id="ket-'.$no.'"  class="form-control"></textarea>
        </td>
        <td>
              <input type="text" name="sisa_invoice[]"   id="sisainvoice-'.$no.'"  class="sisainvoice form-control hitung numberhit" readonly> <span id="jikaadainvoice-'.$no.'">
              </span>
        </td>      
        <td>
              <input type="text" name="nominal_alokasi[]"   id="nominalalokasi-'.$no.'"  class="alokasi form-control hitung numberhit" >
        </td>             
        <td>
            <a  class="btn btn-warning" name="del_item" onclick="deleteRow(this)"><span class="glyphicon glyphicon-remove"></span></a>
        </td>
</tr>';
echo "<script type='text/javascript'>
  $(function () {
    var showPopover = function () {
        $(this).popover('show');
    }
    , hidePopover = function () {
        $(this).popover('hide');
    };   
function num1(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ', ');
}
       $('.numberhit').popover({
        content:  function () {
           return num1($(this).val());
        },
        placement: 'top',
        trigger: 'manual'
    })
    .keyup(showPopover)
    .blur(hidePopover)
    .hover(showPopover,hidePopover);

});
</script>";
echo "<script type='text/javascript'>
      $(function() {
        $('.chosen-select').chosen();
        $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
      });
</script>";


} else if(isset($_GET['editTitipanCustomer'])){
    $id_pay=$_GET['editTitipanCustomer'];
    $filenama = "titipancustomer";
    $aksi="modul/$filenama/aksi_$filenama.php";
    $module=$filenama;

  $query67 = mysql_query("SELECT *, tb.ket_jual as ket, tb.nominal_alokasi_jual as total_alokasi FROM trans_bayarjual_header tb left join akun_kas_perkiraan ak on(tb.id_akunkasperkiraan=ak.id_akunkasperkiraan) LEFT JOIN customer c ON(c.id_customer=tb.id_customer) where id_bayarjual='$id_pay'");   //####### QUERY HEADER
      $my = mysql_fetch_array($query67); //####### QUERY HEADER dan  AKUN KAS
      $jenisbayar= explode(" - ", $my['bukti_bayarjual']);

          $judul = "Alokasi Pembayaran Titipan Customer";
          $desk = "List alokasi pembayaran Titipan Customer";
          headerDeskripsi($judul,$desk);
      echo ' 
   <form method="post" action="'.$aksi.'?module='.$module.'&act=edit">
     <table id="tableheader" class="table table-hover table_without_top" border=0>
     <input type="hidden" name="id_bayar" value="'.$my['id_bayarjual'].'">
              <tr style="text-align:left;border-bottom:1px solid #ddd;">
                      <td class="kop_td">Akun Kas</td>
                      <td>';
                              echo '<select  id="akun_kas" name="akun_kas" class="chosen-select form-control" tabindex="2"  required>';
                                              $tampil=mysql_query("SELECT * , CONCAT(kode_akunkasperkiraan,' - ', nama_akunkasperkiraan) as kode FROM akun_kas_perkiraan where is_void=0 ");
                                                          echo "<option value='' selected> - akun kas perkiraan - </option>";
                                                       while($w=mysql_fetch_array($tampil)){
                                                            echo "<option value=$w[id_akunkasperkiraan] data=$w[kode_akunkasperkiraan] >$w[kode]</option>";
                                                          }
                                              echo '</select>
                              </td>
                              <td colspan="2"></td>';
                    echo'
              </tr>
             <tr>
                    <td class="kop_td">No. Bukti </td>
                                              <td class="batas_header_form">
                                                    <input name="no_bukti" class="form-control" id="tampilBuktiBayarTitipan"  value="'.$my['bukti_bayarjual'].'"   required>
                                                        <!--<span class="input-group-addon" style="padding:0px 12px;">
                                                             <b  style="border-right: 2px solid #000000;margin:-5px 2px;"> <input type="radio" name="optradio" id="buktibayarCash"> Cash </b>
                                                             <b  style="border-right: 2px solid #000000;margin:-5px 2px;"> <input type="radio" name="optradio" id="buktibayarGiro"> Giro </b>
                                                             <b style="margin:-5px 2px;"> <input type="radio" name="optradio" id="buktibayarTransfer"> Transfer </b>
                                                      </span>--><!-- /input-group --></td>
                     <td class="kop_td">Tanggal bayar</td>
                    <td><input id="datepicker" name="tgl" value="'.$my['tgl_pembayaranjual'].'"  class="form-control" ></td>
            </tr>';
            echo'
              <tr>
                     <td class="kop_td">Nominal</td>
                     <td class="batas_header_form" id="sup">
                     <input name="nominal" class="form-control numberhit"  id="nominalbkk" data="'.$my['nominaljual'].'" value="'.$my['nominaljual'].'"  >
                     <td colspan="2"></td>
               </tr>
                <tr>
                            <td class="kop_td">Customer</td>
                            <td id="customerPilih"><input class="form-control" name="customer" value="'.$my['nama_customer'].'" ><input  type="hidden" class="form-control" id="id_customer" name="id_customer" value="'.$my['id_customer'].'"> ';
                        echo '
                        </td>
                         <td class="kop_td" colspan="2"></td>
               </tr>
            <tr>
                      <td colspan="4">
                            <label>Keterangan</label>
                            <textarea id="ket" name="ket" class="form-control" >'.$my['ket_jual'].'</textarea>
                      </td>
            </tr>
  </table>
                            <input class="btn btn-success" type="submit" value=Simpan>
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                        </form>';
      echo "<script type='text/javascript'>
  $(function () {
    var showPopover = function () {
        $(this).popover('show');
    }
    , hidePopover = function () {
        $(this).popover('hide');
    };   
function num1(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ', ');
}
       $('.numberhit').popover({
        content:  function () {
           return num1($(this).val());
        },
        placement: 'top',
        trigger: 'manual'
    })
    .keyup(showPopover)
    .blur(hidePopover)
    .hover(showPopover,hidePopover);

});
</script>";
} else {
 	echo "titik... titik...";
 }
?>