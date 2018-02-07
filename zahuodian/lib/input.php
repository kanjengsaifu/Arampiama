<?php ob_start();

  function combobox($idname,$table,$id_table,$tampiltable,$id_edit,$name='',$req=''){
    $option="";
    $awal="<select id='$idname' name='$idname' class='chosen-select form-control' $req>";
    $comboquery=mysql_query("SELECT * FROM $table where is_void='0'");
          if ($id_edit==null){
             $option.="<option value='' selected>- Pilih $name  -</option>";
          }   
          while($w=mysql_fetch_array($comboquery)){
            if ($w[$id_table]==$id_edit){
              $option.= "<option value=$w[$id_table] selected>$w[$tampiltable]</option>";
            }
            else{
              $option.= "<option value=$w[$id_table]>$w[$tampiltable]</option>";
            }
          }
    $option.="</select>";
    return $awal.$option;
  }
   function comboboxextra($idname,$select,$table,$where,$value,$tampil,$akun="",$id_edit="",$id=""){
    $option="";
    $idname_id=$idname.$id;
    $akun_array= explode(',', $akun);
    $awal="<select id='$idname_id' name='$idname' class='chosen-select form-control' required>";
    $comboquery=mysql_query("SELECT $select FROM $table where $where");
          // if ($id_edit==0){
          //    $option.="<option value=''  selected>- Pilih -</option>";
          // }   
          while($w=mysql_fetch_array($comboquery)){
            $_ck = (array_search($w[$value], $akun_array) === false)? '' : 'checked';
            if ($_ck=='checked') {
              if ($w[$value]==$id_edit){
              $option.= "<option value=$w[$value] selected>$w[$tampil]</option>";
            }
            else{
              $option.= "<option value=$w[$value]>$w[$tampil]</option>";
            }
            }
          }
    $option.="</select>";
    return $awal.$option;
  }
     function comboboxeasy($idname,$query,$ket,$value,$tampil,$id_edit=""){
    $option="";
    $akun_array= explode(',', $akun);
    $awal="<select id='$idname' name='$idname' class='chosen-select form-control' required>";
    $comboquery=mysql_query("$query");
          if ($id_edit==0){
             $option.="<option  selected>- $ket -</option>";
          }   
          while($w=mysql_fetch_array($comboquery)){
              if ($w[$value]==$id_edit){
              $option.= "<option value='$w[$value]' selected>$w[$tampil]</option>";
            }
            else{
              $option.= "<option value='$w[$value]'>$w[$tampil]</option>";
            }
          }
    $option.="</select>";
    return $awal.$option;
  }



function checkbox($table,$whereNorderby,$key, $Label, $Nilai='') {
  $s = "select * from $table $whereNorderby";

  $d = mysql_query($s);
  $_arrNilai = explode(',', $Nilai);
  $str = '';
  $str = "<tr>";
      $no=1;
  while ($w = mysql_fetch_array($d)) {

    $_ck = (array_search($w[$key], $_arrNilai) === false)? '' : 'checked';
    if (($no % 3)!=0) {
      $str .= "<td class='left'><label>$no</label>
                </td><td><input type=checkbox name='".$key."_array[]' value='$w[$key]' $_ck></td>
              <td class='left'><label> - $w[$Label]</label></td>";
    }else{
       $str .= "<td class='left'><label>$no</label> </td><td><input type=checkbox name='".$key."_array[]' value='$w[$key]' $_ck></td>
              <td class='left'><label> - $w[$Label]</label></td></tr><tr>";

    }
  $no++;
  }

  $str .= "</tr>";
  return $str;
}



function input_data($query,$module){
 try {
    if (mysql_query($query)!=1 ) {
     throw new Exception('Problem with tableAresults');
     } ;
echo $query . "</br>";
   $str_rep= str_replace("'","", $query);
    $query="INSERT INTO `log_transaksi`( `user`, `modul`,`tgl_transaksi`, `perintah`) 
          VALUES ('$_SESSION[namauser]','$module',now(),'$str_rep') ";
  
  mysql_query($query);
  
header('location:../../media.php?module='.$module);

 } catch (Exception $e) {
    echo $query. "   " . "error" ;
   echo 'Message: ' .$e->getMessage();
 }
}

function input_only_log($query,$module=''){
try {
    if (mysql_query($query)!=1 ) {
     throw new Exception('Problem with tableAresults');
     } ;

   $str_rep= str_replace("'","", $query);
    $query="INSERT INTO `log_transaksi`( `user`, `modul`,`tgl_transaksi`, `perintah`) 
          VALUES ('$_SESSION[namauser]','$module',now(),'$str_rep') ";
  mysql_query($query);
 } catch (Exception $e) {
   echo $query. "   " . "error </br>" ;
   echo 'Message: ' .$e->getMessage();
 }
}
function check_query($query){
  try {
     if (mysql_query($query)!=1 ) {
     throw new Exception('Problem with tableAresults');
     } ;
  } catch (Exception $e) {
    echo $query;
  }
}

function input_and_print($query,$module, $ikiid){
try {
    if (mysql_query($query)!=1 ) {
     throw new Exception('Problem with tableAresults');
     } ;
   $str_rep= str_replace("'","", $query);
    $query="INSERT INTO `log_transaksi`( `user`, `modul`,`tgl_transaksi`, `perintah`) 
          VALUES ('$_SESSION[namauser]','$module',now(),'$str_rep') ";
  mysql_query($query);
echo "<script type='text/javascript'>window.open('cetak.php?id=$ikiid'); window.location.replace('../../media.php?module=$module');</script>";


 } catch (Exception $e) {
   echo $query. "   " . "error </br>" ;
   echo 'Message: ' .$e->getMessage();
 }
}
  function kode_checker($sql){
  $query = mysql_query($sql);
  $count = mysql_num_rows($query); 
  if ($count>=1) {
    return True;
  }
  }
function kodesurat($kodesurat, $kode, $nameinput, $idinput, $ikiReadOra = NULL)
{
  $no = explode("/",$kodesurat);
  $tgl_sekarang = date("Ymd");
  $bln_sekarang = date("m");
  $thn_sekarang = date("Y");
  $bln = substr($no[1], "4","2");
  $thn = substr($no[1], "0","4"); 
  if($ikiReadOra == NULL || empty($ikiReadOra)){
    $readOraIki = "readonly='readonly'";
  } else {
    $readOraIki = "";
  }
  if ($bln == $bln_sekarang && $thn==$thn_sekarang ){
     $a = $no[2]+1+100000 ;
  }
  else{
    $a = 1+100000 ;
  }
 
  $C = "$kode/". $tgl_sekarang ."/".substr($a,1);
  $date= date("m/d/Y");
  $b = "<input  name='$nameinput' value='$C' id='$idinput' $readOraIki  class='form-control' required />";
  return $b;
}
function kode_surat($kode_trans,$table,$where,$orderby)
{
  // $kode Trans = PO  /  LKB  /  LPB  /  SO  /  SI
  // $table           = Trans_pur_order   /   Trans_sales_order
  // $where           = Id_pur_order   /   id_seales_order
  // $orderby         =  id  /  
  $bln_sekarang = date("m");
  $thn_sekarang = date("Y");
  $query="SELECT * FROM `$table` where $where like '$kode_trans/$thn_sekarang$bln_sekarang%' ORDER BY `$orderby` DESC ";
  $result=mysql_query($query);
  $r=mysql_fetch_array($result);
  $kode=$r[$where];
  if ($kode=='') {
    $no_kode=1+100000;
  }else{
  $no = explode("/",$kode);
    $no_kode=$no[2]+1+100000;
  }
  $tgl_sekarang = date("Ymd");
  $kode_nota = "$kode_trans/". $tgl_sekarang ."/".substr($no_kode,1);
  return  $kode_nota;
}
function generateButtonAdd($nama,$action,$id='')
{
   $s= "<button type=button class='ui-state-default ui-corner-all' id='$id' onclick=\"$action\">
            <span class='ui-icon ui-icon-circle-plus'></span>$nama
        </button>";   
	return $s;
}

function generateButtonCari($nama)
{
    $s="<button class='ui-state-default ui-corner-all' type='submit' role='button'>
            <span class='ui-icon ui-icon-search'></span>$nama
        </button>";
	return $s;
}
function generateButtonCari2($nama,$id_button='')
{
    $s="<button class='ui-state-default ui-corner-all' id='$id_button' type='button' role='button'>
            <span class='ui-icon ui-icon-search'></span>$nama
        </button>";
	return $s;
}

function generateButton($nama,$action,$id_button='')
{
    $s='<button class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" type="button" role="button" aria-disabled="false" onclick="'.$action.'" id="'.$id_button.'"><span class="ui-button-text">'.$nama.'</span></button>';
	return $s;
}

function generateSubmitButton($nama)
{
    $s="<button class='ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only' type='submit' role='button' aria-disabled='false'><span class='ui-button-text'>$nama</span></button>";
	return $s;
}

function generateInputDecimal($nama,$value='',$maxlength=20,$size=20,$action='')
{
	$caption=$nama.'_caption';
	$s="<input class=input type=text name='$caption' id='$caption' maxlength='$maxlength' size='$size' value='$value' onchange='$action'>";
	$s.="<input type=hidden name='$nama' id='$nama' value='$value'>";
	return $s;
}

function generateInputText($caption,$value='',$maxlength=20,$size=20,$onchange='',$prop='')
{
	$s="<input class=input onchange='$onchange' type=text name='$caption' $prop id='$caption' maxlength='$maxlength' size='$size' value='$value'>";
	return $s;
}
function format_rupiah($angka){
  $rupiah="Rp ".number_format($angka,2,',','.');
  return $rupiah;
}
function format_jumlah($angka){
  $jumlah=number_format($angka,0,',','.');
  if ($jumlah==0) {
    $jumlah="";
  }
  return $jumlah;
}
function format_ribuan($angka){
  $ribuan =" ".number_format($angka,2,',','.');
  return $ribuan ;
}
#### Fungsi CSS ####
function ButtonCase($modul,$tambah,$ubah=''){
  echo '<div class="well">
<a href="?module='.$_GET['module'].'&act=tambah" class="btn btn-primary" >'.$tambah.' 
<span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>';
if ($ubah!='') {
  echo '<a href="?module='.$_GET['module'].'&act=edit" class="btn btn-warning" >'.$ubah.' 
<span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a> ';
}
echo '</div>';
          }
function ButtonAksi($nama_aksi){
  echo '
        <div class="well">
        <div class="row">
        <div class="col-md-10 col-sm-10 col-xm-12">
                <h2>'.$nama_aksi.'</h2>
        </div>
        <div class="col-md-2 col-sm-2 col-xm-12"><h1></h1>
          <input class="btn btn-success" type=submit value=Simpan>
          <input class="btn btn-warning" type=button value=Batal onclick=self.history.back()>
        </div>
        </div>
        </div>';
          }
function GenerateInput($id,$name,$type,$value='',$class,$placeholder='',$readonly='')
{
  echo '<input id="'.$id.'" name="'.$name.'" type="'.$type.'" value="'.$value.'" class="'.$class.'" placeholder="'.$placeholder.'"  '.$readonly.' ">';
}
function InputInteger($id,$name,$type,$value='',$class,$placeholder='',$readonly='')
{
  $caption=$name.'_caption';
  echo '<input id="'.$caption.'" name="'.$caption.'" type="'.$type.'" value="'.$value.'" class="'.$class.'" placeholder="'.$placeholder.'" '.$readonly.' ">';
  echo "<input type=hidden name='$name' id='$name' value='$value'>";
  echo "<script>$($caption).keyup(function(){
     setTimeout(function() {
            var val_text=$($caption).val();
         $($name).val(val_text.replace(/,/g,''));
            }, 0);
      });
        </script>" ;
}
function GenerateCombobox($id,$name,$class,$select,$table,$where,$value,$tampil,$id_edit="",$required=''){
    $keterangan=explode('_', $name);
    $option="";
    $awal="<select id='$id' name='$name' class='$class' $required>";
    $comboquery=mysql_query("SELECT $select FROM $table where $where");
          if ($id_edit==0){
             $option.="<option value=''  selected>- Pilih ".$keterangan[1]." -</option>";
          }   
          while($w=mysql_fetch_array($comboquery)){
              if ($w[$value]==$id_edit){
              $option.= "<option value=$w[$value] selected>$w[$tampil]</option>";
            }
            else{
              $option.= "<option value=$w[$value]>$w[$tampil]</option>";
            }
            
          }
    $option.="</select>";
    return $awal.$option;
  }
  function GenerateModal($name_modal,$title,$size='lg',$header='',$button=''){
    echo '<div id="'.$name_modal.'" class="modal fade" role="dialog">
            <div class="modal-dialog modal-'.$size.'">

              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title">'.$title.'</h4>
                </div>
                <div class="modal-body" id="body_'.$name_modal.'">
                    <table id="tb_'.$name_modal.'" class="table table-hover" width="100%" ><thead><tr>';
                    if ($header!='') {
                        foreach ($header as $key => $value) {
                         echo'<th>'.$value.'</th>';
                        }
                    }
                    echo '
                    </tr></thead></table>
                </div>
              </div>

            </div>
          </div>';
  }

function tanggalan ($tgl){
  if($tgl != ''){
      $newDate = date("d M Y", strtotime($tgl));
    } else{
      $newDate = '-';
    }
      return $newDate;
}
