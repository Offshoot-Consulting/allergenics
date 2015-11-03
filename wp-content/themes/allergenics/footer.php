			<?php $title = get_field( 'title','option' ); ?>
            <?php $phone = get_field( 'phone','option' ); ?>
            <?php $email = get_field( 'email','option' ); ?>
            <?php $address = get_field( 'address','option' ); ?>
                
                <footer id="footer">
                    <div class="container">                        
                      <div class="footer-column footer-column-01">
                        <?php dynamic_sidebar( 'footer1-sidebar' ); ?>
                          <?php if ( have_rows( 'social_links','option' )) : ?>
                                <h3><?php _e( 'CONNECT', 'allergenics' ); ?></h3>
                                <ul class="social-network">
                                    <?php while ( have_rows( 'social_links','option' )) : the_row(); ?>
                                        <li><a target="_blank" href="<?php echo esc_url(get_sub_field( 'link' )); ?>"><span class="<?php echo get_row_layout(); ?>"></span></a></li>
                                    <?php endwhile; ?>
                                </ul>
                        <?php endif; ?>
                      </div>
                      <div class="footer-column footer-column-02">
                        <?php dynamic_sidebar( 'footer2-sidebar' ); ?>
                      </div>
                      <div class="footer-column footer-column-03">
                        <?php dynamic_sidebar( 'footer3-sidebar' ); ?>
                      </div>
                    </div>
                </footer>
		</main>
	</div>
    
<script type="text/javascript">
    jQuery(document).ready(function() {
      jQuery('#searchsubmit').val('');
      jQuery('#s').hide();
    // Search
    jQuery('#searchgly').click(function() {   
    jQuery('.hit_me').toggleClass( 'inner' );
        //jQuery('#searchgly').hide('slow');
    jQuery('#s').animate({width: "toggle"},500);
    	jQuery( "#s" ).focus();	
    });
    });
    
    jQuery(document).ready(function(){
        jQuery('#s').keypress(function (e) {
      if (e.which == 13) {
        jQuery('form#searchform').submit();
        return false;    //<---- Add this line
      }
    });
    });
</script>

<?php wp_footer(); ?>
<script type='text/javascript' src='<?php echo get_bloginfo('template_url'); ?>/woocommerce/assets/js/frontend/jquery.payment.min.js'></script>
<script type='text/javascript' src='<?php echo get_bloginfo('template_url'); ?>/woocommerce/assets/js/frontend/credit-card-form.min.js'></script>

</body>
</html>