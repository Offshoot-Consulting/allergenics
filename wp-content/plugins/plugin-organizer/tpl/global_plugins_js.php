<?php
global $wpdb;
if ( current_user_can( 'activate_plugins' ) ) {
	?>
	<script type="text/javascript" language="javascript">
		function PO_submit_global_plugins(){
			var disabledList = new Array();
			var disabledMobileList = new Array();
			var disabledGroupList = new Array();
			var disabledMobileGroupList = new Array();
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
			
			var postVars = { 'PO_disabled_list[]': disabledList, 'PO_disabled_mobile_list[]': disabledMobileList, 'PO_disabled_group_list[]': disabledGroupList, 'PO_disabled_mobile_group_list[]': disabledMobileGroupList, PO_nonce: '<?php print $this->PO->nonce; ?>' };
			PO_submit_ajax('PO_save_global_plugins', postVars, '#post-body-content', function(){});
		}
	</script>
	<?php
}
?>