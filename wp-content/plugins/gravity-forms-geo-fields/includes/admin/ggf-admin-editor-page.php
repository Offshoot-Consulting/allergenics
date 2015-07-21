<?php

/**
 * GGF_Admin_Edit_Form_Page
 */
class GGF_Admin_Edit_Form_Page {

	/**
	 * __construct function.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		//get form
		$this->form 		= RGFormsModel::get_form_meta_by_id( $_GET['id'] );
		$this->ggf_settings = ( isset( $this->form[0]['ggf_settings'] ) ) ? $this->form[0]['ggf_settings'] : '';

		if ( !isset( $this->ggf_settings['address_fields']['use'] ) || $this->ggf_settings['address_fields']['use'] == 0 ) return;

		add_filter( 'gform_add_field_buttons', array( $this, 'field_groups' ), 10, 1 );
		add_filter( 'gform_field_type_title' , array( $this, 'fields_title' ), 10, 1 );
		add_action( 'gform_field_standard_settings' , array( $this, 'fields_settings' ), 10, 2 );
		add_filter( 'gform_tooltips', array( $this, 'tooltips' ) );
		add_action( "gform_editor_js", array( $this, 'js_editor' ) );

		add_action( 'gform_admin_pre_render', array( $this, 'render_form' ) );

	}

	/**
	 * Add GGF group buttons
	 */
	function field_groups( $field_groups ) {

		//add ggf fields button
		$ggf_fields[] = array( "class"=>"button ggf-text-field-button", "style" => "font-size:12px;", "value" => __( "Text Address Field", "GGF" ), "onclick" => "StartAddField('text');" );
		
		//add ggf fields button
		$ggf_fields[] = array( "class"=>"button ggf-fields-button", "style" => "font-size:12px;", "value" => __( "Post Address Field", "GGF" ), "onclick" => "StartAddField('post_custom_field');" );
		
		//only if single address field add the map button
		$ggf_fields[] = array("class"=>"button ggf-map-button", "value" => __( "Map", "GGF" ), "onclick" => "StartAddField('ggfMap');");

		//create ggf locator icon button
		$ggf_fields[] = array( 'class' => 'button ggf-locator-button', 'value' => __( "Auto-Locator", "GGF" ), 'onclick' => "StartAddField('ggfLocator');");

		//create ggf address field button
		$field_groups[] = array( "name" => "ggf_fields", "label"=> __( "GGF Fields" , "GGF"), "fields" => apply_filters('ggf_field_buttons', $ggf_fields, $field_groups));

		return $field_groups;
	}

	/**
	 * Change title name for fields
	 */
	function fields_title( $type ) {

		if ( $type == 'mapIcons' )
			return __( 'GGF map icons' , 'GGF' );
		if ( $type == 'ggfMap' )
			return __( 'GGF Map' , 'GGF' );
		if ( $type == 'ggfLocator' )
			return __( 'GGF Locator' , 'GGF' );
	}

	/**
	 * GGF function - Add ggf fields to the input fields
	 */
	function fields_settings( $position, $form_id ) {

		if ( $position == 50 ) {

			?>
			<!-- Locator button fields -->
			
			<li class="ggf-locator-title field_setting ggf-locator-settings">
				<label for="ggf-locator-title"> 
					<?php _e( "Button Title", "GGF"); ?> <?php gform_tooltip("ggf_locator_title_tt"); ?>
				</label> 
				<input type="text" id="field-ggf-locator-title" class=""
				onkeyup="SetFieldProperty('ggf-locator-title', this.value);">
			</li>
			
			<li class="ggf-locator-auto-submit field_setting ggf-locator-settings">
				<input type="checkbox" id="field-ggf-locator-autosubmit"
				onclick="SetFieldProperty('ggf-locator-autosubmit', this.checked);" />
				<label for="ggf-locator-auto-submit" class="inline"> 
					<?php _e( "Auto-submit Form", "GGF" ); ?><?php gform_tooltip("ggf_locator_autosubmit_tt"); ?>
				</label>
			</li>
			
			<li class="ggf-locator-hide-submit field_setting ggf-locator-settings">
				<input type="checkbox" id="field-ggf-locator-hide-submit"
				onclick="SetFieldProperty('ggf-locator-hide-submit', this.checked);" />
				<label for="ggf-locator-hide-submit" class="inline"> 
					<?php _e( "Hide form's submit button", "GGF" ); ?><?php gform_tooltip("ggf_locator_hide_submit_tt"); ?>
				</label>
			</li>
			
			<li class="ggf-locator-found-message field_setting ggf-locator-settings">
				<input type="checkbox" id="field-ggf-locator-found-message"
				onclick="SetFieldProperty('ggf-locator-found-message', this.checked);" />
				<label for="ggf-locator-found-message" class="inline"> 
					<?php _e( "Display pop-up message when location found?", "GGF" ); ?><?php gform_tooltip("ggf_locator_found_message_tt"); ?>
				</label>
			</li>
			
			<!--  Map fields -->
			
			<li class="ggf-map-width field_setting ggf-map-settings ">
				<label for="ggf-map-width"> <?php _e( "Map Width", "GGF" ); ?> <?php gform_tooltip("ggf_width_tt"); ?></label> 
				<input type="text" id="field-ggf-map-width" class="" size="15"
				onkeyup="SetFieldProperty('ggf-map-width', this.value);">
			</li>
			
			<li class="ggf-map-height field_setting ggf-map-settings ">
				<label for="ggf-map-height"> 
					<?php _e( "Map Height", "GGF" ); ?> <?php gform_tooltip("ggf_height_tt"); ?>
				</label> 
				<input type="text" id="field-ggf-map-height" class="" size="15"
				onkeyup="SetFieldProperty('ggf-map-height', this.value);">
			</li>
			
			<li class="ggf-map-latitude field_setting ggf-map-settings ">
				<label for="ggf-map-latitude"> 
					<?php _e( "Latitude", "GGF" ); ?> <?php gform_tooltip("ggf_lat_tt"); ?>
				</label> 
				<input type="text" id="field-ggf-map-latitude" class=""
				size="25" onkeyup="SetFieldProperty('ggf-map-latitude', this.value);">
			</li>
			
			<li class="ggf-map-longitude field_setting ggf-map-settings ">
				<label for="ggf-map-longitude"> 
					<?php _e( "longitude", "GGF" ); ?> <?php gform_tooltip("ggf_long_tt"); ?>
				</label> 
				<input type="text" id="field-ggf-map-longitude" class=""
				size="25" onkeyup="SetFieldProperty('ggf-map-longitude', this.value);">
			</li>
			
			<li class="ggf-map-type field_setting ggf-map-settings ">
				<label for="ggf-map-type">
					<?php _e( "Map Type", "GGF" ); ?> <?php gform_tooltip("ggf_map_type_tt"); ?>
				</label> 
				<select name="ggf_map_type" id="field-ggf-map-type"
					onchange="SetFieldProperty('ggf_map_type', jQuery(this).val());">
						<option value="ROADMAP"><?php _e( 'ROADMAP','GGF' ); ?></option>
						<option value="SATELLITE"><?php _e( 'SATELLITE','GGF' ); ?></option>
						<option value="HYBRID"><?php _e( 'HYBRID','GGF' ); ?></option>
						<option value="TERRAIN"><?php _e( 'TERRAIN','GGF' ); ?></option>
				</select>
			</li>
			
			<li class="ggf-zoom-level field_setting ggf-map-settings ">
				<label for="ggf-zoom-level"> 
					<?php _e( "Zoom Level", "GGF" ); ?> <?php gform_tooltip("ggf_zoom_level_tt"); ?>
				</label> 
				<select name="ggf_zoom_level" id="field-ggf-zoom-level"
					onchange="SetFieldProperty('ggf_zoom_level', jQuery(this).val());">
						<?php $count = 18; ?>
						<?php
						for ( $x=1; $x<=18; $x++ ) {
							echo '<option value="'.$x.'">'. $x .'</option>';
						}
						?>
				</select>
			</li>
			
			<!-- Text field and custom post fields -->
			
			<li class="post_custom_field_type_setting field_setting ggf-text-field-settings" style="display: list-item;">
				<label for="post_custom_field_type"> <?php _e( 'GEO Location Fields','GGF' ); ?>
					<?php gform_tooltip("ggf_address_fields_tt"); ?>
				</label> 
				<select name="ggf_fields" id="ggf-additional-fields"
					class="ggf-address-fields"
					onchange="SetFieldProperty('ggf_fields', jQuery(this).val());">
						<option value=""><?php _e('N/A','GGF'); ?></option>
						<?php if ( $this->ggf_settings['address_fields']['use'] == 1 ) : ?>
						<option value="address"><?php _e('Full Address','GGF'); ?></option>
						<?php elseif ( $this->ggf_settings['address_fields']['use'] == 2 ) : ?>
						<option value="street"><?php _e('Street','GGF'); ?></option>
						<option value="apt"><?php _e('Apt','GGF'); ?></option>
						<option value="city"><?php _e('City','GGF'); ?></option>
						<option value="state"><?php _e('State','GGF'); ?></option>
						<option value="zipcode"><?php _e('Zipcode','GGF'); ?></option>
						<option value="country"><?php _e('Country','GGF'); ?></option>
						<?php endif; ?>
						<?php if ( class_exists('GEO_my_WP') && isset( $this->ggf_settings['address_fields']['gmw']['use'] ) && $this->ggf_settings['address_fields']['gmw']['use'] == 1 ) : ?>
						<option value="phone"><?php _e('Phone Number','GGF'); ?></option>
						<option value="fax"><?php _e('Fax number','GGF'); ?></option>
						<option value="email"><?php _e('Email Address','GGF'); ?></option>
						<option value="website"><?php _e('Website','GGF'); ?></option>
						<?php endif; ?>
				</select>
			</li>
			
			<li class="post_custom_field_type_setting field_setting ggf-text-field-settings" style="display: list-item;">
				<input type="checkbox"
					id="field-ggf-locator-fill"
					onclick="SetFieldProperty( 'ggf-locator-fill', this.checked );" /> 
					<label
					for="field_address_hide_state_<?php echo $key; ?>" class="inline"> <?php _e("Locator Auto-fill", "GGF"); ?>
					<?php gform_tooltip("ggf_locator_fill_tt"); ?>
				</label>
			</li>
			
			<li class="post_custom_field_type_setting field_setting ggf-text-field-settings" style="display: list-item;">
				<input type="checkbox"
					id="field-ggf-map-autofill"
					onclick="SetFieldProperty( 'ggf-map-autofill', this.checked );" /> 
				<label
					for="field_ggf_map_autofill_<?php echo $key; ?>" class="inline">
					<?php _e("Map Auto-fill", "GGF"); ?> <?php gform_tooltip("ggf_map_autofill_tt"); ?>
				</label>
			</li>
			
			<li class="post_custom_field_type_setting field_setting ggf-text-field-settings" style="display: list-item;">
				<input type="checkbox"
					id="field-ggf-autocomplete"
					onclick="SetFieldProperty( 'ggf-autocomplete', this.checked );" />
				<label
					for="field_address_hide_state_<?php echo $key; ?>" class="inline"> <?php _e("Autocomplete", "GGF"); ?>
					<?php gform_tooltip("ggf_autocomplete_tt"); ?>
				</label>
			</li>
			
			<li class="post_custom_field_type_setting field_setting ggf-text-field-settings" style="display: list-item;">
				<input type="checkbox"
					id="field-ggf-update-map"
					onclick="SetFieldProperty( 'ggf-update-map', this.checked );" /> 
				<label
					for="field_address_hide_update_map_<?php echo $key; ?>" class="inline">
					<?php _e("Update Map Using This Field", "GGF"); ?> <?php gform_tooltip("ggf_update_map_tt"); ?>
				</label>
			</li>
			
			<li class="post_custom_field_type_setting field_setting ggf-autocomplete-country-wrapper ggf-text-field-settings" style="display: list-item;">
				<label for="post_custom_field_type"> <?php _e( 'Restrict Autocomplete Results','GGF' ); ?>
					<?php gform_tooltip("ggf_autocomplete_country_tt"); ?>
				</label> 
				<select name="ggf_autocomplete_country" id="ggf-autocomplete-country"
					class="ggf-autocomplete-country"
					onchange="SetFieldProperty('ggf_autocomplete_country', jQuery(this).val());">
					<option value="">All Countries</option>
					<option value="AF">Afghanistan</option>
					<option value="AX">Aland Islands</option>
					<option value="AL">Albania</option>
					<option value="DZ">Algeria</option>
					<option value="AS">American Samoa</option>
					<option value="AD">Andorra</option>
					<option value="AO">Angola</option>
					<option value="AI">Anguilla</option>
					<option value="AQ">Antarctica</option>
					<option value="AG">Antigua and Barbuda</option>
					<option value="AR">Argentina</option>
					<option value="AM">Armenia</option>
					<option value="AW">Aruba</option>
					<option value="AU">Australia</option>
					<option value="AT">Austria</option>
					<option value="AZ">Azerbaijan</option>
					<option value="BS">Bahamas</option>
					<option value="BH">Bahrain</option>
					<option value="BD">Bangladesh</option>
					<option value="BB">Barbados</option>
					<option value="BY">Belarus</option>
					<option value="BE">Belgium</option>
					<option value="BZ">Belize</option>
					<option value="BJ">Benin</option>
					<option value="BM">Bermuda</option>
					<option value="BT">Bhutan</option>
					<option value="BO">Bolivia, Plurinational State of</option>
					<option value="BQ">Bonaire, Sint Eustatius and Saba</option>
					<option value="BA">Bosnia and Herzegovina</option>
					<option value="BW">Botswana</option>
					<option value="BV">Bouvet Island</option>
					<option value="BR">Brazil</option>
					<option value="IO">British Indian Ocean Territory</option>
					<option value="BN">Brunei Darussalam</option>
					<option value="BG">Bulgaria</option>
					<option value="BF">Burkina Faso</option>
					<option value="BI">Burundi</option>
					<option value="KH">Cambodia</option>
					<option value="CM">Cameroon</option>
					<option value="CA">Canada</option>
					<option value="CV">Cape Verde</option>
					<option value="KY">Cayman Islands</option>
					<option value="CF">Central African Republic</option>
					<option value="TD">Chad</option>
					<option value="CL">Chile</option>
					<option value="CN">China</option>
					<option value="CX">Christmas Island</option>
					<option value="CC">Cocos (Keeling) Islands</option>
					<option value="CO">Colombia</option>
					<option value="KM">Comoros</option>
					<option value="CG">Congo</option>
					<option value="CD">Congo, the Democratic Republic of the</option>
					<option value="CK">Cook Islands</option>
					<option value="CR">Costa Rica</option>
					<option value="CI">Cote d'Ivoire</option>
					<option value="HR">Croatia</option>
					<option value="CU">Cuba</option>
					<option value="CW">Curacao</option>
					<option value="CY">Cyprus</option>
					<option value="CZ">Czech Republic</option>
					<option value="DK">Denmark</option>
					<option value="DJ">Djibouti</option>
					<option value="DM">Dominica</option>
					<option value="DO">Dominican Republic</option>
					<option value="EC">Ecuador</option>
					<option value="EG">Egypt</option>
					<option value="SV">El Salvador</option>
					<option value="GQ">Equatorial Guinea</option>
					<option value="ER">Eritrea</option>
					<option value="EE">Estonia</option>
					<option value="ET">Ethiopia</option>
					<option value="FK">Falkland Islands (Malvinas)</option>
					<option value="FO">Faroe Islands</option>
					<option value="FJ">Fiji</option>
					<option value="FI">Finland</option>
					<option value="FR">France</option>
					<option value="GF">French Guiana</option>
					<option value="PF">French Polynesia</option>
					<option value="TF">French Southern Territories</option>
					<option value="GA">Gabon</option>
					<option value="GM">Gambia</option>
					<option value="GE">Georgia</option>
					<option value="DE">Germany</option>
					<option value="GH">Ghana</option>
					<option value="GI">Gibraltar</option>
					<option value="GR">Greece</option>
					<option value="GL">Greenland</option>
					<option value="GD">Grenada</option>
					<option value="GP">Guadeloupe</option>
					<option value="GU">Guam</option>
					<option value="GT">Guatemala</option>
					<option value="GG">Guernsey</option>
					<option value="GN">Guinea</option>
					<option value="GW">Guinea-Bissau</option>
					<option value="GY">Guyana</option>
					<option value="HT">Haiti</option>
					<option value="HM">Heard Island and McDonald Islands</option>
					<option value="VA">Holy See (Vatican City State)</option>
					<option value="HN">Honduras</option>
					<option value="HK">Hong Kong</option>
					<option value="HU">Hungary</option>
					<option value="IS">Iceland</option>
					<option value="IN">India</option>
					<option value="ID">Indonesia</option>
					<option value="IR">Iran, Islamic Republic of</option>
					<option value="IQ">Iraq</option>
					<option value="IE">Ireland</option>
					<option value="IM">Isle of Man</option>
					<option value="IL">Israel</option>
					<option value="IT">Italy</option>
					<option value="JM">Jamaica</option>
					<option value="JP">Japan</option>
					<option value="JE">Jersey</option>
					<option value="JO">Jordan</option>
					<option value="KZ">Kazakhstan</option>
					<option value="KE">Kenya</option>
					<option value="KI">Kiribati</option>
					<option value="KP">Korea, Democratic People's Republic of</option>
					<option value="KR">Korea, Republic of</option>
					<option value="KW">Kuwait</option>
					<option value="KG">Kyrgyzstan</option>
					<option value="LA">Lao People's Democratic Republic</option>
					<option value="LV">Latvia</option>
					<option value="LB">Lebanon</option>
					<option value="LS">Lesotho</option>
					<option value="LR">Liberia</option>
					<option value="LY">Libya</option>
					<option value="LI">Liechtenstein</option>
					<option value="LT">Lithuania</option>
					<option value="LU">Luxembourg</option>
					<option value="MO">Macao</option>
					<option value="MK">Macedonia, the former Yugoslav Republic of</option>
					<option value="MG">Madagascar</option>
					<option value="MW">Malawi</option>
					<option value="MY">Malaysia</option>
					<option value="MV">Maldives</option>
					<option value="ML">Mali</option>
					<option value="MT">Malta</option>
					<option value="MH">Marshall Islands</option>
					<option value="MQ">Martinique</option>
					<option value="MR">Mauritania</option>
					<option value="MU">Mauritius</option>
					<option value="YT">Mayotte</option>
					<option value="MX">Mexico</option>
					<option value="FM">Micronesia, Federated States of</option>
					<option value="MD">Moldova, Republic of</option>
					<option value="MC">Monaco</option>
					<option value="MN">Mongolia</option>
					<option value="ME">Montenegro</option>
					<option value="MS">Montserrat</option>
					<option value="MA">Morocco</option>
					<option value="MZ">Mozambique</option>
					<option value="MM">Myanmar</option>
					<option value="NA">Namibia</option>
					<option value="NR">Nauru</option>
					<option value="NP">Nepal</option>
					<option value="NL">Netherlands</option>
					<option value="NC">New Caledonia</option>
					<option value="NZ">New Zealand</option>
					<option value="NI">Nicaragua</option>
					<option value="NE">Niger</option>
					<option value="NG">Nigeria</option>
					<option value="NU">Niue</option>
					<option value="NF">Norfolk Island</option>
					<option value="MP">Northern Mariana Islands</option>
					<option value="NO">Norway</option>
					<option value="OM">Oman</option>
					<option value="PK">Pakistan</option>
					<option value="PW">Palau</option>
					<option value="PS">Palestinian Territory, Occupied</option>
					<option value="PA">Panama</option>
					<option value="PG">Papua New Guinea</option>
					<option value="PY">Paraguay</option>
					<option value="PE">Peru</option>
					<option value="PH">Philippines</option>
					<option value="PN">Pitcairn</option>
					<option value="PL">Poland</option>
					<option value="PT">Portugal</option>
					<option value="PR">Puerto Rico</option>
					<option value="QA">Qatar</option>
					<option value="RE">Reunion</option>
					<option value="RO">Romania</option>
					<option value="RU">Russian Federation</option>
					<option value="RW">Rwanda</option>
					<option value="BL">Saint Barthelemy</option>
					<option value="SH">Saint Helena, Ascension and Tristan da Cunha</option>
					<option value="KN">Saint Kitts and Nevis</option>
					<option value="LC">Saint Lucia</option>
					<option value="MF">Saint Martin (French part)</option>
					<option value="PM">Saint Pierre and Miquelon</option>
					<option value="VC">Saint Vincent and the Grenadines</option>
					<option value="WS">Samoa</option>
					<option value="SM">San Marino</option>
					<option value="ST">Sao Tome and Principe</option>
					<option value="SA">Saudi Arabia</option>
					<option value="SN">Senegal</option>
					<option value="RS">Serbia</option>
					<option value="SC">Seychelles</option>
					<option value="SL">Sierra Leone</option>
					<option value="SG">Singapore</option>
					<option value="SX">Sint Maarten (Dutch part)</option>
					<option value="SK">Slovakia</option>
					<option value="SI">Slovenia</option>
					<option value="SB">Solomon Islands</option>
					<option value="SO">Somalia</option>
					<option value="ZA">South Africa</option>
					<option value="GS">South Georgia and the South Sandwich Islands</option>
					<option value="SS">South Sudan</option>
					<option value="ES">Spain</option>
					<option value="LK">Sri Lanka</option>
					<option value="SD">Sudan</option>
					<option value="SR">Suriname</option>
					<option value="SJ">Svalbard and Jan Mayen</option>
					<option value="SZ">Swaziland</option>
					<option value="SE">Sweden</option>
					<option value="CH">Switzerland</option>
					<option value="SY">Syrian Arab Republic</option>
					<option value="TW">Taiwan, Province of China</option>
					<option value="TJ">Tajikistan</option>
					<option value="TZ">Tanzania, United Republic of</option>
					<option value="TH">Thailand</option>
					<option value="TL">Timor-Leste</option>
					<option value="TG">Togo</option>
					<option value="TK">Tokelau</option>
					<option value="TO">Tonga</option>
					<option value="TT">Trinidad and Tobago</option>
					<option value="TN">Tunisia</option>
					<option value="TR">Turkey</option>
					<option value="TM">Turkmenistan</option>
					<option value="TC">Turks and Caicos Islands</option>
					<option value="TV">Tuvalu</option>
					<option value="UG">Uganda</option>
					<option value="UA">Ukraine</option>
					<option value="AE">United Arab Emirates</option>
					<option value="GB">United Kingdom</option>
					<option value="US">United States</option>
					<option value="UM">United States Minor Outlying Islands</option>
					<option value="UY">Uruguay</option>
					<option value="UZ">Uzbekistan</option>
					<option value="VU">Vanuatu</option>
					<option value="VE">Venezuela, Bolivarian Republic of</option>
					<option value="VN">Viet Nam</option>
					<option value="VG">Virgin Islands, British</option>
					<option value="VI">Virgin Islands, U.S.</option>
					<option value="WF">Wallis and Futuna</option>
					<option value="EH">Western Sahara</option>
					<option value="YE">Yemen</option>
					<option value="ZM">Zambia</option>
					<option value="ZW">Zimbabwe</option>
				</select>
			</li>
		<?php
		}//get form details
	}

	/**
	 * GGF buttons tooltips
	 */
	function tooltips($tooltips){

		$tooltips["ggf_update_map_tt"]          = __("<h6>Update Map From Field</h6>Update Marker's location on the map when autocomplete is triggered on this field.","GGF");
		$tooltips["ggf_map_autofill_tt"]    	= __("<h6>Map Auto-fill</h6>Update this field with address when map is being updated.","GGF");
		$tooltips["ggf_address_fields_tt"]      = __("<h6>Address Fields</h6>Select the type of field from the available location fields. The address field that you choose here will be the one to be geocoded","GGF");
		$tooltips["ggf_width_tt"]               = __("<h6>Map Width</h6>Enter the map width in pixels or percentage.","GGF");
		$tooltips["ggf_height_tt"] 				= __("<h6>Map Height</h6>Enter the map height in pixels or percentage.","GGF");
		$tooltips["ggf_lat_tt"]    				= __("<h6>Latitude</h6>Enter the latitude of the initial point that will be displayed on the map.","GGF");
		$tooltips["ggf_long_tt"]   				= __("<h6>Longitude</h6>Enter the longitude of the initial point that will be displayed on the map.","GGF");
		$tooltips["ggf_autocomplete_tt"] 		= __("<h6>Google's Autocomplete</h6>Add Google address autocomplete to this field.","GGF");
		$tooltips["ggf_locator_fill_tt"] 		= __("<h6>Disable locator autofill</h6>Disable locator button adddress autofill on this field.","GGF");
		$tooltips["ggf_locator_title_tt"]       = __("<h6>Button Title</h6>Give a title to the locator button.","GGF");
		$tooltips["ggf_map_type_tt"]            = __("<h6>Map Type</h6>Select the map type.","GGF");
		$tooltips["ggf_zoom_level_tt"]          = __("<h6>Zoom Level</h6>Select the zoom level of the map.","GGF");
		$tooltips["ggf_locator_autosubmit_tt"]  = __("<h6>Auto-submit Form</h6>Submit form automatically after location found.","GGF");
		$tooltips["ggf_locator_hide_submit_tt"] = __("<h6>Hide form's submit button</h6>This can be useful when using the locator button to auto-submit the form after location found.</h6>","GGF");
		$tooltips["ggf_locator_found_message_tt"] = __('<h6>Display pop-up message when location found?</h6>Display an alert saying "location found" when location found','GGF');
		$tooltips["ggf_autocomplete_country_tt"] = __('<h6>Restrict Autocomplete results</h6>Restrict the autocomplete results to a certain country by choosing one of the countries from the drop-down menu.','GGF');
		
		return $tooltips;
	}

	/**
	 * execute some javascript technicalitites for the field to load correctly
	 */
	function js_editor(){
		?>
	
		<script type='text/javascript'>
		
			jQuery(document).ready(function($) {
                                
			//	$('#ggf-additional-fields').change(function() {
				//	if ( jQuery(this).val() == 'address' ) { 
					//	jQuery('#field-ggf-locator-fill').closest('li').show();
						//jQuery('#field-ggf-locator-fill').removeAttr('disabled');
			//		} else { 
			//			jQuery('#field-ggf-locator-fill').closest('li').hide();
			//			jQuery('#field-ggf-locator-fill').attr('disabled','disabled');
			//		}
			//	});
                                
                $('#field-ggf-autocomplete').change(function() {
					if ( jQuery(this).is(":checked") ) { 
						jQuery('#field-ggf-update-map').closest('li').show();
						jQuery('#field-ggf-update-map').removeAttr('disabled');
						jQuery('.ggf-autocomplete-country-wrapper').show();
					} else { 
						jQuery('#field-ggf-update-map').closest('li').hide();
						jQuery('#field-ggf-update-map').attr('disabled','disabled');
						jQuery('.ggf-autocomplete-country-wrapper').hide();
					}
				});

               
                fieldSettings["text"]	 	+= ", .ggf-text-field-settings";
                fieldSettings["hidden"]	 	+= ", .css_class_setting";
				fieldSettings["mapIcons"] 	= ".label_setting, .description_setting, .admin_label_setting, .size_setting, .default_value_textarea_setting, .error_message_setting, .css_class_setting, .visibility_setting"; 
				fieldSettings["ggfMap"]   	= ".ggf-map-settings , .label_setting, .description_setting, .admin_label_setting, .size_setting, .default_value_textarea_setting, .error_message_setting, .css_class_setting, .visibility_setting";
				fieldSettings["ggfLocator"] = ".ggf-locator-settings, .description_setting, .css_class_setting";
                                                        
				jQuery(document).bind("gform_load_field_settings", function(event, field, form){
                                                                    
                    jQuery("#field-ggf-autocomplete").attr("checked", field["ggf-autocomplete"] == true);
                    jQuery("#field-ggf-update-map").attr("checked", field["ggf-update-map"] == true);
                    jQuery("#field-ggf-map-autofill").attr("checked", field["ggf-map-autofill"] == true);
                    jQuery("#field-ggf-locator-fill").attr("checked", field["ggf-locator-fill"] == true);
                    jQuery("#field-ggf-locator-autosubmit").attr("checked", field["ggf-locator-autosubmit"] == true);
                    jQuery("#field-ggf-locator-hide-submit").attr("checked", field["ggf-locator-hide-submit"] == true);
                    jQuery("#field-ggf-locator-found-message").attr("checked", field["ggf-locator-found-message"] == true);

					jQuery("#field-ggf-map-width").val(field["ggf-map-width"]);
					jQuery("#field-ggf-map-height").val(field["ggf-map-height"]);
					jQuery("#field-ggf-map-latitude").val(field["ggf-map-latitude"]);
					jQuery("#field-ggf-map-longitude").val(field["ggf-map-longitude"]);
                    jQuery("#field-ggf-map-type").val(field["ggf_map_type"]);
                    jQuery("#field-ggf-zoom-level").val(field["ggf_zoom_level"]);
					jQuery("#field-ggf-locator-title").val(field["ggf-locator-title"]);
					jQuery("#ggf-additional-fields").val(field["ggf_fields"]);
					jQuery("#ggf-autocomplete-country").val(field["ggf_autocomplete_country"]);

					//if ( jQuery("#ggf-additional-fields").val() == 'address' ) {
					//	jQuery('#field-ggf-locator-fill').closest('li').show();
					//	jQuery('#field-ggf-locator-fill').removeAttr('disabled');
					//} else {
					//	jQuery('#field-ggf-locator-fill').closest('li').hide();
					//	jQuery('#field-ggf-locator-fill').attr('disabled','disabled');
					//}
                                        
                    if ( $('#field-ggf-autocomplete').is(":checked") ) { 
						jQuery('#field-ggf-update-map').closest('li').show();
						jQuery('#field-ggf-update-map').removeAttr('disabled');
						jQuery('.ggf-autocomplete-country-wrapper').show();
					} else { 
						jQuery('#field-ggf-update-map').closest('li').hide();
						jQuery('#field-ggf-update-map').attr('disabled','disabled');
						jQuery('.ggf-autocomplete-country-wrapper').hide();
					}
					
				});
			});
		</script>
	<?php 
	}
	
	function render_form( $form ) {
		
		if ( !class_exists('GFUpdatePost') ) return $form;
		
		?>
		<script type="text/javascript">
		
			gform.addFilter("gform_pre_form_editor_save", "ggf_save_form");
		
			function ggf_save_form(form){
	
				var customFields = form.ggf_settings['address_fields']['fields'];
				var i;
				
				for ( i = 0; i < form.fields.length; i++ ) { 
					var field = form.fields[i];
						
					if ( field.ggf_fields == 'address' ) {
						if ( form.ggf_settings['address_fields']['update_post']['autocheck'] == 1 ) form.fields[i].postCustomFieldUnique = true;
						if ( customFields['address'] != '' ) {
							if ( form.ggf_settings['address_fields']['update_post']['use'] == 1 ) form.fields[i].postCustomFieldName = customFields['address'];
						}
					}
					
					if ( field.ggf_fields == 'street' ) {
						if ( form.ggf_settings['address_fields']['update_post']['autocheck'] == 1 ) form.fields[i].postCustomFieldUnique = true;
						if ( customFields['street'] != '' ) {
							if ( form.ggf_settings['address_fields']['update_post']['use'] == 1 ) form.fields[i].postCustomFieldName = customFields['street'];
						}
					} 
					if ( field.ggf_fields == 'apt' ) {
						if ( form.ggf_settings['address_fields']['update_post']['autocheck'] == 1 ) form.fields[i].postCustomFieldUnique = true;
						if ( customFields['apt'] != '' ) {
							if ( form.ggf_settings['address_fields']['update_post']['use'] == 1 ) form.fields[i].postCustomFieldName = customFields['apt'];
						}
					} 
					if ( field.ggf_fields == 'city' ) {
						if ( form.ggf_settings['address_fields']['update_post']['autocheck'] == 1 ) form.fields[i].postCustomFieldUnique = true;
						if ( customFields['city'] != '' ) {
							if ( form.ggf_settings['address_fields']['update_post']['use'] == 1 ) form.fields[i].postCustomFieldName = customFields['state'];
						}
					} 
					if ( field.ggf_fields == 'state' ) {
						if ( form.ggf_settings['address_fields']['update_post']['autocheck'] == 1 ) form.fields[i].postCustomFieldUnique = true;
						if ( customFields['state'] != '' ) {
							if ( form.ggf_settings['address_fields']['update_post']['use'] == 1 ) form.fields[i].postCustomFieldName = customFields['state'];
						}
					}
					if ( field.ggf_fields == 'zipcode' ) {
						if ( form.ggf_settings['address_fields']['update_post']['autocheck'] == 1 ) form.fields[i].postCustomFieldUnique = true;
						if ( customFields['zipcode'] != '' ) {
							if ( form.ggf_settings['address_fields']['update_post']['use'] == 1 ) form.fields[i].postCustomFieldName = customFields['zpicode'];
						}
					} 
					if ( field.ggf_fields == 'country' ) {
						if ( form.ggf_settings['address_fields']['update_post']['autocheck'] == 1 ) form.fields[i].postCustomFieldUnique = true;
						if ( customFields['country'] != '' ) {
							if ( form.ggf_settings['address_fields']['update_post']['use'] == 1 ) form.fields[i].postCustomFieldName = customFields['country'];
						}
					}
				}
						
				return form;
			}
		</script>
		<?php
		//return the form object from the php hook	
		return $form;
	}	
	
}
new GGF_Admin_Edit_Form_Page;