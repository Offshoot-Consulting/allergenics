<?php
include_once('front_template.php');
$obj= new Frontpage();
$obj->checkLogin();

if(isset($_GET['key']) && $_GET['key'] !='') {
$_SESSION['checkout'] = 'Done';
if(get_option( '_skip_ga_ecommerce') != 1) {
  $url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
    $template_name = strpos($url,'/order-received/') === false ? '/view-order/' : '/order-received/';
    if (strpos($url,$template_name) !== false) {
        $start = strpos($url,$template_name);
        $first_part = substr($url, $start+strlen($template_name));
        $order_id = substr($first_part, 0, strpos($first_part, '/'));
       

        //yes, I can retrieve the order via the order id
        $order = new WC_Order($order_id);
        $items_details = $order->get_items();
        $items = array();
        $i = 0;
        foreach ( $items_details as $item ) {

            //print_r($item);
            $product = $order->get_product_from_item( $item ); 
          $product_id = $item['product_id'];
          $term_list = wp_get_post_terms($product_id,'product_cat',array('fields'=>'ids'));
$cat_id = (int)$term_list[0];
$category = get_term ($cat_id, 'product_cat');
         
          $items[$i]['name'] = $item['name'];
          
          $items[$i]['quantity'] = $item['qty'];
          
          $items[$i]['sku'] = $product->get_sku();
         
          $items[$i]['price'] = $product->get_price();

          $items[$i]['category'] = $category->name;
         
         
          $product_variation_id = $item['variation_id'];
          // etc
       $i++; }
        
        $amount = $order->get_total();
        
        $affliation = 'Order '.$order_id.' details';
        
        $order_shipping_total = $order->get_total_shipping();
        
        $order_cart_tax = $order->get_cart_tax();
        //echo '<pre>'; print_r($order);
         // Transaction Data
        $trans = array('id'=>$order_id, 'affiliation'=>$affliation,'revenue'=>$amount, 'shipping'=>$order_shipping_total, 'tax'=>$order_cart_tax);
       
        // Function to return the JavaScript representation of a TransactionData object.
function getTransactionJs(&$trans) {
  return <<<HTML
ga('ecommerce:addTransaction', {
  'id': '{$trans['id']}',
  'affiliation': '{$trans['affiliation']}',
  'revenue': '{$trans['revenue']}',
  'shipping': '{$trans['shipping']}',
  'tax': '{$trans['tax']}'
});
HTML;
}

// Function to return the JavaScript representation of an ItemData object.
function getItemJs(&$transId, &$item) {
  return <<<HTML
ga('ecommerce:addItem', {
  'id': '$transId',
  'name': '{$item['name']}',
  'sku': '{$item['sku']}',
  'category': '{$item['category']}',
  'price': '{$item['price']}',
  'quantity': '{$item['quantity']}'
});
HTML;
}
    }
 
?>
<!-- Begin HTML -->
<script>
ga('require', 'ecommerce');

<?php
echo getTransactionJs($trans);

foreach ($items as &$item) {
  echo getItemJs($trans['id'], $item);
}
?>

ga('ecommerce:send');
</script>
<?php
}
}
else {
$obj->steps();
}
$obj->step4();

	global $wpdb;
	get_header();
 
?>
<?php if(isset($_SESSION["form_completed"]) && $_SESSION["form_completed"] == 'true') { ?>
<script type="text/javascript">
jQuery(window).load(function() {
step4_js('<?php echo $_SESSION["form_completed"]; ?>');

});
</script>
<style type="text/css">
    .whats-next .step02 { background: rgba(0, 0, 0, 0) url("<?php echo plugin_dir_url( __FILE__ ); ?>picon5.png") no-repeat scroll 10px 0; }
</style>
<?php } else { ?>
<script type="text/javascript">
jQuery(window).load(function() {
var html = '<p style="margin-top:10px;font-weight: bold;">You can send your hair sample by post to</p><p style="font-weight: bold;">PO BOX 60 156, Titirangi, Auckland<br>or by courier to c/o Titirangi Pharmacy 408 Titirangi Rd<br></p>';

                jQuery('.order-one .order-right').append(html);
});
</script>
<?php } ?>
<?php while ( have_posts()) : the_post(); ?>
                <section class="form-section innerpage woocommerce-page woocommerce">
                  <div class="container clearfix">
                    <?php if(!isset($_GET['key'])) { ?>
                    <h1><?php //the_title(); ?>Order your test now</h1>
                    <!-- progressbar -->
                    <div id="msform">

                        <ul id="progressbar">
                        <li class="active">Personal Details</li>
                        <li class="active">Health Assessment</li>
                        <li class="active">Choose Test</li>
                        <li class="active">Pay</li>
                    
                        </ul>

                    </div>
                    <?php } else { ?>

                    <?php } ?>
            
                    <?php the_content(); ?>
                    <?php if(!isset($_GET['key'])) { ?>
                    <div class="step-pagination-steps4"><a href="<?php echo home_url('/step-3'); ?>" style="float:left;" class=""><< Edit Order</a></div>
                    <?php } ?>
              
                  </div>
                </section>
        <?php endwhile; ?>
<?php    
	get_footer();
?>