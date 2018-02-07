<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
// memanggil file config.php
 include "../../config/coneksi.php";
 include "../../lib/input.php";



// DB table to use
$table = 'trans_invoice';

// Table's primary key
$primaryKey = 'id';

$columns = array(
	array('db' => '`a`.`id`', 'dt' => 0, 'field' => 'id'),
	array( 'db' => '`a`.`nama_supplier`', 'dt' => 1, 'field' => 'nama_supplier'),
	array('db' => '`a`.`id_invoice`', 'dt' => 2, 'field' => 'id_invoice' ),
	array( 'db' => '`a`.`tgl`',  'dt' => 3, 'field' => 'tgl' ),
    	array( 'db' => '`a`.`grand_total`', 'dt' => 4, 'field' => 'grand_total',
    		'formatter' => function($d){
	       return format_rupiah($d)."<input type='hidden' class='total' data='\"$d\"'>";
    		}),
	array(
	       'db' => '`a`.`id_invoice`',
	       'dt'        => 5,
	       'field' => 'id_invoice',
	       'formatter' => function($d) {
	       	 return '<a class="btn-sm btn-success" href="#" onclick="akun_invoice(\''.$d.'\')"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>';
    })
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
 
$joinQuery = "FROM ( 
SELECT nama_supplier,`id`, `id_invoice`, `id_lpb`, su.id_supplier, `tgl`,`no_nota`, `grand_total`,'0' as retur FROM `trans_invoice` ti,supplier su WHERE ti.is_void='0' and status_lunas!='2' and `ti`.`id_supplier` = `su`.`id_supplier` 
union all 
SELECT nama_supplier,`id`, `kode_rbb` as id_invoice, `no_invoice`, sup.id_supplier, `tgl_rbb` as tgl, `ket`, `grandtotal_retur` as grand_total,'1' as retur FROM `trans_retur_pembelian` trp,supplier sup WHERE jenis_retur='1' and status='0' and trp.is_void='0' and `trp`.`id_supplier` = `sup`.`id_supplier`
 union all
SELECT nama_supplier,id as id , `no_totalan_tukang` as id_invoice, '' as no_invoice, s.id_supplier, `tgl_totalan` as tgl, `ket`, `nominal_totalan` as grand_total,' ' as retur  FROM `trans_totalan_tukang` t,supplier s 
WHERE s.id_supplier=t.id_supplier 
 union all
 SELECT nama_customer as nama_supplier,`id`, `kode_rjb` as id_invoice, `no_invoice`, cu.`id_customer` as id_supplier, `tgl_rjb` as tgl, `ket`, `grandtotal_retur` as grand_total,'1' as retur FROM `trans_retur_penjualan` trp,`customer` cu WHERE status='0' and trp.is_void=0 and `trp`.`id_customer` = `cu`.`id_customer`) a";
//$groupBy=  " `tb`.`id_barang`";
 
echo json_encode(
   SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, null)
//SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, null, $extraWhere )
);
} else {
    echo '<script>window.location="404.html"</script>';
}
