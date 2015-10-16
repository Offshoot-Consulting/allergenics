jQuery(document).ready(function($) {
	$(".single_add_to_cart_button, .add_to_cart_button").on("click", function() {
		
		if($(this).hasClass('product_type_variable')) return true;
		
		if(parseInt(jQuery.data(document.body, "processing")) == 1) return false;
		jQuery.data(document.body, "processing", 1);
		
		var context = this;
		var product_id_Val;
		var quantity_Val;
		var variation_data = '';
		
		
		if ($(".variations_form").length) {
			
			variation_data = "variation_id="+$("input[name='variation_id']").val();
					
			var attrs = {};
			var variation_form = $('.variations_form');
			
			if($(variation_form).find('select[name^=attribute]').length) {
				$(variation_form).find('select[name^=attribute]').each(function() {
					var name = $(this).attr("name");
					var value = $(this).val();
					attrs[name] = value;
				});
			} else {
				$(variation_form).find('input[name^=attribute]').each(function() {
					attrs[$(this).attr("name")] = $(this).val();
				});
			}
			
			for(var entry in attrs) {
				variation_data += "&attribute["+entry+"]="+attrs[entry];
			}
		}
		
		if($(this).hasClass('single_add_to_cart_button')) {
			product_id_Val = $("input[name='add-to-cart']").val();
			quantity_Val = $("input[name='quantity']").val();
		} else {
			product_id_Val = $(this).attr('data-product_id');
			quantity_Val = $(this).attr('data-quantity');
			variation_data = '';
		}
		
		if(typeof quantity_Val == 'undefined') {
			quantity_Val = 1;
		}
		
		
		if($(context).hasClass('single_add_to_cart_button') && typeof use_product_grabber_2 != 'undefined' && use_product_grabber_2 == 1) 
		{
			var form = $(this).closest('form');
			
			$.ajax( {
				type: "POST",
				url: form.attr( 'action' ),
				data: form.serialize(),
				success: function( response ) 
				{
					if($(response).find('.woocommerce-error').length > 0) 
					{
						alert($(response).find('.woocommerce-error').text().trim());
						jQuery.data(document.body, "processing", 0);
					} 
					else 
					{
						if($(context).hasClass('single_add_to_cart_button')) 
						{
							if(typeof use_default_gallery_image != 'undefined' && use_default_gallery_image == 1) {
								LetsFly($(gallery_image_selector), context);
							} else {
								LetsFly($('.attachment-shop_single'), context);
							}
						} else {
							LetsFly($(context).parents('li').find('img'), context);
						}
						jQuery.data(document.body, "processing", 0);
					}
				}
			} );
		} 
		else 
		{		
			$.post(site_url+ "/wp-admin/admin-ajax.php", 
				"action=orak_add_to_cart_fly&product_id="+product_id_Val+"&quantity="+quantity_Val+"&"+variation_data
			
			).done(function( data ) {
				if($(context).hasClass('single_add_to_cart_button')) 
				{
					if(typeof use_default_gallery_image != 'undefined' && use_default_gallery_image == 1) {
						LetsFly($(gallery_image_selector), context);
					} else {
						LetsFly($('.attachment-shop_single'), context);
					}
				} else {
					LetsFly($(context).parents('li').find('img'), context);
				}
			});
		}	
		return false;
	});
	
	function LetsFly(imageToDrag, context)
	{
		var scrollMillis = flying_speed;
		var scrollTop = $("html, body").scrollTop();
		
		if(scrollTop > 500 && scrollTop < 1000) {
			scrollMillis = scrollTop;
		} else {
			scrollMillis = parseInt(flying_speed)-(parseInt(flying_speed)*0.2);
		}
		
		if(typeof scroll_on_add != 'undefined' && scroll_on_add == 1) {
			$('html, body').animate({
				scrollTop: 0
			}, scrollMillis);
		}
		
		var defEq = parseInt(element_num)-1;
		
		var cart;
		var imgtodrag = $(imageToDrag);
		
		if(typeof use_default_selector != 'undefined' && use_default_selector == 1) {
			cart = $('a[href="'+cart_url+'"]:eq('+defEq+')');
		} else {
			cart = $(custom_selector+':eq('+defEq+')');
		}
		
		if (imgtodrag) 
		{
			var imgclone = imgtodrag.clone()
				.offset({
				top: imgtodrag.offset().top,
				left: imgtodrag.offset().left
			}).css({
				'opacity': parseInt(flying_img_opacity)/100,
				'position': 'absolute',
				'height': $(imgtodrag).css('height'),
				'width': $(imgtodrag).css('width'),
				'z-index': '999999'
			}).appendTo($('body')).animate({
				'top': cart.offset().top + parseInt(arrive_offset_y),
				'left': cart.offset().left + parseInt(arrive_offset_x),
				'width': 75,
				'height': 75
			}, parseInt(flying_speed)-(parseInt(flying_speed)*0.2));
			
			if(typeof use_woocommerce_widget != 'undefined' && use_woocommerce_widget == 1) {
				$supports_html5_storage = ( 'sessionStorage' in window && window['sessionStorage'] !== null );
				$fragment_refresh = {
					url: woocommerce_params.ajax_url,
					type: 'POST',
					data: { action: 'woocommerce_get_refreshed_fragments' },
					success: function( data ) {
						if ( data && data.fragments ) {

							$.each( data.fragments, function( key, value ) {
								$(key).replaceWith(value);
							});

							if ( $supports_html5_storage ) {
								sessionStorage.setItem( "wc_fragments", JSON.stringify( data.fragments ) );
								sessionStorage.setItem( "wc_cart_hash", data.cart_hash );
							}

							$('body').trigger( 'wc_fragments_refreshed' );
						}
					}
				};

				$.ajax($fragment_refresh);
			} 
			
			imgclone.animate({
				'width': 0,
				'height': 0
			}, function() {
				$.ajax({}).done(function(response) {
				
					var found_rs;
					var response_rs;
					
					if(typeof custom_selector_cb == 'undefined' || custom_selector_cb == 0) 
					{
						if(typeof use_default_selector != 'undefined' && use_default_selector == 1) {
							found_rs = $("html, body").find('a[href="'+cart_url+'"]:eq('+defEq+')');
						} else {
							found_rs = $("html, body").find(custom_selector+':eq('+defEq+')');
						}
						if(update_level == 2) found_rs = $(found_rs).parent();
						if(update_level == 3) found_rs = $(found_rs).parent().parent();
						
						if(typeof use_default_selector != 'undefined' && use_default_selector == 1) {
							response_rs = $(response).find('a[href="'+cart_url+'"]:eq('+defEq+')');
						} else {
							response_rs = $(response).find(custom_selector+':eq('+defEq+')');
						}
						if(update_level == 2) response_rs = $(response_rs).parent();
						if(update_level == 3) response_rs = $(response_rs).parent().parent();
						
						$(found_rs).html( $(response_rs).html());
					} else {
					
						jQuery(response).filter("script").each(function(i) {
							if(typeof jQuery(this).attr('src') != 'undefined' && jQuery(this).attr('src').indexOf('orakatc') == -1 && jQuery(this).attr('src').indexOf('jquery')==-1) {
								jQuery(this).appendTo("body");
							}
						});
						
						var rsp_switch = '';
						var use_filter = false;
						if(typeof $(response).filter(custom_selector_update).html() != 'undefined') {
							rsp_switch = $(response).filter(custom_selector_update).html();
							use_filter = true;
						} else {
							rsp_switch = $(response).find(custom_selector_update).html();
						}
						
						$("html, body").find(custom_selector_update).html(rsp_switch);
						if(use_filter == true) {
							found_rs = $("html, body").filter(custom_selector_update);
						} else {
							found_rs = $("html, body").find(custom_selector_update);
						}
				
					}
					
					setTimeout(function () {
						if(typeof use_blink_effect != 'undefined' && use_blink_effect == 1) {
							if(typeof custom_selector_cb == 'undefined' || custom_selector_cb == 0) {
								$(found_rs).toggle("pulsate", {times:1}, 'slow',function() { $(found_rs).toggle("pulsate", {times:1}) });
								
							} else {
								$("html, body").find(custom_selector_update).toggle("pulsate", {times:1}, 'slow',function() { $("html, body").find(custom_selector_update).toggle("pulsate", {times:1}) });
							}
						}
						jQuery.data(document.body, "processing", 0);
					}, 300);
				});
				$(this).detach();
			});
		}
	}
});