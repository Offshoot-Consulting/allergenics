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

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-64114162-1', 'auto');
  ga('send', 'pageview');

</script>
<meta name="google-site-verification" content="yHdrzlaJQLoxIT5lW9FOjVKZdBUb6nPA6U-SNs0bkXM" />
<meta name="msvalidate.01" content="73B3E1A7212985B5308196BFD5121639" />
<meta name="geo.region" content="NZ-AUK" />
<meta name="geo.placename" content="Auckland" />
<meta name="geo.position" content="-36.938182;174.654076" />
<meta name="ICBM" content="-36.938182, 174.654076" />

<!-- Begin Inspectlet Embed Code -->
<script type="text/javascript" id="inspectletjs">
window.__insp = window.__insp || [];
__insp.push(['wid', 1091053351]);
(function() {
function __ldinsp(){var insp = document.createElement('script'); insp.type = 'text/javascript'; insp.async = true; insp.id = "inspsync"; insp.src = ('https:' == document.location.protocol ? 'https' : 'http') + '://cdn.inspectlet.com/inspectlet.js'; var x = document.getElementsByTagName('script')[0]; x.parentNode.insertBefore(insp, x); };
document.readyState != "complete" ? (window.attachEvent ? window.attachEvent('onload', __ldinsp) : window.addEventListener('load', __ldinsp, false)) : __ldinsp();

})();
</script>
<!-- End Inspectlet Embed Code -->
   
	</head>
	<body <?php body_class(); ?>>
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
                            <?php wp_nav_menu( array(
                                    'container_class' => 'drop',
                                    'theme_location' => 'primary',
                                    'items_wrap'     => '<ul>%3$s</ul>'
                                    )); ?>
                        </nav>
                    <?php endif; ?>
                </div>
            </header>