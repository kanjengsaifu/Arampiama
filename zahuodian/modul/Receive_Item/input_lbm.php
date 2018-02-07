<?php
function GetCheckboxes($table, $key, $Label, $Nilai='') {
  $s = "select * from $table order by id_mainmenu";
  $d = mysql_query($s);
  $_arrNilai = explode(',', $Nilai);
  $str = '';
  echo "<tr>";
  while ($w = mysql_fetch_array($d)) {
    $_ck = (array_search($w[$key], $_arrNilai) === false)? '' : 'checked';
    $str .= "<td>$w[$key]</td>
              <td><input type=checkbox name='".$key."_array[]' value='$w[$key]' $_ck>$w[$Label] </td>";
  }
  echo "</tr>";
  return $str;
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
