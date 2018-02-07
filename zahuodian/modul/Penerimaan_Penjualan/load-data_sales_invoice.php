<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
// memanggil file config.php
 include "../../config/coneksi.php";
 include "../../lib/input.php";




// DB table to use
$table = 'trans_sales_invoice';

// Table's primary key
$primaryKey = 'id';
$columns = array(
	array('db' => '`ti`.`id`', 'dt' => 0, 'field' => 'id'),
	array( 'db' => '`ti`.`nama_customer`', 'dt' => 1, 'field' => 'nama_customer'),
	array('db' => '`ti`.`id_invoice`', 'dt' => 2, 'field' => 'id_invoice' ),
	array( 'db' => '`ti`.`tgl`',  'dt' => 3, 'field' => 'tgl' ),
    	array( 'db' => '`ti`.`grand_total`', 'dt' => 4, 'field' => 'grand_total',
    		'formatter' => function($d){
	       return format_rupiah($d)."<input type='hidden' class='total' data='\"$d\"'>";
    		}),
	array(
	       'db' => '`ti`.`id_invoice`',
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



require( '../../lib/scripts/ssp.customized.class.php' );
 
$joinQuery = "FROM (
SELECT nama_customer,`id`, `id_invoice`, `id_lkb`, cu.`id_customer`, `tgl`,`no_nota`, `grand_total`,'0' as retur FROM `trans_sales_invoice` trp,`customer` cu WHERE status_lunas!='2' and trp.is_void=0 and `trp`.`id_customer` = `cu`.`id_customer` union all 
SELECT nama_supplier as nama_customer,`id`, `kode_rbb` as id_invoice, `no_invoice`, sup.id_supplier, `tgl_rbb` as tgl, `ket`, `grandtotal_retur` as grand_total,'1' as retur FROM `trans_retur_pembelian` trp,supplier sup WHERE jenis_retur='1' and status='0' and trp.is_void='0' and `trp`.`id_supplier` = `sup`.`id_supplier` 
	union all
 SELECT nama_customer as nama_supplier,`id`, `kode_rjb` as id_invoice, `no_invoice`, cu.`id_customer` as id_supplier, `tgl_rjb` as tgl, `ket`, `grandtotal_retur` as grand_total,'1' as retur FROM `trans_retur_penjualan` trp,`customer` cu WHERE jenis_retur='2' and status='0' and trp.is_void=0 and `trp`.`id_customer` = `cu`.`id_customer`)  ti";
//$groupBy=  " `tb`.`id_barang`";
 
echo json_encode(
   SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, null)
//SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, null, $extraWhere )
);
} else {
    echo '<script>window.location="404.html"</script>';
}
