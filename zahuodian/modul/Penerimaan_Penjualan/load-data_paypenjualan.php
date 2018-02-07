<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
// memanggil file config.php
 include "../../config/coneksi.php";
 include "../../lib/input.php";

// DB table to use
$table = 'trans_bayarjual_header';

// Table's primary key
$primaryKey = 'id_bayarjual';
//$test = "CONCAT(`u`.`harga_sat1`,' / (',`u`.`satuan1`)";

$columns = array(
	array('db' => '`u`.`id_bayarjual`', 'dt' => 0, 'field' => 'id_bayarjual'),
	array('db' => '`u`.`customer`', 'dt' => 1, 'field' => 'customer'),
	array('db' => '`u`.`bukti_bayarjual`', 'dt' => 2, 'field' => 'bukti_bayarjual'),
	array( 'db' => '`u`.`nominaljual`', 'dt' => 3, 'field' => 'nominaljual',
    		'formatter' => function($d){
	       return format_rupiah($d);
    		}),
	array( 'db' => '`u`.`nama_akunkasperkiraan`', 'dt' => 4, 'field' => 'nama_akunkasperkiraan'),
	array( 'db' => '`u`.`ket_jual`', 'dt' => 5, 'field' => 'ket_jual'),
	array( 'db' => '`u`.`status_bayar_jual`', 'dt' => 6, 'field' => 'status_bayar_jual'),
	array( 'db'        => '`u`.`id_bayarjual`', 'dt'        => 7,  'field' => 'id_bayarjual',
	       'formatter' => function( $d ) {
	       return '<a class="btn-sm btn-warning" href="?module=pembayaranpenjualan&act=alokasi&id='.$d.'"><span class="glyphicon glyphicon-list" aria-hidden="true"></span></a>';
    }),
		array( 'db'        => '`u`.`hapus`', 'dt'        => 8,  'field' => 'hapus',
	       'formatter' => function( $d ) {
	       $del=explode('-', $d);
	       if ($del[1]=='Yes') {
	       	return '<a class="btn-sm btn-danger" href="modul/pembayaran_penjualan/aksi_pembayaranpenjualan.php?module=pembayaranpenjualan&act=hapus&id='.$del[0].'"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>';
	       }
	       
    }),
);


// SQL server connection information
$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_server
);


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */

require( '../../lib/scripts/ssp.customized.class.php' );
 
$joinQuery = "FROM (select a.id_bayarjual,a.bukti_bayarjual,if((z.bukti_bayarjual is null)and(giro_ditolak_jual='0') ,concat(id_bayarjual,'-','Yes'),concat(id_bayarjual,'-','no')) as hapus,a.nominaljual,nama_akunkasperkiraan,a.ket_jual,a.status_bayar_jual,a.id_akunkasperkiraan,concat(kode_customer,' - ',nama_customer) as customer from trans_bayarjual_header a left join trans_bayarjual_detail z on (z.bukti_bayarjual=a.bukti_bayarjual) left join customer s on (s.id_customer=a.id_customer), akun_kas_perkiraan b where a.id_akunkasperkiraan = b.id_akunkasperkiraan and a.is_void='0' group by a.bukti_bayarjual order by a.id_bayarjual desc ) u ";
echo json_encode(
   SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery )
//SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, null, $extraWhere )
);
} else {
    echo '<script>window.location="404.html"</script>';
}
