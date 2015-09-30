<?php
global $wpdb;
if ( current_user_can( 'activate_plugins' ) ) {
	$globalPluginLists = array(get_option('PO_disabled_plugins'), get_option('PO_disabled_mobile_plugins'), get_option('PO_disabled_groups'), get_option('PO_disabled_mobile_groups'));
	foreach($globalPluginLists as $key=>$list) {
		if (!is_array($list)) {
			$globalPluginLists[$key] = array();
		}
	}
	?>
	<script type="text/javascript" language="javascript">
		var globalPlugins = <?php print json_encode($globalPluginLists); ?>;
		
		function PO_submit_pt_plugins(){
			var disabledList = new Array();
			var disabledMobileList = new Array();
			var disabledGroupList = new Array();
			var disabledMobileGroupList = new Array();
			var selectedPostType = jQuery('select#PO-selected-post-type').val();
			jQuery('.PO-disabled-list').each(function() {
				if (this.checked) {
					disabledList[disabledList.length] = jQuery(this).val();
				}
			});

			jQuery('.PO-disabled-mobile-list').each(function() {
				if (this.checked) {
					disabledMobileList[disabledMobileList.length] = jQuery(this).val();
				}
			});

			jQuery('.PO-disabled-group-list').each(function() {
				if (this.checked) {
					disabledGroupList[disabledGroupList.length] = jQuery(this).val();
				}
			});

			jQuery('.PO-disabled-mobile-group-list').each(function() {
				if (this.checked) {
					disabledMobileGroupList[disabledMobileGroupList.length] = jQuery(this).val();
				}
			});
			
			var postVars = { 'PO_disabled_list[]': disabledList, 'PO_disabled_mobile_list[]': disabledMobileList, 'PO_disabled_group_list[]': disabledGroupList, 'PO_disabled_mobile_group_list[]': disabledMobileGroupList, 'selectedPostType': selectedPostType, PO_nonce: '<?php print $this->PO->nonce; ?>' };
			PO_submit_ajax('PO_save_pt_plugins', postVars, '#PO-pt-settings', PO_get_pt_plugins);
		}

		function PO_toggle_saved_buttons(listSelector, values) {
			var count=0;
			jQuery(listSelector).each(function() {
				count++;
				if (jQuery.inArray(jQuery(this).val(), values[0]) > -1) {
					PO_set_button(jQuery(this), 1, '', 0);
				} else if (jQuery.inArray(jQuery(this).val(), values[1]) > -1) {
					PO_set_button(jQuery(this), 0, '', 0);
				} else if (jQuery.inArray(jQuery(this).val(), values[2]) > -1) {
					PO_set_button(jQuery(this), 1, '', 0);
				} else {
					PO_set_button(jQuery(this), 0, '', 0);
				}
			});
		}
		
		function PO_get_pt_plugins() {
			var selectedPostType = jQuery('select#PO-selected-post-type').val();
			PO_toggle_loading('#PO-pt-settings');
			jQuery.post(encodeURI(ajaxurl + '?action=PO_get_pt_plugins'), {'selectedPostType': selectedPostType, PO_nonce: '<?php print $this->PO->nonce; ?>' }, function (result) {
				var pluginLists = jQuery.parseJSON(result);
				PO_toggle_saved_buttons('.PO-disabled-list', new Array(pluginLists[0], pluginLists[1], globalPlugins[0]));
				PO_toggle_saved_buttons('.PO-disabled-mobile-list', new Array(pluginLists[2], pluginLists[3], globalPlugins[1]));
				PO_toggle_saved_buttons('.PO-disabled-group-list', new Array(pluginLists[4], pluginLists[5], globalPlugins[2]));
				PO_toggle_saved_buttons('.PO-disabled-mobile-group-list', new Array(pluginLists[6], pluginLists[7], globalPlugins[3]));
				
				PO_toggle_loading('#PO-pt-settings');
				
			});
		}

		function PO_reset_pt_settings() {
			var selectedPostType = jQuery('select#PO-selected-post-type').val();
			if (confirm('Are you sure you want to reset the enabled/disabled plugins back to default for this post type?')) {
				if (jQuery('#PO-reset-all-pt').prop('checked')) {
					resetAll = 1;
				} else {
					resetAll = 0;
				}
				var postVars = {'selectedPostType': selectedPostType, PO_nonce: '<?php print $this->PO->nonce; ?>', PO_reset_all_pt: resetAll };
				PO_submit_ajax('PO_reset_pt_settings', postVars, '#PO-pt-settings', PO_get_pt_plugins);
			}
		}
		
		jQuery(function() {
			PO_toggle_loading('#PO-pt-settings');
			PO_get_pt_plugins();
			jQuery('#PO-selected-post-type').change(function() {
				PO_get_pt_plugins()
			});
		});
	</script>
	<?php
}
?>