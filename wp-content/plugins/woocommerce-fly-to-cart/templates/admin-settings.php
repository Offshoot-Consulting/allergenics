<?php
if (!defined('ABSPATH')) die();
$opts = OrakAtcEffects::$options;
?>
<style>
	.wrap {
		font-size:15px;
	}
	#use_default_selector:checked ~ #custom_selector {
		display:none;
	}	
	#custom_selector_update {
		display:none;
	}
	#custom_selector_cb:checked ~ #custom_selector_update {
		display:block;
	}
	
	#use_default_gallery_image:checked ~ #gallery_image_selector {
		display:block;
	}
	
	#gallery_image_selector {
		display:none;
	}

</style>
<div class="wrap">
	<h2><?php echo __('Fly to cart effect settings', OrakAtcEffects::TEXTDOMAIN); ?></h2>
	<form method="post" action="options.php">
		<?php echo settings_fields(OrakAtcEffects::OPTION_GROUP); ?>
		
		<h3><?php echo __('Cart button/link configurations', OrakAtcEffects::TEXTDOMAIN); ?></h3><hr/>
		<input type="checkbox" <?php if(isset($opts['use_default_selector'])) echo "checked='checked'"; ?> id="use_default_selector" name="<?php echo OrakAtcEffects::OPTION_NAME; ?>[use_default_selector]" value="1"/>
		<label for="use_default_selector"><?php echo __('Let the plugin detect my cart button automatically (recommended).', OrakAtcEffects::TEXTDOMAIN); ?></label> 
		<br/><br/>
		<div id="custom_selector">
		<?php echo __('(Advanced) Insert your custom selector here: ', OrakAtcEffects::TEXTDOMAIN); ?><input type="text" name="<?php echo OrakAtcEffects::OPTION_NAME; ?>[custom_selector]" value="<?php echo $opts['custom_selector']; ?>" style="width:120px;"/> (ex. #my-custom-cart, #cart-btn, etc)
		<br/><br/>
		</div>
		
		<?php echo __('Use the', OrakAtcEffects::TEXTDOMAIN); ?>
		<select name="<?php echo OrakAtcEffects::OPTION_NAME; ?>[element_num]">
			<option value="1" <?php if($opts['element_num'] == 1): ?>selected="selected"<?php endif; ?>><?php echo __('1st', OrakAtcEffects::TEXTDOMAIN); ?></option>
			<option value="2" <?php if($opts['element_num'] == 2): ?>selected="selected"<?php endif; ?>><?php echo __('2nd', OrakAtcEffects::TEXTDOMAIN); ?></option>
			<option value="3" <?php if($opts['element_num'] == 3): ?>selected="selected"<?php endif; ?>><?php echo __('3th', OrakAtcEffects::TEXTDOMAIN); ?></option>
			<option value="4" <?php if($opts['element_num'] == 4): ?>selected="selected"<?php endif; ?>><?php echo __('4th', OrakAtcEffects::TEXTDOMAIN); ?></option>
			<option value="5" <?php if($opts['element_num'] == 5): ?>selected="selected"<?php endif; ?>><?php echo __('5th', OrakAtcEffects::TEXTDOMAIN); ?></option>
		</select> <?php echo __('found cart button/link', OrakAtcEffects::TEXTDOMAIN); ?>.
		<br/><br/>
		
		<h3><?php echo __('"Fly" effect configurations', OrakAtcEffects::TEXTDOMAIN); ?></h3><hr/>
		<?php echo __('If the product doesn\'t "arrive" exactly in the cart, you can modify the x/y offsets here: ', OrakAtcEffects::TEXTDOMAIN); ?><br/><br/>
		<?php echo __('Offset X: ', OrakAtcEffects::TEXTDOMAIN); ?><input type="text" name="<?php echo OrakAtcEffects::OPTION_NAME; ?>[arrive_offset_x]" value="<?php echo $opts['arrive_offset_x']; ?>" style="width:60px;"/> px<br/>
		<?php echo __('Offset Y: ', OrakAtcEffects::TEXTDOMAIN); ?><input type="text" name="<?php echo OrakAtcEffects::OPTION_NAME; ?>[arrive_offset_y]" value="<?php echo $opts['arrive_offset_y']; ?>" style="width:60px;"/> px<br/>
		<hr/>
		<input type="text" name="<?php echo OrakAtcEffects::OPTION_NAME; ?>[flying_speed]" value="<?php echo $opts['flying_speed']; ?>" style="width:60px;"/> <strong>ms</strong> - <?php echo __('"<strong>Flying</strong>" speed in milliseconds (1s=1000ms).', OrakAtcEffects::TEXTDOMAIN); ?>
		<br/><br/>
		<input type="text" name="<?php echo OrakAtcEffects::OPTION_NAME; ?>[flying_img_opacity]" value="<?php echo $opts['flying_img_opacity']; ?>" style="width:60px;" maxlength="3"  /> <strong>%</strong>  - <?php echo __('The opacity of the product image while "<strong>flying</strong>". Please enter a value between <strong>1</strong> and <strong>100</strong>.', OrakAtcEffects::TEXTDOMAIN); ?>
		<br/><br/>

		<input type="checkbox" <?php if(isset($opts['use_blink_effect'])) echo "checked='checked'"; ?> id="use_blink_effect" name="<?php echo OrakAtcEffects::OPTION_NAME; ?>[use_blink_effect]" value="1"/>

		<label for="use_blink_effect"><?php echo __('Use "blink" effect to highlight the cart button after the product has been added.', OrakAtcEffects::TEXTDOMAIN); ?></label> 
		
		<br/><br/>

		<input type="checkbox" <?php if(isset($opts['scroll_on_add'])) echo "checked='checked'"; ?> id="scroll_on_add" name="<?php echo OrakAtcEffects::OPTION_NAME; ?>[scroll_on_add]" value="1"/>

		<label for="scroll_on_add"><?php echo __('Scroll up the page after the product has been added.', OrakAtcEffects::TEXTDOMAIN); ?></label> 

		<br/><br/>
		
		<input type="checkbox" <?php if(isset($opts['use_woocommerce_widget'])) echo "checked='checked'"; ?> id="use_woocommerce_widget" name="<?php echo OrakAtcEffects::OPTION_NAME; ?>[use_woocommerce_widget]" value="1"/>

		<label for="use_woocommerce_widget"><?php echo __('Update WooCommerce\'s cart widget.', OrakAtcEffects::TEXTDOMAIN); ?></label> 

		<br/><br/>
		<h3><?php echo __('Other configurations', OrakAtcEffects::TEXTDOMAIN); ?></h3><hr/>
		<?php echo __('Use update area level', OrakAtcEffects::TEXTDOMAIN); ?> #		
		<select name="<?php echo OrakAtcEffects::OPTION_NAME; ?>[update_level]">
			<option value="1" <?php if($opts['update_level'] == 1): ?>selected="selected"<?php endif; ?>>1</option>
			<option value="2" <?php if($opts['update_level'] == 2): ?>selected="selected"<?php endif; ?>>2</option>
			<option value="3" <?php if($opts['update_level'] == 3): ?>selected="selected"<?php endif; ?>>3</option>
		</select> &nbsp; 
		<i><u><?php echo __('Just play with these values (the levels) if you don\'t like the area that blinks/updates.', OrakAtcEffects::TEXTDOMAIN); ?></u></i>
		<br/>
		<strong> or... </strong>
		<br/><br/>
		<input type="checkbox" <?php if(isset($opts['custom_selector_cb'])) echo "checked='checked'"; ?> id="custom_selector_cb" name="<?php echo OrakAtcEffects::OPTION_NAME; ?>[custom_selector_cb]" /> <label for="custom_selector_cb"><?php echo __('I\'ll use my own selector for the blinking/updating part', OrakAtcEffects::TEXTDOMAIN); ?></label>
		<div id="custom_selector_update">
		<?php echo __('Insert your update selector here: ', OrakAtcEffects::TEXTDOMAIN); ?> <input type="text" name="<?php echo OrakAtcEffects::OPTION_NAME; ?>[custom_selector_update]" value="<?php echo $opts['custom_selector_update']; ?>" style="width:180px;"/> (ex. #my-custom-cart, #cart-btn, etc)
		</div>
		<br/>
		<hr/><br/>
		
		<input type="checkbox" <?php if(isset($opts['use_default_gallery_image'])) echo "checked='checked'"; ?> id="use_default_gallery_image" name="<?php echo OrakAtcEffects::OPTION_NAME; ?>[use_default_gallery_image]" value="1"/>

		<label for="use_default_gallery_image"><?php echo __('Use custom gallery image selector (recommended/must for custom product image galleries)', OrakAtcEffects::TEXTDOMAIN); ?></label> 

		
		<br/><br/>

		<div id="gallery_image_selector">

		<?php echo __('Insert your gallery image selector here: ', OrakAtcEffects::TEXTDOMAIN); ?><input type="text" name="<?php echo OrakAtcEffects::OPTION_NAME; ?>[gallery_image_selector]" value="<?php echo $opts['gallery_image_selector']; ?>" style="width:120px;"/> (ex. img.product_gallery-custom, .active-slide img )

		<br/><br/>

		</div>
		
		<hr/>
		
		<input type="checkbox" <?php if(isset($opts['use_product_grabber_2'])) echo "checked='checked'"; ?> id="use_product_grabber_2" name="<?php echo OrakAtcEffects::OPTION_NAME; ?>[use_product_grabber_2]" value="1"/>

		<label for="use_product_grabber_2"><?php echo __('Use an alternative method of grabbing the product data.', OrakAtcEffects::TEXTDOMAIN); ?></label> 

		<br/><br/>
		<div style="border-left:3px solid orange; padding:10px; line-height:20px; width:95%;background:#fff;">
		<h3>When to enable the "alternative method"?</h3>
			<ol>
				<li>When you're experiencing issues with your 3rd party plugins.</li>
				<li>When you have issues with products out of stock.</li>
				<li>You can always try this alternative variant, when you have other issues than #1 and #2.</li>
			</ol>
		</div>
		<br/><br/>
		<input type="submit" name="save" value="<?php echo __('Save', OrakAtcEffects::TEXTDOMAIN); ?>" class='button-primary'/>		<br/><hr/></br>
		
		<div style="border-left:3px solid cyan; background:#fafafa;padding:3px 3px 20px 15px;">
			<h4>Having trouble configuring our "WooCommerce Fly to Cart + Ajax add to cart" plugin?</h4>
			Just send us a quick email through <a target="_blank" href="http://codecanyon.net/item/woocommerce-fly-to-cart-effect-ajax-add-to-cart/10058071/support">CodeCanyon's support form</a> and we will be more than happy to configure it for you.
		</div>
	</form>
</div>