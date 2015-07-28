<!DOCTYPE html>

<html <?php language_attributes(); ?>>

	<head>

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
    jQuery(".searcher").click(function(event){
        event.preventDefault();
        jQuery("#hidden-search").toggle();
        jQuery("#hidden-search input[type=search]").focus();
    });
    jQuery("li#menu-item-470 a").click(function(event){
        event.preventDefault();
        jQuery("#hidden-search").toggle();
        jQuery("#hidden-search input[type=search]").focus();
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

<meta name="google-site-verification" content="yHdrzlaJQLoxIT5lW9FOjVKZdBUb6nPA6U-SNs0bkXM" />

<meta name="msvalidate.01" content="73B3E1A7212985B5308196BFD5121639" />

<meta name="geo.region" content="NZ-AUK" />

<meta name="geo.placename" content="Auckland" />

<meta name="geo.position" content="-36.938182;174.654076" />

<meta name="ICBM" content="-36.938182, 174.654076" />
   

	</head>

	<body <?php body_class(); ?>>
	
	<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-NL5FL6"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-NL5FL6');</script>
<!-- End Google Tag Manager -->
	

        <div id="wrapper" <?php if ( is_page() || !is_active_sidebar( 'default-sidebar' )) echo 'class="full-width"'; ?>>

            <header id="header">

                <div class="holder">

                    <div class="logo"><a href="<?php echo home_url(); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" alt="<?php echo bloginfo( 'name' ); ?>"></a></div>

                    <?php if ( $site_desc = get_bloginfo( 'description' )) : ?>

                        <strong class="slogan"><?php echo $site_desc; ?></strong>

                    <?php endif; ?>

                    <?php if( has_nav_menu( 'primary' ) ) : ?>

                        <nav id="main-nav">

                            <a href="#" class="opener"><span><?php _e( 'Menu', 'allergenics' ); ?></span></a>
                            
                            <a href="#" class="searcher"><span><?php _e( 'Search', 'allergenics' ); ?></span></a>

                            <?php wp_nav_menu( array(

                                    'container_class' => 'drop',

                                    'theme_location' => 'primary',

                                    'items_wrap'     => '<ul>%3$s</ul>'

                                    )); ?>
                                    
                            <div class="hidden-search" id="hidden-search" style="display:none">
                               <?php get_search_form(); ?>
                            </div>

                        </nav>

                    <?php endif; ?>

                </div>

            </header>