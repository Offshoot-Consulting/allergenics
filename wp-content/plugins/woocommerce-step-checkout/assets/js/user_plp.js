
function get_responsible_office(id) {

	
	// We can also pass the url value separately from ajaxurl for front end AJAX implementations
	jQuery.ajax({
        url: ajaxurl,
        data: {
            'action':'responsible_office_ajax_request',
			'datatype':'JSON',
            'division_id' : id
        },
        success:function(data) {
            var objs=JSON.parse(data);
  var dd = ''
   jQuery.each(objs, function (index,item) {
      
        dd += '<option value=' + item.id + '>' + item.name + '</option>';
    });
  
   jQuery("#responsible_office_user").html(dd);
        },
        error: function(errorThrown){
            console.log(errorThrown);
        }
    });  
	
}