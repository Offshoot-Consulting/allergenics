<div id="wrap">
    <div class="po-setting-icon fa fa-asterisk" id="icon-po-pt"> <br /> </div>

    <h2 class="po-setting-title">Post Type Plugins</h2>
    
	<div style="clear: both;"></div>
	<p>Select the plugins you would like to disable/enable on the selected post type.
	  <a href="#TB_inline?width=400&height=200&inlineId=PO-pt-help" title="Post Type Plugins" class="thickbox">
	    <span class="dashicons PO-dashicon dashicons-editor-help"></span>
	  </a>
	  <div id="PO-pt-help" class="PO-help">
		<p>
		This will overwrite any settings you have applied to any posts matching this post type.  The settings for individual posts can not be restored once this is done.  You can override these settings on each individual post by checking a checkbox.
		</p>
	  </div>
	</p>
	<div id="PO-pt-settings" class="metabox-holder">
      <div class="PO-loading-container">
		<div>
			<img src="<?php print $this->PO->urlPath . "/image/ajax-loader.gif"; ?>">
		</div>
	  </div>
	  <div id="pluginListdiv" class="stuffbox inside" style="width: 98%">
		<?php
	    $ajaxSaveFunction = "PO_submit_pt_plugins();";
	    require_once('postMetaBox.php');
	    ?>
	  </div>
    </div>
</div>

