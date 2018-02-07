<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
// memanggil file config.php
 include "../../config/coneksi.php";
 include "../../lib/input.php";

// DB table to use
$table = 'trans_bayarbeli_header';

// Table's primary key
$primaryKey = 'id_bayarbeli';
//$test = "CONCAT(`u`.`harga_sat1`,' / (',`u`.`satuan1`)";

$columns = array(
	array('db' => '`u`.`id_bayarbeli`', 'dt' => 0, 'field' => 'id_bayarbeli'),
	array('db' => '`u`.`supplier`', 'dt' => 1, 'field' => 'supplier'),
	array('db' => '`u`.`bukti_bayar`', 'dt' => 2, 'field' => 'bukti_bayar'),
	array( 'db' => '`u`.`nominal`', 'dt' => 3, 'field' => 'nominal',
    		'formatter' => function($d){
	       return format_rupiah($d);
    		}),
	array( 'db' => '`u`.`nama_akunkasperkiraan`', 'dt' => 4, 'field' => 'nama_akunkasperkiraan'),
	array( 'db' => '`u`.`ket`', 'dt' => 5, 'field' => 'ket'),
	array( 'db' => '`u`.`status_bayar`', 'dt' => 6, 'field' => 'status_bayar'),
	array( 'db'        => '`u`.`id_bayarbeli`', 'dt'        => 7,  'field' => 'id_bayarbeli',
	       'formatter' => function( $d ) {
	       return '<a class="btn-sm btn-success" href="?module=pembayaranpembelian&act=alokasi&id='.$d.'"><span class="glyphicon glyphicon-list" aria-hidden="true"></span></a>';
    }),
		array( 'db'        => '`u`.`hapus`', 'dt'        => 8,  'field' => 'hapus',
	       'formatter' => function( $d ) {
	       $del=explode('-', $d);
	       if ($del[1]=='Yes') {
	       	return '<a class="btn-sm btn-danger" href="modul/pembayaran/aksi_pembayaranpembelian.php?module=pembayaranpembelian&act=hapus&id='.$del[0].'"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></a>';
	       }
	       
    }),
	array( 'db'        => '`u`.`hapus`', 'dt'        => 9,  'field' => 'hapus',
	       'formatter' => function( $d ) {
	       $del=explode('-', $d);
	       if ($del[1]=='Yes') {
	       	return '<a class="btn-sm btn-warning" href="?module=pembayaranpembelian&act=edit_header&id='.$del[0].'"><span class="glyphicon glyphicon-list" aria-hidden="true"></span></a>';
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
 
$joinQuery = "FROM (select a.id_bayarbeli,a.bukti_bayar,if((z.bukti_bayar is null)and(giro_ditolak='0') ,concat(id_bayarbeli,'-','Yes'),concat(id_bayarbeli,'-','no')) as hapus,a.nominal,nama_akunkasperkiraan,a.ket,a.status_bayar,a.id_akunkasperkiraan,concat(kode_supplier,' - ',nama_supplier) as supplier from trans_bayarbeli_header a left join trans_bayarbeli_detail z on (z.bukti_bayar=a.bukti_bayar) left join supplier s on (s.id_supplier=a.id_supplier), akun_kas_perkiraan b where a.id_akunkasperkiraan = b.id_akunkasperkiraan and a.is_void='0' group by a.bukti_bayar order by a.tgl_pembayaran desc,a.tgl_update desc  ) u ";
echo json_encode(
   SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery )
//SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, null, $extraWhere )
);
} else {
    echo '<script>window.location="404.html"</script>';
}
