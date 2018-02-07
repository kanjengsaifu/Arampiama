<?php 
$id=$_GET['id'];
$id_edit=$_GET['id_region'];
echo GenerateCombobox('id_region-'+$id,'region','chosen-select','id_region,region','region','1','id_region','region',$id_edit,'required');
 ?>