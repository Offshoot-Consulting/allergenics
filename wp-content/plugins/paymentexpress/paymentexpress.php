<?php
/*
Plugin Name: Payment Express PX Fusion
Plugin URI: http://www.syedaliahmad.com/
Version: 1.0
Author: Syed Ali Ahmad
Description: Implementation of the PXfusion Gateway method of Payment Express
*/

define('PAYMENTEXPRESS_PAY_NOW_SHORTCODE', 'paymentexpress_pay_now');


function paymentexpress_pay_now_shortcode($attrs, $content='') {

	$fld_user_name = 'mt_user_name';
	$fld_pwd = 'mt_pwd';	
    	$fld_ccform_url = 'mt_ccform_url';
	$fld_succ_url = 'mt_succ_url';	
	$fld_fail_url = 'mt_fail_url';	
	
	 $fld_user_name_val = get_option( $fld_user_name );
 	 $fld_pwd_val = get_option( $fld_pwd );
 	 $fld_ccform_url_val = get_option( $fld_ccform_url );
	$fld_succ_url_val = get_option( $fld_succ_url);	
	$fld_fail_url_val = get_option( $fld_fail_url );
         $frmelements='<input type="hidden" name="payexpuser" value="'.$fld_user_name_val.'"><input type="hidden" name="payexppwd" value="'.$fld_pwd_val.'"><input type="hidden" name="ccardurl" value="'.$fld_ccform_url_val.'"><input type="hidden" name="succurl" value="'.$fld_succ_url_val .'"><input type="hidden" name="failurl" value="'.$fld_fail_url_val.'">';
// return '<form name="yahoo" action="'.plugins_url().'/paymentexpress/start.php" method="POST"><button type="submit" name="pay"  style="">Pay by Payment Express</button></FORM>';
	 return '<form name="yahoo" action="'.plugins_url().'/paymentexpress/start.php" method="POST">'.$frmelements.'<button type="submit" name="pay"  style="">Pay by Payment Express</button></FORM>';
}

add_shortcode(PAYMENTEXPRESS_PAY_NOW_SHORTCODE, 'paymentexpress_pay_now_shortcode');

/** Step 2 (from text above). */
add_action( 'admin_menu', 'my_plugin_menu' );

/** Step 1. */
function my_plugin_menu() {
	add_options_page( 'Payment Express Plugin Options', 'Payment Express', 'manage_options', 'payment-express', 'my_plugin_options' );
}

/** Step 3. */
function my_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	//echo '<div class="wrap">';
	//echo '<p>Here is where the form would go if I actually had options.</p>';
	//echo '</div>';
	$fld_user_name = 'mt_user_name';
	$fld_pwd = 'mt_pwd';	
	$fld_user_name2 = 'mt_user_name2';
	$fld_pwd2 = 'mt_pwd2';
	
	$fld_ccform_url = 'mt_ccform_url';
	$fld_succ_url = 'mt_succ_url';	
	$fld_fail_url = 'mt_fail_url';		
	
	
	$fld_user_name_val = get_option( $fld_user_name );
 	$fld_pwd_val = get_option( $fld_pwd );
 	$fld_user_name_val2 = get_option( $fld_user_name2 );
 	$fld_pwd_val2 = get_option( $fld_pwd2 );
 	
 	$fld_ccform_url_val = get_option( $fld_ccform_url );
	$fld_succ_url_val = get_option( $fld_succ_url);	
	$fld_fail_url_val = get_option( $fld_fail_url );		

 	 
 	 
 	 
 	 
 	 if (isset($_POST[$fld_user_name]) && isset($_POST[$fld_pwd])&& isset($_POST[$fld_user_name2]) && isset($_POST[$fld_pwd2])){
 	 
		$fld_user_name_val = $_POST[ $fld_user_name ];
		$fld_pwd_val = $_POST[ $fld_pwd ];
		$fld_user_name_val2 = $_POST[ $fld_user_name2 ];
		$fld_pwd_val2 = $_POST[ $fld_pwd2 ];
		
		$fld_ccform_url_val = $_POST[$fld_ccform_url];
		$fld_succ_url_val =  $_POST[$fld_succ_url];	
		$fld_fail_url_val = $_POST[$fld_fail_url];
		
		
		// Save the posted value in the database
		update_option( $fld_user_name, $fld_user_name_val );
		update_option( $fld_pwd,  $fld_pwd_val );
		update_option( $fld_user_name2, $fld_user_name_val2 );
		update_option( $fld_pwd2,  $fld_pwd_val2 );
		
		
		update_option($fld_ccform_url, $fld_ccform_url_val );
		update_option($fld_succ_url, $fld_succ_url_val );
		update_option($fld_fail_url,$fld_fail_url_val );
		// Put an settings updated message on the screen
		
?>
		<div class="updated"><p><strong><?php _e('settings saved.', 'payment-express' ); ?></strong></p></div>
<?php

    }
 	 
 	 
 	 
 	 
	
	 echo '<div class="wrap">';

    // header

    echo "<h2>" . __( 'Payment Express Plugin Configuration', 'payment-express' ) . "</h2>";

	 ?>
	
	 
	 <form name="form1" method="post" action="">
<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

<p><?php _e("Payment Express User Name (FUSION):", 'payment-express' ); ?> 
<input type="text" name="<?php echo $fld_user_name; ?>" value="<?php echo  $fld_user_name_val; ?>" size="20">
</p><hr />
<p><?php _e("Payment Express Password (FUSION):", 'payment-express' ); ?> 
<input type="text" name="<?php echo $fld_pwd; ?>" value="<?php echo  $fld_pwd_val; ?>" size="20">
</p><hr />

<p><?php _e("Payment Express User Name (A2A):", 'payment-express' ); ?> 
<input type="text" name="<?php echo $fld_user_name2; ?>" value="<?php echo  $fld_user_name_val2; ?>" size="20">
</p><hr />
<p><?php _e("Payment Express Password (A2A):", 'payment-express' ); ?> 
<input type="text" name="<?php echo $fld_pwd2; ?>" value="<?php echo  $fld_pwd_val2; ?>" size="20">
</p><hr />




<p><?php _e("Credit Card Form URL:", 'payment-express' ); ?> 
<input type="text" name="<?php echo $fld_ccform_url; ?>" value="<?php echo  $fld_ccform_url_val; ?>" size="20">
</p><hr />
<p><?php _e("Success URL:", 'payment-express' ); ?> 
<input type="text" name="<?php echo $fld_succ_url; ?>" value="<?php echo  $fld_succ_url_val; ?>" size="20">
</p><hr />
<p><?php _e("Failure URL:", 'payment-express' ); ?> 
<input type="text" name="<?php echo $fld_fail_url; ?>" value="<?php echo  $fld_fail_url_val; ?>" size="20">
</p><hr />
<p class="submit">
<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
</p>

</form>
</div>
<?php
	 
	
}

//add_action('plugins_loaded', 'paymentexpress_init');

?>