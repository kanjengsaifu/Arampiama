<?php
session_start();
ob_start("ob_gzhandler");
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="author" content="">
	<title>Program Toko </title>   
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta content="" name="description" />
	<meta content="" name="author" />
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />    <!-- Favicon -->
      <?php include "head.php"; ?>
</head>
<body>
<?php include 'header.php'; ?>
</body>
</html>
<!--test commit-->
<script>
  // Pengaturan tab Index
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
// Mask Tanggal Bisa di tambah Mask Lainnya seperti Telp
    $(function() {
          $.mask.definitions['~'] = "[+-]";
        $(".date").mask("99/99/9999",{completed:function(){
            if($(this).mask()>31129999){alert('Format Tanggal Salah!! format : dd/mm/yyyy');$(this).val('');
            }else{$(".keterangan").focus();}}});
        $(".telp").mask("# ###0",{reverse:true,maxlength:false});
        $(".nominal").mask("###,###,###,###",{reverse:true,maxlength:true});
        $(".decimal").mask("###,###,###,###.##",{reverse:true,maxlength:true});
        $(".hari").mask("#,##0 hari",{reverse:true,maxlength:false});
        $(".isi").mask("#,##0 PS",{reverse:true,maxlength:false});
        $(".pp").mask("PP/99999/XI/2017",{completed:function(){
        $(".date").focus();}
        });
    });
 $(document).ready(function () {
     $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
                {
                    return {
                        "iStart": oSettings._iDisplayStart,
                        "iEnd": oSettings.fnDisplayEnd(),
                        "iLength": oSettings._iDisplayLength,
                        "iTotal": oSettings.fnRecordsTotal(),
                        "iFilteredTotal": oSettings.fnRecordsDisplay(),
                        "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
                        "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
                    };
                };
                tb_supplier($('#module').val());
function tb_supplier($module) {
                  var t = $('.tb_supplier').DataTable({
                      "columns": [
                        { "searchable": false },
                        { "searchable": false },
                         { class:"dt-left" },
                        null,
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false }
                      ],
                    "iDisplayLength": 8,
                    "processing": true,
                    "serverSide": true,
                     "ajax": {
                  "url": "json/load-data-supplier.php",
                  "cache": false,
                  "type": "GET",
                  "data": {"module": $module}
                  },
                    "order": [[1, 'asc']],
                    "rowCallback": function (row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                        $('td:eq(0)', row).html(index);
                      
                    }
                });
}
  tb_customer($('#module').val());
function tb_customer($module) {
                  var t = $('.tb_customer').DataTable({
                      "columns": [
                        { "searchable": false },
                        { "searchable": false },
                        { class:"dt-left" },
                        null,
                        { "searchable": false },
                        { "searchable": false },
                        { "searchable": false }
                      ],
                    "iDisplayLength": 8,
                    "processing": true,
                    "serverSide": true,
                     "ajax": {
                  "url": "json/load-data-customer.php",
                  "cache": false,
                  "type": "GET",
                  "data": {"module": $module}
                  },
                    "order": [[1, 'asc']],
                    "rowCallback": function (row, data, iDisplayIndex) {
                        var info = this.fnPagingInfo();
                        var page = info.iPage;
                        var length = info.iLength;
                        var index = page * length + (iDisplayIndex + 1);
                        $('td:eq(0)', row).html(index);
                      
                    }
                });
}

              });
</script>