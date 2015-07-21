<?php
class GGF_Functions {

	/**
	 * __constructor
	 */
	function __construct() {

		add_filter( 'gform_field_input' , array( $this, 'map_field') , 23, 5 );
		add_filter( 'gform_pre_render', array( $this, 'render_form' ) );
		add_action( 'gform_post_submission', array( $this, 'update_location' ), 5, 2 );
		add_action( 'gform_user_registered', array( $this, 'add_user_location' ), 10, 4 );
		add_action( 'gform_user_updated', array( $this, 'add_user_location' ), 10, 4 );
		add_filter( 'gform_form_tag', array( $this, 'form_tag'), 2, 10 );

		//if GEO my WP exists
		if ( function_exists('gmw_loaded') || class_exists('GEO_my_WP') )
			add_action( 'ggf_after_location_updated', array( $this, 'update_gmw_posts' ), 10, 5 );

	}

	/**
	 * Execute function on form load
	 * @param unknown_type $form
	 * @return unknown|string
	 */
	function render_form( $form ) {

		$this->form               = $form;
		$this->ggf_settings 	  = $form['ggf_settings'];
		$this->ggf_settings['id'] = $this->form['id'];
		
		//no need message when using autolocator on page load
		$this->ggf_settings['fields']['pageLoad']['locator_found_message'] = 0;
		$this->ggf_settings['auto_locator']['use'] = ( isset( $this->ggf_settings['auto_locator']['use'] ) ) ? $this->ggf_settings['auto_locator']['use'] : 0;

		if ( !isset( $this->ggf_settings['address_fields']['use'] ) || $this->ggf_settings['address_fields']['use'] == 0 ) return $this->form;
			
		//add classes to location fields
		foreach ( $this->form['fields'] as $key => $field ) {
			
			//auto locator features
			if ( $field['type'] == 'ggfLocator' ) {

				$this->form['fields'][$key]['label']   = '';
				
				//these settings will pass to javascript
				$this->ggf_settings['locator_hide_submit'] = ( isset( $field['ggf-locator-hide-submit'] ) && $field['ggf-locator-hide-submit'] == 1 ) ? 1 : 0;
				
				$this->ggf_settings['fields'][$field['id']]['locator_autosubmit']    = ( isset( $field['ggf-locator-autosubmit'] ) && $field['ggf-locator-autosubmit'] == 1 ) ? 1 : 0;
				$this->ggf_settings['fields'][$field['id']]['locator_found_message'] = ( isset( $field['ggf-locator-found-message'] ) && $field['ggf-locator-found-message'] == 1 ) ? 1 : 0;
					
			}

			$this->ggf_settings['fields'][$field['id']]['autocomplete'] = ( isset( $field['ggf-autocomplete']) && $field['ggf-autocomplete'] == 1 ) ? 1 : 0;
			$this->ggf_settings['fields'][$field['id']]['restrictions'] = ( isset( $field['ggf_autocomplete_country'] ) && !empty( $field['ggf_autocomplete_country'] ) ) ? $field['ggf_autocomplete_country'] : false;
			$this->ggf_settings['fields'][$field['id']]['locator_fill'] = ( isset( $field['ggf-locator-fill'] ) && !empty( $field['ggf-locator-fill'] ) ) ? 1 : 0;
			$this->ggf_settings['fields'][$field['id']]['update_map']   = ( isset( $field['ggf-update-map'] ) && !empty( $field['ggf-update-map'] ) ) ? 1 : 0;
			
			$ac  = ( isset( $field['ggf-autocomplete']) && $field['ggf-autocomplete'] == 1 ) ? ' ggf-autocomplete' : '';
			//enablelocator autofill
			$dlf = ( isset( $field['ggf-locator-fill'] ) && !empty( $field['ggf-locator-fill'] ) ) ? 'locator-fill' : '';
			//enablelocator autofill
			$maf = ( isset( $field['ggf-map-autofill'] ) && !empty( $field['ggf-map-autofill'] ) ) ? 'map-autofill' : '';
			//update map
			$um  = ( isset( $field['ggf-update-map'] ) && !empty( $field['ggf-update-map'] ) ) ? 'autocomplete-update-map' : '';
			
			$this->form['fields'][$key]['cssClass'] .=  ' '.$ac . ' ' . $dlf . ' ' . $um .' '. $maf;
			
			if ( isset( $field['ggf_fields'] ) ) {
								
				//full address field
				if ( $field['ggf_fields'] == 'address' ) $this->form['fields'][$key]['cssClass'] .= ' ggf-field  ggf-full-address ';

				if ( $field['ggf_fields'] == 'street' ) $this->form['fields'][$key]['cssClass']  .= ' ggf-field ggf-field-street ';

				if ( $field['ggf_fields'] == 'apt' ) $this->form['fields'][$key]['cssClass']     .= ' ggf-field ggf-field-apt ';

				if ( $field['ggf_fields'] == 'city' ) $this->form['fields'][$key]['cssClass']    .= ' ggf-field ggf-field-city ';
					
				if ( $field['ggf_fields'] == 'state' ) $this->form['fields'][$key]['cssClass']   .= ' ggf-field ggf-field-state ';
					
				if ( $field['ggf_fields'] == 'zipcode' ) $this->form['fields'][$key]['cssClass'] .= ' ggf-field ggf-field-zipcode ';

				if ( $field['ggf_fields'] == 'country' ) $this->form['fields'][$key]['cssClass'] .= ' ggf-field ggf-field-country ';
			}
			
		}
				
		if ( !wp_script_is( 'google-maps', 'enqueue' ) ) wp_enqueue_script( 'google-maps' );
		wp_enqueue_script( 'ggf-js');
		wp_enqueue_script( 'jquery-ui-autocomplete');
		wp_enqueue_style( 'ggf-style');
		wp_localize_script('ggf-js', 'ggfSettings', $this->ggf_settings );
		
		return $this->form;
	}

	/**
	 * add hidden location fields to form
	 * @param unknown_type $input
	 * @param unknown_type $this->form
	 * @return string
	 */
	function form_tag( $input, $form ) {

		if ( !isset( $this->ggf_settings['address_fields']['use'] ) || $this->ggf_settings['address_fields']['use'] == 0 ) return $input;

		$address_fields = array( 'street', 'city', 'state', 'state_long', 'zipcode', 'country', 'country_long', 'formatted_address', 'lat', 'lng' );

		$custom_fields = ( isset( $this->ggf_settings['address_fields']['fields'] ) && !empty( $this->ggf_settings['address_fields']['fields'] ) ) ? $this->ggf_settings['address_fields']['fields'] : array();

		$input .= '<div id="ggf-text-fields-wrapper">';

		if ( ( isset( $_POST['ggf_field_location'] ) && !empty( $_POST['ggf_field_location'] ) ) || !isset( $_GET['gform_post_id'] ) ) {

			foreach ( $address_fields as $field ) {

				$post_field = ( isset( $_POST['ggf_field_location'][$field] ) && !empty( $_POST['ggf_field_location'][$field] ) ) ? $_POST['ggf_field_location'][$field] : '';

				$input .= '<input type="hidden" id="ggf-field-'.$field.'" name="ggf_field_location['.$field.']" value="'.$post_field.'" />';

			}

		} else {

			foreach ( $address_fields as $field ) {

				$value = get_post_meta( $_GET['gform_post_id'], $custom_fields[$field], true );

				$post_field = ( isset( $value ) && !empty( $value ) ) ? $value : '';
					
				$input .= '<input type="hidden" id="ggf-field-'.$field.'" name="ggf_field_location['.$field.']" value="'.$post_field.'" />';
					
			}

		}
		
		$post_field = ( isset( $_POST['ggf_field_location']['autolocator'] ) && !empty( $_POST['ggf_field_location']['autolocator'] ) ) ? $_POST['ggf_field_location']['autolocator'] : '';
		
		$input .= '<input type="hidden" id="ggf-autolocator" name="ggf_field_location[autolocator]" value="'.$post_field.'" />';
		$input .= '<input type="hidden" id="ggf-update-location" />';
		$input .= '</div>';

		return $input;
	}

	/**
	 * Display map
	 */
	function map_field( $input, $field, $value, $lead_id, $form_id ){
			
		if ( $field["type"] == "ggfMap" ) {

			if ( !IS_ADMIN ) :

				$map_type   = ( isset( $field['ggf_map_type' ] ) && !empty( $field['ggf_map_type' ] ) ) ? $field['ggf_map_type' ] : 'ROADMAP';
				$zoom_level = ( isset( $field['ggf_zoom_level'] ) && !empty( $field['ggf_zoom_level' ] ) ) ? $field['ggf_zoom_level' ] : '12';
				// set map's size
				$map_width = ( isset( $field['ggf-map-width'] ) && !empty( $field['ggf-map-width'] ) ) ? $field['ggf-map-width'] : "300px";
				$map_height = ( isset( $field['ggf-map-height'] ) && !empty( $field['ggf-map-height'] ) ) ? $field['ggf-map-height'] : "300px";
	
	
				if ( isset( $_POST['ggf_field_location']['lat'] ) && !empty( $_POST['ggf_field_location']['lat'] ) ) {
					$latitude = $_POST['ggf_field_location']['lat'];
				} elseif ( isset( $field['ggf-map-latitude'] ) && !empty( $field['ggf-map-latitude'] ) ) {
					$latitude = $field['ggf-map-latitude'];
				} else {
					$latitude = '40.7827096';
				}
	
				if ( isset( $_POST['ggf_field_location']['lng'] ) && !empty( $_POST['ggf_field_location']['lng'] ) ) {
					$longitude = $_POST['ggf_field_location']['lng'];
				} elseif ( isset( $field['ggf-map-longitude'] ) && !empty( $field['ggf-map-longitude'] ) ) {
					$longitude = $field['ggf-map-longitude'];
				} else {
					$longitude = '-73.965309';
				}
	
				$mapArgs = array (
						'latitude'  => $latitude,
						'longitude' => $longitude,
						'map_type'  => $map_type,
						'zoom_level'=> $zoom_level
				);
	
				wp_localize_script( 'ggf-js', 'mapArgs', $mapArgs );
	
				$input = '
				<div id="ggf-map-wrapper" class="ggf-map-wrapper ggf-map-wrapper">
				<div id="ggf-map" class="ggf-map" style="height:'.$map_height.';width:'.$map_width.'"></div>
				</div><!-- map holder -->';

			endif;
		}

		if ( $field["type"] == "ggfLocator" && isset( $this->ggf_settings['address_fields']['use']) && $this->ggf_settings['address_fields']['use'] != 0 ) {

			if ( !IS_ADMIN ) :

			$field['ggf-locator-title'] = ( isset( $field['ggf-locator-title'] ) && !empty( $field['ggf-locator-title'] ) ) ? $field['ggf-locator-title'] : '';
			$input = '<input type="button" class="ggf-locator-button" value="'.$field['ggf-locator-title'].'" /><span class="ggf-locator-spinner-wrapper" style="display:none"><img class="ggf-locator-spinner" src="'.GGF_URL .'/assets/images/ajax-loader.gif'.'" />';

			endif;
		}

		return $input;
	}

	/**
	 * Update location when form submitted
	 */
	function update_location( $entry, $form ) {

		$ggfSettings = $form['ggf_settings'];

		if ( !isset( $ggfSettings['address_fields']['use'] ) || $ggfSettings['address_fields']['use'] == 0 ) return;
			
		$ggfLocation 	 = $_POST['ggf_field_location'];
		$postID 		 = $entry['post_id'];
		$org_location 	 = array();
		$additional_info = array(
				'phone'   => '',
				'fax' 	  => '',
				'email'   => '',
				'website' => ''
		);

		//when single address field
		if ( isset( $ggfSettings['address_fields']['use'] ) && $ggfSettings['address_fields']['use'] == 1 ) {

			//find the address field and get its value
			foreach ( $form['fields'] as $field ) {

				if ( isset( $field['ggf_fields'] ) && $field['ggf_fields'] == 'address' ) {
					$fAddress = $_POST['input_'.$field['id']];
				} else {
					$fAddress = $ggfLocation['formatted_address'];
				}

				if ( isset( $ggfSettings['address_fields']['gmw']['use'] ) && $ggfSettings['address_fields']['gmw']['use'] == 1 ) {

					if( isset($field['ggf_fields']) && $field['ggf_fields'] == 'phone' ) {
						$additional_info['phone'] =  $_POST['input_'.$field['id']];
					}
					if( isset($field['ggf_fields']) && $field['ggf_fields'] == 'fax' ) {
						$additional_info['fax'] =  $_POST['input_'.$field['id']];
					}
					if( isset($field['ggf_fields']) && $field['ggf_fields'] == 'email' ) {
						$additional_info['email'] =  $_POST['input_'.$field['id']];
					}
					if( isset($field['ggf_fields']) && $field['ggf_fields'] == 'website' ) {
						$additional_info['website'] =  $_POST['input_'.$field['id']];
					}
				}
			}

			//get location information into array
			$org_location['street']  = $ggfLocation['street'];
			$org_location['apt']  	 = ( isset( $ggfLocation['apt'] ) && !empty( $ggfLocation['apt'] ) ) ? $ggfLocation['apt'] : '';
			$org_location['city'] 	 = $ggfLocation['city'];
			$org_location['state']   = $ggfLocation['state'];
			$org_location['zipcode'] = $ggfLocation['zipcode'];
			$org_location['country'] = $ggfLocation['country'];

			$ggfLocation['address']  	 = $fAddress;
			$ggfLocation['org_location'] = $org_location;

			//when using multiple fields
		} else {

			//get fields value
			foreach ( $form['fields'] as $field ) {

				if( isset($field['ggf_fields']) && $field['ggf_fields'] == 'street' ) {
					$org_location['street'] =  $_POST['input_'.$field['id']];
				}
				if( isset($field['ggf_fields']) && $field['ggf_fields'] == 'apt' ) {
					$org_location['apt'] =  $_POST['input_'.$field['id']];
				}
				if( isset($field['ggf_fields']) && $field['ggf_fields'] == 'city' ) {
					$org_location['city'] =  $_POST['input_'.$field['id']];
				}
				if( isset($field['ggf_fields']) && $field['ggf_fields'] == 'state' ) {
					$org_location['state'] =  $_POST['input_'.$field['id']];
					//$updateFields['state'] =  $field['postCustomFieldName'];
				}
				if( isset($field['ggf_fields']) && $field['ggf_fields'] == 'zipcode' ) {
					$org_location['zipcode'] =  $_POST['input_'.$field['id']];
				}
				if( isset($field['ggf_fields']) && $field['ggf_fields'] == 'country' ) {
					$org_location['country'] =  $_POST['input_'.$field['id']];
					//$updateFields['country'] =  $field['postCustomFieldName'];
				}

				if ( isset( $ggfSettings['address_fields']['gmw']['use'] ) && $ggfSettings['address_fields']['gmw']['use'] == 1 ) {

					if( isset($field['ggf_fields']) && $field['ggf_fields'] == 'phone' )
						$additional_info['phone'] =  $_POST['input_'.$field['id']];

					if( isset($field['ggf_fields']) && $field['ggf_fields'] == 'fax' )
						$additional_info['fax'] =  $_POST['input_'.$field['id']];

					if( isset($field['ggf_fields']) && $field['ggf_fields'] == 'email' )
						$additional_info['email'] =  $_POST['input_'.$field['id']];

					if( isset($field['ggf_fields']) && $field['ggf_fields'] == 'website' )
						$additional_info['website'] =  $_POST['input_'.$field['id']];
				}
			}

			$ggfLocation['org_location'] = $org_location;
			$ggfLocation['address'] 	 = implode(' ', $org_location);
		}

		$custom_fields = ( isset( $ggfSettings['address_fields']['fields'] ) && !empty( $ggfSettings['address_fields']['fields'] ) ) ? $ggfSettings['address_fields']['fields'] : array();

		//save location to custom fields
		foreach ( $custom_fields as $key => $value ) {

			if ( $key == 'street' ) {
				if ( isset( $ggfLocation['org_location']['street'] ) && !empty( $ggfLocation['org_location']['street'] ) ) {
					update_post_meta($postID, $value, $ggfLocation['org_location']['street'] );
				} else {
					update_post_meta($postID, $value, $ggfLocation['street'] );
					$ggfLocation['org_location']['street'] = $ggfLocation['street'];
				}
			} elseif ( $key == 'apt' ) {
				if ( isset( $ggfLocation['org_location']['apt'] ) && !empty( $ggfLocation['org_location']['apt'] ) ) {
					update_post_meta( $postID, $value, $ggfLocation['org_location']['apt'] );
				}
			} elseif ( $key == 'city' ) {
				if ( isset( $ggfLocation['org_location']['city'] ) && !empty( $ggfLocation['org_location']['city'] ) ) {
					update_post_meta( $postID, $value, $ggfLocation['org_location']['city'] );
				} else {
					update_post_meta($postID, $value, $ggfLocation['city'] );
					$ggfLocation['org_location']['city'] = $ggfLocation['city'];
				}
			} elseif ( $key == 'zipcode' ) {
				if ( isset( $ggfLocation['org_location']['zipcode'] ) && !empty( $ggfLocation['org_location']['zipcode'] ) ) {
					update_post_meta( $postID, $value, $ggfLocation['org_location']['zipcode'] );
				} else {
					update_post_meta($postID, $value, $ggfLocation['zipcode'] );
					$ggfLocation['org_location']['zipcode'] = $ggfLocation['zipcode'];
				}
			} else {
				if ( isset( $ggfLocation[$key] ) && !empty( $ggfLocation[$key] ) ) update_post_meta( $postID, $value, $ggfLocation[$key] );
			}

		}
		do_action('ggf_after_location_updated', $postID, $entry, $form, $ggfLocation, $additional_info);

	}

	//updated GEO my WP database
	function update_gmw_posts( $postID, $entry, $form, $ggfLocation, $additional_info ) {

		$ggfSettings = $form['ggf_settings'];
		if ( !isset( $ggfSettings['address_fields']['gmw']['use'] ) || $ggfSettings['address_fields']['gmw']['use'] == 0 ) return;
			
		$ggfLocation['org_location']['apt'] = ( isset( $ggfLocation['org_location']['apt'] ) ) ? $ggfLocation['org_location']['apt'] : false;
		//Save information to database
		global $wpdb;
		$wpdb->replace( $wpdb->prefix . 'places_locator',
				array(
						'post_id'			=> $postID,
						'feature'  			=> 0,
						'post_type' 		=> get_post_type($postID),
						'post_title' 		=> get_the_title($postID),
						'post_status'		=> 'publish',
						'street' 			=> $ggfLocation['org_location']['street'],
						'apt' 				=> $ggfLocation['org_location']['apt'],
						'city' 				=> $ggfLocation['org_location']['city'],
						'state' 			=> $ggfLocation['state'],
						'state_long' 		=> $ggfLocation['state_long'],
						'zipcode' 			=> $ggfLocation['zipcode'],
						'country' 			=> $ggfLocation['country'],
						'country_long' 		=> $ggfLocation['country_long'],
						'address' 			=> $ggfLocation['address'],
						'formatted_address' => $ggfLocation['formatted_address'],
						'phone' 			=> $additional_info['phone'],
						'fax' 				=> $additional_info['fax'],
						'email' 			=> $additional_info['email'],
						'website' 			=> $additional_info['website'],
						'lat' 				=> $ggfLocation['lat'],
						'long' 				=> $ggfLocation['lng'],
						'map_icon'  		=> '_default.png',
				)
		);
	}

	/**
	 * Update users location using "User registration" gravity forms add-on
	 */
	function add_user_location( $user_id, $config, $entry, $user_pass ) {

		//get form details
		$form = RGFormsModel::get_form_meta_by_id($config['form_id']);
		$form = $form[0];

		//get options
		$ggfSettings = $form['ggf_settings'];

		//get GEO user registration fields
		$ggfURSettings = $config['meta']['ggf_settings'];
		
		if ( !isset( $ggfSettings['address_fields']['use'] ) || $ggfSettings['address_fields']['use'] == 0 ) return;

		//when single address field
		if ( isset( $ggfSettings['address_fields']['use'] ) && $ggfSettings['address_fields']['use'] == 1 ) {

			//find the address field and get its value
			foreach ( $form['fields'] as $field )
				if ( isset( $field['ggf_fields'] ) && $field['ggf_fields'] == 'address' ) $address = $entry[$field['id']];

			//cehck if fields avaialbe with form posting. This means that using is being activated automatically
			//on form subbmittion. Otherwise, if activated manually we need to get the address fields
			//from the entry, geocode it and do the rest
			if ( isset( $_POST['ggf_field_location'] ) && !empty( $_POST['ggf_field_location'] ) ) {
				$ggfLocation = $_POST['ggf_field_location'];
			} else {
				include_once( GGF_PATH .'/includes/ggf-geocoder.php' );
				$ggfLocation = ggf_geocoder( $address, false );
			}
			
			//get location information into array
			$org_location['street']  = $ggfLocation['street'];
			$org_location['apt']  	 = ( isset( $ggfLocation['apt'] ) && !empty( $ggfLocation['apt'] ) ) ? $ggfLocation['apt'] : '';
			$org_location['city'] 	 = $ggfLocation['city'];
			$org_location['state']   = $ggfLocation['state'];
			$org_location['zipcode'] = $ggfLocation['zipcode'];
			$org_location['country'] = $ggfLocation['country'];

			$ggfLocation['org_location'] = $org_location;
			$ggfLocation['address']  	 = $address;

		} else {

			foreach ( $form['fields'] as $field ) {
					
				if ( isset( $field['ggf_fields'] ) && $field['ggf_fields'] == 'street' ) {
					$org_location['street'] =  $entry[$field['id']];
				}
				if ( isset( $field['ggf_fields'] ) && $field['ggf_fields'] == 'apt' ) {
					$org_location['apt'] =  $entry[$field['id']];
				}
				if ( isset( $field['ggf_fields'] ) && $field['ggf_fields'] == 'city' ) {
					$org_location['city'] =  $entry[$field['id']];
				}
				if ( isset( $field['ggf_fields'] ) && $field['ggf_fields'] == 'state' ) {
					$org_location['state'] =  $entry[$field['id']];
				}
				if ( isset( $field['ggf_fields'] ) && $field['ggf_fields'] == 'zipcode' ) {
					$org_location['zipcode'] =  $entry[$field['id']];
				}
				if ( isset( $field['ggf_fields'] ) && $field['ggf_fields'] == 'country' ) {
					$org_location['country'] =  $entry[$field['id']];
				}
					
			}
			
			//cehck if fields avaialbe with form posting. This means that using is being activated automatically
			//on form subbmittion. Otherwise, if activated manually we need to get the address fields
			//from the entry, geocode it and do the rest
			if ( isset( $_POST['ggf_field_location'] ) && !empty( $_POST['ggf_field_location'] ) ) {
				$ggfLocation = $_POST['ggf_field_location'];
			} else {
				include_once( GGF_PATH .'/includes/ggf-geocoder.php' );
				$ggfLocation = ggf_geocoder( implode( ' ', $org_location ), false );
			}
			
			$ggfLocation['org_location'] = $org_location;
			$ggfLocation['address'] 	 = implode(' ', $org_location);
		}

		$user_meta_fields = ( isset( $ggfURSettings['address_fields']['user_meta_fields'] ) && !empty( $ggfURSettings['address_fields']['user_meta_fields'] ) ) ? $ggfURSettings['address_fields']['user_meta_fields'] : array();

		//save location to user meta
		foreach ( $user_meta_fields as $key => $value ) {

			if ( $key == 'street' ) {
				if ( isset( $ggfLocation['org_location']['street'] ) && !empty( $ggfLocation['org_location']['street']) ) {
					update_user_meta( $user_id, $value, $ggfLocation['org_location']['street'] );
				} else {
					update_user_meta( $user_id, $value, $ggfLocation['street'] );
				}
			} elseif ( $key == 'apt' ) {
				if ( isset( $ggfLocation['org_location']['apt'] ) && !empty( $ggfLocation['org_location']['apt'] ) ) {
					update_user_meta($user_id, $value, $ggfLocation['org_location']['apt'] );
				}
			} elseif ( $key == 'city' ) {
				if ( isset( $ggfLocation['org_location']['city'] ) && !empty($ggfLocation['org_location']['city'] ) ) {
					update_user_meta($user_id, $value, $ggfLocation['org_location']['city'] );
				} else {
					update_user_meta($user_id, $value, $ggfLocation['city'] );
				}
			} elseif ( $key == 'zipcode' ) {
				if ( isset($ggfLocation['org_location']['zipcode'] ) && !empty($ggfLocation['org_location']['zipcode'] ) ) {
					update_user_meta( $user_id, $value, $ggfLocation['org_location']['zipcode'] );
				} else {
					update_user_meta( $user_id, $value, $ggfLocation['zipcode'] );
				}
			} else {
				if ( isset($ggfLocation[$key]) && !empty($ggfLocation[$key]) ) update_user_meta($user_id, $value, $ggfLocation[$key] );
			}

		}

		//check if buddypress activated
		if ( class_exists('BuddyPress') ) {

			//get the xprofile fiields
			$user_xprofile_fields = ( isset( $ggfURSettings['address_fields']['bp_fields'] ) && !empty( $ggfURSettings['address_fields']['bp_fields'] ) ) ? $ggfURSettings['address_fields']['bp_fields'] : array();

			//save location to xprofile fields
			foreach ( $user_xprofile_fields as $key => $value ) {

				if ( $key == 'street' ) {
					if ( isset( $ggfLocation['org_location']['street'] ) && !empty( $ggfLocation['org_location']['street'] ) )
						xprofile_set_field_data( $value, $user_id, $ggfLocation['org_location']['street'] );
					else
						xprofile_set_field_data( $value, $user_id, $ggfLocation['street'] );
				} elseif ( $key == 'apt' ) {
					if ( isset($ggfLocation['org_location']['apt']) && !empty($ggfLocation['org_location']['apt']) )
						xprofile_set_field_data($value, $user_id, $ggfLocation['org_location']['apt'] );
				} elseif ( $key == 'city' ) {
					if ( isset($ggfLocation['org_location']['city']) && !empty($ggfLocation['org_location']['city']) )
						xprofile_set_field_data($value, $user_id, $ggfLocation['org_location']['city'] );
					else
						xprofile_set_field_data($value, $user_id, $ggfLocation['city'] );
				} elseif ( $key == 'zipcode' ) {
					if ( isset($ggfLocation['org_location']['zipcode']) && !empty($ggfLocation['org_location']['zipcode']) )
						xprofile_set_field_data($value, $user_id, $ggfLocation['org_location']['zipcode'] );
					else
						xprofile_set_field_data($value, $user_id, $ggfLocation['zipcode'] );
				} else {
					if ( isset($ggfLocation[$key]) && !empty($ggfLocation[$key]) ) xprofile_set_field_data($value, $user_id, $ggfLocation[$key] );
				}
			}
		}

		//check if GEO my WP activated and we need to save locaiton to member
		if ( isset( $ggfURSettings['address_fields']['gmwbp']['use'] ) && $ggfURSettings['address_fields']['gmwbp']['use'] == 1 && ( class_exists( 'GEO_my_WP' ) || function_exists('gmw_loaded') ) ) {

			$map_icon = ( isset($_POST['map_icon']) ) ? $_POST['map_icon'] : '_default.png';

			//save location into GEO my WP members table in database
			if ( isset( $ggfLocation['lat'] ) && !empty( $ggfLocation['lat'] ) ) {

				global $wpdb;

				$wpdb->replace('wppl_friends_locator', array(
						'member_id'			=> $user_id,
						'street'			=> $ggfLocation['org_location']['street'],
						'apt'				=> $ggfLocation['org_location']['apt'],
						'city' 				=> $ggfLocation['org_location']['city'],
						'state' 			=> $ggfLocation['state'],
						'state_long' 		=> $ggfLocation['state_long'],
						'zipcode'			=> $ggfLocation['zipcode'],
						'country' 			=> $ggfLocation['country'],
						'country_long'	 	=> $ggfLocation['country_long'],
						'address'			=> $ggfLocation['address'],
						'formatted_address' => $ggfLocation['formatted_address'],
						'lat'				=> $ggfLocation['lat'],
						'long'				=> $ggfLocation['lng'],
						'map_icon'			=> $map_icon
				));
			}
		}
		//hook and do something with the information
		do_action( 'ggf_after_member_location_saved', $user_id, $config, $entry, $user_pass, $ggfLocation );
	}

}
new GGF_Functions;
?>