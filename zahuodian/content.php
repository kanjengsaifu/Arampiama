        <div id="page-content-wrapper">
            <div class="container-fluid xyz">
                <div class="row">
                    <div class="col-lg-12">


<?php
$tambah='Tambah';
$ubah  ='Ubah';
if (empty($_SESSION['username']) AND empty($_SESSION['password'])):
echo "<link href='style.css' rel='stylesheet' type='text/css'>";
echo "<center>Untuk mengakses modul, Anda harus login <br>";
echo "<a href=index.php><b>LOGIN</b></a></center>";
else:
    $aksi="modul/".$_GET['module']."/aksi.php";
    include "modul/".$_GET['module']."/index.php";
endif;






// if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
//   echo "<link href='style.css' rel='stylesheet' type='text/css'>
//  <center>Untuk mengakses modul, Anda harus login <br>";
//   echo "<a href=index.php><b>LOGIN</b></a></center>";
// }
// else
//         // menu baru masukan dibawah 
// {
//     if ($mod=='home'){
//             echo "<h2>Selamat Datang</h2>";
//             echo "Hai, </b> Selamat datang  di Toko Melati";       
//     }
//     else if ($mod=='tambahmenu')
//     {
//         include "modul/tambahmenu/tambahmenu.php";
//     }
//     else if ($mod=='supplier')
//     {
//         include "modul/supplier/supplier.php";
//     }
//     else if ($mod=='gudang')
//     {
//         include "modul/gudang/gudang.php";
//     }
//     else if ($mod=='user')
//     {
//         include "modul/user/user.php";
//     }
//     else if ($mod=='setting_akun')
//     {
//         include "modul/setting_akun/setting_akun.php";
//     }
//     else if ($mod=='merk')
//     {
//         include "modul/merk/merk.php";
//     }
//     else if ($mod=='kategori')
//     {
//         include "modul/kategori/kategori.php";
//     }
//     else if ($mod=='barang')
//     {
//         include "modul/barang/barang.php";      
//     }
//     else if ($mod=='adjustment')
//     {
//         include "modul/adjustment/adjustment.php";
//     }
//     else if ($mod=='customer')
//     {
//         include "modul/customer/customer.php";
//     }
//         else if ($mod=='stock')
//     {
//         include "modul/stock/stock.php";
//     }
//     else if ($mod=='purchaseorder')
//     {
//         include "modul/purchaseorder/purchaseorder.php";
//     }
//      else if ($mod=='purchaseinvoice')
//     {
//         include "modul/purchaseinvoice/purchaseinvoice.php";
//     }
//      else if ($mod=='purchasepayment')
//     {
//         include "modul/purchasepayment/index.php";
//     }
//      else if ($mod=='noncash')
//     {
//         include "modul/noncash/noncash.php";
//     }
//       else if ($mod=='laporanbarangmasuk')
//     {
//         include "modul/laporanbarangmasuk/laporanbarangmasuk.php";
//     }
//          else if ($mod=='salesorder')
//     {
//         include "modul/salesorder/salesorder.php";
//     }
//          else if ($mod=='laporankeluarbarang')
//     {
//         include "modul/laporankeluarbarang/laporankeluarbarang.php";
//     }
//          else if ($mod=='salesinvoice')
//     {
//         include "modul/salesinvoice/salesinvoice.php";
//     }
//      else if ($mod=='transfergudang')
//     {
//         include "modul/transfergudang/transfergudang.php";
//     }
//          else if ($mod=='kartubarang')
//     {
//         include "modul/kartubarang/kartubarang.php";
//     }
//              else if ($mod=='returpembelian')
//     {
//         include "modul/returpembelian/returpembelian.php";
//     }
//                  else if ($mod=='returpenjualan')
//     {
//         include "modul/returpenjualan/returpenjualan.php";
//     }
//                 else if ($mod=='masterbank')
//     {
//         include "modul/masterbank/masterbank.php";
//     }
//                 else if ($mod=='pencairangiro')
//     {
//         include "modul/pencairangiro/pencairangiro.php";
//     }
//                 else if ($mod=='akunkasperkiraan')
//     {
//         include "modul/akunkasperkiraan/akunkasperkiraan.php";
//     }
//                 else if ($mod=='pembayaranpembelian')
//     {
//         include "modul/pembayaran/pembayaranpembelian.php";
//     }
//                 else if ($mod=='pembayaranpenjualan')
//     {
//         include "modul/pembayaran_penjualan/pembayaranpenjualan.php";
//     }           
//                 else if ($mod=='sales')
//     {
//         include "modul/sales/sales.php";
//     }
//                  else if ($mod=='omsetsales')
//     {
//         include "modul/omsetsales/omsetsales.php";
//     }
//                  else if ($mod=='girojatuhtempo')
//     {
//         include "modul/konfirmasi-giro/girojatuhtempo.php";
//     }
//                 else if ($mod=='laporanpembeliannota')
//     {
//         include "modul/laporanpembelian/laporanpembeliannota.php";
//     }
//                 else if ($mod=='laporanhutang')
//     {
//         include "modul/laporanhutang/laporanhutang.php";
//     }   
//                  else if ($mod=='laporanpiutang')
//     {
//         include "modul/laporanpiutang/laporanpiutang.php";
//     }
//         else if ($mod=='laporanpembeliansupplier')
//     {
//         include "modul/laporanpembelian/laporanpembeliansupplier.php";
//     }
//      else if ($mod=='kreditmemo')
//     {
//         include "modul/kreditmemo/kreditmemo.php";
//     }
//      else if ($mod=='kreditmemopenjualan')
//     {
//         include "modul/kreditmemopenjualan/kreditmemo.php";
//     }
//      else if ($mod=='laporangiro')
//     {
//         include "modul/laporangiro/laporangiro.php";
//     }
//         else if ($mod=='laporangiromasuk')
//     {
//         include "modul/laporangiromasuk/laporangiro.php";
//     }
//          else if ($mod=='pembeliansupplier')
//     {
//         include "modul/laporanpembelian/laporanpembelian.php";
//     }
//              else if ($mod=='penjualancustomer')
//     {
//         include "modul/laporanpenjualan/laporanpenjualancustomer.php";
//     }
//      else if ($mod=='penjualanbarang')
//     {
//         include "modul/laporanpenjualan/laporanpenjualanbarang.php";
//     }
//      else if ($mod=='pembelianbarang')
//     {
//         include "modul/laporanpembelian/laporanpembelianbarang.php";
//     }
//         else if ($mod=='region')
//     {
//         include "modul/region/region.php";
//     }
//            else if ($mod=='settings')
//     {
//         include "modul/settings/settings.php";
//     }
//                else if ($mod=='pembeliannota')
//     {
//         include "modul/laporanpembelian/laporanpembeliannota.php";
//     }
//                  else if ($mod=='penjualannota')
//     {
//         include "modul/laporanpenjualan/laporanpenjualannota.php";
//     }
//         else if ($mod=='giromasukcustomer') 
//     {
//         include "modul/giromasukcustomer/giromasukcustomer.php";
//     }
//         else if ($mod=='girokeluarsupplier') 
//     {
//         include "modul/girokeluarsupplier/girokeluarsupplier.php";
//     }
//         else if ($mod=='titipansupplier') 
//     {
//         include "modul/titipansupplier/titipansupplier.php";
//     }
//         else if ($mod=='titipancustomer') 
//     {
//         include "modul/titipancustomer/titipancustomer.php";
//     }
//         else if ($mod=='laporantitipansupplier') 
//     {
//         include "modul/laporantitipansupplier/laporantitipansupplier.php";
//     } else if ($mod=='laporanrinciangiro') {
//         include "modul/laporanrinciangiro/laporanrinciangiro.php";
//     } else if ($mod=='jurnalvoucer') {
//         include "modul/jurnalvoucer/jurnalvoucer.php";
//     } else if ($mod=='tukanganpemberian') {
//         include "modul/tukanganpemberian/tukanganpemberian.php";
//     } else if ($mod=='tukanganpenerimaan') {
//         include "modul/tukanganpenerimaan/tukanganpenerimaan.php";
//     } else if ($mod=='transfertukang') {
//         include "modul/transfertukang/transfertukang.php";
//     } else if ($mod=='adjustmenttukang') {
//         include "modul/adjustmenttukang/adjustmenttukang.php";
//     } else if ($mod=='laporanbahanketukang') {
//         include "modul/laporanbahanketukang/laporanbahanketukang.php";
//     } else if ($mod=='laporantransfertukang') {
//         include "modul/laporantransfertukang/laporantransfertukang.php";
//     } else if ($mod=='hitunghpp') {
//         include "modul/hitunghpp/hitunghpp.php";
//     } else if ($mod=='bontukang') {
//         include "modul/bontukang/bontukang.php";
//     } else if ($mod=='stoktukang') {
//         include "modul/stoktukang/stoktukang.php";
//     }else if ($mod=='pembayaran_dimuka') {
//         include "modul/pembayaran_dimuka/pembayaran_dimuka.php";
//     } else if ($mod=='totalantukangan') {
//         include "modul/totalantukangan/totalantukangan.php";
//     } else if ($mod=='tukanganpenotaan') {
//         include "modul/tukanganpenotaan/tukanganpenotaan.php";
//     }
















//         // menu baru masukan dibawah 
//     // -------------------------- bawah sini -----------------------------//
//              else if ($mod=='jenispembayaran')
//     {
//         include "modul/jenispembayaran/jenispembayaran.php";
//     }
//                     else if ($mod=='kodenota')
//     {
//         include "modul/kodenota/kodenota.php";
//     }
//                 else if ($mod=='test')
//     {
//         include "modul/test/test.php";
//     }  
//                 else if ($mod=='laporan') 
//     {
//         include "modul/laporan/laporan.php";
//     }   
//                 else if ($mod=='laporanakuntansi') 
//     {
//         include "modul/laporanakuntansi/laporan.php";
//     }
//                     else if ($mod=='laporansales') 
//     {
//         include "modul/laporansales/laporan.php";
//     }


//        // -------------------------- atas sini -----------------------------//    
//     // menu baru masukan diatas
//     else
//     {
//       echo "<div id='demobox'><b>MODUL BELUM ADA ATAU BELUM LENGKAP</b></div>
//       <h3>
//                 ( - ) tambah modul baru di \"tambah menu\".<br>
//                 ( - ) tambah letak folder modul di file content.php.<br>
//                 ( - ) <b>Atau</b> Anda tidak mempunyai akses di modul tersebut
//       </h3>";
      
//              //include "error.php";
//     }
// }
?>
                    </div>
                </div>
            </div>
        </div>
<!-- <script type="text/javascript">
$(document).ready(function(){
    setInterval(function() {
        $("#demobox").animate({opacity: "0.1", left: "+=400"}, 1200)
        .animate({opacity: "1", left: "0", height: "100%", width: "100%"}, "slow")
        .animate({top: "0"}, "fast")
        .slideUp()
        .slideDown("slow")
    }, 1500);
        return false;
});
</script> 

<style type="text/css">
#demobox {
    background: #000000;
    color:#ffffff;
    height: 100%;
    width: 100%;
    position: relative;
    font-size: 3em;
    overflow-x:hidden;
}
</style> -->
