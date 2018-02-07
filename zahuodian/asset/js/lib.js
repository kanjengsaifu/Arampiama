function tabindex(){
    $(document).on('keypress', '.form-control', function (e) {
    if (e.which == 13) {
        e.preventDefault();
        // Get all focusable elements on the page
        var $canfocus = $(':focusable');
        var index = $canfocus.index(document.activeElement) + 1;
        if (index >= $canfocus.length) index = 0;
        $canfocus.eq(index).focus();
    }});

    jQuery.extend(jQuery.expr[':'], {
    focusable: function (el, index, selector) {
        return $(el).is('a, button, :input, [tabindex]');
    }
});
}

function datetimepiker(){
$(document).ready(function(){  
    $( ".datetimepicker" ).datepicker({
        dateFormat:"yy-mm-dd",
      changeMonth:true,
    changeYear:true
  });
  });
}
 function add_newitemcombobox(id_cobobox="",nama=""){
                select = $('#'+id_cobobox);
                select.chosen({ no_results_text: 'Apakah Anda mau menambah '+nama+' baru :', width: '100%'});
                $('.chosen-select-deselect').chosen({ allow_single_deselect: true });
                chosen = select.data('chosen');
                chosen.dropdown.find('input').on('keyup', function(e)
                {
                if (e.which == 13 && chosen.dropdown.find('li.no-results').length > 0)
                    {
                    var option = $("<option>").val(this.value).text(this.value);
                    select.prepend(option); 
                    }
                  i++;
                });
              } 
function ajax_check($id,$table,$field){
  $('#'+$id).blur(function() {
    var id_val=$('#'+$id).val();
     var dataString = 'data='+ id_val +'&table='+$table +'&field='+$field;
                $.ajax({
                      url: "check_database.php",
                     data: dataString,
                     cache: false,
                     success: function(r){
                      if (r>=1){
                        alert("Data Telah Terpakai");
                        $('#'+$id).val('');
                      }
                     } 
              });
  });
              
}
