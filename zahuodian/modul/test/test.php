<?php
 include "config/koneksi.php";
 error_reporting(E_ALL ^ E_NOTICE);
 session_start();
 if (empty($_SESSION['username']) AND empty($_SESSION['password'])){
  echo "
 <center>Untuk mengakses modul, Anda harus login <br>";
  echo "<a href=../../index.php><b>LOGIN</b></a></center>";
}
else{
  for ($i=1; $i <= 3 ; $i++) { 
    for ($j=1; $j <= 3 ; $j++) { 
       $value=array("a"=>"$i", 
                    "b"=>"$j");
        Insert_database("test",$value,$_SESSION['username']);
    }
  }

echo $abcd;
"UPDATE `test` SET 
`a`=(case b when 1 then 4 when 2 then 5 when 3 then 6 end),
`c`=(case b When 1 then 5 when 2 then 7 end)
where b in (1,2)";

}
function Insert_database($table,$data,$user){
   $date_transaktion=date("Y-m-d h:i:s");
   global $abcd;
   echo $abcd.'aaaa';
  foreach ($data as $fields => $values) {
    $field .= ",`".$fields."`";
    $value .=",'".$values."'";
  }
        $insert="Insert Into `".$table."` (".substr($field, 1).") values (".substr($value, 1).")";
        if (mysql_query($insert)==1) {Log_transaksi($insert,$user,$date_transaktion);echo "succes<br>";}
        else{echo $insert."---Gagal--- <br>";}  
}
function Update_database($table,$data,$where,$user){
 $date_transaktion=date("Y-m-d h:i:s");
 foreach ($data as $fields => $values) {
   $value .=",`".$fields."`='".$values."'";
 }
 $update="UPDATE `".$table."` SET ".substr($value, 1)."WHERE ".$where;
  if (mysql_query($update)==1) {Log_transaksi($update,$user,$date_transaktion);echo "succes<br>";}
  else{echo $update."---Gagal--- <br>";}  
}
function Log_transaksi($query,$user,$date_transaktion){
   $result=explode("`", $query);
   $description=str_replace("`","",$query);
   $description=str_replace("'","",$description);
    $log= "'".$result[0]."','".$result[1]."','".$date_transaktion."','".$user."','".$description."'";
    $insert="Insert Into `log_transaksi` (`perintah`,`table`,`tanggal_transaksi`,`username`,`query`) values (".$log.")";
    mysql_query($insert);
}

?>

