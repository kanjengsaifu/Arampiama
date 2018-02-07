<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
// memanggil file config.php
  include "../../config/coneksi.php";
  include "../../lib/input.php";

 if (isset($_GET['nota'])) { // ########################## Query Tampilan Awal



$table = 'trans_sales_invoice';

$primaryKey = 'id';
$periode="";
$columns = array(
	array('db' => '`u`.`id`', 'dt' => 0, 'field' => 'id' ),
	array('db' => '`u`.`nama_customer`', 'dt' => 1, 'field' => 'nama_customer'),
	array('db' => '`u`.`telp_customer`', 'dt' => 2, 'field' => 'telp_customer'),
	array('db' => '`u`.`total_pembelian`', 'dt' => 3, 'field' => 'total_pembelian',
		'formatter' => function($d){
			$explode=explode(' ', $d);
			$k= format_ribuan($explode[0])." ".$explode[1];
		return $k;
		}),
	array('db' => '`u`.`total_terbayar`', 'dt' => 4, 'field' => 'total_terbayar',
		'formatter' => function($d){
		return format_ribuan($d);
		}),
	array('db' => '`u`.`total_hpp`', 'dt' => 5, 'field' => 'total_hpp',
		'formatter' => function($d){
		return format_ribuan($d);
		}),  
	array('db' => '`u`.`total_laba`', 'dt' => 6, 'field' => 'total_laba',
		'formatter' => function($d){
		return format_ribuan($d);
		}),
	array('db' => '`u`.`sisa_pembayaran`', 'dt' => 7, 'field' => 'sisa_pembayaran',
		'formatter' => function($d){
		return format_ribuan($d);
		}), 
	array('db' => '`u`.`id_customer`', 'dt' => 8, 'field' => 'detail', 'as' => 'detail',
		'formatter' => function($d){
		$explode= explode('#', $d);
		if (count($explode)==1) {
			$detail ="<a href='?module=penjualancustomer&act=detail&id=$explode[0]' class='btn btn-sm btn-success' title='Detail' target='_blank'><span class='glyphicon glyphicon-list'></span></a>";
		}elseif (count($explode)==2) {
			$detail ="<a href='?module=penjualancustomer&act=detail&id=$explode[0]&periode_awal=$explode[1]' class='btn btn-sm btn-success' title='Detail' target='_blank'><span class='glyphicon glyphicon-list'></span></a>";
		}elseif (count($explode)==3) {
			$detail ="<a href='?module=penjualancustomer&act=detail&id=$explode[0]&periode_awal=$explode[1]&periode_akhir=$explode[2]' class='btn btn-sm btn-success' title='Detail' target='_blank'><span class='glyphicon glyphicon-list'></span></a>";
		}
				return $detail;	
		} )
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

if((!empty($periode_awal)) && (empty($periode_akhir))  ){
	$tgl_periode= $periode_awal;
	$periode =  " and t.tgl>='".$periode_awal."'";
}elseif( (!empty($periode_awal)) && (!empty($periode_akhir))){
	$tgl_periode= $periode_awal."#".$periode_akhir;
	$periode = " AND (t.tgl BETWEEN  '".$periode_awal."' AND '".$periode_akhir."')";
}else{
	$periode = "";
	$tgl_periode="";
}

$joinQuery = "FROM (SELECT  concat(t.id_customer,'#','".$tgl_periode."') as id_customer,t.tgl_update as tgl_update,
t.tgl,
concat(sum(t.grand_total),' (',count(*),') ') as total_pembelian,
sum(hpp) as total_hpp,
sum(total_terbayar) as total_terbayar,t.id,
(t.grand_total-hpp) as total_laba,
(sum(t.grand_total)-sum(total_terbayar)) as sisa_pembayaran ,
nama_customer,telp_customer,t.is_void
FROM trans_sales_order tso,trans_lkb lkb,customer c,sales sls,
(SELECT id_invoice,sum(hpp) as hpp FROM trans_sales_invoice_detail t group by id_invoice) hpp,trans_sales_invoice t left join
(SELECT nota_invoice,sum(nominal_alokasi_detail_jual) as total_terbayar FROM trans_bayarjual_detail t group by nota_invoice) pembayaran
on t.id_invoice=pembayaran.nota_invoice
where  t.id_customer=c.id_customer and t.id_lkb=lkb.id_lkb and lkb.id_sales_order=tso.id_sales_order and sls.id_sales=tso.id_sales
and hpp.id_invoice=t.id_invoice ".$periode." group by id_customer) as u";
	$extraWhere =  " `u`.`is_void` = 0 ";

echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery, $extraWhere)
); 
} else if (isset($_GET["id_detail"])){ //################################ CARI modal sales


$table = 'trans_sales_invoice';

$primaryKey = 'id';

$columns = array(
	array('db' => '`u`.`id`', 'dt' => 0, 'field' => 'id' ),
	array('db' => '`u`.`id_sales_order`', 'dt' => 1, 'field' => 'id_sales_order',
		'formatter' => function($id){
			$detail = "<a href = '?module=penjualancustomer&act=so&id=$id' title = 'Detail SO' target = '_blank'>$id</a>";
			return $detail;
		} ),
	array('db' => '`u`.`id_lkb`', 'dt' => 2, 'field' => 'id_lkb',
		'formatter' => function($id){
			$detail = "<a href = '?module=penjualancustomer&act=lkb&id=$id' title = 'Detail LKB' target = '_blank'>$id</a>";
			return $detail;
		}),
	array('db' => '`u`.`id_invoice`', 'dt' => 3, 'field' => 'id_invoice',
		'formatter' => function($id){
			$detail = "<a href = '?module=penjualancustomer&act=si&id=$id' title = 'Detail SI' target = '_blank'>$id</a>";
			return $detail;
		}),
	array('db' => '`u`.`tanggal`', 'dt' => 4, 'field' => 'tanggal'),
	array('db' => '`u`.`grand_total`', 'dt' => 5, 'field' => 'grand_total',
		'formatter' => function($d){
		return format_rupiah($d);
		}),
	array('db' => '`u`.`total_terbayar`', 'dt' => 6, 'field' => 'total_terbayar',
		'formatter' => function($d){
		return format_rupiah($d);
		}),
	array('db' => '`u`.`hpp`', 'dt' => 7, 'field' => 'hpp',
		'formatter' => function($d){
		return format_rupiah($d);
		}),  
	array('db' => '`u`.`laba`', 'dt' => 8, 'field' => 'laba',
		'formatter' => function($d){
		return format_rupiah($d);
		}),
	array('db' => '`u`.`sisa_pembayaran`', 'dt' => 9, 'field' => 'sisa_pembayaran',
		'formatter' => function($d){
		return format_rupiah($d);
		}), 
	array('db' => '`u`.`nama_customer`', 'dt' => 10, 'field' => 'nama_customer' ),
	array('db' => '`u`.`nama_sales`', 'dt' => 11, 'field' => 'nama_sales' )
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
if (isset($_GET["id_detail"])){
	$id_customer=	$_GET["id_detail"];
};
require( '../../lib/scripts/ssp.customized.class.php' );
	$joinQuery = "FROM (SELECT t.tgl_update as tanggal,t.tgl,t.alltotal,t.alldiscnominal,t.allppnnominal,t.grand_total,hpp,total_terbayar,(t.grand_total-hpp) as laba,(t.grand_total-total_terbayar) as sisa_pembayaran ,
t.id,t.id_invoice,lkb.id_lkb,tso.id_sales_order,nama_customer,telp_customer,nama_sales,t.is_void,t.id_customer
FROM trans_sales_order tso,trans_lkb lkb,customer c,sales sls,
(SELECT id_invoice,sum(hpp) as hpp FROM trans_sales_invoice_detail t group by id_invoice) hpp,trans_sales_invoice t left join
(SELECT nota_invoice,sum(nominal_alokasi_detail_jual) as total_terbayar FROM trans_bayarjual_detail t group by nota_invoice) pembayaran
on t.id_invoice=pembayaran.nota_invoice
where  t.id_customer=c.id_customer and t.id_lkb=lkb.id_lkb and lkb.id_sales_order=tso.id_sales_order and sls.id_sales=tso.id_sales
and hpp.id_invoice=t.id_invoice) as u";

	$extraWhere =  " `u`.`is_void` = 0 and u.id_customer=".$id_customer;
	if((!empty($periode_awal)) && (empty($periode_akhir))  ){
	$extraWhere .=  " AND `u`.`tanggal` >= '".$periode_awal."'";
	}elseif( (!empty($periode_awal)) && (!empty($periode_akhir))){
	$extraWhere .= " AND (u.tanggal BETWEEN  '".$periode_awal."' AND '".$periode_akhir."') ";
	}
 
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
		return format_ribuan($d);
		}),
	array('db' => '`ti`.`allppnnominal`', 'dt' => 6, 'field' => 'allppnnominal',
		'formatter' => function($d){
		return format_ribuan($d);
		}),
	array('db' => '`ti`.`alldiscnominal`', 'dt' => 7, 'field' => 'alldiscnominal',
		'formatter' => function($d){
		return format_ribuan($d);
		}),
	array('db' => '`ti`.`grand_total`', 'dt' => 8, 'field' => 'grand_total',
		'formatter' => function($d){
		return format_ribuan($d);
		}),
	array('db' => '`tk`.`pembayaran`', 'dt' => 9, 'field' => 'pembayaran',
		'formatter' => function($d){
		return format_ribuan($d);
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

