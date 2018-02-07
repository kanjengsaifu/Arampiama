<?php  ob_start();
/// Ijection and scurity
function Anti_injection($data){
  $filter = mysql_real_escape_string(stripslashes(strip_tags(htmlspecialchars($data,ENT_QUOTES))));
  return $filter;
}
function Check_login($username,$pass){
	$r=['result'=>'', 'error'=>'','message'=>''];
	$username = Anti_injection($username);
	$pass     = Anti_injection(md5($pass));
	$date = date('Y-m-d H:i:s');
	if (!ctype_alnum($username) OR !ctype_alnum($pass)){
	    $r['message']= 'Indikasi Injeksi';
		$r['error']  = 'Error :';
	}else{
	    $query = "SELECT * FROM users WHERE username='$username' AND password='$pass' and is_void='0'";
	    $login=mysql_query($query);
	    $ketemu=mysql_num_rows($login);
	    $r=mysql_fetch_array($login);
	  if ($ketemu > 0){
	      mysql_query("UPDATE users SET tgl_loging='$date'  WHERE username='$username'");
	      $_SESSION['username']     = $r['username'];
	      $_SESSION['password']    = $r['password'];
	      $_SESSION['akses']        = $r['level'];
	      header('location:../media.php?module=home');
	  }else{
	  	 	$r['message']= 'User dan Password Tidak Terdaftar';
			$r['error']  = 'Error :';
	  }
	}
	return $r;
}

// Query and database
function Connect_to_database(){
	$server = "localhost"; 
	$username = "root";  
	$password = ""; 
	$database = "programtoko";
	$konek = mysql_connect($server, $username, $password) or 
			die ("Gagal konek ke server MySQL" .mysql_error());
	$bukadb = mysql_select_db($database) 
			or die ("Gagal membuka database $database" .mysql_error());
}
function Select_database($query,$show_query=''){
    if ($show_query!='') {echo $query;}
    $result = mysql_query($query);
    $result =mysql_fetch_array($result);
    return $result;
}

function Plugin_css_and_javascript()
{	
	$r=['result'=>'', 'error'=>'','message'=>''];
	$text='';
	$plugin=['css'	=>	['bootstrap-chosen.css',
						'bootstrap.min.css',
						'bootstrap-theme.css',
						'style.css',
						'font-awesome.min.css',
						'jquery-ui.css',
						'multiple-select.css',
						'bootstrap-select.min.css',
						'layout.css'],
			'script'=>	['jquery.min.js',
						'lib.js',
						'bootstrap.min.js',
						'jquery.dataTables.min.js',
						'jquery.validate.min.js',
						'jquery-ui.js',
						'bootbox.min.js',
						'jquery-confirm.js',
						'chosen.jquery.js',
						'multiple-select.js',
						'zelect.js',
						'bootstrap-select.min.js',
						'jquery.maskedinput.min.js',
						'jquery.mask.min.js',
						'datatable.fix.columns.js']
			];
	try {
		foreach ($plugin as $plugin_key => $plugin_value) {
			foreach ($plugin_value as $plugin_name) {
				if ($plugin_key=='css') {
					$text .='<link rel="stylesheet" 
										type="text/css"
										href="asset/css/'.$plugin_name.'" />';
				}else{
					$text .='<script type="text/javascript" 
									 src="asset/js/'.$plugin_name.'"></script>';
				}
			}
		}
		$r['result']=$text;
	} catch (Exception $e) {
		$r['message']= $e->getMessage();
		$r['error']  = 'Function : Plugin_css_and_javascript';
	}
	return $r;
}
 ?>