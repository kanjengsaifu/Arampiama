<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
// memanggil file config.php
 include "../../config/coneksi.php";
 include "../../lib/input.php";

  if (isset($_GET['titipancustomerglobal'])) { // ##########################	QUERY GLOBAL

$table = 'trans_bayarjual_header';
$primaryKey = 'id_bayarjual';
$columns = array(
	array('db' => '`tb`.`id_bayarjual`', 'dt' => 0, 'field' => 'id_bayarjual'),
	array('db' => '`tb`.`bukti_bayarjual`', 'dt' => 1, 'field' => 'bukti_bayarjual'),
	array('db' => '`c`.`nama_customer`', 'dt' => 2, 'field' => 'nama_customer'),
	array( 'db' => '`tb`.`nominaljual`', 'dt' => 3, 'field' => 'nominaljual',
    		'formatter' => function($d){
	       return format_rupiah($d);
    		}),
	array( 'db' => '`tb`.`nominal_alokasi_jual`', 'dt' => 4, 'field' => 'nominal_alokasi_jual',
    		'formatter' => function($d){
	       return format_rupiah($d);
    		}),
	array( 'db' => '`tb`.`sisa_alokasi_jual`', 'dt' => 5, 'field' => 'sisa_alokasi_jual',
    		'formatter' => function($d){
	       return format_rupiah($d);
    		}),
	array( 'db' => '`a`.`kode_akunkasperkiraan`', 'dt' => 6, 'field' => 'kode_akunkasperkiraan'),
	array( 'db' => '`tb`.`ket_jual`', 'dt' => 7, 'field' => 'ket_jual'),
	array( 'db' => '`tb`.`status_bayar_jual`', 'dt' => 8, 'field' => 'status_bayar_jual'),
	array( 'db'        => 'CONCAT(`tb`.`id_bayarjual`," # ",`tb`.`nominal_alokasi_jual`)', 'dt'        => 9,  'field' => 'id_bayarjual', 'as' => 'id_bayarjual',
	       'formatter' => function( $d ) {
	       	 $jenisaksi = explode(" # ", $d);
	       	 if($jenisaksi[1] == '0'){
	       	$s =  '<div class="row">
	       	<a class="btn-sm btn-warning" href="?module=titipancustomer&act=alokasi&id='.$d.'"><span class="glyphicon glyphicon-list" aria-hidden="true"></span></a>
	       <a class="btn-sm btn-success" onclick="editTitipan('.$jenisaksi['0'].')" ><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a>
	       </div>';
	    }
	       else {
	       	$s =  '<a class="btn-sm btn-warning" href="?module=titipancustomer&act=alokasi&id='.$d.'"><span class="glyphicon glyphicon-list" aria-hidden="true"></span></a>';
	       }
	       return $s;
    })

);
$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_server
);
require( '../../lib/scripts/ssp.customized.class.php' );
 
$joinQuery = "FROM `trans_bayarjual_header` AS `tb` JOIN `akun_kas_perkiraan` AS `a` ON (`tb`.`id_akunkasperkiraan` = `a`.`id_akunkasperkiraan`) LEFT JOIN `customer` AS `c` ON(`c`.`id_customer`=`tb`.`id_customer`) ";
$extraWhere = "`tb`.`is_void` = '0'  AND status_titipan = 'T' "; 
//$groupBy=  " `tb`.`id_barang`";
$orderBy = "`tb`.`id_bayarjual` DESC";
 
echo json_encode(
   SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere,  $orderBy )
//SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, null, $extraWhere )
	);
} else if(isset($_GET['invoicecustomer'])){

$table = 'trans_sales_invoice';
$primaryKey = 'id';
$columns = array(
	array('db' => '`ti`.`id`', 'dt' => 0, 'field' => 'id'),
	array( 'db' => '`s`.`nama_customer`', 'dt' => 1, 'field' => 'nama_customer'),
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
	       'formatter' => function($d, $row) {
	       return "<a class='btn-sm btn-success' onclick='intorow(this)' data='$d' id='close'><span class='glyphicon glyphicon-plus' aria-hidden='true'></span></a>";
    })
);


$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_server
);

require( '../../lib/scripts/ssp.customized.class.php' );
 
$joinQuery = "FROM `trans_sales_invoice` AS `ti` JOIN `customer` AS `s` ON (`ti`.`id_customer` = `s`.`id_customer`) ";
$extraWhere = "`ti`.`is_void` = 0 AND `ti`.`status_lunas` != '1'  AND `ti`.`id_customer`= '$_GET[invoicecustomer]' "; 
echo json_encode(
   SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);
} else {
	echo"titik... titik...";
}
//################## END SESIIONN
} else {
    echo '<script>window.location="404.html"</script>';
}
