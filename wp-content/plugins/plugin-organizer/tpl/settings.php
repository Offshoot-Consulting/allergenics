<div id="wrap">
    <div class="po-setting-icon fa fa-cogs" id="icon-po-settings"> <br /> </div>

    <h2 class="po-setting-title">Settings</h2>
    <div style="clear: both;"></div>
	
	<div id="PO-tab-container">
		<div id="PO-tab-menu-container">
			<ul id="PO-tab-menu">
				<li id="PO-tab-1" class="active"><span class="PO-tab-content">General Settings</span></li>
				<li id="PO-tab-2"><span class="PO-tab-content">Admin CSS</span></li>
				<li id="PO-tab-3"><span class="PO-tab-content">Recreate Permalinks</span></li>
				<li id="PO-tab-4"><span class="PO-tab-content">Mobile User Agents</span></li>
				<li id="PO-tab-5"><span class="PO-tab-content">Manage MU plugin file</span></li>
			</ul>
			<div style="clear: both;"></div>
		</div>
		<div id="PO-tab-content">
			<div id="PO-tab-1-content" class="PO-tab-content active">

				<div id="PO-gen-settings-div">
					<h3>General Settings</h3>
					<div class="PO-loading-container">
						<div>
							<img src="<?php print $this->PO->urlPath . "/image/ajax-loader.gif"; ?>">
						</div>
					</div>
					<div class="inside">
						<div class="stuffbox">
							<?php $fuzzyUrlMatching = get_option("PO_fuzzy_url_matching"); ?>
							<div class="PO-settings-button-container">
								<input type="checkbox" id="PO-fuzzy-url-matching" name="PO_fuzzy_url_matching" class="hidden-checkbox" <?php print ($fuzzyUrlMatching === "1")? 'checked="checked"':""; ?>>
								<input type="button" id="PO-fuzzy-url-matching-button" class="toggle-button-<?php print ($fuzzyUrlMatching === "1")? "on":"off"; ?>" value="<?php print ($fuzzyUrlMatching === "1")? "On":"Off"; ?>"  onclick="PO_toggle_button('PO-fuzzy-url-matching', '', 0);" />
							</div>
							<div id="PO-fuzzy-url-help" class="PO-help">
							  <p>
								This gives any URL the ability to affect children of that URL.  This is not the same as wordpress children.  It is using the URL structure to determine children.  So <?php print home_url($path='/'); ?>page/ will affect <?php print home_url($path='/'); ?>page/child/ and <?php print home_url($path='/'); ?>page/child2/.
							  </p>
							</div>
							<h4>
							  <label for="PO_fuzzy_url_matching">Fuzzy URL matching</label>
							  <a href="#TB_inline?width=400&height=200&inlineId=PO-fuzzy-url-help" title="Fuzzy URL matching" class="thickbox">
								<span class="dashicons PO-dashicon dashicons-editor-help"></span>
							  </a>
							</h4>
						</div>

							
							
						<div class="stuffbox">
							<?php $ignoreProtocol = get_option("PO_ignore_protocol"); ?>
							<div class="PO-settings-button-container">
								<input type="checkbox" id="PO-ignore-protocol" name="PO_ignore_protocol" class="hidden-checkbox" <?php print ($ignoreProtocol === "1")? 'checked="checked"':""; ?>>
								<input type="button" id="PO-ignore-protocol-button" class="toggle-button-<?php print ($ignoreProtocol === "1")? "on":"off"; ?>" value="<?php print ($ignoreProtocol === "1")? "On":"Off"; ?>"  onclick="PO_toggle_button('PO-ignore-protocol', '', 0);" />
							</div>
							<div id="PO-ignore-protocol-help" class="PO-help">
								<p>
								This allows you to ignore the protocol (http, https) of a URL when trying to match it in the database at page load time.  With this turned on <?php print home_url($path='/', $scheme='https'); ?>page/ will have the same plugins loaded as <?php print home_url($path='/'); ?>page/.  If it is turned off they can be set seperately using plugin filters.
								</p>
							</div>
							<h4>
							  <label for="PO_ignore_protocol">Ignore URL Protocol</label>
							  <a href="#TB_inline?width=400&height=200&inlineId=PO-ignore-protocol-help" title="Ignore URL Protocol" class="thickbox">
								<span class="dashicons PO-dashicon dashicons-editor-help"></span>
							  </a>
							</h4>
						</div>


							
						<div class="stuffbox">
							<?php $ignoreArguments = get_option("PO_ignore_arguments"); ?>
							<div class="PO-settings-button-container">
								<input type="checkbox" id="PO-ignore-arguments" name="PO_ignore_arguments" class="hidden-checkbox" <?php print ($ignoreArguments === "1")? 'checked="checked"':""; ?>>
								<input type="button" id="PO-ignore-arguments-button" class="toggle-button-<?php print ($ignoreArguments === "1")? "on":"off"; ?>" value="<?php print ($ignoreArguments === "1")? "On":"Off"; ?>"  onclick="PO_toggle_button('PO-ignore-arguments', '', 0);" />
							</div>
							<div id="PO-ignore-arguments-help" class="PO-help">
								<p>
								This allows you to ignore the arguments of a URL when trying to match it in the database at page load time.  With this turned on <?php print home_url($path='/'); ?>page/?foo=2&bar=3 will have the same plugins loaded as <?php print home_url($path='/'); ?>page/.  If it is turned off you can enter URLs with arguments included to load different plugins depending on what arguments are used.
								</p>
							</div>
							<h4>
							  <label for="PO_ignore_arguments">Ignore URL Arguments</label>
							  <a href="#TB_inline?width=400&height=200&inlineId=PO-ignore-arguments-help" title="Ignore URL Arguments" class="thickbox">
								<span class="dashicons PO-dashicon dashicons-editor-help"></span>
							  </a>
							</h4>
						</div>
							
							
						<div class="stuffbox">
							<?php $orderAccessNetAdmin = get_option("PO_order_access_net_admin"); ?>
							<div class="PO-settings-button-container">
								<input type="checkbox" id="PO-order-access-net-admin" title="Network Admin Access" name="PO_order_access_net_admin" class="hidden-checkbox" value="1" <?php print ($orderAccessNetAdmin === "1")? 'checked="checked"':""; ?>>
								<input type="button" id="PO-order-access-net-admin-button" class="toggle-button-<?php print ($orderAccessNetAdmin === "1")? "yes":"no"; ?>" value="<?php print ($orderAccessNetAdmin === "1")? "Yes":"No"; ?>"  onclick="PO_toggle_button('PO-order-access-net-admin', '', 1);" />
							</div>
							<div id="PO-order-access-net-admin-help" class="PO-help">
								<p>
								When this option is turned on only a network admin will be able to reorder plugins.  All other user types will not see the options to change the load order.
								</p>
							</div>
							<h4>
							  <label for="PO_order_access_net_admin">Only allow network admins to change plugin load order?</label>
							  <a href="#TB_inline?width=400&height=200&inlineId=PO-order-access-net-admin-help" title="Only allow network admins to change plugin load order?" class="thickbox">
								<span class="dashicons PO-dashicon dashicons-editor-help"></span>
							  </a>
							</h4>
						</div>

							
							
						<div id="PO-custom-post-type-container" class="stuffbox">
							<div id="PO-custom-post-type-help" class="PO-help">
								<p>
								This is a list of registered post types on your wordpress install.  Select the checkbox next to the post types you would like to disable/enable plugins on.  If a post type is not selected then the list of plugins will not appear on the post edit screen.
								</p>
							</div>
							<h4>
							  <label for="PO_cutom_post_type">Custom Post Type Support</label>
							  <a href="#TB_inline?width=400&height=200&inlineId=PO-custom-post-type-help" title="Custom Post Type Support" class="thickbox">
								<span class="dashicons PO-dashicon dashicons-editor-help"></span>
							  </a>
							</h4>
							<div class="PO-post-type-container">
								<?php
								$supportedPostTypes = get_option("PO_custom_post_type_support");
								if (!is_array($supportedPostTypes)) {
									$supportedPostTypes = array();
								}
								
								$customPostTypes = get_post_types();
								if (is_array($customPostTypes)) {
									foreach ($customPostTypes as $postType) {
										if (!in_array($postType, array("attachment", "revision", "nav_menu_item", "plugin_group", "plugin_filter"))) {
											print '<input type="checkbox" class="PO-cutom-post-type" name="PO_cutom_post_type[]" value="'.$postType.'" '.((in_array($postType, $supportedPostTypes))? 'checked="checked"' : '').' />'.$postType.'<br />';
										}
									}
								}
								?>
							</div>
						</div>



						<div class="stuffbox">
							<?php $autoTrailingSlash = get_option("PO_auto_trailing_slash"); ?>
							<div class="PO-settings-button-container">
								<input type="checkbox" id="PO-auto-trailing-slash" name="PO_auto_trailing_slash" class="hidden-checkbox" <?php print ($autoTrailingSlash === "0")? '':'checked="checked"'; ?>>
								<input type="button" id="PO-auto-trailing-slash-button" class="toggle-button-<?php print ($autoTrailingSlash === "0")? "off":"on"; ?>" value="<?php print ($autoTrailingSlash === "0")? "Off":"On"; ?>"  onclick="PO_toggle_button('PO-auto-trailing-slash', '', 0);" />
							</div>
							<div id="PO-auto-trailing-slash-help" class="PO-help">
								<p>
								When this option is turned on Plugin Organizer will either remove or add a trailing slash to your plugin filter permalinks based on your permalink structure.   If you are having issues with your plugin filters not matching you can disable it by turning this off.
								</p>
							</div>
							<h4>
							  <label for="PO_auto_trailing_slash">Auto Trailing Slash</label>
							  <a href="#TB_inline?width=400&height=200&inlineId=PO-auto-trailing-slash-help" title="Auto Trailing Slash" class="thickbox">
								<span class="dashicons PO-dashicon dashicons-editor-help"></span>
							  </a>
							</h4>
						</div>
						
						
						<div class="stuffbox">
							<?php $selectiveLoad = get_option("PO_disable_plugins"); ?>
							<div class="PO-settings-button-container">
								<input type="checkbox" id="PO-disable-plugins" name="PO_disable_plugins" class="hidden-checkbox" <?php print ($selectiveLoad === "1")? 'checked="checked"':""; ?>>
								<input type="button" id="PO-disable-plugins-button" class="toggle-button-<?php print ($selectiveLoad === "1")? "on":"off"; ?>" value="<?php print ($selectiveLoad === "1")? "On":"Off"; ?>"  onclick="PO_toggle_button('PO-disable-plugins', '', 0);" />
							</div>
							<div id="PO-disable-plugins-help" class="PO-help">
								<p>
								When this option is turned on you must copy the PluginOrganizerMU.class.php file from /wp-content/plugins/plugin_organizer/lib and place it in <?php print WPMU_PLUGIN_DIR; ?> before it will work.  If you don't have an mu-plugins folder you need to create it.
								</p>
							</div>
							<h4>
							  <label for="PO_disable_plugins">Selective Plugin Loading</label>
							  <a href="#TB_inline?width=400&height=200&inlineId=PO-disable-plugins-help" title="Selective Plugin Loading" class="thickbox">
								<span class="dashicons PO-dashicon dashicons-editor-help"></span>
							  </a>
							</h4>
						</div>
							
						<div class="stuffbox">
							<?php $selectiveMobileLoad = get_option("PO_disable_mobile_plugins"); ?>
							<div class="PO-settings-button-container">
								<input type="checkbox" id="PO-disable-mobile-plugins" name="PO_disable_mobile_plugins" class="hidden-checkbox" <?php print ($selectiveMobileLoad === "1")? 'checked="checked"':""; ?>>
								<input type="button" id="PO-disable-mobile-plugins-button" class="toggle-button-<?php print ($selectiveMobileLoad === "1")? "on":"off"; ?>" value="<?php print ($selectiveMobileLoad === "1")? "On":"Off"; ?>"  onclick="PO_toggle_button('PO-disable-mobile-plugins', '', 0);" />
							</div>
							<div id="PO-disable-mobile-plugins-help" class="PO-help">
								<p>
								When this option is turned on plugins will be disabled differently for mobile browsers. Selective Plugin Loading must be turned on before this one will be applied.
								</p>
							</div>
							<h4>
							  <label for="PO_disable_mobile_plugins">Selective Mobile Plugin Loading</label>
							  <a href="#TB_inline?width=400&height=200&inlineId=PO-disable-mobile-plugins-help" title="Selective Mobile Plugin Loading" class="thickbox">
								<span class="dashicons PO-dashicon dashicons-editor-help"></span>
							  </a>
							</h4>
						</div>
							
						<div class="stuffbox">
							<?php $selectiveAdminLoad = get_option("PO_admin_disable_plugins"); ?>
							<div class="PO-settings-button-container">
								<input type="checkbox" id="PO-admin-disable-plugins" name="PO_admin_disable_plugins" class="hidden-checkbox" <?php print ($selectiveAdminLoad === "1")? 'checked="checked"':""; ?>>
								<input type="button" id="PO-admin-disable-plugins-button" class="toggle-button-<?php print ($selectiveAdminLoad === "1")? "on":"off"; ?>" value="<?php print ($selectiveAdminLoad === "1")? "On":"Off"; ?>"  onclick="PO_toggle_button('PO-admin-disable-plugins', '', 0);" />
							</div>
							<div id="PO-admin-disable-plugins-help" class="PO-help">
								<p>
								When this option is turned on plugin filters will also apply to the admin area. Selective Plugin Loading must be turned on before this one will be applied.
								</p>
							</div>
							<h4>
							  <label for="PO_admin_disable_plugins">Selective Admin Plugin Loading</label>
							  <a href="#TB_inline?width=400&height=200&inlineId=PO-admin-disable-plugins-help" title="Selective Admin Plugin Loading" class="thickbox">
								<span class="dashicons PO-dashicon dashicons-editor-help"></span>
							  </a>
							</h4>
						</div>
						<div style="clear: both;"></div>


						<input type="button" name="submit-gen-settings" value="Save Settings" onmousedown="PO_submit_gen_settings();" />
					</div>
				</div>

				
			</div>
			
			<?php
			$POAdminStyles = get_option('PO_admin_styles');
			if (!is_array($POAdminStyles)) {
				$POAdminStyles = array();
			}
			?>

			<div id="PO-tab-2-content" class="PO-tab-content">
				<h3>Manage CSS settings</h3>
				<div id="PO-manage-css-div" class="stuffbox" style="width: 98%">
				  <div class="PO-loading-container">
					<div>
					  <img src="<?php print $this->PO->urlPath . "/image/ajax-loader.gif"; ?>">
					</div>
				  </div>
				  <div class="inside">
					<h4 class="PO-settings-section-title first">Group And Order Plugins Page</h4>
					<div class="PO-settings-left-column">
					  Network plugins:
					</div>
					<div class="PO-settings-right-column">
					  <div>
					    <div class="PO-color-preview"></div>
					    <input type="text" id="PO-network-plugins-bg-color" name="PO_network_plugins_bg_color" class="PO-colorpicker" value="<?php print (isset($POAdminStyles['network_plugins_bg_color']) && $POAdminStyles['network_plugins_bg_color'] != '')? $POAdminStyles['network_plugins_bg_color'] : '#D7DF9E'; ?>" />  Background<br />
					  </div>
					  <div>
					    <div class="PO-color-preview"></div>
					    <input type="text" id="PO-network-plugins-font-color" name="PO_network_plugins_font_color" class="PO-colorpicker" value="<?php print (isset($POAdminStyles['network_plugins_font_color']) && $POAdminStyles['network_plugins_font_color'] != '')? $POAdminStyles['network_plugins_font_color'] : '#444'; ?>" />  Font
					  </div>
					</div>
					<div style="clear: both;"></div>
					<hr>
					<div class="PO-settings-left-column">
					  Active plugins:
					</div>
					<div class="PO-settings-right-column">
					  <div>
					    <div class="PO-color-preview"></div>
					    <input type="text" id="PO-active-plugins-bg-color" name="PO_active_plugins_bg_color" class="PO-colorpicker" value="<?php print (isset($POAdminStyles['active_plugins_bg_color']) && $POAdminStyles['active_plugins_bg_color'] != '')? $POAdminStyles['active_plugins_bg_color'] : '#99cc99'; ?>" />  Background<br />
					  </div>
					  <div>
					    <div class="PO-color-preview"></div>
					    <input type="text" id="PO-active-plugins-font-color" name="PO_active_plugins_font_color" class="PO-colorpicker" value="<?php print (isset($POAdminStyles['active_plugins_font_color']) && $POAdminStyles['active_plugins_font_color'] != '')? $POAdminStyles['active_plugins_font_color'] : '#444'; ?>" />  Font
					  </div>
					</div>
					<div style="clear: both;"></div>
					<hr>
					<div class="PO-settings-left-column">
					  Inactive plugins:
					</div>
					<div class="PO-settings-right-column">
					  <div>
					    <div class="PO-color-preview"></div>
					    <input type="text" id="PO-inactive-plugins-bg-color" name="PO_inactive_plugins_bg_color" class="PO-colorpicker" value="<?php print (isset($POAdminStyles['inactive_plugins_bg_color']) && $POAdminStyles['inactive_plugins_bg_color'] != '')? $POAdminStyles['inactive_plugins_bg_color'] : '#ddd'; ?>" />  Background<br />
					  </div>
					  <div>
					    <div class="PO-color-preview"></div>
					    <input type="text" id="PO-inactive-plugins-font-color" name="PO_inactive_plugins_font_color" class="PO-colorpicker" value="<?php print (isset($POAdminStyles['inactive_plugins_font_color']) && $POAdminStyles['inactive_plugins_font_color'] != '')? $POAdminStyles['inactive_plugins_font_color'] : '#444'; ?>" />  Font
					  </div>
					</div>
					<div style="clear: both;"></div>
					<hr>




					<h4 class="PO-settings-section-title">Buttons</h4>
					<div class="PO-settings-left-column">
					  On Button:
					</div>
					<div class="PO-settings-right-column">
					  <div>
					    <div class="PO-color-preview"></div>
					    <input type="text" id="PO-on-btn-bg-color" name="PO_on_btn_bg_color" class="PO-colorpicker" value="<?php print (isset($POAdminStyles['on_btn_bg_color']) && $POAdminStyles['on_btn_bg_color'] != '')? $POAdminStyles['on_btn_bg_color'] : '#336600'; ?>" />  Background<br />
					  </div>
					  <div>
					    <div class="PO-color-preview"></div>
					    <input type="text" id="PO-on-btn-font-color" name="PO_on_btn_font_color" class="PO-colorpicker" value="<?php print (isset($POAdminStyles['on_btn_font_color']) && $POAdminStyles['on_btn_font_color'] != '')? $POAdminStyles['on_btn_font_color'] : '#ffffff'; ?>" />  Font
					  </div>
					</div>
					<div style="clear: both;"></div>
					<hr>
					<div class="PO-settings-left-column">
					  Off Button:
					</div>
					<div class="PO-settings-right-column">
					  <div>
					    <div class="PO-color-preview"></div>
					    <input type="text" id="PO-off-btn-bg-color" name="PO_off_btn_bg_color" class="PO-colorpicker" value="<?php print (isset($POAdminStyles['off_btn_bg_color']) && $POAdminStyles['off_btn_bg_color'] != '')? $POAdminStyles['off_btn_bg_color'] : '#990000'; ?>" />  Background<br />
					  </div>
					  <div>
					    <div class="PO-color-preview"></div>
					    <input type="text" id="PO-off-btn-font-color" name="PO_off_btn_font_color" class="PO-colorpicker" value="<?php print (isset($POAdminStyles['off_btn_font_color']) && $POAdminStyles['off_btn_font_color'] != '')? $POAdminStyles['off_btn_font_color'] : '#ffffff'; ?>" />  Font
					  </div>
					</div>
					<div style="clear: both;"></div>
					<hr>

					<div class="PO-settings-left-column">
					  Yes Button:
					</div>
					<div class="PO-settings-right-column">
					  <div>
					    <div class="PO-color-preview"></div>
					    <input type="text" id="PO-yes-btn-bg-color" name="PO_yes_btn_bg_color" class="PO-colorpicker" value="<?php print (isset($POAdminStyles['yes_btn_bg_color']) && $POAdminStyles['yes_btn_bg_color'] != '')? $POAdminStyles['yes_btn_bg_color'] : '#336600'; ?>" />  Background<br />
					  </div>
					  <div>
					    <div class="PO-color-preview"></div>
					    <input type="text" id="PO-yes-btn-font-color" name="PO_yes_btn_font_color" class="PO-colorpicker" value="<?php print (isset($POAdminStyles['yes_btn_font_color']) && $POAdminStyles['yes_btn_font_color'] != '')? $POAdminStyles['yes_btn_font_color'] : '#ffffff'; ?>" />  Font
					  </div>
					</div>
					<div style="clear: both;"></div>
					<hr>

					<div class="PO-settings-left-column">
					  No Button:
					</div>
					<div class="PO-settings-right-column">
					  <div>
					    <div class="PO-color-preview"></div>
					    <input type="text" id="PO-no-btn-bg-color" name="PO_no_btn_bg_color" class="PO-colorpicker" value="<?php print (isset($POAdminStyles['no_btn_bg_color']) && $POAdminStyles['no_btn_bg_color'] != '')? $POAdminStyles['no_btn_bg_color'] : '#990000'; ?>" />  Background<br />
					  </div>
					  <div>
					    <div class="PO-color-preview"></div>
					    <input type="text" id="PO-no-btn-font-color" name="PO_no_btn_font_color" class="PO-colorpicker" value="<?php print (isset($POAdminStyles['no_btn_font_color']) && $POAdminStyles['no_btn_font_color'] != '')? $POAdminStyles['no_btn_font_color'] : '#ffffff'; ?>" />  Font
					  </div>
					</div>
					<div style="clear: both;"></div>
					<hr>

					<div class="PO-settings-left-column">
					  Toggle On Button:
					</div>
					<div class="PO-settings-right-column">
					  <div>
					    <div class="PO-color-preview"></div>
					    <input type="text" id="PO-group-on-btn-bg-color" name="PO_group_on_btn_bg_color" class="PO-colorpicker" value="<?php print (isset($POAdminStyles['group_on_btn_bg_color']) && $POAdminStyles['group_on_btn_bg_color'] != '')? $POAdminStyles['group_on_btn_bg_color'] : '#336699'; ?>" />  Background<br />
					  </div>
					  <div>
					    <div class="PO-color-preview"></div>
					    <input type="text" id="PO-group-on-btn-font-color" name="PO_group_on_btn_font_color" class="PO-colorpicker" value="<?php print (isset($POAdminStyles['group_on_btn_font_color']) && $POAdminStyles['group_on_btn_font_color'] != '')? $POAdminStyles['group_on_btn_font_color'] : '#ffffff'; ?>" />  Font
					  </div>
					</div>
					<div style="clear: both;"></div>
					<hr>

					<div class="PO-settings-left-column">
					  Toggle Off Button:
					</div>
					<div class="PO-settings-right-column">
					  <div>
					    <div class="PO-color-preview"></div>
					    <input type="text" id="PO-group-off-btn-bg-color" name="PO_group_off_btn_bg_color" class="PO-colorpicker" value="<?php print (isset($POAdminStyles['group_off_btn_bg_color']) && $POAdminStyles['group_off_btn_bg_color'] != '')? $POAdminStyles['group_off_btn_bg_color'] : '#336699'; ?>" />  Background<br />
					  </div>
					  <div>
					    <div class="PO-color-preview"></div>
					    <input type="text" id="PO-group-off-btn-font-color" name="PO_group_off_btn_font_color" class="PO-colorpicker" value="<?php print (isset($POAdminStyles['group_off_btn_font_color']) && $POAdminStyles['group_off_btn_font_color'] != '')? $POAdminStyles['group_off_btn_font_color'] : '#ffffff'; ?>" />  Font
					  </div>
					</div>
					<div style="clear: both;"></div>
					<hr>
					<input type=button name="submit_admin_css_settings" value="Submit" onmousedown="PO_submit_admin_css_settings();">
				  </div>
				</div>
			</div>
			<div id="PO-tab-3-content" class="PO-tab-content">
				<h3><label for="redo-permalinks">Recreate Permalinks</label></h3>
				<div id="PO-redo-permalinks-div" class="stuffbox" style="width: 98%">
				  <div class="PO-loading-container">
					<div>
					  <img src="<?php print $this->PO->urlPath . "/image/ajax-loader.gif"; ?>">
					</div>
				  </div>
				  <div class="inside">
					Old site address (optional): <input type="text" name="PO_old_site_address" id="PO-old-site-address" /><br />
					New site address (optional): <input type="text" name="PO_new_site_address" id="PO-new-site-address" value="<?php print preg_replace('/^.{1,5}:\/\//', '', get_site_url()); ?>" /><br />
					<br />
					If you are changing your site address you can enter your new and old addresses to update your plugin filters.  If you don't enter the new and old site addresses your plugin filters will not be updated.  All other post types will be updated by getting the new permalink from wordpress.<br />
					WARNING:  This does a regular expression search on your permalinks for the string you enter in the old address box and replaces it with the string you put in the new addres box so be careful what you enter.  This can't be undone.<br />
					<input type="button" name="redo-permalinks" value="Recreate Permalinks" onmousedown="PO_submit_redo_permalinks();" />
				  </div>
				</div>
			</div>

			<div id="PO-tab-4-content" class="PO-tab-content">
				<h3><label for="PO_mobile_user_agents">Mobile User Agents</label></h3>
				<div id="PO-browser-string-div" class="stuffbox" style="width: 98%">
				  <div class="PO-help-container">
				    <a href="#TB_inline?width=400&height=200&inlineId=PO-mobile-user-agents-help" title="Mobile User Agents" class="thickbox">
					  <span class="dashicons PO-dashicon dashicons-editor-help"></span>
				    </a>
				  </div>
				  <div id="PO-mobile-user-agents-help" class="PO-help">
					<p>
					This is the list of strings that will be used to determine if a visitor is using a mobile browser.  If the browser string they send contains one of these words then the mobile set of plugins will be loaded.
					</p>
				  </div>
				  <div class="PO-loading-container">
					<div>
					  <img src="<?php print $this->PO->urlPath . "/image/ajax-loader.gif"; ?>">
					</div>
				  </div>
				  <div class="inside">
					<textarea name="PO_mobile_user_agents" id="PO-mobile-user-agents" rows="20" cols="50" style="width: 100%;"><?php
						$userAgents = get_option("PO_mobile_user_agents");
						if (is_array($userAgents)) {
							foreach ($userAgents as $key=>$agent) {
								if ($key > 0) {
									print "\n";
								}
								print $agent;
							}
						}
					?></textarea>
					<br />
					<input type="button" name="save-user-agents" value="Save User Agents" onmousedown="PO_submit_mobile_user_agents();">
				  </div>
				</div>
			</div>

			<div id="PO-tab-5-content" class="PO-tab-content">
				<h3><label for="PO_manage_mu">Manage MU plugin file</label></h3>
				<div id="PO-manage-mu-div" class="stuffbox" style="width: 98%">
				  <div class="PO-loading-container">
					<div>
					  <img src="<?php print $this->PO->urlPath . "/image/ajax-loader.gif"; ?>">
					</div>
				  </div>
				  <div class="inside">
					<input type=button name="manage-mu-plugin" value="Delete" onmousedown="PO_manage_mu_plugin_file('delete');">
					<input type=button name="manage-mu-plugin" value="Copy" onmousedown="PO_manage_mu_plugin_file('move');">
				  </div>
				</div>
			</div>
		</div>
	</div>
</div>

