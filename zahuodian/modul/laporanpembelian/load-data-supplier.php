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
	array('db' => '`u`.`nama_supplier`', 'dt' => 1, 'field' => 'nama_supplier'),
	array('db' => '`u`.`telp1_supplier`', 'dt' => 2, 'field' => 'telp1_supplier'),
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
	array('db' => '`u`.`sisa_pembayaran`', 'dt' => 5, 'field' => 'sisa_pembayaran',
		'formatter' => function($d){
		return format_ribuan($d);
		}), 
	array('db' => '`u`.`id_supplier`', 'dt' => 6, 'field' => 'detail', 'as' => 'detail',
		'formatter' => function($d){
				$explode= explode('#', $d);
		if (count($explode)==1) {
			$detail ="<a href='?module=laporanpembeliansupplier&act=detail&id=$explode[0]' class='btn btn-sm btn-success' title='Detail' target='_blank'><span class='glyphicon glyphicon-list'></span></a>";
		}elseif (count($explode)==2) {
			$detail ="<a href='?module=laporanpembeliansupplier&act=detail&id=$explode[0]&periode_awal=$explode[1]' class='btn btn-sm btn-success' title='Detail' target='_blank'><span class='glyphicon glyphicon-list'></span></a>";
		}elseif (count($explode)==3) {
			$detail ="<a href='?module=laporanpembeliansupplier&act=detail&id=$explode[0]&periode_awal=$explode[1]&periode_akhir=$explode[2]' class='btn btn-sm btn-success' title='Detail' target='_blank'><span class='glyphicon glyphicon-list'></span></a>";
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


$joinQuery = "FROM (SELECT t.is_void,concat(t.id_supplier,'#','".$tgl_periode."') as id_supplier,t.id,nama_supplier,telp1_supplier,t.tgl_update,t.tgl,tpo.id_pur_order,
	lpb.id_lpb,t.id_invoice,
	concat(sum(t.Grand_total) ,' (',count(*),') ') as total_pembelian
	,total_terbayar,
	(sum(t.grand_total)-sum(total_terbayar)) as sisa_pembayaran 
	FROM
trans_pur_order tpo,
trans_lpb lpb,
supplier s,
trans_invoice t left join
(SELECT nota_invoice,sum(nominal_alokasi) as total_terbayar FROM trans_bayarbeli_detail t group by nota_invoice)pembayaran
on t.id_invoice=pembayaran.nota_invoice
where  t.id_supplier=s.id_supplier and t.id_lpb=lpb.id_lpb and lpb.id_pur_order=tpo.id_pur_order
 ".$periode." group by t.id_supplier) as u";

	$extraWhere =  " `u`.`is_void` = 0 ";

echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery, $extraWhere)
); 

} else if (isset($_GET['id_detail'])) { // ##########################query detail awal



$table = 'trans_sales_invoice';

$primaryKey = 'id';

$columns = array(
	array('db' => '`u`.`id`', 'dt' => 0, 'field' => 'id' ),
	array('db' => '`u`.`id_pur_order`', 'dt' => 1, 'field' => 'id_pur_order',
		'formatter' => function($d){
			$detail = "<a href = '?module=laporanpembeliansupplier&act=po&id=$d' title='Detail PO' target='_blank'>$d</a>";
			return $detail;
		} ),
	array('db' => '`u`.`id_lpb`', 'dt' => 2, 'field' => 'id_lpb',
		'formatter' => function($id){
			$detail = "<a href = '?module=laporanpembeliansupplier&act=lpb&id=$id' title='Detail LPB' target='_blank'>$id</a>";
			return $detail;
		}),
	array('db' => '`u`.`id_invoice`', 'dt' => 3, 'field' => 'id_invoice',
		'formatter' => function($id){
			$detail = "<a href = '?module=laporanpembeliansupplier&act=pi&id=$id' title = 'Detail PI' target = '_blank'>$id</a>";
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
if (isset($_GET["id_detail"])){
	$id_supplier=	$_GET["id_detail"];
};

require( '../../lib/scripts/ssp.customized.class.php' );
	$joinQuery = "FROM (SELECT t.id_supplier,t.is_void,nama_supplier,telp1_supplier,t.id,t.tgl_update as tanggal,t.tgl,tpo.id_pur_order,lpb.id_lpb,t.id_invoice,t.Grand_total,total_terbayar,(t.grand_total-total_terbayar)as sisa_pembayaran FROM
trans_pur_order tpo,
trans_lpb lpb,
supplier s,
trans_invoice t left join
(SELECT nota_invoice,sum(nominal_alokasi) as total_terbayar FROM trans_bayarbeli_detail t group by nota_invoice)pembayaran
on t.id_invoice=pembayaran.nota_invoice
where  t.id_supplier=s.id_supplier and t.id_lpb=lpb.id_lpb and lpb.id_pur_order=tpo.id_pur_order) as u";

	$extraWhere =  " `u`.`is_void` = 0 and u.id_supplier=".$id_supplier;
	if((!empty($periode_awal)) && (empty($periode_akhir))  ){
	$extraWhere .=  " AND `u`.`tanggal` >= '".$periode_awal."'";
	}elseif( (!empty($periode_awal)) && (!empty($periode_akhir))){
	$extraWhere .= " AND (u.tanggal BETWEEN  '".$periode_awal."' AND '".$periode_akhir."') order by u.tanggal asc";
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

