<?php
/**
 * GGF function - add options section to user registration setting page
 * @since 1.2
 */
function ggf_options_section($config, $form, $is_validation_error) {
	//get options
	$ggfSettings = $config['meta']['ggf_settings'];
	
	//address fields
	$address_fields = array(
			__('Street','GGF') 			  => 'street',
			__('Apt','GGF')    			  => 'apt',
			__('City','GGF')   			  => 'city',
			__('State','GGF')  			  =>  'state',
			__('State long name','GGF')   => 'state_long',
			__('Zipcode','GGF') 		  => 'zipcode',
			__('Country','GGF') 		  => 'country',
			__('Country long name','GGF') => 'country_long',
			__('Full Address','GGF')      => 'address',
			__('Formatted Address','GGF') => 'formatted_address',
			__('Latitude','GGF') 		  => 'lat',
			__('Longitude','GGF') 		  => 'lng'
	);

	$gmwbp_use = ( isset( $ggfSettings['address_fields']['gmwbp']['use']) && $ggfSettings['address_fields']['gmwbp']['use'] == 1 ) ? 'checked="checked"' : '';
	
	if ( function_exists('gmw_loaded') || class_exists( 'GEO_my_WP' ) ) {
		
		$addons = get_option( 'gmw_addons' );
		
		if ( !isset( $addons['friends'] ) ) {
			
			$message  = '<span style="color:#666;font-weight:normal">'. __( 'You must activate "Friends Locator" add-on in GEO my WP\'s', 'GGF'). ' <a href="' . get_bloginfo('wpurl') . '/wp-admin/admin.php?page=gmw-add-ons">'. __( '"Add-ons"', 'GGF' ). '</a> ' . __( 'page' ,'GGF' ) . '</a></span>';
			$disabled = 'disabled="disabled"';
			
		} else {
			
			$disabled = '';
			$message  = '';
			
		}
	} else {
		
		$gmw_on   = array();
		$disabled = 'disabled="disabled"';
		$message  = '<span style="color:#666;font-weight:normal"> requires <a href="http://geomywp.com" target="_blank">GEO my WP</a> plugin</span>';	
		
	}
	?>
    <div id="ggf_options" class="ggf_options">
    
        <h3><?php _e('GEO Fields Options','GGF'); ?></h3>
        <table>
        	<tbody>
		        <tr>
					<td colspan="2" class="gf_sub_settings_cell">
		                <div class="gf_animate_sub_settings">
		                    <table>
		                    	<tr>
		                    		<th style="border: 1px solid #eee;background: #f7f7f7;padding:8px 5px;"><?php _e('Address Field','GGF'); ?></th>
									<th style="border: 1px solid #eee;background: #f7f7f7;padding:8px 5px;"><?php _e('User Meta','GGF'); ?></th>
									<?php if ( class_exists('BuddyPress') ) { ?>
										<th style="border: 1px solid #eee;background: #f7f7f7;padding:8px 5px;"><?php _e('Xprofile fields','GGF'); ?></th>
									<?php } ?>
								</tr>
		                   		<tbody>
									<?php foreach ( $address_fields as $name => $value ) { ?>
										<tr id="ggf_address_field_address" class="child_setting_row" style="">
			            					<th style="text-transform:capitalize;text-align: left;font-weight: normal;min-width: 170px;"><?php echo $name; ?></th>
			            					<td>
			            						<input type="text" id="ggf_user_meta_address_field_'.$value.'" name="ggf_settings[address_fields][user_meta_fields][<?php echo $value; ?>]" size="25px" class="ggf_user_meta_address_field" value="<?php if ( isset($ggfSettings['address_fields']['user_meta_fields'][$value]) ) { echo $ggfSettings['address_fields']['user_meta_fields'][$value]; } ?>">
			            					</td>
										<?php if ( class_exists('BuddyPress') ) {	?>					
			            					<td>
			            						<select name="ggf_settings[address_fields][bp_fields][<?php echo $value; ?>]">
													<option value="0"><?php _e('N/A','GGF'); ?></option>
													<?php foreach ( GFUser::get_buddypress_fields() as $field) { ?>
														<option value="<?php echo $field['value']; ?>" <?php  if ( isset( $ggfSettings['address_fields']['bp_fields'] ) && $field['value'] == $ggfSettings['address_fields']['bp_fields'][$value] ) echo ' selected="selected"'; ?>><?php echo $field['name']; ?></option>
													<?php } ?>
			            						</select>
			            					</td>
										<?php } ?>
			        					</tr>
									<?php } ?>            			    
		                    	</tbody>
		                    </table>
		                </div>
		            </td>
		        </tr>
     		</tbody>
   		</table> 
    </div>
    <div id="gmw_options" class="gmw_options">
    
        <h3><?php _e('GEO my WP options','GGF'); ?></h3>
        <div id="gmw_settings_use" class="margin_vertical_10" style="">
        	<label class="left_header">
        		<?php _e("Save members location with GEO my WP","GGF")?> <a href="#" onclick="return false;" class="gf_tooltip tooltip" title="<?php _e('Saving member location to make it searchable with GEO my WP.','GGF'); ?>">(?)</a>
        	</label>
        	<input type="hidden" value="0" name="ggf_settings[address_fields][gmwbp][use]" />
            <input type="checkbox" id="gmw_gf_on" value="1" <?php echo $disabled; ?> name="ggf_settings[address_fields][gmwbp][use]" <?php echo $gmwbp_use; ?> />
        	<label for="gmw_settings_use" class="checkbox-label"><?php echo $message; ?></label>
        </div>
    </div>
    <?php
}
add_action("gform_user_registration_add_option_section", "ggf_options_section", 10, 3);

//save options
function ggf_save_options($config) {
	
	$config['meta']['ggf_settings']  = RGForms::post("ggf_settings");

	return $config;
}
add_filter("gform_user_registration_save_config", "ggf_save_options");

?>