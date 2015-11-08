<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
 <!-- Add Google Experiment code for front page only -->
<?php if(is_front_page()) { ?>
 <!-- Google Analytics Content Experiment code -->
<script>function utmx_section(){}function utmx(){}(function(){var
k='103850818-2',d=document,l=d.location,c=d.cookie;
if(l.search.indexOf('utm_expid='+k)>0)return;
function f(n){if(c){var i=c.indexOf(n+'=');if(i>-1){var j=c.
indexOf(';',i);return escape(c.substring(i+n.length+1,j<0?c.
length:j))}}}var x=f('__utmx'),xx=f('__utmxx'),h=l.hash;d.write(
'<sc'+'ript src="'+'http'+(l.protocol=='https:'?'s://ssl':
'://www')+'.google-analytics.com/ga_exp.js?'+'utmxkey='+k+
'&utmx='+(x?x:'')+'&utmxx='+(xx?xx:'')+'&utmxtime='+new Date().
valueOf()+(h?'&utmxhash='+escape(h.substr(1)):'')+
'" type="text/javascript" charset="utf-8"><\/sc'+'ript>')})();
</script><script>utmx('url','A/B');</script>
<!-- End of Google Analytics Content Experiment code -->  
<?php } ?>
	
	
		<meta charset="<?php bloginfo( 'charset' ); ?>">	
    <meta name="viewport" content="width=device-width, initial-scale=1.0">	

    <link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.ico" />

		<script type="text/javascript">
			var pathInfo = {
				base: '<?php echo get_template_directory_uri(); ?>/',
				css: 'css/',
				js: 'js/',
				swf: 'swf/',
			}
		</script>

		<?php wp_head(); ?>

    <script type="text/javascript">

    jQuery( document ).ready(function() {
        jQuery("form").bind("keypress", function (e) {
          if (e.keyCode == 13) {
              return false;
          }
        });
    });

    </script>
    
    
<script type="text/javascript">

// SHOW / HIDE SEARCH BOX

jQuery(document).ready(function(){
    jQuery("li#menu-item-474 a").click(function(event){
        event.preventDefault();
        jQuery("#hidden-search").toggle();
        jQuery("#hidden-search input[type=search]").focus();
    });
    jQuery(".carter").click(function(event){
        event.preventDefault();
        jQuery("#hidden-search").toggle();
    });
    
    // SUBMIT FORM ON ENTER

    jQuery("#hidden-search input[type=search]").keypress(function(event) {
        if (event.which == 13) {
            event.preventDefault();
            jQuery(".search-form").submit();
        }
    });
   
});

</script>


<?php if( (is_singular('post')) || (is_page('learn-more')) ) { ?>
<!-- SMOOTH SCROLL -->
<script type="text/javascript">
jQuery(document).ready(function(){
  //alert('single blog page');
  jQuery('a[href*=#]:not([href=#])').click(function() {
      if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') 
          || location.hostname == this.hostname) {
  
          var target = jQuery(this.hash);
          target = target.length ? target : jQuery('[name=' + this.hash.slice(1) +']');
             if (target.length) {
               jQuery('html,body').animate({
                   scrollTop: target.offset().top
              }, 1000);
              return false;
          }
      }
  });
});
</script>
<?php } ?>

<meta name="google-site-verification" content="yHdrzlaJQLoxIT5lW9FOjVKZdBUb6nPA6U-SNs0bkXM" />
<meta name="msvalidate.01" content="73B3E1A7212985B5308196BFD5121639" />
<meta name="geo.region" content="NZ-AUK" />
<meta name="geo.placename" content="Auckland" />
<meta name="geo.position" content="-36.938182;174.654076" />
<meta name="ICBM" content="-36.938182, 174.654076" />
   

	</head>

	<body <?php body_class(); ?>>
	
	<?php if ( function_exists( 'gtm4wp_the_gtm_tag' ) ) { gtm4wp_the_gtm_tag(); } ?>	

  <div id="wrapper" <?php if ( is_page() || !is_active_sidebar( 'default-sidebar' )) echo 'class="full-width"'; ?>>
    
    <div class="top-header">
    	<div class="header-wrapper">
         <div class="right-section">
          	<?php wp_nav_menu( array('menu' => 'top-head-menu', 'menu_class' => 'top-menu-right' )); ?>
            <div class="product_search"><?php get_product_search_form(); ?></div>
          </div>
       </div>
    </div>
    
            <header id="header">
                <div class="holder">
                    <div class="logo"><a href="<?php echo home_url(); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="<?php echo bloginfo( 'name' ); ?>"></a></div>

                    <?php if ( $site_desc = get_bloginfo( 'description' )) : ?>
                        <strong class="slogan"><?php echo $site_desc; ?></strong>
                    <?php endif; ?>

                    <?php if( has_nav_menu( 'primary' ) ) : ?>

                        <nav id="main-nav">
                            <a href="#" class="opener"><span><?php _e( 'Menu', 'allergenics' ); ?></span></a>
                            <a href="#" class="carter"><span><?php _e( 'Cart', 'allergenics' ); ?></span></a>

                            <?php wp_nav_menu( array(
                                    'container_class' => 'drop header_menu',
                                    'theme_location' => 'primary',
									                   'menu_class' => '',
                                    'items_wrap'     => '<ul class="parent_menu">%3$s</ul>'
                                    )); ?>
                                    
                            <div class="hidden-search" id="hidden-search">
                                <?php dynamic_sidebar( 'mini-cart' ); ?>
                               <?php //woocommerce_mini_cart(); ?>
                            </div>

                        </nav>

                    <?php endif; ?>

                </div>
            </header>