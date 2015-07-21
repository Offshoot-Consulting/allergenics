jQuery(document).bind('gform_post_render', function(){
	
	//function ggf_init( ggfSettings ) {
	//hide submit button if needed. when using locator button
	if ( ggfSettings['locator_hide_submit'] == 1 ) 
		jQuery('#gform_submit_button_'+ggfSettings['id']).hide();
	
	//set the autocomplete field
	ggfAutocomplete = '.ggf-autocomplete input[type="text"]';
	
	//clear location fields when address changes
	jQuery('.ggf-field input[type="text"]').on("input", function() {
		jQuery('#ggf-text-fields-wrapper [id^="ggf-field"], [class^="ggf-cf"] input').val('');
		jQuery('[class*="ggf-cf-"] input').val('');
		jQuery('#ggf-update-location').addClass('update');
	});

	//check if map exists in the form and if so trigger it
	if( jQuery('#ggf-map').length ) {

		var latlng = new google.maps.LatLng(mapArgs.latitude,mapArgs.longitude);
	
		var options = {
			zoom: parseInt(mapArgs.zoom_level),
			center: latlng,
			mapTypeId: google.maps.MapTypeId[mapArgs.map_type],
		};
	
		// create the map
		ggfMap = new google.maps.Map(document.getElementById("ggf-map"), options);
	
		// the geocoder object allows us to do latlng lookup based on address
		geocoder = new google.maps.Geocoder();
	
		ggfMarker = new google.maps.Marker({
			position:latlng,
			map: ggfMap,
			draggable: true,
		});
		
		//when dragging the marker on the map
		google.maps.event.addListener( ggfMarker, 'dragend', function(evt){
			jQuery('#ggf-update-location').removeClass('update');
			jQuery('#ggf-update-location').addClass('mapUpdating');
			jQuery("#ggf-field-lat").val( evt.latLng.lat() );
			jQuery("#ggf-field-lng").val( evt.latLng.lng() );
			jQuery(".ggf-cf-lat input").val( evt.latLng.lat() );
			jQuery(".ggf-cf-lng input").val( evt.latLng.lng() );
			returnAddress( evt.latLng.lat(), evt.latLng.lng(), false );  
		});
	
	}
	
	var locatorID;
	
	//run autolocator on page load if needed
	if ( ggfSettings['auto_locator']['use'] == 1 && jQuery('#ggf-autolocator').val() != 'located' ) {
		locatorID = 'pageLoad';
		jQuery('#ggf-update-location').removeClass('update');
        jQuery('#ggf-update-location').addClass('autolocating');
        
        //add value to tell the plugin that autolocator already happened once
        jQuery('#ggf-autolocator').val('located');
        
		getLocationBP();
	}
	
    //locator button clicked 
    jQuery('.ggf-locator-button').click(function() {
    	locatorID = jQuery(this).closest('li').attr('id').substr(-1);
    	jQuery('#ggf-update-location').removeClass('update');
        jQuery('#ggf-update-location').addClass('autolocating');
    	jQuery(".ggf-locator-spinner-wrapper").show();
  		getLocationBP();
  	}); 
  	
    //get current location
    function getLocationBP() {
    	//if browser supported
		if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition( showPosition, showError, {timeout:10000} );
		} else {
   	 		alert("Geolocation is not supported by this browser.");
   	 		jQuery(".ggf-locator-spinner-wrapper").hide();
   	 		jQuery('#ggf-update-location').removeClass('autolocating');
   		}
		
	}
    
    //show results of current location
	function showPosition(position) {	

		if ( ggfSettings['fields'][locatorID]['locator_found_message'] == 1 ) alert('Location found');
   		
  		returnAddress( position.coords.latitude, position.coords.longitude, true );
  		jQuery(".ggf-locator-spinner-wrapper").hide();
  		
  		if ( ggfSettings['fields'][locatorID]['locator_autosubmit'] == 1 ) jQuery('#ggf-update-location').addClass('autosubmit');
  		
	}

	//error message for locator button
	function showError(error) {
  		
		switch(error.code) {
   	 		case error.PERMISSION_DENIED:
   	 			alert('User denied the request for Geolocation.');
     		break;
   		 	case error.POSITION_UNAVAILABLE:
   		   		alert("Location information is unavailable.");
    	  	break;
    		case error.TIMEOUT:
      			alert("The request to get user location timed out.");
     		break;
    		case error.UNKNOWN_ERROR:
      			alert("An unknown error occurred.");
      		break;
		}
		jQuery(".ggf-locator-spinner-wrapper").hide();
		jQuery('#ggf-update-location').removeClass('autolocating');
	}
	
	//update map when using autocomplete field
	function update_map() {
		
		//check that map exists on the form
		if ( !jQuery('#ggf-map').length ) return;
			
		var latLng = new google.maps.LatLng( jQuery('#ggf-field-lat').val(), jQuery('#ggf-field-lng').val() );
		
		ggfMarker.setMap(null);
		
		ggfMarker = new google.maps.Marker({
		    position: latLng,
		    map: ggfMap,
            draggable: true
		});
		ggfMap.setCenter(latLng);
		
		//when dragging the marker on the map
		google.maps.event.addListener( ggfMarker, 'dragend', function(evt){
			jQuery('#ggf-update-location').removeClass('update');
			jQuery('#ggf-update-location').addClass('mapUpdating');
			jQuery("#ggf-field-lat").val( evt.latLng.lat() );
			jQuery("#ggf-field-lng").val( evt.latLng.lng() );
			jQuery(".ggf-cf-lat input").val( evt.latLng.lat() );
			jQuery(".ggf-cf-lng input").val( evt.latLng.lng() );
			returnAddress( evt.latLng.lat(), evt.latLng.lng(), false );  
		});
			
	}
					
	//trigger autocomplete
	function ggfAutocompleteInit() {
		
		//do it for each autocomplete field in the form
		jQuery('.ggf-autocomplete').each(function() {
				
			var fieldID 	  = jQuery(this).attr('id').split('_');
			fieldID 		  = fieldID[fieldID.length-1];
			var fieldSettings = ggfSettings['fields'][fieldID];
	        var faField 	  = ( jQuery(this).hasClass('ggf-full-address') ) ? true : false;    
	        var input 		  = document.getElementById( jQuery(this).find('div :input').attr('id') );
	        
	        //if displaying results worldwide
	        if ( fieldSettings['restrictions'] == false ) {
		        var options = {
		        		types: ['geocode'],
		        };
		    //otherwise restrict to single country
	        } else {
	        	var options = {
		        		types: ['geocode'],
		        		componentRestrictions: { country: fieldSettings['restrictions'] }
		        };
	        }
	
	        var autocomplete = new google.maps.places.Autocomplete(input, options);
	        
	        google.maps.event.addListener(autocomplete, 'place_changed', function(e) {
	        	
	        	var place = autocomplete.getPlace();
	
				if (!place.geometry) {
					return;
				}
	
				//if we updating hidden fields of location
				if ( faField == true ) {
					
                    jQuery('#ggf-update-location').removeClass('update');	
                    jQuery('#ggf-text-fields-wrapper [id^="ggf-field"]').val('');
                    jQuery('[class*="ggf-cf-"] input').val('');
                    var item = autocomplete.getPlace();
                    breakAddress(item);
                }
				
	        	// update map if needed
				if ( fieldSettings['update_map'] == 1 ) {
					
					//return if no map exists
					if ( !jQuery('#ggf-map').length ) return;
					
					if (place.geometry.viewport) {
						ggfMap.fitBounds(place.geometry.viewport);
					} else {
						ggfMap.setCenter(place.geometry.location);
					}
					
					ggfMarker.setPosition(place.geometry.location);
					ggfMarker.setVisible(true);
				}
				
		    });
	        
		});

	}
	ggfAutocompleteInit();
	
			 
	//autocomplete
	/*
	function ggfAutocompleteInit() {
		
                var acField;
                var umField;
                var faField;
                
		jQuery(ggfAutocomplete).autocomplete({
	
			source: function(request,response) {
                     				
				geocoder = new google.maps.Geocoder();
				// the geocode method takes an address or LatLng to search for
				// and a callback function which should process the results into
				// a format accepted by jqueryUI autocomplete
				geocoder.geocode( {'address': request.term }, function(results, status) {
					response(jQuery.map(results, function(item) {
						return {
							label: item.formatted_address, // appears in dropdown box
							value: item.formatted_address, // inserted into input element when selected
							geocode: item                  // all geocode data: used in select callback event
						};
					}));
				});
			},
	
			// event triggered when drop-down option selected
			select: function(event,ui){
				
				if ( jQuery('#'+acField).length == 1 ) {
                                    
					//update_ui(  ui.item.value, ui.item.geocode.geometry.location );
					//update_map( ui.item.geocode.geometry );
   
                    //if we updating hidden fields of location
					if ( faField == true ) {
                        jQuery('#ggf-update-location').removeClass('update');	
                        jQuery('#ggf-text-fields-wrapper input').val('');
                        breakAddress(ui.item.geocode);
                    }
                    //when updating marker on the map
                    if ( umField == true ) update_map();
	
				}
				
			}
		});
		
		// triggered when user presses a key in the address box
		jQuery(ggfAutocomplete).bind('keydown', function(event) {
                        
                acField = jQuery(this).attr('id');
                umField = ( jQuery(this).closest('li').hasClass('autocomplete-update-map') ) ? true : false;
                faField = ( jQuery(this).closest('li').hasClass('ggf-full-address') ) ? true : false;
                        
			if(event.keyCode == 13) {
				// ensures dropdown disappears when enter is pressed
				jQuery(acField).autocomplete("disable");
			} else {
				// re-enable if previously disabled above
				jQuery(acField).autocomplete("enable");
			}
		});
		
	}
	ggfAutocompleteInit();
	*/
	
	//reverse geocode coords to address
	function returnAddress( gotLat, gotLng, updateMap ) {
				
		geocoder = new google.maps.Geocoder();
		var latlng = new google.maps.LatLng(gotLat ,gotLng);
	
		//geocode lat/lng to address
		geocoder.geocode( {'latLng': latlng }, function(results, status) {
      		
			if (status == google.maps.GeocoderStatus.OK) {
                if ( results[0] ) {
                    breakAddress(results[0]);
                    if ( updateMap == true ) update_map();
                }
            } else {
                alert("Geocoder failed due to: " + status);
            }
   		});
	} 
	
	//address components
	function breakAddress(location) {
		
		//remove all address fields
		jQuery('#ggf-text-fields-wrapper [id^="ggf-field"]').val('');
		jQuery('[class*="ggf-cf-"] input').val('');
		
		//update fields with address from map
		if ( jQuery('#ggf-update-location').hasClass('mapUpdating') ) {

			jQuery('.map-autofill input[type="text"]').val(location.formatted_address);		
			jQuery('#ggf-update-location').removeClass('mapUpdating');
			
		//update fields with values from auto locator
		} else if ( jQuery('#ggf-update-location').hasClass('autolocating') ) {
            
            jQuery('.locator-fill input[type="text"]').val(location.formatted_address);
            jQuery('#ggf-update-location').removeClass('autolocating');
        } 
        
		jQuery('#ggf-field-formatted_address').val(location.formatted_address);
		jQuery('.ggf-cf-formatted input').val(location.formatted_address);
		jQuery("#ggf-field-lat").val( location.geometry.location.lat() );
		jQuery("#ggf-field-lng").val( location.geometry.location.lng() );
		jQuery(".ggf-cf-lat input").val( location.geometry.location.lat() );
		jQuery(".ggf-cf-lng input").val( location.geometry.location.lng() );
		
		address = location.address_components;
		
		var street_number = false;
		
		for ( x in address ) {

			if ( address[x].types == 'street_number' ) {
				street_number = address[x].long_name;
			}
			
			if ( address[x].types == 'route' ) {
				street = address[x].long_name;  
				if ( street_number != false ) {
					street = street_number + ' ' + street;
					jQuery("#ggf-field-street").val(street);
					jQuery(".ggf-cf-street input").val(street);
					
					if ( !jQuery('#ggf-update-location').hasClass('update') ) 
						jQuery('.ggf-field-street input[type="text"]').val(street);
					
				} else {
					jQuery("#ggf-field-street").val(street);
					jQuery(".ggf-cf-street input").val(street);
					
					if ( !jQuery('#ggf-update-location').hasClass('update') ) 
						jQuery('.ggf-field-street input[type="text"]').val(street);
					
				}
			}
	
			if ( address[x].types == 'administrative_area_level_1,political' ) {
                state = address[x].short_name;
                state_long = address[x].long_name;
                jQuery("#ggf-field-state").val(state);
                jQuery(".ggf-cf-state input").val(state);
                
                jQuery("#ggf-field-state_long").val(state_long);
                jQuery(".ggf-cf-state_long input").val(state_long);
                
                jQuery('.ggf-field-state input[type="text"]').val(state);

            } 

            if (address[x].types == 'locality,political') {
	            city = address[x].long_name;
	            jQuery("#ggf-field-city").val(city);
	            jQuery(".ggf-cf-city input").val(city);
	
            if ( !jQuery('#ggf-update-location').hasClass('update') ) 
                jQuery('.ggf-field-city input[type="text"]').val(city);
            } 

            if (address[x].types == 'postal_code') {
                zipcode = address[x].long_name;
                jQuery("#ggf-field-zipcode").val(zipcode);
                jQuery(".ggf-cf-zipcode input").val(zipcode);
                
                if ( !jQuery('#ggf-update-location').hasClass('update') ) 
                    jQuery('.ggf-field-zipcode input[type="text"]').val(zipcode);

            } 

            if (address[x].types == 'country,political') {
                country = address[x].short_name;
                country_long = address[x].long_name;
                jQuery("#ggf-field-country").val(country);
                jQuery("#ggf-field-country_long").val(country_long);
                
                jQuery(".ggf-cf-country input").val(country);
                jQuery(".ggf-cf-country_long input").val(country_long);
                
                jQuery('.ggf-field-country input[type="text"]').val(country);

             } 
        }
		
		if ( jQuery('#ggf-update-location').hasClass('update') || jQuery('#ggf-update-location').hasClass('autosubmit') ) {
			
			jQuery('#ggf-update-location').removeClass(function() {
				setTimeout(function() {
					jQuery('#gform_'+ggfSettings['id'] ).submit();	
				}, 800);
			},'update');
			
		}
	}

	//convert address to lat/lng
	jQuery('#gform_submit_button_'+ggfSettings['id'] ).click(function(e) {
		
		//check if address field need to be geocoded 
		if ( jQuery('#ggf-update-location').hasClass('update') || jQuery('#ggf-field-lat').val() == '' || jQuery('#ggf-field-lng').val() == '' ) {
			
			//we add the update class in order to later submit the form
			//we adding it here in case that we geocoded because no lat/lng exists
			jQuery('#ggf-update-location').addClass('update');
			
			e.preventDefault();		
			getLatLong();
		}
		
	});
	
	var geoAddress = [];
	
	/* convert address to lat/long */
	function getLatLong() {
		
		geoAddress = [];
		if ( ggfSettings.address_fields.use == 1 ) {
			geoAddress.push(jQuery('.ggf-full-address input[type="text"]').val());
		} else if ( ggfSettings.address_fields.use == 2 ) {
			geoAddress = [];
			
			jQuery.each(['street','city','state','zipcode','country'], function(index, value) {
				if ( jQuery('.ggf-field-'+value ).length ) {
					if ( jQuery.trim( jQuery('.ggf-field-'+value + ' input[type="text"]').val() ).length )  {
						geoAddress.push(jQuery('.ggf-field-'+value + ' input[type="text"]').val());
					}
				}
			});
		}

		if ( geoAddress == undefined || geoAddress == null || geoAddress.length == 0) {
     		jQuery('#gform_'+ggfSettings['id'] ).submit();	
     		return;
		}
				
    	geocoder = new google.maps.Geocoder();
   	 	geocoder.geocode( { 'address': geoAddress.join(' ')}, function(results, status) {
    
   	 		if (status == google.maps.GeocoderStatus.OK) {
        		
        		breakAddress(results[0]);
          		       						
    		} else {
    			alert( 'Geocode was not successful for the following reason: ' + status + '. Please check the address you entered.' );     
    			//jQuery('#gform_'+ggfSettings['id'] ).submit();	
    		}
    	});
	}
	  	
});