<?php




if(isset($_POST['save']) && $_POST['save'] != '') {


	update_option( '_skip_step_2', $_POST['_skip_step_2'] );
	update_option( '_skip_step_2_admin', $_POST['_skip_step_2_admin'] );
} 
else {
	update_option( '_skip_step_2', 0 );
	update_option( '_skip_step_2_admin', 0 );
}

?>
<div class="wrap">

    <div class="icon32 icon32-posts-post" id="icon-edit"><br></div>
    <h2><?php _e('Woocommerce Step Checkout Settings', 'wcs')?>
    </h2>
  <div class="wrap woocommerce">
	<form method="post">
<h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
			<a href="" class="nav-tab nav-tab-active">General</a></h2>

<h3>General Options</h3><table class="form-table">

<tbody>							<tr valign="top" class="">
<th scope="row" class="titledesc">Store Notice</th>
<td class="forminp forminp-checkbox">
<fieldset>
<legend class="screen-reader-text"><span>Allow user to skip form</span></legend>
<label for="skip_step_2">
<input name="_skip_step_2" id="skip_step_2" type="checkbox" value="1" <?php if(get_option( '_skip_step_2') == 1) { echo 'checked'; } ?>> Allow user to skip form</label>
</fieldset>
</td>
</tr>
<tr valign="top" class="">
<th scope="row" class="titledesc">Store Notice</th>
<td class="forminp forminp-checkbox">
<fieldset>
<legend class="screen-reader-text"><span>Allow admin user to skip form</span></legend>
<label for="_skip_step_2_admin">
<input name="_skip_step_2_admin" id="_skip_step_2_admin" type="checkbox" value="1" <?php if(get_option( '_skip_step_2_admin') == 1) { echo 'checked'; } ?>> Allow admin user to skip form</label>
</fieldset>
</td>
</tr>
</tbody></table>
<p class="submit">
							<input name="save" class="button-primary" type="submit" value="Save changes">
						</p>
						
	</form>
</div>

  

</div>
