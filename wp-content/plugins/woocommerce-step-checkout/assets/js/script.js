

jQuery(window).load(function() {

	jQuery('.form_heading').html('Your details');
	jQuery('.form-row-phone').next().removeClass('form-row-wide');
  	jQuery('.form-row-phone').next().addClass('form-row-first form-row-email');
  	
  	jQuery('.form-row-email').next().removeClass('form-row-wide');
  	jQuery('.form-row-email').next().addClass('form-row-first form-row-password');

  	
  	jQuery('.col-2 .register p:last').addClass('form-row-wide form-row-submit');
  	jQuery('.col-1 .login p:eq(3)').addClass('form-row-wide form-row-submit');
  	jQuery('.col-1 .login').attr('id','lgnFrm');
  	jQuery('.col-2 .register').attr('id','regFrm');
  	
  	jQuery('.col-1 .login .form-row-submit .button').attr('type','hidden');
  	jQuery('.col-2 .register .form-row-submit .button').attr('type','hidden');

  	jQuery('.woocommerce-billing-fields h3').html('Address');

  	
  	jQuery('.col-1 .login p:eq(1)').addClass('form-row-wide form-row-username');
  	jQuery('.col-1 .login p:eq(1)').after('<p class="form-row form-row-wide form-row-middle"></p>');
  	jQuery('.col-1 .login p:eq(3)').addClass('form-row-password');
  	/*jQuery('#billing_state_field label').html('City <abbr title="required" class="required">*</abbr>');
  	jQuery('#billing_city_field label').html('Suburb <abbr title="required" class="required">*</abbr>');
  	jQuery('#billing_city').attr('placeholder','Suburb');
  	jQuery('#billing_address_2').attr('placeholder','');*/
  	
  	
  	
});

	
  jQuery(document).ready(function($){


		
			jQuery.ajax({
         type : "post",
         url : myAjax.ajaxurl,
         data : {action: "wcs_cart"},
         success: function(response) {
			 response = response.split('##');
			 if(response[1] > 0) {
			// jQuery('#no_prpduct').hide();
			 jQuery('#next_move').show();
			 jQuery('#next_blank').hide();
			 }
			 else if(response[1] == 0) {
			//	jQuery('#no_prpduct').show();
				jQuery('#next_move').hide();
			 jQuery('#next_blank').show();
			 }
            jQuery('#product_list_order_summary').html(response[0]);
			//jQuery('.amount').remove();
			//jQuery('.amount').html(response[1]);
         }
      }); 
          });
  /*
jQuery('.add_to_cart').click(function() {
	id = this.id;
	ids = id.split('_');
	product_id = ids[1];
	
				  
    

      jQuery.ajax({
         type : "post",
         url : myAjax.ajaxurl,
         data : {action: "wcs_add_to_cart", product_id : product_id},
         success: function(response) {
			 response = response.split('##');
			 if(response[1] > 0) {
			// jQuery('#no_prpduct').hide();
			
			jQuery('#next_move').show();
			jQuery('#next_blank').hide();
			 }
			 else if(response[1] == 0) {
			//	jQuery('#no_prpduct').show();
				jQuery('#next_move').hide();
			 jQuery('#next_blank').show();
			 }
			 if(product_id != 574) {
			 var remove_a = '<a href="javascript:void(0);" class="button remove_from_cart" id="remove_'+product_id+'" onclick="removeItem('+product_id+')">Remove</a>';
			jQuery('#add_'+product_id).after(remove_a);
			 jQuery('#add_'+product_id).remove();
			}
            jQuery('#product_list_order_summary').html(response[0]);
			//jQuery('.amount').remove();
			//jQuery('.amount').html(response[1]);
         }
      });   
           
});*/

function addtem(product_id) {


	
				  
    

      jQuery.ajax({
         type : "post",
         url : myAjax.ajaxurl,
         data : {action: "wcs_add_to_cart", product_id : product_id},
         success: function(response) {
			 response = response.split('##');
			 if(response[1] > 0) {
			// jQuery('#no_prpduct').hide();
			
			jQuery('#next_move').show();
			jQuery('#next_blank').hide();
			 }
			 else if(response[1] == 0) {
			//	jQuery('#no_prpduct').show();
				jQuery('#next_move').hide();
			 jQuery('#next_blank').show();
			 }
			 if(product_id != 574) {
			 var remove_a = '<a href="javascript:void(0);" class="button remove_from_cart" id="remove_'+product_id+'" onclick="removeItem('+product_id+')">Remove</a>';
			jQuery('#add_'+product_id).after(remove_a);
			 jQuery('#add_'+product_id).remove();
			}
			else {
			var remove_a = '<a href="javascript:void(0);" class="button remove_from_cart" id="remove_'+product_id+'" onclick="removeItem('+product_id+')">Remove urgent processing</a>';
			jQuery('#add_'+product_id).after(remove_a);
			 jQuery('#add_'+product_id).remove();	
			}
            jQuery('#product_list_order_summary').html(response[0]);
			//jQuery('.amount').remove();
			//jQuery('.amount').html(response[1]);
         }
      });  

}
function removeItem(product_id) {
	
	jQuery('#product_id_'+product_id).remove();
	
	jQuery.ajax({
         type : "post",
         url : myAjax.ajaxurl,
         data : {action: "wcs_remove_from_cart", remove_item : product_id},
         success: function(response) {
			response = response.split('##');
			 if(response[1] > 0) {
			 //jQuery('#no_prpduct').hide();
			 jQuery('#next_move').show();
			 jQuery('#next_blank').hide();
			 }
			 else if(response[1] == 0) {
			//	jQuery('#no_prpduct').show();
				jQuery('#next_move').hide();
			 jQuery('#next_blank').show();
			 	
			 }
			 if(response[2] == 0) {
				 var add_a = '<a href="javascript:void(0);" class="button add_to_cart" id="add_574" onclick="addtem(574)">Yes process my tests urgently</a>';
			 	jQuery('#remove_574').after(add_a);
				jQuery('#remove_574').remove();
			 }
			//jQuery('.amount').remove();
			if(product_id == 574) {
							var add_a = '<a href="javascript:void(0);" class="button add_to_cart" id="add_'+product_id+'" onclick="addtem('+product_id+');">Yes process my tests urgently</a>';
			}
			else {
			var add_a = '<a href="javascript:void(0);" class="button add_to_cart" id="add_'+product_id+'" onclick="addtem('+product_id+');">ADD</a>';
			}
			jQuery('#remove_'+product_id).after(add_a);
			jQuery('#remove_'+product_id).remove();

			jQuery('#product_list_order_summary').html(response[0]);
         }
      });
}

jQuery('#show_login').click(function() {

	jQuery('#customer_login .col-1').show();
	jQuery('#customer_login .col-2').hide();
	jQuery('#form_type').val(1);
	//jQuery('.selection_step1').hide();
});

jQuery('#show_register').click(function() {
	jQuery('#customer_login .col-1').hide();
	jQuery('#customer_login .col-2').show();
	jQuery('#form_type').val(0);
	//jQuery('.selection_step1').show();
});

jQuery('#someone').click(function() {

	var form_type = jQuery('#form_type').val();
	if(form_type == 0) {
		jQuery('#for_whome_text_reg').val(this.value);
	}
	else {
		jQuery('#for_whome_text').val(this.value);
	}
	
	jQuery('#client_first_name').show();
	jQuery('#client_last_name').show();
	jQuery('#client_info_save').show();
});

jQuery('#myself').click(function() {
	var form_type = jQuery('#form_type').val();
	if(form_type == 0) {
		jQuery('#for_whome_text_reg').val(this.value);
	}
	else {
		jQuery('#for_whome_text').val(this.value);
	}
	
	jQuery('#client_first_name').hide();
	jQuery('#client_last_name').hide();
	jQuery('#client_info_save').hide();
});

function show_form(form_name) {

	if(form_name == 'Login') {

		jQuery('#customer_login .col-1').show();
		jQuery('#customer_login .col-2').hide();
		//jQuery('.selection_step1').hide();
		
	}
	else {
		jQuery('#customer_login .col-1').hide();
		jQuery('#customer_login .col-2').show();
		//jQuery('.selection_step1').show();
	}
}


function show_client(for_whome_text) {

	if(for_whome_text == '1') {

		jQuery('#client_first_name').show();
		jQuery('#client_last_name').show();
		
	}
	else {
		jQuery('#client_first_name').hide();
		jQuery('#client_last_name').hide();
	
	}
}


function checkMe() {
	
	var form_type = jQuery('#form_type').val();
	if(form_type == 0) {
		jQuery( "#regFrm" ).submit();
	}
	else {
		jQuery( "#lgnFrm" ).submit();
	}

}
jQuery('#reg_client_first_name').live('blur',function() {

	var form_type = jQuery('#form_type').val();
	if(form_type == 0) {
		jQuery( "#client_f_name_reg" ).val(this.value);
	}
	else {
		jQuery( "#client_f_name" ).val(this.value);
	}
	
});

jQuery('#reg_client_last_name').live('blur',function() {
	
	var form_type = jQuery('#form_type').val();
	if(form_type == 0) {
		jQuery( "#client_l_name_reg" ).val(this.value);
	}
	else {
		jQuery( "#client_l_name" ).val(this.value);
	}


});


function setPrdDes(id) {

	var text = jQuery('#prd_desc_'+id).html();
	var head = jQuery('#head_'+id).html();

	
	jQuery('#modal-description .modal-body').html(text);
	jQuery('#modal-description .modal-header h2').html(head);
	
}

function step4_js(form) {

		var html = '<p>Please note we cannot process your test until your hair sample have been received so please send it in as soon as you can. We will email you when your hair sample has been received and when your test results are ready.</p>';
      			html += "<p>Please don't hesitate to contact us if you have any questions.</p> <p>You can send your hair sample by post to</p><p>PO BOX 60 156, Titirangi, Auckland<br>or by courier to c/o Titirangi Pharmacy 408 Titirangi Rd<br></p>";

      			jQuery('.order-one .order-right').html(html);
      			jQuery('.step01').remove();
      			
      	
      		
}
/*
function step4_js_box() {

	var html = '<p style="margin-top:10px;">You can send your hair sample by post to</p><p>PO BOX 60 156, Titirangi, Auckland<br>or by courier to c/o Titirangi Pharmacy 408 Titirangi Rd<br></p>';

      			jQuery('.order-one .order-right').append(html);
  
}
*/

function apply_coupon() {

	var code = jQuery('#cpn_code').val();
	if(code != '') {
	jQuery.ajax({
         type : "post",
         url : myAjax.ajaxurl,
         data : {action: "wcs_cart",add_coupon : code},
         success: function(response) {
			 response = response.split('##');
			 if(response[1] > 0) {
			// jQuery('#no_prpduct').hide();
			 jQuery('#next_move').show();
			 jQuery('#next_blank').hide();
			 }
			 else if(response[1] == 0) {
			//	jQuery('#no_prpduct').show();
				jQuery('#next_move').hide();
			 jQuery('#next_blank').show();
			 }
            jQuery('#product_list_order_summary').html(response[0]);
            jQuery('.cpn_message').html(response[2]);
			//jQuery('.amount').remove();
			//jQuery('.amount').html(response[1]);
         }
      });
}
else {
	jQuery('.cpn_message').html('Please enter coupon code');
}
}

function remove_coupon(code) {

	
	if(code != '') {
	jQuery.ajax({
         type : "post",
         url : myAjax.ajaxurl,
         data : {action: "wcs_cart",remove_coupn : code},
         success: function(response) {
			 response = response.split('##');
			 if(response[1] > 0) {
			// jQuery('#no_prpduct').hide();
			 jQuery('#next_move').show();
			 jQuery('#next_blank').hide();
			 }
			 else if(response[1] == 0) {
			//	jQuery('#no_prpduct').show();
				jQuery('#next_move').hide();
			 jQuery('#next_blank').show();
			 }
            jQuery('#product_list_order_summary').html(response[0]);
            jQuery('.cpn_message').html(response[2]);
			//jQuery('.amount').remove();
			//jQuery('.amount').html(response[1]);
         }
      });
}
}

function change_client_info() {
	var client_first_name = jQuery('#reg_client_first_name').val();
	var client_last_name = jQuery('#reg_client_last_name').val();

	jQuery.ajax({
         type : "post",
         url : myAjax.ajaxurl,
         data : {action: "change_client_info",client_first_name : client_first_name, client_last_name : client_last_name},
         success: function(response) {
         	if(response == true) {
         		jQuery('#step_client_info').hide();
         	}	
         }
      });
}