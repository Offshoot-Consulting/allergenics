<script language="javascript" src="<?php print $this->PO->urlPath; ?>/js/validation.js"></script>
<script language="javascript" type="text/javascript">
	var toggleButtonOptions = [['Off','On'], ['No','Yes']];
	function PO_reverse_toggle_buttons() {
		toggleButtonOptions = [['On','Off'], ['Yes','No']];
	}
	
	jQuery(function() {
		PO_set_expand_info_action();
	});
	
	
	function PO_set_expand_info_action() {
		jQuery('.expand-info-icon').each(function() {
			jQuery(this).unbind();
			var targetID = jQuery(this).prop('id').replace('PO-expand-info-', '');
			var infoContainer = jQuery('#PO-info-container-' + targetID);
			if (!jQuery(infoContainer).find('.PO-info-inner').html().match(/^\s*$/)) {
				jQuery(this).click(function() {
					if (jQuery(this).hasClass('fa-plus-square-o')) {
						jQuery(this).removeClass('fa-plus-square-o');
						jQuery(this).addClass('fa-minus-square-o');
						infoContainer.slideDown(300);
					} else {
						jQuery(this).removeClass('fa-minus-square-o');
						jQuery(this).addClass('fa-plus-square-o');
						infoContainer.slideUp(300);
					}
				});
			}
		});
	}
	function PO_toggle_loading(container) {
		jQuery(container+' .PO-loading-container').toggle();
		jQuery(container+' .inside').toggle();
	}
	
	function PO_toggle_all(toggleCheckbox, itemClass, optionIndex) {
		var toggle = jQuery("#"+toggleCheckbox).prop('checked');
		jQuery("."+itemClass).each(function() {  
			if (toggle) {
				PO_set_button(this, 1, '', optionIndex);
			} else {
				PO_set_button(this, 0, '', optionIndex);
			}
		});  
	}

	function PO_toggle_button(checkboxID, buttonPrefix, optionIndex) {
		if (jQuery('#'+checkboxID).prop('checked') == false) {
			PO_set_button(jQuery('#'+checkboxID), 1, buttonPrefix, optionIndex);
		} else {
			PO_set_button(jQuery('#'+checkboxID), 0, buttonPrefix, optionIndex);
		}
	}
	
	function PO_set_button(checkbox, onOff, buttonPrefix, optionIndex) {
		if (onOff == 1) {
			jQuery(checkbox).prop('checked', true);
		} else {
			jQuery(checkbox).prop('checked', false);
		}
		jQuery(checkbox).parent().find("input[type='button']").removeClass();
		jQuery(checkbox).parent().find("input[type='button']").addClass(buttonPrefix+'toggle-button-'+toggleButtonOptions[optionIndex][onOff].toLowerCase());
		jQuery(checkbox).parent().find("input[type='button']").attr('value',toggleButtonOptions[optionIndex][onOff]);
	}
	
	function PO_reset_post_settings(postID) {
		jQuery.post(encodeURI(ajaxurl + '?action=PO_reset_post_settings'), { 'postID': postID, PO_nonce: '<?php print $this->PO->nonce; ?>' }, function (result) {
			if (result == '1') {
				jQuery('#PO-ajax-notices-container').html('The settings were successfully reset.');
				jQuery("#PO-show-ajax-notices").click();
				location.reload(true);
			} else if (result == '-1') {
				jQuery('#PO-ajax-notices-container').html('There were no settings found in the database.');
				jQuery("#PO-show-ajax-notices").click();
			} else {
				jQuery('#PO-ajax-notices-container').html('There was an issue removing the settings.');
				jQuery("#PO-show-ajax-notices").click();
			}
		});
	}

	function PO_submit_ajax(action, postVars, container, callback) {
		PO_toggle_loading(container);
		jQuery.post(encodeURI(ajaxurl + '?action='+action), postVars, function (result) {
			jQuery('#PO-ajax-notices-container').html(result);
			jQuery("#PO-show-ajax-notices").click();
			
			if (typeof(callback) == 'function') {
				callback();
			}

			PO_toggle_loading(container);
		});
	}
	
	
	<?php
	print "var regex = new Array();\n";
	foreach ($this->PO->regex as $key=>$val) {
		print "regex['$key'] = $val;\n";
	}
	?>
</script>