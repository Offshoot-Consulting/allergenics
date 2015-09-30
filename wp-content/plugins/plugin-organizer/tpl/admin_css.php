<?php
$POAdminStyles = get_option('PO_admin_styles');
if (!is_array($POAdminStyles)) {
	$POAdminStyles = array();
}

?>

<style type="text/css">
	.plugin.network-active {
		background-color: <?php print (isset($POAdminStyles['network_plugins_bg_color']) && $POAdminStyles['network_plugins_bg_color'] != '')? $POAdminStyles['network_plugins_bg_color'] : '#D7DF9E'; ?> !important;
		color: <?php print (isset($POAdminStyles['network_plugins_font_color']) && $POAdminStyles['network_plugins_font_color'] != '')? $POAdminStyles['network_plugins_font_color'] : '#444'; ?> !important;
	}

	.plugin.active {
		background-color: <?php print (isset($POAdminStyles['active_plugins_bg_color']) && $POAdminStyles['active_plugins_bg_color'] != '')? $POAdminStyles['active_plugins_bg_color'] : '#99cc99'; ?> !important;
		color: <?php print (isset($POAdminStyles['active_plugins_font_color']) && $POAdminStyles['active_plugins_font_color'] != '')? $POAdminStyles['active_plugins_font_color'] : '#444'; ?> !important;
	}

	.plugin.inactive {
		background-color: <?php print (isset($POAdminStyles['inactive_plugins_bg_color']) && $POAdminStyles['inactive_plugins_bg_color'] != '')? $POAdminStyles['inactive_plugins_bg_color'] : '#ddd'; ?> !important;
		color: <?php print (isset($POAdminStyles['inactive_plugins_font_color']) && $POAdminStyles['inactive_plugins_font_color'] != '')? $POAdminStyles['inactive_plugins_font_color'] : '#444'; ?> !important;
	}


	.toggle-button-on {
		background-color: <?php print (isset($POAdminStyles['on_btn_bg_color']) && $POAdminStyles['on_btn_bg_color'] != '')? $POAdminStyles['on_btn_bg_color'] : '#336600'; ?>;
		color: <?php print (isset($POAdminStyles['on_btn_font_color']) && $POAdminStyles['on_btn_font_color'] != '')? $POAdminStyles['on_btn_font_color'] : '#fff'; ?>;
	}

	.toggle-button-off {
		background-color: <?php print (isset($POAdminStyles['off_btn_bg_color']) && $POAdminStyles['off_btn_bg_color'] != '')? $POAdminStyles['off_btn_bg_color'] : '#990000'; ?>;
		color: <?php print (isset($POAdminStyles['off_btn_font_color']) && $POAdminStyles['off_btn_font_color'] != '')? $POAdminStyles['off_btn_font_color'] : '#fff'; ?>;
	}

	.toggle-button-yes {
		background-color: <?php print (isset($POAdminStyles['yes_btn_bg_color']) && $POAdminStyles['yes_btn_bg_color'] != '')? $POAdminStyles['yes_btn_bg_color'] : '#336600'; ?>;
		color: <?php print (isset($POAdminStyles['yes_btn_font_color']) && $POAdminStyles['yes_btn_font_color'] != '')? $POAdminStyles['yes_btn_font_color'] : '#fff'; ?>;
	}
	
	.toggle-button-no {
		background-color: <?php print (isset($POAdminStyles['no_btn_bg_color']) && $POAdminStyles['no_btn_bg_color'] != '')? $POAdminStyles['no_btn_bg_color'] : '#990000'; ?>;
		color: <?php print (isset($POAdminStyles['no_btn_font_color']) && $POAdminStyles['no_btn_font_color'] != '')? $POAdminStyles['no_btn_font_color'] : '#fff'; ?>;
	}


	.group-toggle-button-on {
		background-color: <?php print (isset($POAdminStyles['group_on_btn_bg_color']) && $POAdminStyles['group_on_btn_bg_color'] != '')? $POAdminStyles['group_on_btn_bg_color'] : '#336699'; ?>;
		color: <?php print (isset($POAdminStyles['group_on_btn_font_color']) && $POAdminStyles['group_on_btn_font_color'] != '')? $POAdminStyles['group_on_btn_font_color'] : '#fff'; ?>;
	}

	.group-toggle-button-off {
		background-color: <?php print (isset($POAdminStyles['group_off_btn_bg_color']) && $POAdminStyles['group_off_btn_bg_color'] != '')? $POAdminStyles['group_off_btn_bg_color'] : '#336699'; ?>;
		color: <?php print (isset($POAdminStyles['group_off_btn_font_color']) && $POAdminStyles['group_off_btn_font_color'] != '')? $POAdminStyles['group_off_btn_font_color'] : '#fff'; ?>;
	}
</style>