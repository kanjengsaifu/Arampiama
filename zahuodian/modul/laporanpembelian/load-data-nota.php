<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
// memanggil file config.php
  include "../../config/coneksi.php";
  include "../../lib/input.php";

 if (isset($_GET['nota'])) { // ########################## Query Tampilan Awal



$table = 'trans_sales_invoice';

$primaryKey = 'id';

$columns = array(
	array('db' => '`u`.`id`', 'dt' => 0, 'field' => 'id' ),
	array('db' => '`u`.`id_pur_order`', 'dt' => 1, 'field' => 'id_pur_order',
		'formatter' => function($d){
			$detail = "<a href = '?module=laporanpembeliannota&act=detailpo&id=$d' title='Detail PO' target='_blank'>$d</a>";
			return $detail;
		} ),
	array('db' => '`u`.`id_lpb`', 'dt' => 2, 'field' => 'id_lpb',
		'formatter' => function($d){
			$detail = "<a href = '?module=laporanpembeliannota&act=detaillpb&id=$d' title = 'Detail LPB' target = '_blank'>$d</a>";
			return $detail;
		}
	 ),
	array('db' => '`u`.`id_invoice`', 'dt' => 3, 'field' => 'id_invoice',
		'formatter' => function($d){
			$detail = "<a href = '?module=laporanpembeliannota&act=detailpi&id=$d' title = 'Detail PI' target = '_blank'>$d</a>";
			return $detail;
		}
		),
	array('db' => '`u`.`tanggal`', 'dt' => 4, 'field' => 'tanggal'),
	array('db' => '`u`.`grand_total`', 'dt' => 5, 'field' => 'grand_total',
		'formatter' => function($d){
		return format_rupiah($d);
		}),
	array('db' => '`u`.`total_terbayar`', 'dt' => 6, 'field' => 'total_terbayar',
		'formatter' => function($d){
		return format_rupiah($d);
		}),
	array('db' => '`u`.`sisa_pembayaran`', 'dt' => 7, 'field' => 'sisa_pembayaran',
		'formatter' => function($d){
		return format_rupiah($d);
		}), 
	array('db' => '`u`.`nama_supplier`', 'dt' => 8, 'field' => 'nama_supplier' ),
	array('db' => '`u`.`telp1_supplier`', 'dt' => 9, 'field' => 'telp1_supplier' )
);

$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_server
);
if (isset($_GET['awal'])){
	$periode_awal=$_GET['awal'];
};
if (isset($_GET['akhir'])){
	$periode_akhir=$_GET['akhir'];
};
require( '../../lib/scripts/ssp.customized.class.php' );
	$joinQuery = "FROM (SELECT t.is_void,nama_supplier,telp1_supplier,t.id,t.tgl_update as tanggal,t.tgl,tpo.id_pur_order,lpb.id_lpb,t.id_invoice,t.Grand_total,total_terbayar,(t.grand_total-total_terbayar)as sisa_pembayaran FROM
trans_pur_order tpo,
trans_lpb lpb,
supplier s,
trans_invoice t left join
(SELECT nota_invoice,sum(nominal_alokasi) as total_terbayar FROM trans_bayarbeli_detail t group by nota_invoice)pembayaran
on t.id_invoice=pembayaran.nota_invoice
where  t.id_supplier=s.id_supplier and t.id_lpb=lpb.id_lpb and lpb.id_pur_order=tpo.id_pur_order) as u";

	$extraWhere =  " `u`.`is_void` = 0 ";
	if((!empty($periode_awal)) && (empty($periode_akhir))  ){
	$extraWhere .=  " AND `u`.`tanggal` >= '".$periode_awal."'";
	}elseif( (!empty($periode_awal)) && (!empty($periode_akhir))){
	$extraWhere .= " AND (u.tanggal BETWEEN  '".$periode_awal."' AND '".$periode_akhir."') order by u.tanggal asc";
	}

echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery, $extraWhere)
); 
} else if (isset($_GET["sales"])){ //################################ CARI modal sales

$table = 'trans_sales_invoice';


$primaryKey = 'id_invoice';

$columns = array(
	array('db' => '`u`.`id`', 'dt' => 0, 'field' => 'id' ),
	array( 'db' => '`u`.`id_invoice`', 'dt' => 1, 'field' => 'id_invoice' ),
	array( 'db' => '`u`.`id_lkb`', 'dt' => 2, 'field' => 'id_lkb' ),
	array( 'db' => '`u`.`id_sales_order`', 'dt' => 3, 'field' => 'id_sales_order' ),
	array( 'db' => '`u`.`nama_customer`',  'dt' => 4 , 'field' => 'nama_customer' ),
	array( 'db' => '`u`.`telp_customer`',  'dt' => 5 , 'field' => 'telp_customer' ),
	array('db'        => 'CONCAT(`u`.`id`," # ",`u`.`id_invoice`)', 'dt' => 6, 'field' => 'id_invoice', 'as' =>  'id_invoice',
	       'formatter' => function( $d ) {
	       	 $j = explode(" # ", $d);
	       return '<button class="btn-sm btn-success" onclick="addMore(\''.$j[0].'\',\''.$j[1].'\',\'sales\')"><span class="glyphicon glyphicon-arrow-right" aria-hidden="true"></span></button>';
    }
),
);

$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_server
);



require( '../../lib/scripts/ssp.customized.class.php' );
 
$joinQuery = "FROM (SELECT t.id,id_invoice,lkb.id_lkb,tso.id_sales_order,nama_customer,telp_customer,t.is_void
FROM trans_sales_invoice t,trans_sales_order tso,trans_lkb lkb,customer c
where  t.id_customer=c.id_customer and t.id_lkb=lkb.id_lkb and lkb.id_sales_order=tso.id_sales_order) AS u";
$extraWhere = "`u`.`is_void` = 0 "; 
//$groupBy=  " `u`.`id_barang`";
 
echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
);

} else if (isset($_GET['startup'])) { // ##########################query detail awal

$table = 'trans_sales_invoice';

$primaryKey = 'id';

$columns = array(
	array('db' => '`ti`.`id`', 'dt' => 0, 'field' => 'id' ),
	array('db' => '`ti`.`tgl`', 'dt' => 1, 'field' => 'tgl',
		'formatter' => function($d){
			return tanggalan($d);
		} ),
	array('db' => '`ti`.`id_invoice`', 'dt' => 2, 'field' => 'id_invoice' ),
	array('db' => '`ti`.`id_lkb`', 'dt' => 3, 'field' => 'id_lkb' ),
	array('db' => '`ti`.`id_sales_order`', 'dt' => 4, 'field' => 'id_sales_order' ),
	array('db' => '`ti`.`alltotal`', 'dt' => 5, 'field' => 'alltotal',
		'formatter' => function($d){
		return format_rupiah($d);
		}),
	array('db' => '`ti`.`allppnnominal`', 'dt' => 6, 'field' => 'allppnnominal',
		'formatter' => function($d){
		return format_rupiah($d);
		}),
	array('db' => '`ti`.`alldiscnominal`', 'dt' => 7, 'field' => 'alldiscnominal',
		'formatter' => function($d){
		return format_rupiah($d);
		}),
	array('db' => '`ti`.`grand_total`', 'dt' => 8, 'field' => 'grand_total',
		'formatter' => function($d){
		return format_rupiah($d);
		}),
	array('db' => '`tk`.`pembayaran`', 'dt' => 9, 'field' => 'pembayaran',
		'formatter' => function($d){
		return format_rupiah($d);
		}),
	array('db' => '`s`.`nama_customer`', 'dt' => 10, 'field' => 'nama_customer' ),
	array('db' => 'CONCAT(`tk`.`giro_ditolak1`," ## ",`tk`.`no_giro1` )', 'dt' => 11, 'field' => 'no_giro1', 'as' => 'no_giro1',
		'formatter' =>  function( $d ) {
			$ini = explode(" ## ", $d);
			if($ini['0'] == '0'){
				if(empty($ini['1'])){
					$ini1 = '';
				} else {
					$ini1 = '<b>No. </b>'.$ini['1'];
				}
				$cair = '<b>Sudah Cair</b><br>'.$ini1;
			} else {
				if(empty($ini['1'])){
					$ini1 = '';
				} else {
					$ini1 = '<b>No. </b>'.$ini['1'];
				}
				$cair = '<b>Belum Cair</b><br>'.$ini1;
			}
			return $cair;
		}
	 ),
	array('db' => '`sl`.`nama_sales`', 'dt' => 11, 'field' => 'nama_sales' )

);

$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_server
);

require( '../../lib/scripts/ssp.customized.class.php' );
	$joinQuery = "FROM 
(SELECT tbh.no_giro_jual AS no_giro1, SUM(tbd.nominal_alokasi_detail_jual) AS pembayaran , tbd.id_customer 
AS id_customer1, tbd.nota_invoice AS nota_invoice1, tbh.giro_ditolak_jual AS giro_ditolak1
FROM trans_bayarjual_detail tbd 
LEFT JOIN trans_bayarjual_header tbh ON(tbh.bukti_bayarjual=tbd.bukti_bayarjual) 
GROUP BY tbd.nota_invoice) AS tk 

RIGHT JOIN  trans_sales_invoice ti ON(tk.nota_invoice1=ti.id_invoice)
LEFT JOIN trans_lkb lkb ON(lkb.id_lkb=ti.id_lkb)
LEFT JOIN trans_sales_order tso ON(tso.id_sales_order=lkb.id_sales_order) 
LEFT JOIN customer s ON (ti.id_customer=s.id_customer)  
LEFT JOIN sales sl ON (sl.id_sales=tso.id_sales)";

	$extraWhere =  "`ti`.`is_void` = 0 AND `ti`.`id_customer` = ".$_GET['startup'];
	if(!empty($_GET['sal'])){
		$extraWhere .=  " AND `sl`.`id_sales` = ".$_GET['sal'];
	}
	if(!empty($_GET['akhir']) && !empty($_GET['awal'])){
	$extraWhere .= " AND (ti.tgl BETWEEN  \"".$_GET['awal']."\" AND \"".$_GET['akhir']."\") order by ti.tgl";
	}

echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery, $extraWhere)
); 
}// ################ akhir else


// ###################### ELSE JIKA SESSIN TIDAK SESUAI ################
} else {
	alert("aaaa");
    echo '<script>window.location="404.html"</script>';
}

// select *,if(Mosule='Laporan Barang Masuk',qty,''),if(Mosule='Laporan Barang Keluar' ,qty,'') from
// (SELECT id_barang, tgl_lpb as tgl,'Laporan Barang Masuk' as Mosule,qty_diterima_convert As qty FROM trans_lpb tl,trans_lpb_detail tld WHERE tl.id_lpb=tld.id_lpb 
// union
// SELECT id_barang, tgl_adjustment as tgl,'Adjustment' as Mosule,plusminus_barang As qty FROM adjustment_stok
// union
// SELECT id_barang, tgl_lkb as tgl,'Laporan Barang Keluar' as Mosule,qty_diterima_convert As qty FROM trans_lkb tl,trans_lkb_detail tld WHERE tl.id_lkb=tld.id_lkb ) as a where id_barang=3 order by tgl

