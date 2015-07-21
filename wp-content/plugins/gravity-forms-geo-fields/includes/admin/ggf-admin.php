<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * GGF_Admin class
 */
class GGF_Admin {

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {
		
		$this->addons = get_option( 'gmw_addons' ); 
		add_filter( 'gform_form_settings', array( $this , 'gravity_settings_page' ), 50, 2 );
		add_filter( 'gform_pre_form_settings_save', array( $this, 'update_gravity_forms_update_post_settings' ) );
	
		//check if we are in edit form page
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'gf_edit_forms' && isset( $_GET['id'] ) && !empty( $_GET['id'] ) )
			include( 'ggf-admin-editor-page.php' );
		
		//if user registration add-on installed
		if ( class_exists( 'GFUser' ) ) 
			include( 'ggf-admin-user-registration.php' );
				
	}
			
	public function gravity_settings_page( $settings, $form ) {
		
		//get settings
		$ggfSettings = rgar( $form, 'ggf_settings' );
	
		if ( !isset($ggfSettings) || empty($ggfSettings) ) $ggfSettings = array();
		
		//address fields
		$address_fields = array( 
				__('Street','GGF') 			  => 'street', 
				__('Apt','GGF')    			  => 'apt', 
				__('City','GGF')   			  => 'city', 
				__('State','GGF')  			  => 'state',
				__('State long name','GGF')   => 'state_long',
				__('Zipcode','GGF') 		  => 'zipcode',
				__('Country','GGF') 		  => 'country',
				__('Country long name','GGF') => 'country_long',
				__('Full Address','GGF')      => 'address',
				__('Formatted Address','GGF') => 'formatted_address',
				__('Latitude','GGF') 		  => 'lat',
				__('Longitude','GGF') 		  => 'lng'
			);
		
		$ggfUsageSingle   = ( isset( $ggfSettings['address_fields']['use'] ) && $ggfSettings['address_fields']['use'] == 1 ) ? 'checked="checked"' : '';
		$ggfUsageMultiple = ( isset( $ggfSettings['address_fields']['use'] ) && $ggfSettings['address_fields']['use'] == 2 ) ? 'checked="checked"' : '';
		
		if ( !class_exists('GEO_my_WP') ) {
			$disabled = 'disabled="disabled"';
			$message  = '<span style="color:#666;font-weight:normal"> '.__( 'requires','GGF' ).' <a href="http://geomywp.com" target="_blank">GEO my WP</a> '.__( 'plugin', 'GGf' ).'</span>';
			$gmwOn 	  = 0;
		} else {
			$gmwOn 	  = 1;
			$disabled = '';
			$message  = '';
		}
		if ( !class_exists('GFUpdatePost') ) {
			$UpDisabled = 'disabled="disabled"';
			$UpMessage  = '<span style="color:#666;font-weight:normal"> '.__( 'requires','GGF' ).' <a href="http://wordpress.org/plugins/gravity-forms-update-post/" target="_blank">'.__( 'Gravity Forms - Update Post','GGF' ).'</a> '.__('plugin','GGf').'</span>';
		} else {
			$UpDisabled = '';
			$UpMessage  = '';
		}
			
		$uPFields 	 = ( isset( $ggfSettings['address_fields']['update_post']['use'] ) && $ggfSettings['address_fields']['update_post']['use'] == 1 ) ? 'checked="checked"' : '';
		$uPGwp 	 	 = ( isset( $ggfSettings['address_fields']['update_post']['use'] ) && $ggfSettings['address_fields']['update_post']['use'] == 2 ) ? 'checked="checked"' : '';
		$uPAutocheck = ( isset( $ggfSettings['address_fields']['update_post']['autocheck'] ) && $ggfSettings['address_fields']['update_post']['autocheck'] == 1 ) ? 'checked="checked"' : '';
		$gmw_use 	 = ( isset( $ggfSettings['address_fields']['gmw']['use'] ) &&  $ggfSettings['address_fields']['gmw']['use'] == 1 ) ? 'checked="checked"' : '';
		$autoLocator = ( isset( $ggfSettings['auto_locator']['use'] ) && $ggfSettings['auto_locator']['use'] == 1 ) ? 'checked="checked"' : '';
		
		//display settings on page
		$settings['GEO Fields Options']['addressFieldsUse'] = '
			<tr>
				<th>'.__('GEO Fields Usage','GGF').' <a href="#" onclick="return false;" class="gf_tooltip tooltip ggf_tooltip ggf_address_field)usage_tooltip" title="'.__('How would you like to use the GEO address fields in the form.','GGF').'"><i class="fa fa-question-circle"></i></a></th>
				<td>
					<input type="radio" class="ggf_usage-toggle ggf_usage_toggle_none" name="ggf_settings[address_fields][use]" value="0" name="ggf_method" checked="checked" />
					<label for="na" class="inline">'.__('Do not use','GGF').'</label>
					<input type="radio" class="ggf_usage-toggle ggf_usage_toggle_single" name="ggf_settings[address_fields][use]" value="1" name="ggf_method" ' . $ggfUsageSingle . '  />
					<label for="single-address-field" class="inline">'.__('Single address field','GGF').'</label>
					<input type="radio" class="ggf_usage-toggle ggf_usage_toggle_multiple" name="ggf_settings[address_fields][use]" value="2" name="ggf_method" ' . $ggfUsageMultiple . ' />
					<label for="multiple-address-fields" class="inline">'.__('Multiple address fields','GGF').'</label>
				</td>
			</tr>';
		
		//display settings on page
		$settings['GEO Fields Options']['autoLocator'] = '
			<tr>
				<th>'.__('Auto locator on form load','GGF').' <a href="#" onclick="return false;" class="gf_tooltip tooltip ggf_tooltip ggf_address_field)usage_tooltip" title="'.__('Get the user\'s current location on initial load of the form.','GGF').'"><i class="fa fa-question-circle"></i></a></th>
            	<td>
            		<input type="hidden" value="0" name="ggf_settings[auto_locator][use]" />
                	<input type="checkbox" id="gmw_gf_on" value="1" name="ggf_settings[auto_locator][use]" ' . $autoLocator . '>
            	</td>
			</tr>';
		
		$settings['GEO Fields Options']['addressFieldsValues'] = '
			<tr class="ggf-toggle-additional-fields-warpper">
				<th style="font-weight: bold">'.__('Posts custom fields','GGF').' <a href="#" onclick="return false;" class="gf_tooltip tooltip tooltip_form_button_text" title="'.__('Enter the custom fields where you would like to save each of the address fields.','GGF').'"><i class="fa fa-question-circle"></i></a></th>
			</tr>
			<tr class="ggf-toggle-additional-fields-warpper">
				<td colspan="2" class="gf_sub_settings_cell">
	                <div class="gf_animate_sub_settings">
	                    <table>
	                   		<tbody>';
	                   			foreach ( $address_fields as $name => $value ) {
									$settings['GEO Fields Options']['addressFieldsValues'] .= '
									<tr id="ggf_address_field_address" class="child_setting_row" style="">
		            					<th style="text-transform:capitalize;">'.$name.'</th>
		            					<td>
		            						<input type="text" id="ggf_cf_address_field_'.$value.'" name="ggf_settings[address_fields][fields]['.$value.']" size="25px" class="ggf_cf_address_field" value="'; if ( isset($ggfSettings['address_fields']['fields'][$value]) ) { $settings['GEO Fields Options']['addressFieldsValues'] .= $ggfSettings['address_fields']['fields'][$value]; } $settings['GEO Fields Options']['addressFieldsValues'] .= '">
		            					</td>
		        					</tr>';	
								}
	                   			      
	                    		$settings['GEO Fields Options']['addressFieldsValues'] .= '
	                   			         
	                    	</tbody>
	                    </table>
	                </div>
	            </td>
	        </tr>
	        <tr class="ggf-toggle-additional-fields-warpper">
				<th style="font-weight: bold">'.__( 'GEO my WP Options', 'GGF' ) .' <a href="#" onclick="return false;" class="gf_tooltip tooltip tooltip_form_button_text" title="'.__('Save location information so it will be searchable with GEO my WP.','GGF').'"><i class="fa fa-question-circle"></i></a></th>
				<td>'.$message.'</td>
			</tr>
	        <tr class="ggf-toggle-additional-fields-warpper">
				<td colspan="2" class="gf_sub_settings_cell">
	                <div class="gf_animate_sub_settings">
	                    <table>
	                   		<tbody>
	                   			<tr class="child_setting_row" style="">
	            					<th>
	                					'.__('Save posts location','GGF').'
	            					</th>
	            					<td>
	            						<input type="hidden" value="0" name="ggf_settings[address_fields][gmw][use]" />
	                					<input type="checkbox" id="gmw_gf_on" value="1" '.$disabled.' name="ggf_settings[address_fields][gmw][use]" ' . $gmw_use . '>
	            					</td>
	        					</tr>
	        				</body>
	                    </table>
	                </div>
	            </td>
	        </tr>
	        <tr class="ggf-toggle-additional-fields-warpper">
				<th style="font-weight: bold">'.__('Update Post Options', 'GGF' ) .' <a href="#" onclick="return false;" class="gf_tooltip tooltip tooltip_form_button_text" title="'.__('Gravity Forms - Update Post plugin options.','GGF').'"><i class="fa fa-question-circle"></i></a></th>
				<td>'.$UpMessage.'</td>
			</tr>
	        <tr class="ggf-toggle-additional-fields-warpper">
				<td colspan="2" class="gf_sub_settings_cell">
	                <div class="gf_animate_sub_settings">
	                    <table>
	                   		<tbody>
	                   			<tr class="child_setting_row" style="">
	            					<th>
	                					'.__('Update post custom fields ','GGF').' <a href="#" onclick="return false;" class="gf_tooltip tooltip tooltip_form_button_text" title="'.__('Fields must have custom fields set in order to be displayed in the form being updated. You can choose to automatically use the fields above otherwise you can add them manually.','GGF').'"><i class="fa fa-question-circle"></i></a>
	            					</th>
	            					<td>
	                					<input type="radio" id="ggf_update_post_na" value="0" '.$UpDisabled.' name="ggf_settings[address_fields][update_post][use]" checked="checked" />
	                					<label for="ggf_update_post_na" class="inline">'.__('Do not use - enter manually','GGF').'</label>
	                					<br />
	                					<br />
	                					<input type="radio" id="ggf_update_post_fields" value="1" '.$UpDisabled.' name="ggf_settings[address_fields][update_post][use]" '.$uPFields.' />
	                					<label for="ggf_update_post_fields" class="inline">'.__('Custom Fields chosen above','GGF').'</label>	                					
	            					</td>
	        					</tr>
	        					<tr class="child_setting_row" style="">
	            					<th>
	                					'.__('Autocheck "Unique Custom Field?" ','GGF').'<a href="#" onclick="return false;" class="gf_tooltip tooltip tooltip_form_button_text" title="'.__('Custom fields must be checked as Unique Custom Fields in order to display their values when updating a form. Checking this checkbox will automatically set all the address fields as Unique Custom Field. Otherwise you can do it manually.','GGF').'"><i class="fa fa-question-circle"></i></a>
	            					</th>
	            					<td>
	                					<input type="hidden" value="0" name="ggf_settings[address_fields][update_post][autocheck]" />
	                					<input type="checkbox" id="update_posts_autocheck" value="1" '.$UpDisabled.' name="ggf_settings[address_fields][update_post][autocheck]" ' . $uPAutocheck . '>
	            					</td>
	        					</tr>
	        				</body>
	                    </table>
	                </div>
	            </td>
	        </tr>';
	                    		
		?>
		<script>
			//toggle checkboxes
			 jQuery(document).ready(function($) {	
			 	if ( $('.ggf_usage_toggle_none').is(':checked') ) {
			 		$('.ggf-toggle-additional-fields-warpper').hide();
				} else {
					$('.ggf-toggle-additional-fields-warpper').show();
				}
				
				$('.ggf_usage-toggle').change(function() {
					if ($(this).val() == 0 ) {
						$('.ggf-toggle-additional-fields-warpper').hide();
					} else {
						$('.ggf-toggle-additional-fields-warpper').show();		
					}  		
				});
			 });
		</script>
		<?php 
	
		return $settings;
	}
	
	/**
	 * save custom fields and unique custom field checkbox used with Gravity forms update post plugin
	 */
	function update_gravity_forms_update_post_settings( $form ) {
	
		$form['ggf_settings'] = rgpost('ggf_settings');
	
		if ( !class_exists('GFUpdatePost') ) return $form;
	
		/*
		 * assign custom fields to address fields and check as "Unique custom field"
		 * when using Gravity Forms - Update post plugin
		 */
		foreach ( $form['fields'] as $key => $value ) {
	
			if ( isset($value['ggf_fields']) && $value['ggf_fields'] == 'address' ) {
				
				if ( $form['ggf_settings']['address_fields']['update_post']['autocheck'] == 1 ) {
					$form['fields'][$key]['postCustomFieldUnique'] = true;
				} else {
					$form['fields'][$key]['postCustomFieldUnique'] = false;
				}
				if ( $form['ggf_settings']['address_fields']['fields']['address'] != '' && $form['ggf_settings']['address_fields']['update_post']['use'] == 1 ) {
					$form['fields'][$key]['postCustomFieldName'] = $form['ggf_settings']['address_fields']['fields']['address'];
				}
				
			}
	
			if ( isset($value['ggf_fields']) && $value['ggf_fields'] == 'street' ) {
				
				if ( $form['ggf_settings']['address_fields']['update_post']['autocheck'] == 1 ) {
					$form['fields'][$key]['postCustomFieldUnique'] = true;
				} else {
					$form['fields'][$key]['postCustomFieldUnique'] = false;
				}
				if ( $form['ggf_settings']['address_fields']['fields']['street'] != '' && $form['ggf_settings']['address_fields']['update_post']['use'] == 1 ) {
					echo $form['ggf_settings']['address_fields']['fields']['street'];
					$form['fields'][$key]['postCustomFieldName'] = $form['ggf_settings']['address_fields']['fields']['street'];
				}
				
			}
			
			if ( isset($value['ggf_fields']) && $value['ggf_fields'] == 'apt' ) {
				
				if ( $form['ggf_settings']['address_fields']['update_post']['autocheck'] == 1 ) {
					$form['fields'][$key]['postCustomFieldUnique'] = true;
				} else {
					$form['fields'][$key]['postCustomFieldUnique'] = false;
				}
				if ( $form['ggf_settings']['address_fields']['fields']['apt'] != '' && $form['ggf_settings']['address_fields']['update_post']['use'] == 1 ) {
					$form['fields'][$key]['postCustomFieldName'] = $form['ggf_settings']['address_fields']['fields']['apt'];
				}
			}
			if ( isset($value['ggf_fields']) && $value['ggf_fields'] == 'city' ) {
				if ( $form['ggf_settings']['address_fields']['update_post']['autocheck'] == 1 ) {
					$form['fields'][$key]['postCustomFieldUnique'] = true;
				} else {
					$form['fields'][$key]['postCustomFieldUnique'] = false;
				}
				if ( $form['ggf_settings']['address_fields']['fields']['city'] != '' && $form['ggf_settings']['address_fields']['update_post']['use'] == 1 ) {
					$form['fields'][$key]['postCustomFieldName'] = $form['ggf_settings']['address_fields']['fields']['city'];
				}
			}
			if ( isset($value['ggf_fields']) && $value['ggf_fields'] == 'state' ) {
				if ( $form['ggf_settings']['address_fields']['update_post']['autocheck'] == 1 ) {
					$form['fields'][$key]['postCustomFieldUnique'] = true;
				} else {
					$form['fields'][$key]['postCustomFieldUnique'] = false;
				}
				if ( $form['ggf_settings']['address_fields']['fields']['state'] != '' && $form['ggf_settings']['address_fields']['update_post']['use'] == 1 ) {
					$form['fields'][$key]['postCustomFieldName'] = $form['ggf_settings']['address_fields']['fields']['state'];
				}
			}
			if ( isset($value['ggf_fields']) && $value['ggf_fields'] == 'zipcode' ) {
				if ( $form['ggf_settings']['address_fields']['update_post']['autocheck'] == 1 ) {
					$form['fields'][$key]['postCustomFieldUnique'] = true;
				} else {
					$form['fields'][$key]['postCustomFieldUnique'] = false;
				}
				if ( $form['ggf_settings']['address_fields']['fields']['zipcode'] != '' && $form['ggf_settings']['address_fields']['update_post']['use'] == 1 ) {
					$form['fields'][$key]['postCustomFieldName'] = $form['ggf_settings']['address_fields']['fields']['zipcode'];
				}
			}
			if ( isset($value['ggf_fields']) && $value['ggf_fields'] == 'country' ) {
				if ( $form['ggf_settings']['address_fields']['update_post']['autocheck'] == 1 ) {
					$form['fields'][$key]['postCustomFieldUnique'] = true;
				} else {
					$form['fields'][$key]['postCustomFieldUnique'] = false;
				}
				if ( $form['ggf_settings']['address_fields']['fields']['country'] != '' && $form['ggf_settings']['address_fields']['update_post']['use'] == 1 ) {
					$form['fields'][$key]['postCustomFieldName'] = $form['ggf_settings']['address_fields']['fields']['country'];
				}
			}
		}
	
		return $form;
	}
}
new GGF_Admin;