<?php 
include "../config/koneksi.php";
include "../lib/input.php";
error_reporting(0);
include $_GET['file'].'.php';
 ?>
 <script type="text/javascript">
 	$(function() {
  var tabindex = 1;
  $('input,select,a,button').each(function() {
     if (this.type != "hidden") {
       var $input = $(this);
       $input.attr("tabindex", tabindex);
       tabindex++;
     }
  });
        $('.chosen-select').chosen({
          width: "100%"
        });
});
 </script>