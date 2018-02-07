<?php
 include "../../config/koneksi.php";
 include "../../lib/input.php";
 if(isset($_GET['jenisbuktibayar'])){
 	$jenisbuktibayar = $_GET['jenisbuktibayar'];
 	if($jenisbuktibayar == "BGK"){
		$query2 = mysql_query("SELECT bukti_bayar FROM `trans_bayarbeli_header` where bukti_bayar like 'BGK%' order by id_bayarbeli desc limit 1 ");
		$kode = mysql_fetch_array($query2);
		$kode = kode_pembayaran($kode['bukti_bayar'],"BGK",null);
		echo $kode;

	} else if($jenisbuktibayar == "BKK"){
		$query2=mysql_query("SELECT bukti_bayar FROM `trans_bayarbeli_header` where bukti_bayar like 'BKK%' order by id_bayarbeli desc limit 1 ");
		$kode=mysql_fetch_array($query2);
		$kode = kode_pembayaran($kode['bukti_bayar'],"BKK",null);
		echo $kode;
	} else if($jenisbuktibayar == "BBK"){
                     $query2=mysql_query("SELECT bukti_bayar FROM `trans_bayarbeli_header` where bukti_bayar like 'BBK%' order by id_bayarbeli desc limit 1 ");
                       $kode=mysql_fetch_array($query2);
                       $kode = kode_pembayaran($kode['bukti_bayar'],"BBK",null);
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
} else if(isset($_GET['editTitipanSupplier'])){
    $id_pay=$_GET['editTitipanSupplier'];
    $filenama = "titipansupplier";
    $aksi="modul/$filenama/aksi_$filenama.php";
    $module=$filenama;

     $query67 = mysql_query("SELECT *, tb.ket as ket, tb.nominal_alokasi as total_alokasi FROM trans_bayarbeli_header tb left join akun_kas_perkiraan ak on(tb.id_akunkasperkiraan=ak.id_akunkasperkiraan) LEFT JOIN supplier s ON(s.id_supplier=tb.id_supplier) where tb.id_bayarbeli='$id_pay' ");
      $my = mysql_fetch_array($query67);
      $jenisbayar= explode(" - ", $my['bukti_bayar']);

       $judul = "Edit Titipan Supplier";
          $desk = "List alokasi pembayaran Titipan Supplier";
          headerDeskripsi($judul,$desk);
      echo ' 
   <form method="post" action="'.$aksi.'?module='.$module.'&act=edit">
                                      <table class="table table-hover table_without_top" border=0 id=tambah>
                                      <input type="hidden" name="id_bayar" value="'.$my['id_bayarbeli'].'">
                                  <tr style="border-bottom:1px solid #ddd;">
                                      <td class="kop_td">Akun Kas</td>
                                      <td id="sup">';

                                               echo '<select  class="chosen-select  form-control" id="akun_kas" name="akun_kas" required>';
                                              $tampil=mysql_query("SELECT * , CONCAT(kode_akunkasperkiraan,' - ', nama_akunkasperkiraan) as kode FROM akun_kas_perkiraan where is_void=0 ");
                                                          echo "<option value='$my[id_akunkasperkiraan]' data='$my[kode_akunkasperkiraan]' selected> $my[kode_akunkasperkiraan] - $my[nama_akunkasperkiraan]</option>";
                                                       while($w=mysql_fetch_array($tampil)){
                                                            echo "<option value=$w[id_akunkasperkiraan] data=$w[kode_akunkasperkiraan] >$w[kode]</option>";
                                                          }
                                              echo '</select>
                                              </td>
                                               <td colspan="2"></td>
                                     </tr>
                                       <tr>
                                              <td class="kop_td">No. Bukti </td>';

                                              echo'
                                              <td class="batas_header_form">

                                                    <input name="no_bukti" class="form-control" value="'.$my['bukti_bayar'].'"    id="tampilBuktiBayarTitipan" required>
                                                        <!--<span class="input-group-addon" style="padding:0px 12px;">
                                                             <b  style="border-right: 2px solid #000000;margin:-5px 2px;"> <input type="radio" name="optradio" id="buktibayarCash"> Cash </b>
                                                             <b  style="border-right: 2px solid #000000;margin:-5px 2px;"> <input type="radio" name="optradio" id="buktibayarGiro"> Giro </b>
                                                             <b style="margin:-5px 2px;"> <input type="radio" name="optradio" id="buktibayarTransfer"> Transfer </b>
                                                      </span>-->
                                                  <!-- /input-group -->
                                                      </td>
                                               <td class="kop_td">Tanggal bayar</td>
                                              <td><input name="tgl" value="'.$my['tgl_pembayaran'].'"  class="datepicker form-control"></td>
                                      </tr>
                                        <tr>
                                               <td class="kop_td">Nominal</td>
                                               <td class="batas_header_form">
                                               <input name="nominal" class="form-control numberhit"  data="'.$my['nominal'].'" value="'.$my['nominal'].'"  required>
                                               <td class="kop_td" colspan="2"></td>
                                     </tr>
                                     <tr>
                                                  <td class="kop_td">Supplier</td>
                                                  <td id="supplierPilih">';
                                               echo '<select  class="chosen-select  form-control" id="supplier" name="supplier" required>';
                                              $tampil=mysql_query("SELECT id_supplier, CONCAT(nama_supplier, ' - ', alamat_supplier) AS ini_supplier FROM supplier where is_void=0 ");
                                                          echo "<option value='$my[id_supplier]' selected> $my[nama_supplier] - $my[alamat_supplier] </option>";
                                                       while($w=mysql_fetch_array($tampil)){
                                                            echo "<option value=$w[id_supplier]>$w[ini_supplier]</option>";
                                                          }
                                              echo '</select>
                                              </td>
                                               <td class="kop_td" colspan="2"></td>
                                     </tr>
                                      <tr>
                                                <td colspan="4">
                                                      <label>Keterangan</label>
                                                      <textarea id="ket" name="ket" class="form-control">'.$my['ket'].'</textarea>
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