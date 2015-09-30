<?php
/**
 * Welcome Page Class
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * SO_Admin_Welcome class
 */
class SO_Admin_Welcome {

	/**
	 * Hook in tabs.
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'admin_menus') );
		add_action( 'admin_head', array( $this, 'admin_head' ) );
		add_action( 'admin_init', array( $this, 'so_welcome' ) );
		add_action( 'admin_footer', array( $this, 'smart_offers_support_ticket_content' ) );
	}

	/**
	 * Add admin menus/screens.
	 */
	public function admin_menus() {

		if ( empty( $_GET['page'] ) ) {
			return;
		}

		$welcome_page_name  = __( 'About Smart Offers', SA_Smart_Offers::$text_domain );
		$welcome_page_title = __( 'Welcome to Smart Offers', SA_Smart_Offers::$text_domain );

		switch ( $_GET['page'] ) {
			case 'so-about' :
				$page = add_dashboard_page( $welcome_page_title, $welcome_page_name, 'manage_options', 'so-about', array( $this, 'about_screen' ) );
			break;
			case 'so-faqs' :
			 	$page = add_dashboard_page( $welcome_page_title, $welcome_page_name, 'manage_options', 'so-faqs', array( $this, 'faqs_screen' ) );
			break;
			case 'so-shortcode' :
				$page = add_dashboard_page( $welcome_page_title, $welcome_page_name, 'manage_options', 'so-shortcode', array( $this, 'shortcode_screen' ) );
			break;
		}
	}

	/**
	 * Add styles just for this page, and remove dashboard page links.
	 */
	public function admin_head() {
		remove_submenu_page( 'index.php', 'so-about' );
		remove_submenu_page( 'index.php', 'so-faqs' );
		remove_submenu_page( 'index.php', 'so-shortcode' );

		?>
		<style type="text/css">
			/*<![CDATA[*/
			.about-wrap h3 {
				margin-top: 1em;
				margin-right: 0em;
				margin-bottom: 0.1em;
				font-size: 1.25em;
				line-height: 1.3em;
			}
			.about-wrap p {
				margin-top: 0.6em;
				margin-bottom: 0.8em;
				line-height: 1.6em;
				font-size: 14px;
			}
			.about-wrap .feature-section {
				padding-bottom: 5px;
			}
			/*]]>*/
		</style>
		<?php
	}

	/**
	 * Smart Offer's Support Form
	 */
	function smart_offers_support_ticket_content() {
            global $sa_smart_offers_upgrade;

            if (!wp_script_is('thickbox')) {
            	if (!function_exists('add_thickbox')) {
                	require_once ABSPATH . 'wp-includes/general-template.php';
            	}
            	add_thickbox();
        	}

            if ( ! method_exists( 'Store_Apps_Upgrade', 'support_ticket_content' ) ) return;

            $prefix = 'smart_offers';
            $sku = 'so';
            $plugin_data = get_plugin_data( SO_PLUGIN_FILE );
            $license_key = get_site_option( $prefix.'_license_key' );
            $text_domain = 'smart_offers';

            Store_Apps_Upgrade::support_ticket_content( $prefix, $sku, $plugin_data, $license_key, $text_domain );
    }

	/**
	 * Intro text/links shown on all about pages.
	 */
	private function intro() {

		if ( is_callable( 'SA_Smart_Offers::get_smart_offers_plugin_data' ) ) {
			$plugin_data = SA_Smart_Offers::get_smart_offers_plugin_data();
			$version = $plugin_data['Version'];
		} else {
			$version = '';
		}

		?>
		<h1><?php printf( __( 'Welcome to Smart Offers %s', SA_Smart_Offers::$text_domain ), $version ); ?></h1>

		<h3><?php echo __( 'Thanks for installing! We hope you enjoy using Smart Offers.', SA_Smart_Offers::$text_domain ); ?></h3>

		<div class="feature-section col two-col">
			<div class="col-1">
				<p class="woocommerce-actions">
					<a href="<?php echo admin_url('edit.php?post_type=smart_offers'); ?>" class="button button-primary"><?php echo __( 'Get Started!', SA_Smart_Offers::$text_domain ); ?></a>
					<a href="<?php echo admin_url('admin.php?page=wc-settings&tab=smart_offers'); ?>" class="button button-primary" target="_blank"><?php echo __( 'Settings', SA_Smart_Offers::$text_domain ); ?></a>
					<a href="<?php echo esc_url( apply_filters( 'smart_offers_docs_url', 'http://www.storeapps.org/support/documentation/smart-offers/', SA_Smart_Offers::$text_domain ) ); ?>" class="docs button button-primary" target="_blank"><?php echo __( 'Docs', SA_Smart_Offers::$text_domain ); ?></a>
				</p>
			</div>

			<div class="col-2 last-feature">
				<p align="right">
					<?php echo __( 'Questions? Need Help?', SA_Smart_Offers::$text_domain ); ?><br>
		            <a class="thickbox" href="<?php echo admin_url('#TB_inline?inlineId=smart_offers_post_query_form&post_type=smart_offers'); ?>">
		          		<?php echo __( 'Contact Us', SA_Smart_Offers::$text_domain ); ?>
		            </a>
				</p>
			</div>
		</div>
		<?php
			if( get_option( 'smart_offers_sample_data_imported' ) != 'yes') {
				?>
				<p>
					<h4><?php echo __( 'Don\'t know, how to create offers? We\'ve created some sample offers for you!', SA_Smart_Offers::$text_domain); ?></h4>
					<a class="button button-primary" href="<?php echo admin_url('index.php?page=so-about&action=import'); ?>">
						<?php echo __( 'Edit & Publish it', SA_Smart_Offers::$text_domain ); ?>
					</a>
				</p>
				<?php
	        }
		?>
		<h2 class="nav-tab-wrapper">
			<a class="nav-tab <?php if ( $_GET['page'] == 'so-about' ) echo 'nav-tab-active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'so-about' ), 'index.php' ) ) ); ?>">
				<?php echo __( 'Know Smart Offers', SA_Smart_Offers::$text_domain ); ?>
			</a>
			<a class="nav-tab <?php if ( $_GET['page'] == 'so-shortcode' ) echo 'nav-tab-active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'so-shortcode' ), 'index.php' ) ) ); ?>">
				<?php echo __( 'Shortcode', SA_Smart_Offers::$text_domain ); ?>
			</a>
			<a class="nav-tab <?php if ( $_GET['page'] == 'so-faqs' ) echo 'nav-tab-active'; ?>" href="<?php echo esc_url( admin_url( add_query_arg( array( 'page' => 'so-faqs' ), 'index.php' ) ) ); ?>">
				<?php echo __( 'FAQ\'s', SA_Smart_Offers::$text_domain ); ?>
			</a>
		</h2>
		<?php
	}

	/**
	 * Output the about screen.
	 */
	public function about_screen() {
		?>
		<div class="wrap about-wrap">

		<?php $this->intro(); ?>

			<div class="changelog">

				<div class="col feature-section"><br>
					<h4><?php echo __( 'What is Smart Offers?', SA_Smart_Offers::$text_domain ); ?></h4>
					<p>
						<?php echo __( 'Smart Offers is a WooCommerce extension that lets you create powerful sales funnels and special offers easily.', SA_Smart_Offers::$text_domain ); ?>
					</p>
					<p>
						<?php echo __( 'Create powerful, profit boosting sales funnels. Sell more to customers while they are making another purchase. Use <code>upsells, cross-sells, downsells, one time offers and backend promotions</code> for targeted customers.', SA_Smart_Offers::$text_domain ); ?>
					</p>
					<p>
						<?php echo __( 'If the customer does not accept your upsell offer, you can give them another offer – either a lower priced downsell or another offer that better suits customer need. You can link as many offers as you want like this..', SA_Smart_Offers::$text_domain ); ?>
					</p>
				</div>
				<div class="feature-section col two-col">
					<div class="col">
						<h4><?php echo __( 'How to create your first new Offer', SA_Smart_Offers::$text_domain ); ?></h4>
						<p><?php echo __( 'To get full insight on How to create a Smart Offer, ', SA_Smart_Offers::$text_domain ); ?>
							<a target="_blank" href="http://www.storeapps.org/support/documentation/smart-offers/create-new-smart-offer/">
								<?php echo __( 'Click Here', SA_Smart_Offers::$text_domain ); ?>
							</a>
						</p>
						<h4><?php echo __( 'Smart Offers in Action', SA_Smart_Offers::$text_domain ); ?></h4>
						<p><?php echo __( 'Want to know how it works before setting up your 1st Offer?', SA_Smart_Offers::$text_domain ); ?>
							<a target="_blank" href="http://www.storeapps.org/support/documentation/smart-offers/smart-offers-action/">
								<?php echo __( 'Click Here', SA_Smart_Offers::$text_domain ); ?>
							</a>
						</p>
						<h4><?php echo __( '<code>1-click upsell</code> using Smart Offers?', SA_Smart_Offers::$text_domain ); ?></h4>
						<p><?php echo __( 'Use', SA_Smart_Offers::$text_domain ); ?>
							<a target="_blank" href="http://www.storeapps.org/product/woocommerce-buy-now/"><?php echo __( 'Buy Now', SA_Smart_Offers::$text_domain ); ?></a>
							<?php echo __( '& Smart Offers together for true <code>1-click upsell</code>. Read more about it here: ', SA_Smart_Offers::$text_domain ); ?>
							<a target="_blank" href="http://www.storeapps.org/awesome-bundle-to-reduce-cart-abandonment/"><?php echo __( 'Awesome Bundle to Reduce Cart Abandonment', SA_Smart_Offers::$text_domain ); ?></a>
						</p>
					</div>
					<div class="col last-feature">
						<h4><?php echo __( 'More Help', SA_Smart_Offers::$text_domain ); ?></h4>
						<p><?php echo __( 'How to ', SA_Smart_Offers::$text_domain ); ?>
							<a target="_blank" href="http://www.storeapps.org/support/documentation/smart-offers/how-to-create-tempting-backend-offers-your-customers-just-cant-resist/">
							<?php echo __( 'create tempting backend offers', SA_Smart_Offers::$text_domain ); ?></a>
							<?php echo __( "your customer's just can't resist!!", SA_Smart_Offers::$text_domain); ?>
						</p>
						<p><?php echo __( 'Create  ', SA_Smart_Offers::$text_domain ); ?>
							<a target="_blank" href="http://www.storeapps.org/support/documentation/smart-offers/history-matters-woocommerce-up-sells-cross-sells-based-on-customers-purchase-history-using-smart-offers/">
							<?php echo __( "special offers based on customer's purchase history", SA_Smart_Offers::$text_domain ); ?></a>
							<?php echo __( "to win loyalty and raise profits.", SA_Smart_Offers::$text_domain); ?>
						</p>
						<p><a target="_blank" href="http://www.storeapps.org/support/documentation/smart-offers/cart-page-the-best-place-to-gain-loyalty-and-close-more-deals/">
							<?php echo __( "Cart Notices and offers on Cart Page with Smart Offers", SA_Smart_Offers::$text_domain ); ?></a>
							<?php echo __( "- the best place to gain loyalty and close more deals.", SA_Smart_Offers::$text_domain); ?>
						</p>
					</div>
				</div>
			</div>
			<div class="changelog" align="center">
				<h4><?php echo __( 'Do check out Some of our other products!', SA_Smart_Offers::$text_domain ); ?></h4>
				<p><a target="_blank" href="<?php echo esc_url('http://www.storeapps.org/shop/'); ?>"><?php echo __( 'Let me take to product catalog', SA_Smart_Offers::$text_domain ); ?></a></p>
			</div>
		</div>
		<?php
	}

	/**
	 * Output the shortcode reference screen.
	 */
	public function shortcode_screen() {
		?>
		<div class="wrap about-wrap">

		<?php $this->intro(); ?>

        <h3><?php echo __( "Smart Offer has a few custom shortcodes that you can use anywhere in posts, pages and even widgets.", SA_Smart_Offers::$text_domain ); ?></h3>

		<div class="feature-section col two-col">
			<div class="col">
				<h4>
					<?php echo __( '1) ', SA_Smart_Offers::$text_domain ) ?>
					<code><?php echo __( '[so_show_offers]', SA_Smart_Offers::$text_domain ); ?></code>
				</h4>
				<p>
					<?php echo __( 'In addition to default page option i.e Home, Cart, Checkout, Order Received, My Account, you can show offer on any other page with the use of shortcode <code>[so_show_offers]</code>', SA_Smart_Offers::$text_domain ); ?>
				</p>
				<p><b>
					<?php echo __( 'Shortcode Attributes', SA_Smart_Offers::$text_domain ); ?>
				</b></p>
				<table class="wp-list-table widefat striped">
            		<thead>
        				<tr>
        					<th><?php echo __( 'Attributes', SA_Smart_Offers::$text_domain ); ?></th>
        					<th><?php echo __( 'Values', SA_Smart_Offers::$text_domain ); ?></th>
        					<th><?php echo __( 'Description', SA_Smart_Offers::$text_domain ); ?></th>
        				</tr>
            		</thead>
        			<tbody>
        				<tr>
							<td><code><?php echo __( 'display_as', SA_Smart_Offers::$text_domain ); ?></code></td>
							<td><code><?php echo __( 'inline', SA_Smart_Offers::$text_domain ); ?></code> / <code><?php echo __( 'popup', SA_Smart_Offers::$text_domain); ?></code></td>
							<td><?php echo __( 'It determines how to display the offer. If no value is passed then value would be taken from the option saved in “Which page/pages to show offer on -> Show offer as” of the offer that will be shown.', SA_Smart_Offers::$text_domain ); ?></td>
						</tr>
						<tr>
							<td><code><?php echo __( 'offer_ids', SA_Smart_Offers::$text_domain ); ?></code></td>
							<td><?php echo __( 'Comma separated offer_ids', SA_Smart_Offers::$text_domain ); ?></td>
							<td><?php echo __( 'It will show one of the offer from the ids mentioned in this argument. If no value is passed, then Smart Offers will fetch all offers having option “Any other page where shortcode is added” ticked under “Which page/pages to show offer on -> Show offer on” and show one of the offer satisfying Offer rules and Smart Offers Settings.', SA_Smart_Offers::$text_domain ); ?></td>
						</tr>
        			</tbody>
        		</table>
        		<p>
					<?php echo __( 'For example,', SA_Smart_Offers::$text_domain ); ?>
				</p>
				<p><code><?php echo __( '[so_show_offers]', SA_Smart_Offers::$text_domain ); ?></code><br>
				   <code><?php echo __( '[so_show_offers display_as="popup" offer_ids="1,2,3"]', SA_Smart_Offers::$text_domain ); ?></code>
				</p>
			</div>
			<div class="col last-feature">
				<h4>
					<?php echo __( '2) ', SA_Smart_Offers::$text_domain ) ?>
					<code><?php echo __( '[so_quantity]', SA_Smart_Offers::$text_domain ); ?></code>
				</h4>
				<p>
					<?php echo __( 'Shortcode <code>[so_quantity]</code> will show quantity box in the offer, allowing your customer to select the quantity of the offered product.', SA_Smart_Offers::$text_domain ); ?>
				</p>
				<p><b>
					<?php echo __( 'Shortcode Attributes', SA_Smart_Offers::$text_domain ); ?>
				</b></p>
				<table class="wp-list-table widefat striped">
            		<thead>
        				<tr>
        					<th><?php echo __( 'Attributes', SA_Smart_Offers::$text_domain ); ?></th>
        					<th><?php echo __( 'Values', SA_Smart_Offers::$text_domain ); ?></th>
        					<th><?php echo __( 'Description', SA_Smart_Offers::$text_domain ); ?></th>
        				</tr>
            		</thead>
        			<tbody>
        				<tr>
							<td><code><?php echo __( 'value', SA_Smart_Offers::$text_domain ); ?></code></td>
							<td><?php echo __( '-', SA_Smart_Offers::$text_domain ); ?></td>
							<td><?php echo __( 'It defines what should be the quantity value. Default is 1.', SA_Smart_Offers::$text_domain ); ?></td>
						</tr>
						<tr>
							<td><code><?php echo __( 'min', SA_Smart_Offers::$text_domain ); ?></code></td>
							<td><?php echo __( '-', SA_Smart_Offers::$text_domain ); ?></td>
							<td><?php echo __( 'It defines what should be the minimum quantity that your customer can select. Default is 1.', SA_Smart_Offers::$text_domain ); ?></td>
						</tr>
						<tr>
							<td><code><?php echo __( 'max', SA_Smart_Offers::$text_domain ); ?></code></td>
							<td><?php echo __( '-', SA_Smart_Offers::$text_domain ); ?></td>
							<td><?php echo __( 'It defines what should be the maximum quantity that your customer can select.', SA_Smart_Offers::$text_domain ); ?></td>
						</tr>
						<tr>
							<td><code><?php echo __( 'allow_change', SA_Smart_Offers::$text_domain ); ?></code></td>
							<td><code><?php echo __( 'true', SA_Smart_Offers::$text_domain ); ?></code> / <code><?php echo __( 'false', SA_Smart_Offers::$text_domain); ?></code></td>
							<td><?php echo __( 'It defines whether you want to allow your customer to change the quantity or not and indirectly determining whether to show quantity in the offer or not. Default is false.', SA_Smart_Offers::$text_domain ); ?></td>
						</tr>
        			</tbody>
        		</table>
        		<p>
					<?php echo __( 'For example,', SA_Smart_Offers::$text_domain ); ?>
				</p>
				<p><code><?php echo __( '[so_quantity allow_change=true]', SA_Smart_Offers::$text_domain ); ?></code><br>
				   <code><?php echo __( '[so_quantity value=2 max=10 allow_change=true]', SA_Smart_Offers::$text_domain ); ?></code><br>
				   <code><?php echo __( '[so_quantity min=2 allow_change=true max=6]', SA_Smart_Offers::$text_domain ); ?></code>
				</p>
			</div>
		</div>

		<div class="feature-section col two-col">
			<div class="col">
				<h4>
					<?php echo __( '3) ', SA_Smart_Offers::$text_domain ) ?>
					<code><?php echo __( '[so_product_variants]', SA_Smart_Offers::$text_domain ); ?></code>
				</h4>
				<p>
					<?php echo __( 'Shortcode <code>[so_product_variants]</code> will show the variation option for the parent variable product.', SA_Smart_Offers::$text_domain ); ?>
				</p>
				<p>
					<?php echo __( 'If you want to let your customer to select which variation product they would want as an offer, then add parent variable product in Offered product and shortcode <code>[so_product_variants]</code> in the offer description.', SA_Smart_Offers::$text_domain ); ?>
				</p>
				<p>
					<?php echo __( 'For example,', SA_Smart_Offers::$text_domain ); ?>
				</p>
				<p><code>
					<?php echo __( '[so_product_variants]', SA_Smart_Offers::$text_domain ); ?>
				</code></p>
			</div>
			<div class="col last-feature">
				<h4>
					<?php echo __( '4) ', SA_Smart_Offers::$text_domain ) ?>
					<code><?php echo __( '[so_product_image]', SA_Smart_Offers::$text_domain ); ?></code>
				</h4>
				<p>
					<?php echo __( 'Shortcode <code>[so_product_image]</code> will show default image of the product image in the offer.', SA_Smart_Offers::$text_domain ); ?>
				</p>
				<table class="wp-list-table widefat striped">
            		<thead>
        				<tr>
        					<th><?php echo __( 'Attribute', SA_Smart_Offers::$text_domain ); ?></th>
        					<th><?php echo __( 'Default Value', SA_Smart_Offers::$text_domain ); ?></th>
        				</tr>
            		</thead>
        			<tbody>
        				<tr>
							<td><code><?php echo __( 'image', SA_Smart_Offers::$text_domain ); ?></code></td>
							<td><code><?php echo __( 'yes', SA_Smart_Offers::$text_domain ); ?> </code></td>
						</tr>
        			</tbody>
        		</table>
				<p>
					<?php echo __( 'For example,', SA_Smart_Offers::$text_domain ); ?>
				</p>
				<p><code><?php echo __( '[so_product_image image="yes"]', SA_Smart_Offers::$text_domain ); ?></code><br>
				   <code><?php echo __( '[so_product_image]', SA_Smart_Offers::$text_domain ); ?></code>
				</p>
			</div>
		</div>

		<div class="feature-section col two-col">
			<div class="col">
				<h4>
					<?php echo __( '5) ', SA_Smart_Offers::$text_domain ) ?>
					<code><?php echo __( '[so_price]', SA_Smart_Offers::$text_domain ); ?></code>
				</h4>
				<p>
					<?php echo __( 'Shortcode <code>[so_price]</code> will show the original price & the new price of the offered product in the offer.', SA_Smart_Offers::$text_domain ); ?>
				</p>
				<p>
					<?php echo __( 'For example,', SA_Smart_Offers::$text_domain ); ?>
				</p>
				<p><code>
						<?php echo __( '[so_price]', SA_Smart_Offers::$text_domain ); ?>
				</code></p>
				<p>
					<?php echo __( '<b>Note</b>: This shortcode is only for offered products having Simple products. For Variable products, it is by default included i.e. you do not need to write this shortcode for Variable offered products.', SA_Smart_Offers::$text_domain ); ?>
				</p>
			</div>
			<div class="col last-feature">
				<h4>
					<?php echo __( '6) ', SA_Smart_Offers::$text_domain ) ?>
					<code><?php echo __( '[so_acceptlink]', SA_Smart_Offers::$text_domain ); ?></code>
				</h4>
				<p>
					<?php echo __( 'Shortcode <code>[so_acceptlink]</code> will generate an accept url for the offer.' ); ?>
				</p>
				<p>
					<?php echo __( 'For example,', SA_Smart_Offers::$text_domain ); ?>
				</p>
				<p><code>
						<?php echo __( '[so_acceptlink]', SA_Smart_Offers::$text_domain ); ?>
				</code></p>
			</div>
		</div>

		<div class="feature-section col two-col">
			<div class="col">
				<h4>
					<?php echo __( '7) ', SA_Smart_Offers::$text_domain ) ?>
					<code><?php echo __( '[so_skiplink]', SA_Smart_Offers::$text_domain ); ?></code>
				</h4>
				<p>
					<?php echo __( 'Shortcode <code>[so_skiplink]</code> will generate an skip url for the offer.' ); ?>
				</p>
				<p>
					<?php echo __( 'For example,', SA_Smart_Offers::$text_domain ); ?>
				</p>
				<p><code>
						<?php echo __( '[so_skiplink]', SA_Smart_Offers::$text_domain ); ?>
				</code></p>
			</div>
			<div class="col last-feature">
			</div>
        </div>
        <?php
	}

	/**
	 * Output the FAQ's screen.
	 */
	public function faqs_screen() {
		?>
		<div class="wrap about-wrap">

		<?php $this->intro(); ?>

		<h3><?php echo __("FAQ / Common Problems", SA_Smart_Offers::$text_domain); ?></h3>

		<?php
        	$faqs = array(
        				array(
        						'que' => __( 'Is it possible to change the text & look of “Yes, Add to Cart” and “No, I want to skip” ?', SA_Smart_Offers::$text_domain ),
        						'ans' => sprintf(__( 'Yes, it is possible to change the text and look of the “Yes, Add to Cart” and “No, I want to skip”. The text can be changed in the Offer Description and style can be changed by going to %s', SA_Smart_Offers::$text_domain ), '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=smart_offers' ) . '" target="_blank">' . __( 'Settings', SA_Smart_Offers::$text_domain ) . '</a>' )
        					),
        				array(
        						'que' => __( 'Are there any pre-defined button styles for Add to Cart links?', SA_Smart_Offers::$text_domain ),
        						'ans' => sprintf(__( 'Yes, Smart Offers gives you 3 pre-defined styles for Add to Cart links. You can find it in Smart Offers global %s', SA_Smart_Offers::$text_domain ), '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=smart_offers' ) . '" target="_blank">' . __( 'Settings', SA_Smart_Offers::$text_domain ) . '</a>' )
        					),
        				array(
        						'que' => __( 'Is Smart Offers WPML (Multilingual usage) compatible ?', SA_Smart_Offers::$text_domain ),
        						'ans' => __( 'Yes, Smart Offers is WPML compatible.', SA_Smart_Offers::$text_domain )
        					),
        				array(
        						'que' => __( 'I have setup offer to display as Popup but it shows as an Inline Offer.', SA_Smart_Offers::$text_domain ),
        						'ans' => __( 'Have you selected multiple offers to display on that same one page? If yes, then it is behaving correctly i.e. when you wish to show multiple offers all offers will be shown “Inline”', SA_Smart_Offers::$text_domain )
        					),
        				array(
        						'que' => __( 'How can I check Conversion rate of my offers?', SA_Smart_Offers::$text_domain ),
        						'ans' => __( 'You can know how much money you are making from your Offers from Smart Offers Dashboard widget on dashboard page. Smart Offers Dashboard shows you the statistics of how many times an offer was shown, accepted, skipped and paid through. It also tells you the conversion rate for offers', SA_Smart_Offers::$text_domain )
        					),
        				array(
        						'que' => __( 'I have added shortcode <code>[so_quantity]</code> but quantity box doesn\'t appear in offer.', SA_Smart_Offers::$text_domain ),
        						'ans' => __( 'That is because you haven\'t added any parameter to the shortcode. To show quantity in the offer, add <code>[so_quantity allow_change=true]</code>, this will show quantity box in the offer.', SA_Smart_Offers::$text_domain )
        					),
        				array(
        						'que' => __( 'I can\'t find a way to do X...', SA_Smart_Offers::$text_domain ),
        						'ans' => __( 'Smart Offers is actively developed. If you can\'t find your favorite feature (or have a suggestion) contact us. We\'d love to hear from you.', SA_Smart_Offers::$text_domain )
        					)
           			);

				$faqs = array_chunk( $faqs, 2 );

				echo '<div>';
				foreach ( $faqs as $fqs ) {
					echo '<div class="two-col">';
					foreach ( $fqs as $index => $faq ) {
						echo '<div' . ( ( $index == 1 ) ? ' class="col last-feature"' : ' class="col"' ) . '>';
						echo '<h4>' . $faq['que'] . '</h4>';
						echo '<p>' . $faq['ans'] . '</p>';
						echo '</div>';
					}
					echo '</div>';
				}
				echo '</div>';
	    	?>

			</div>
		
		<?php
	}

	/**
	 * Sends user to the welcome page on activation.
	 */
	public function so_welcome() {

       	if ( ! get_transient( '_so_activation_redirect' ) ) {
			return;
		}
		
		// Delete the redirect transient
		delete_transient( '_so_activation_redirect' );

		wp_redirect( admin_url( 'index.php?page=so-about' ) );
		exit;

	}
}

new SO_Admin_Welcome();