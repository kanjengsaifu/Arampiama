<?php
 include "../../config/koneksi.php";
 include "../../lib/input.php";
   $val = $_GET['val'];
   $nota = $_GET['nota'];
   switch($_GET['jpr']){
          default:
            echo 'pilih jenis pembayaran';
          break;
          case '1':
            echo formjenispay($val,$nota,null);
          break;
          case '2':

            echo '
            <tr>
            <td>Transfer Dari</td>
            <td colspan="3"><select id="id_masterbank" class="form-control" name="id_masterbank" required>';
            $tampil_lbr=mysql_query("SELECT id,concat(rek,'  -  ',ac,'  (',nama_bank,')') as ket FROM master_bank where is_void=0 ");
                echo "<option value='' selected>- Pilih Rekening -</option>";
                    while($w=mysql_fetch_array($tampil_lbr)){
                      echo "<option value=$w[id]>$w[ket]</option>";
                     }
          echo'
            </tr>

            
              <tr class="bg-success">
                <td>No. Rekening Tujuan</td>
                <td><input id="no_rek" name="no_rek" class="form-control" required></td>
                <td>Atas Nama</td>
                <td><input id="na_supplier" name="na_supplier" class="form-control" required></td>
              </tr>
             ';
            $query=mysql_query("SELECT id_pembayaran FROM `trans_pembayaran` where id_pembayaran like 'BBK%' order by id desc limit 1 ");
          $kode=mysql_fetch_array($query);
          $kode_pembayaran= kode_pembayaran($kode[id_pembayaran],"BBK");

            echo formjenispay($val,$kode_pembayaran,"readonly");
          break;
          case '3':
            echo '
            <tr>
            <td>Transfer Dari</td>
            <td colspan="3"><select id="id_masterbank" class="form-control" name="id_masterbank" required>';
            $tampil_lbr=mysql_query("SELECT id,concat(rek,'  -  ',ac,'  (',nama_bank,')') as ket FROM master_bank where is_void=0 ");
                echo "<option value='' selected>- Pilih Rekening -</option>";
                    while($w=mysql_fetch_array($tampil_lbr)){
                      echo "<option value=$w[id]>$w[ket]</option>";
                     }
          echo'
            </tr>
             <tr class="bg-warning">
                <td>No Giro</td>
                <td><input id="no_giro" name="no_giro" class="form-control" required></td>
                <td>Tanggal jatuh tempo</td>
                <td><input id="jatuh_tempo" name="jatuh_tempo" class="datepicker form-control" required>
                <input type="hidden" value="1" id="status_giro" name="status_giro" class="datepicker form-control"></td>
              </tr>
              <tr class="bg-warning">
                <td>No. Rekening Tujuan</td>
                <td><input id="no_rek" name="no_rek" class="form-control" required></td>
                 <td>Atas Nama</td>
                <td><input id="na_supplier" name="na_supplier" class="form-control" required></td>
              </tr>
              ';
          $query=mysql_query("SELECT id_pembayaran FROM `trans_pembayaran` where id_pembayaran like 'BGK%' order by id desc limit 1 ");
          $kode=mysql_fetch_array($query);
          $kode_pembayaran= kode_pembayaran($kode[id_pembayaran],"BGK");

            echo formjenispay($val,$kode_pembayaran,"readonly");
          break;
        }


function formjenispay($kode,$kodenota,$rd){

    echo '
    <tr>
       <td>No Bukti</td>
       <td><input name="no_nota" class="form-control" value="'.$kodenota.'" '.$rd.' required></td>
       <td>Tanggal Pembayaran</td>
      <td><input id="datepicker" name="tgl" value="'.date("Y-m-d").'" class="datepicker form-control"></td>
    </tr>
   
    <tr>
      <td>Hutang Pembayaran</td>
      <td colspan="3"><input id="jumlah_sisa" name="jumlah_sisa" value="'.$kode.'" readonly class="form-control"></td>
    </tr>
    <tr>
      <td>Jumlah Pembayaran</td>
      <td colspan="3"><input id="jumlah_dibayarkan" name="jumlah_dibayarkan" class="form-control"></td>
    </tr>
    <tr>
      <td>Sisa Pembayaran</td>
       <td colspan="3"><input text-align: right id="sisa_dipembayaran" name="sisa_dipembayaran"  readonly class="form-control"></td>
    </tr>
    <tr>
      <td>Keterangan</td>
       <td colspan="3"><textarea id="ket" name="ket" class="form-control"></textarea></td>
    </tr>';
}
?>
<script type="text/javascript">
   $( function() {
    $( ".datepicker" ).datepicker({
        dateFormat:"yy-mm-dd",
      changeMonth:true,
    changeYear:true}
      );
  } );

</script>