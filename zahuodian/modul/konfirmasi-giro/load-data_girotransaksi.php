<?php
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && ( $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' )) {
// memanggil file config.php
 include "../../config/coneksi.php";
 include "../../lib/input.php";
 if(($_GET['giro'] == "semua") && (!isset($_GET['aksen'])) ){ //############ tampil semua
 $table = 'trans_bayarbeli_header';

$primaryKey = 'id_bayarbeli';

$columns = array(
	array('db' => 'id_bayarbeli', 'dt' => 0, 'field' => 'id_bayarbeli' ),
	array('db' => 'bukti_bayar', 'dt' => 1, 'field' => 'bukti_bayar',
		  'formatter' => function( $d ) {
	       	 $jenisbayar= explode(" - ", $d);
	       	 if($jenisbayar[0] == 'BGM'){
	       	 	$ty = '<span class="spy">'.$d.'</span> 
	       	 	<script>$(document).ready(function(){
			    $(".spy").closest("tr").css({"background-color": "#93FFFB"});
			});</script>';
	       	 } elseif ($jenisbayar[0] == 'BGK'){
	       	 	$ty = $d;
	       	 }
		       return "$ty";
		    }),
	array( 'db' => 'nominal', 'dt' => 2, 'field' => 'nominal',
		'formatter' => function($d){
		return format_rupiah($d);
		}),
	array( 'db' => 'nama_akunkasperkiraan', 'dt' => 3, 'field' => 'nama_akunkasperkiraan'),
	array( 'db' => 'no_giro', 'dt' => 4, 'field' => 'no_giro' ),
	array( 'db' => 'ket', 'dt' => 5, 'field' => 'ket' ),
	array( 'db' => 'tgl_pembayaran', 'dt' => 6, 'field' => 'tgl_pembayaran',
		'formatter' => function($d){
			return $tglt = date("d M Y", strtotime($d));
		}),
	array( 'db' => 'jatuh_tempo', 'dt' => 7, 'field' => 'jatuh_tempo', 
		'formatter' => function($d){
			  $date = date('Y-m-d', strtotime(' + 7 days'));
		             $dateh1 = date('Y-m-d', strtotime(' + 1 days'));
		             $tglt = date("d M Y", strtotime($d));
		if($d<=$dateh1){
		             $tyu = '<span class="wrt1">'.$tglt.'</span><script>$(document).ready(function(){
			    $(".wrt1").closest("td").attr("id","blink");
			});</script>';
		 }
		 else if($d<=$date){
		            $tyu = '<span class="wrt">'.$tglt.'</span><script>$(document).ready(function(){
			    $(".wrt").closest("td").attr("id","blink2");
			});</script>';
		 }
		 else{
		              $tyu = $tglt;
		 }
		return $tyu;
		}),
	array( 'db' => 'id_bayarbeli1', 'dt'        => 8,  'field' => 'id_bayarbeli1' ,
	       'formatter' => function( $d ) {
	       	 $jenisbayar= explode(" # ", $d);
	       return '
	       <a class="btn-sm btn-warning" onclick="confirmgiro(\''.$jenisbayar[0].'\',\''.$jenisbayar[1].'\')" id="'.$d.'"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></a>';})
);

$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_server
);


require( '../../lib/scripts/ssp.customized.class.php' );
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////CODE BARU/////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$joinQuery = "FROM (
SELECT id_bayarbeli,bukti_bayar,nominal,nama_akunkasperkiraan,no_giro,tgl_pembayaran,jatuh_tempo,tg.ket,CONCAT(id_bayarbeli,' # ',bukti_bayar) AS id_bayarbeli1 FROM 
(
SELECT id_bayarbeli,bukti_bayar,nominal,no_giro,tgl_pembayaran,jatuh_tempo,ket,status_giro,giro_ditolak,id_akunkasperkiraan FROM trans_bayarbeli_header t where is_void='0' and bukti_bayar like 'BGK%'
union all
SELECT id_bayarjual as id_bayarbeli,bukti_bayarjual as bukti_bayar,nominaljual as nominal,no_giro_jual as no_giro,tgl_pembayaranjual as tgl_pembayaran,jatuh_tempo_jual as jatuh_tempo,ket_jual as ket,status_giro_jual as status_giro,giro_ditolak_jual as giro_ditolak,id_akunkasperkiraan  FROM trans_bayarjual_header t where is_void='0' and bukti_bayarjual like 'BGM%' ) as tg left join
akun_kas_perkiraan akp on ( akp.id_akunkasperkiraan=tg.id_akunkasperkiraan) where status_giro=1 and giro_ditolak=0) AS ttable";
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////CODE lAMA/////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
// $joinQuery = "FROM (
// SELECT id_bayarbeli AS 'id_bayarbeli',bukti_bayar AS 'bukti_bayar', nominal AS 'nominal',CONCAT(mb.rek, '(', mb.nama_bank, ')<br> <b>a/n :</b>',mb.ac ) AS id_akunkasperkiraan,CONCAT('<b>Rek :</b>', tb.rek_tujuan,'<br> <b>a/n :</b>', tb.ac_tujuan) AS 'ac_tujuan',no_giro AS' no_giro', ket AS 'ket', jatuh_tempo AS 'jatuh_tempo',CONCAT(tb.id_bayarbeli,' # ',tb.bukti_bayar) AS id_bayarbeli1, tb.is_void  
// FROM trans_bayarbeli_header tb LEFT JOIN master_bank mb ON(tb.id_masterbank=mb.id) WHERE bukti_bayar like 'BGK%'  AND status_giro != 0 AND giro_ditolak != 1
// UNION
// SELECT id_bayarjual AS 'id_bayarbeli',bukti_bayarjual AS 'bukti_bayar', nominaljual AS ' nominal', CONCAT(mb.rek, '(', mb.nama_bank, ')<br> <b>a/n :</b>',mb.ac )  AS id_akunkasperkiraan, CONCAT('<b>Rek :</b>', tj.rek_asal,'<br> <b>a/n :</b>', tj.ac_asal) AS 'ac_tujuan', no_giro_jual AS 'no_giro', ket_jual AS 'ket', jatuh_tempo_jual AS 'jatuh_tempo', CONCAT(tj.id_bayarjual,' # ',tj.bukti_bayarjual) AS id_bayarbeli1, tj.is_void 
// FROM trans_bayarjual_header tj  LEFT JOIN master_bank mb ON(tj.id_masterbank=mb.id) WHERE  bukti_bayarjual like 'BGM%'  AND  status_giro_jual != 0 AND giro_ditolak_jual != 1
// ) AS ttable";
// $extraWhere = "is_void=0 ORDER BY jatuh_tempo DESC";  
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

echo json_encode(
    SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns,$joinQuery, $extraWhere =null)
);

 } else if(isset($_GET['aksen'])) { //###################Query Giro penerimaan pembayaran BGM

 //$extraWhere = "`ti`.`is_void` = 0 AND `ti`.`status_lunas` != '1' AND `ti`.`giro_ditolak` = '0' "; 

$table = 'trans_bayarjual_header';

$primaryKey = 'id_bayarjual';

$columns = array(
	array('db' => '`u`.`id_bayarjual`', 'dt' => 0, 'field' => 'id_bayarjual'),
	array('db' => '`u`.`bukti_bayarjual`', 'dt' => 1, 'field' => 'bukti_bayarjual'),
	array( 'db' => '`u`.`nominaljual`', 'dt' => 2, 'field' => 'nominaljual',
    		'formatter' => function($d){
	       return format_rupiah($d);
    		}),
    array( 'db' => 'nama_akunkasperkiraan', 'dt' => 3, 'field' => 'nama_akunkasperkiraan'),
	array( 'db' => '`u`.`no_giro_jual`', 'dt' => 4, 'field' => 'no_giro_jual'),
	array( 'db' => '`u`.`ket_jual`', 'dt' => 5, 'field' => 'ket_jual'),
	array( 'db' => 'tgl_pembayaranjual', 'dt' => 6, 'field' => 'tgl_pembayaranjual',
		'formatter' => function($d){
			return $tglt = date("d M Y", strtotime($d));
		}),
	array( 'db' => '`u`.`jatuh_tempo_jual`', 'dt' => 7, 'field' => 'jatuh_tempo_jual',
		'formatter' => function($d){
		return date("d M Y", strtotime($d));
		}),
	array( 'db'        => 'CONCAT(`u`.`id_bayarjual`," # ",`u`.
		`bukti_bayarjual`," # ",`u`.`giro_ditolak_jual`," # ",`u`.`status_giro_jual`)', 'dt'        => 8,  'field' => 'id_bayarjual', 'as' => 'id_bayarjual',
	       'formatter' => function( $d ) {
	     	 $jenisbayar= explode(" # ", $d);
	     	  if ($jenisbayar[2] == 1 || $jenisbayar[3] == 0){
	     	 	$ert = "";
	     	 } else {
	     	 	$ert = '<a class="btn-sm btn-warning" onclick="confirmgiro(\''.$jenisbayar[0].'\',\''.$jenisbayar[1].'\')" id="'.$d.'"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></a>';
	     	 }
	       return $ert;})
);

$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_server
);

require( '../../lib/scripts/ssp.customized.class.php' );
 
$joinQuery = "FROM (select id_bayarjual,bukti_bayarjual,nominaljual,nama_akunkasperkiraan,b.id_akunkasperkiraan,no_giro_jual,ket_jual,jatuh_tempo_jual,
giro_ditolak_jual,status_giro_jual,a.is_void,tgl_pembayaranjual from trans_bayarjual_header a, akun_kas_perkiraan b where a.id_akunkasperkiraan=b.id_akunkasperkiraan) as u";
 if(!empty($_GET['giro'])){
 	$giro = $_GET['giro'];
 	if($giro == 'abelumterima'){
 		$joinQuery = "FROM (select id_bayarjual,bukti_bayarjual,'Belum Terdapat' as nama_akunkasperkiraan,nominaljual,no_giro_jual,ket_jual,jatuh_tempo_jual,
giro_ditolak_jual,status_giro_jual,a.is_void,tgl_pembayaranjual from trans_bayarjual_header a) as u";
 		$extraWhere = "`u`.`bukti_bayarjual` like 'BGM%' AND `u`.`is_void` = '0' AND `u`.`status_giro_jual` = '1'  AND `u`.`giro_ditolak_jual` ='0'  "; 
 	} elseif ($giro == 'aditolak'){
 		$extraWhere = "`u`.`bukti_bayarjual` like 'BGM%' AND `u`.`is_void` = '0' AND `u`.`status_giro_jual` = '1' and  `u`.`giro_ditolak_jual` = '1' "; 
 	} elseif($giro == 'aditerima'){ 
 		$extraWhere = "`u`.`bukti_bayarjual` like 'BGM%' AND `u`.`is_void` = '0' AND `u`.`status_giro_jual` = '1'  and `u`.`giro_ditolak_jual` = '2'  "; 
 		
 	}
 } 

//$extraWhere = "`u`.`is_void` = '0'  AND `u`.`bukti_bayar` like 'BGK%' AND `u`.`status_giro` = '1'  AND `u`.`giro_ditolak` = '0' "; 
//$groupBy=  " `u`.`id_barang`";
 
echo json_encode(
   SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
//SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, null, $extraWhere )
);
} else { //###################Query Giro penerimaan pembayaran BGK

 //$extraWhere = "`ti`.`is_void` = 0 AND `ti`.`status_lunas` != '1' AND `ti`.`giro_ditolak` = '0' "; 

$table = 'trans_bayarbeli_header'; 

$primaryKey = 'id_bayarbeli';

$columns = array(
	array('db' => '`u`.`id_bayarbeli`', 'dt' => 0, 'field' => 'id_bayarbeli'),
	array('db' => '`u`.`bukti_bayar`', 'dt' => 1, 'field' => 'bukti_bayar'),
	array( 'db' => '`u`.`nominal`', 'dt' => 2, 'field' => 'nominal',
    		'formatter' => function($d){
	       return format_rupiah($d);
    		}),
	 array( 'db' => 'nama_akunkasperkiraan', 'dt' => 3, 'field' => 'nama_akunkasperkiraan'),
	array( 'db' => '`u`.`no_giro`', 'dt' => 4, 'field' => 'no_giro'),
	array( 'db' => '`u`.`ket`', 'dt' => 5, 'field' => 'ket'),
		array( 'db' => 'tgl_pembayaran', 'dt' => 6, 'field' => 'tgl_pembayaran',
		'formatter' => function($d){
			return $tglt = date("d M Y", strtotime($d));
		}),
	array( 'db' => '`u`.`jatuh_tempo`', 'dt' => 7, 'field' => 'jatuh_tempo',
		'formatter' => function($d){
		return date("d M Y", strtotime($d));
		}),
	array( 'db'        => 'CONCAT(`u`.`id_bayarbeli`," # ",`u`.`bukti_bayar`," # ",`u`.`giro_ditolak`," # ",`u`.`status_giro`)', 'dt'        => 8,  'field' => 'status_giro', 'as' => 'status_giro',
	       'formatter' => function( $d ) {
	     	 $jenisbayar= explode(" # ", $d);
	     	  if ($jenisbayar[2] == 1 || $jenisbayar[3] == 0){
	     	 	$ert = "";
	     	 } else {
	     	 	$ert = '<a class="btn-sm btn-warning" onclick="confirmgiro(\''.$jenisbayar[0].'\',\''.$jenisbayar[1].'\')" id="'.$d.'"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></a>';
	     	 }
	       return $ert;})
);

$sql_details = array(
	'user' => $db_username,
	'pass' => $db_password,
	'db'   => $db_name,
	'host' => $db_server
);

require( '../../lib/scripts/ssp.customized.class.php' );
 
$joinQuery = "FROM (select id_bayarbeli,bukti_bayar,nominal,nama_akunkasperkiraan,b.id_akunkasperkiraan,no_giro,a.ket,jatuh_tempo, giro_ditolak,status_giro,a.is_void,tgl_pembayaran from trans_bayarbeli_header a, akun_kas_perkiraan b where a.id_akunkasperkiraan=b.id_akunkasperkiraan
	) as u";
 if(!empty($_GET['giro'])){
 	$giro = $_GET['giro'];
 	if($giro == 'belumterima'){
 		$extraWhere = "`u`.`bukti_bayar` like 'BGK%' AND `u`.`is_void` = '0' AND `u`.`status_giro` = '1'  AND `u`.`giro_ditolak` = '0'  "; 
 	} elseif ($giro == 'ditolak'){
 		$extraWhere = "`u`.`bukti_bayar` like 'BGK%' AND `u`.`is_void` = '0' AND `u`.`status_giro` = '1'  AND `u`.`giro_ditolak` = '1' "; 
 	} elseif($giro == 'diterima'){ 
 		$extraWhere = "`u`.`bukti_bayar` like 'BGK%' AND `u`.`is_void` = '0' AND `u`.`status_giro` = '1' AND `u`.`giro_ditolak` = '2' "; 
 	}
 } 

//$extraWhere = "`u`.`is_void` = '0'  AND `u`.`bukti_bayar` like 'BGK%' AND `u`.`status_giro` = '1'  AND `u`.`giro_ditolak` = '0' "; 
//$groupBy=  " `u`.`id_barang`";
 
echo json_encode(
   SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
//SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, null, $extraWhere )
);
}

} else {
    echo '<script>window.location="404.html"</script>';
}
