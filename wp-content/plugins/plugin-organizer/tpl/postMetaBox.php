<script type="text/javascript" language="javascript">
	PO_reverse_toggle_buttons();
</script>

<?php 
$adminPage = '';
if (isset($_GET['page'])) {
	$adminPage = $_GET['page'];
}

?>
		
<?php
if (isset($errMsg) && $errMsg != "") {
	?>
	<h3 style="color: #CC0066;"><?php print $errMsg; ?></h3>
	<?php
}
	
if (isset($post) && $ptOverride == 0 && is_array(get_option('PO_disabled_pt_plugins_'.get_post_type($post->ID)))) {
	?>
	Settings for this post type have been overridden by the post type settings.  You can edit them by going <a href="<?php print get_admin_url(); ?>admin.php?page=PO_pt_plugins&PO_target_post_type=<?php print get_post_type($post->ID); ?>">here</a>.  You can also override them by checking the box below and saving the post.
	<br /><input type="checkbox" id="PO-pt-override" name="PO_pt_override" value="1" <?php print ($ptOverride == "1")? 'checked="checked"':''; ?>>Override Post Type settings
	<a href="#TB_inline?width=400&height=200&inlineId=PO-pt-override-help" title="Override Post Type Settings" class="thickbox">
	  <span class="dashicons PO-dashicon dashicons-editor-help"></span>
	</a>
	<div id="PO-pt-override-help" class="PO-help">
		<p>
		By checking this box the changes you make here will not be overwritten by the settings that have been set for the <?php print get_post_type($post->ID); ?> post type.  You will be able to see the plugins disabled/enabled on this page and make changes to them.
		</p>
	</div>
	<?php
} else {
	if ($adminPage != 'PO_search_plugins' && $adminPage != 'PO_global_plugins') { ?>
		<?php if(isset($post) && get_post_type($post->ID) == 'plugin_filter') { ?>
			<div class="metaBoxLabel">
				Name
			</div>
			<div class="metaBoxContent">
				<input type="text" class="poFilterNameInput" size="25" name="filterName" value="<?php print $filterName; ?>">
			</div>
			<div class="metaBoxLabel">
				Permalink
			</div>
			<div class="metaBoxContent">
				<input type="text" class="poPermalinkInput" size="25" name="permalinkFilter" value="<?php print ($permalinkFilter != "") ? (($secure == 1)? "https://":"http://") . $permalinkFilter : ""; ?>">
			</div>
		<?php } ?>

		<div id="settingsMetaBox" class="metaBoxContent">
			<div class="pluginListHead">Settings<?php if(isset($ajaxSaveFunction)) { ?><input type=button name=submit value="Save" onmousedown="<?php print $ajaxSaveFunction; ?>" class="PO-ajax-save-btn"><?php } ?></div>
			<?php if ($adminPage == 'PO_pt_plugins') { ?>
				<div style="padding-left: 10px;">
				Post Type: <select id="PO-selected-post-type" name="PO_selected_post_type">
				<?php
					$supportedPostTypes = get_option("PO_custom_post_type_support");
					if (!is_array($supportedPostTypes)) {
						$supportedPostTypes = array();
					}
					if (isset($_REQUEST['PO_target_post_type'])) {
						$targetPostType = $_REQUEST['PO_target_post_type'];
					} else {
						$targetPostType = '';
					}
					
					foreach($supportedPostTypes as $postType) {
						print '<option value="' . $postType . '" ' . (($targetPostType == $postType)? 'selected="selected" ':'') . '>' . $postType . '</option>';
					}
				?>
				</select>
				</div>
				<hr>
				<input type="button" class="button" style="float: left;margin: 5px;" id="resetPostTypeSettings" value="Reset settings for this post type" onclick="PO_reset_pt_settings();">
				<div style="float: left;margin: 10px 5px 0px 0px;">
					<input type="checkbox" id="PO-reset-all-pt" name="PO-reset-all-pt" value="1"><label for="PO-reset-all-pt">Reset All</label>
				</div>
				<a href="#TB_inline?width=400&height=200&inlineId=PO-affect-children-help" title="Reset all matching posts" class="thickbox">
					<span class="dashicons PO-dashicon dashicons-editor-help"></span>
				 </a>
				<div id="PO-affect-children-help" class="PO-help">
					<p>
					By checking this box all posts that match the selected post type will be reset.  If the box isn't checked the post type setting will be reset but the individual posts will still keep the settings until they are changed individually.  You can go directly to each post matching this post type and override this setting.  Then the changes you make here will not affect that post.
					</p>
				</div>
				<div style="clear: both;"></div>
			<?php } else { ?>
				<?php if (isset($post)) { ?>
					<input type="checkbox" id="affectChildren" name="affectChildren" value="1" <?php print ($affectChildren == "1")? 'checked="checked"':''; ?>>Also Affect Children
					<a href="#TB_inline?width=400&height=200&inlineId=PO-affect-children-help" title="Also Affect Children" class="thickbox">
					  <span class="dashicons PO-dashicon dashicons-editor-help"></span>
					</a>
					<div id="PO-affect-children-help" class="PO-help">
						<p>
						By checking this box the plugins disabled or enabled for this page will be used for its children if they have nothing set.
						</p>
					</div>
					<hr>
					<?php if(isset($post) && in_array(get_post_type($post->ID), get_option('PO_custom_post_type_support'))) { ?>
						<input type="checkbox" id="PO-pt-override" name="PO_pt_override" value="1" <?php print ($ptOverride == "1")? 'checked="checked"':''; ?>>Override Post Type settings
						<a href="#TB_inline?width=400&height=200&inlineId=PO-pt-override-help" title="Override Post Type Settings" class="thickbox">
						  <span class="dashicons PO-dashicon dashicons-editor-help"></span>
						</a>
						<div id="PO-pt-override-help" class="PO-help">
							<p>
							By checking this box the changes you make here will not be overwritten by the settings that have been set for the <?php print get_post_type($post->ID); ?> post type.
							</p>
						</div>
						<hr>
					<?php } ?>
					<input type="button" class="button" style="margin: 5px;" id="resetPostSettings" value="Reset settings for this post" onclick="PO_reset_post_settings(<?php print $post->ID; ?>);">
				<?php } ?>
			<?php } ?>
		</div>
	<?php } ?>
	<div id="pluginContainer" class="metaBoxContent">
		<div class="pluginListHead">Plugins<?php if(isset($ajaxSaveFunction)) { ?><input type=button name=submit value="Save" onmousedown="<?php print $ajaxSaveFunction; ?>" class="PO-ajax-save-btn"><?php } ?></div>
		<div class="pluginWrap">
			<div class="PO-toggle-container">
				Standard<br />
				<input type="checkbox" id="PO-toggle-all-plugins" name="PO_toggle_all_plugins" value="">
				<input type="button" id="PO-toggle-all-plugins-button" class="group-toggle-button-on" value="On" onclick="PO_toggle_button('PO-toggle-all-plugins', 'group-', 0);PO_toggle_all('PO-toggle-all-plugins', 'PO-disabled-list', 0);" />
			</div>
			<?php if (get_option('PO_disable_mobile_plugins') == 1) { ?>
				<div class="PO-toggle-container">
					Mobile<br />
					<input type="checkbox" id="PO-toggle-all-mobile-plugins" name="PO_toggle_all_mobile_plugins" value="">
					<input type="button" id="PO-toggle-all-mobile-plugins-button" class="group-toggle-button-on" value="On" onclick="PO_toggle_button('PO-toggle-all-mobile-plugins', 'group-', 0);PO_toggle_all('PO-toggle-all-mobile-plugins', 'PO-disabled-mobile-list', 0);" />
				</div>
			<?php } ?>
			<div style="clear: both;"></div>
		</div>
		<?php
		$count = 0;
		foreach ($plugins as $key=>$plugin) {
			$count++;
			?>
			<div class="pluginWrap <?php print (in_array($key, $activeSitewidePlugins) || in_array($key, $activePlugins))? "activePluginWrap" : "inactivePluginWrap"; ?>">
				<div class="PO-toggle-container">
					<?php 
					if ((in_array($key, $globalPlugins) && !in_array($key, $enabledPluginList)) || in_array($key, $disabledPluginList)) {
						?>
						<input type="checkbox" class="PO-disabled-list" id="PO-disabled-list-<?php print $count; ?>" name="PO_disabled_list[]" value="<?php print $key; ?>" checked="checked" />
						<input type="button" id="PO-disabled-list-button-<?php print $count; ?>" class="toggle-button-off" value="Off"  onclick="PO_toggle_button('PO-disabled-list-<?php print $count; ?>', '', 0);" />
						<?php
					} else {
						?>
						<input type="checkbox" class="PO-disabled-list" id="PO-disabled-list-<?php print $count; ?>" name="PO_disabled_list[]" value="<?php print $key; ?>" />
						<input type="button" id="PO-disabled-list-button-<?php print $count; ?>" class="toggle-button-on" value="On"  onclick="PO_toggle_button('PO-disabled-list-<?php print $count; ?>', '', 0);" />
						<?php
					}
					?>
				</div>
				<?php if (get_option('PO_disable_mobile_plugins') == 1) { ?>
					<div class="PO-toggle-container">
						<?php 
						if ((in_array($key, $globalMobilePlugins) && !in_array($key, $enabledMobilePluginList)) || in_array($key, $disabledMobilePluginList)) {
							?>
							<input type="checkbox" class="PO-disabled-mobile-list" id="PO-disabled-mobile-list-<?php print $count; ?>" name="PO_disabled_mobile_list[]" value="<?php print $key; ?>" checked="checked" />
							<input type="button" id="PO-disabled-mobile-list-button-<?php print $count; ?>" class="toggle-button-off" value="Off"  onclick="PO_toggle_button('PO-disabled-mobile-list-<?php print $count; ?>', '', 0);" />
							<?php
						} else {
							?>
							<input type="checkbox" class="PO-disabled-mobile-list" id="PO-disabled-mobile-list-<?php print $count; ?>" name="PO_disabled_mobile_list[]" value="<?php print $key; ?>" />
							<input type="button" id="PO-disabled-mobile-list-button-<?php print $count; ?>" class="toggle-button-on" value="On"  onclick="PO_toggle_button('PO-disabled-mobile-list-<?php print $count; ?>', '', 0);" />
							<?php
						}
						?>
					</div>
				<?php } ?>
				<div class="pluginLabel">
					<?php print $plugin['Name']; ?>
				</div>
				<div style="clear: both;"></div>
			</div>
			<?php
		}
		?>
	</div>
	<div style="clear: both;"></div>

	<?php if (sizeOf($groupList) > 0) { ?>
	  <div id="pluginGroupContainer" class="metaBoxContent">
		<div class="pluginListHead">Plugin Groups<?php if(isset($ajaxSaveFunction)) { ?><input type=button name=submit value="Save" onmousedown="<?php print $ajaxSaveFunction; ?>" class="PO-ajax-save-btn"><?php } ?></div>
		<div class="pluginWrap">
			<div class="PO-toggle-container">
				Standard<br />
				<input type="checkbox" id="PO-toggle-all-plugin-groups" name="PO_toggle_all_plugin_groups" value="">
				<input type="button" id="PO-toggle-all-plugin-groups-button" class="group-toggle-button-on" value="On" onclick="PO_toggle_button('PO-toggle-all-plugin-groups', 'group-', 0);PO_toggle_all('PO-toggle-all-plugin-groups', 'PO-disabled-group-list', 0);" />
			</div>
			<?php if (get_option('PO_disable_mobile_plugins') == 1) { ?>
				<div class="PO-toggle-container">
					Mobile<br />
					<input type="checkbox" id="PO-toggle-all-mobile-plugin-groups" name="PO_toggle_all_mobile_plugin_groups" value="">
					<input type="button" id="PO-toggle-all-mobile-plugin-groups-button" class="group-toggle-button-on" value="On" onclick="PO_toggle_button('PO-toggle-all-mobile-plugin-groups-button', 'PO-toggle-all-mobile-plugin-groups', 'group-', 0);PO_toggle_all('PO-toggle-all-mobile-plugin-groups', 'PO-disabled-mobile-group-list', 0);" />
				</div>
			<?php } ?>
			<div style="clear: both;"></div>
		</div>
		<?php
		$count = 0;
		foreach ($groupList as $key=>$group) {
			$count++;
			?>
			<div class="pluginWrap activePluginGroupWrap">
				<div class="PO-toggle-container">
					<?php 
					if ((in_array($group->ID, $globalGroups) && !in_array($group->ID, $enabledGroupList)) || in_array($group->ID, $disabledGroupList)) {
						?>
						<input type="checkbox" class="PO-disabled-group-list" id="PO-disabled-group-list-<?php print $count; ?>" name="PO_disabled_group_list[]" value="<?php print $group->ID; ?>" checked="checked" />
						<input type="button" id="PO-disabled-group-list-button-<?php print $count; ?>" class="toggle-button-off" value="Off"  onclick="PO_toggle_button('PO-disabled-group-list-<?php print $count; ?>', '', 0);" />
						<?php
					} else {
						?>
						<input type="checkbox" class="PO-disabled-group-list" id="PO-disabled-group-list-<?php print $count; ?>" name="PO_disabled_group_list[]" value="<?php print $group->ID; ?>" />
						<input type="button" id="PO-disabled-group-list-button-<?php print $count; ?>" class="toggle-button-on" value="On"  onclick="PO_toggle_button('PO-disabled-group-list-<?php print $count; ?>', '', 0);" />
						<?php
					}
					?>
				</div>
				<?php if (get_option('PO_disable_mobile_plugins') == 1) { ?>
					<div class="PO-toggle-container">
						<?php 
						if ((in_array($group->ID, $globalMobileGroups) && !in_array($group->ID, $enabledMobileGroupList)) || in_array($group->ID, $disabledMobileGroupList)) {
							?>
							<input type="checkbox" class="PO-disabled-mobile-group-list" id="PO-disabled-mobile-group-list-<?php print $count; ?>" name="PO_disabled_mobile_group_list[]" value="<?php print $group->ID; ?>" checked="checked" />
							<input type="button" id="PO-disabled-mobile-group-list-button-<?php print $count; ?>" class="toggle-button-off" value="Off"  onclick="PO_toggle_button('PO-disabled-mobile-group-list-<?php print $count; ?>', '', 0);" />
							<?php
						} else {
							?>
							<input type="checkbox" class="PO-disabled-mobile-group-list" id="PO-disabled-mobile-group-list-<?php print $count; ?>" name="PO_disabled_mobile_group_list[]" value="<?php print $group->ID; ?>" />
							<input type="button" id="PO-disabled-mobile-group-list-button-<?php print $count; ?>" class="toggle-button-on" value="On"  onclick="PO_toggle_button('PO-disabled-mobile-group-list-<?php print $count; ?>', '', 0);" />
							<?php
						}
						?>
					</div>
				<?php } ?>
				<div class="group-name">
					<?php print $group->post_title; ?>
				</div>
				<div class="group-members">
					<?php 
					$groupMembers = get_post_meta($group->ID, "_PO_group_members", $single=true);
					if (is_array($groupMembers)) {
						?>
						<div id="PO-expand-info-<?php print $group->ID; ?>" class="PO-dashicon fa fa-plus-square-o expand-info-icon">
						  Members
						</div>
						<div id="PO-info-container-<?php print $group->ID; ?>" class="PO-info-container">
							<div class="PO-info-inner">
								<?php
								foreach($groupMembers as $plugin) {
									print $plugins[$plugin]['Name'].'<br /><hr>';
								}
								?>
							</div>
						</div>
						<?php
					}
					?>
				</div>
				<div style="clear: both;"></div>
			</div>
			<?php
		}
		?>
	  </div>
	<?php } ?>
<?php } ?>
<div style="clear: both;"></div>
<input type="hidden" name="poSubmitPostMetaBox" value="1" />