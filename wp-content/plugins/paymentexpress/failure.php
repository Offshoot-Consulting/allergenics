<?php
session_start();
$http_host   = $_SERVER['HTTP_HOST'];
$server_url  = "https://$http_host";	
$failursubmitUrl =$server_url."/wp-content/plugins/paymentexpress/start.php";
?>

<!DOCTYPE html>
<html lang="en-AU">

<title>Order your test now | Allergenics</title>

<link rel='stylesheet' id='font-awesome-css'  href='//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css' type='text/css' media='all' />
<link rel='stylesheet' id='base-style-css'  href='/wp-content/themes/allergenics/style.css' type='text/css' media='all' />
<link rel='stylesheet' id='font-open-sans-css'  href='//fonts.googleapis.com/css?family=Open+Sans%3A400%2C300%2C600%2C700&#038;ver=4.2.3' type='text/css' media='all' />
<link rel='stylesheet' id='base-theme-css'  href='/wp-content/themes/allergenics/theme.css' type='text/css' media='all' />
<!--[if IE 9]>
<link rel='stylesheet' id='base-ie-css'  href='/wp-content/themes/allergenics/css/ie.css' type='text/css' media='all' />
<![endif]-->
<link rel='stylesheet' id='gforms_reset_css-css'  href='/wp-content/plugins/gravityforms/css/formreset.min.css' type='text/css' media='all' />
<link rel='stylesheet' id='gforms_formsmain_css-css'  href='/wp-content/plugins/gravityforms/css/formsmain.min.css' type='text/css' media='all' />
<link rel='stylesheet' id='gforms_ready_class_css-css'  href='/wp-content/plugins/gravityforms/css/readyclass.min.css' type='text/css' media='all' />
<link rel='stylesheet' id='gforms_browsers_css-css'  href='/wp-content/plugins/gravityforms/css/browsers.min.css' type='text/css' media='all' />
<script type='text/javascript' src='/wp-includes/js/jquery/jquery.js'></script>
<script type='text/javascript' src='/wp-includes/js/jquery/jquery-migrate.min.js'></script>
<script type='text/javascript' src='/wp-content/themes/allergenics/js/jquery.main.js'></script>
<link rel="EditURI" type="application/rsd+xml" title="RSD" href="/xmlrpc.php?rsd" />
<link rel="wlwmanifest" type="application/wlwmanifest+xml" href="/wp-includes/wlwmanifest.xml" /> 
<link rel='shortlink' href='/?p=160' />


<script type="text/javascript">

// search when hit enter

  jQuery( document ).ready(function() {
        jQuery("form").bind("keypress", function (e) {
      if (e.keyCode == 13) {
          return false;
      }
    });
  });
</script>
    
    
<script type="text/javascript">

// show/hide search box

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

		<meta charset="UTF-8">	
    <meta name="viewport" content="width=device-width, initial-scale=1.0">	

<!-- Google Code for food Conversion Page -->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 948192091;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "D9KZCLnDgF8Q24aRxAM";
var google_remarketing_only = false;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//www.googleadservices.com/pagead/conversion/948192091/?label=D9KZCLnDgF8Q24aRxAM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<!-- Google Code para etiquetas de remarketing -->
<!--------------------------------------------------
Es posible que las etiquetas de remarketing todavía no estén asociadas a la información de identificación personal o que estén en páginas relacionadas con las categorías delicadas. Para obtener más información e instrucciones sobre cómo configurar la etiqueta, consulte http://google.com/ads/remarketingsetup.
--------------------------------------------------->
<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 948192091;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/948192091/?value=0&amp;guid=ON&amp;script=0"/>
</div>
</noscript>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-64114162-1', 'auto');
  ga('send', 'pageview');

</script>

	
</head>
	
  <body class="page page-id-103 page-template page-template-pages page-template-template-order page-template-pagestemplate-order-php no-customize-support">
	
        <div id="wrapper" class="full-width">
             
        <header id="header">
                <div class="holder">
                    <div class="logo"><a href="/"><img alt="Allergenics" src="/wp-content/themes/allergenics/images/logo.png"></a></div>
                    <strong class="slogan">Health Assessment Services</strong>
                    
                        <nav id="main-nav">

                            <a class="opener" href="#"><span>Menu</span></a>                             
                            <a class="searcher" href="#"><span>Search</span></a>
                            
                            <div class="drop">
                            
                            <ul>
                            <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-has-children menu-item-8" id="menu-item-8">
                            <a href="/hair-testing-services/">Hair Testing Services</a>
                            <ul class="sub-menu">
                            <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-202" id="menu-item-202">
                            <a href="/hair-testing-services/food-and-environmental-sensitivity-assessment-hair-test/">Food and Environmental Sensitivity Assessment</a></li>
	                          <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-201" id="menu-item-201"><a href="/hair-testing-services/individual-organ-stress-assessment-hair-test/">Organ Stress Assessment</a></li>
                            <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-200" id="menu-item-200"><a href="/hair-testing-services/vitamin-and-mineral-assessment-human-hair-test/">Vitamin and Mineral Assessment</a></li>
                            <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-199" id="menu-item-199"><a href="/hair-testing-services/heavy-metal-and-toxic-element-assessment-human-hair-test/">Heavy Metal and Toxic Element Assessment</a></li>
                            </ul>
                            </li>
                            <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-10" id="menu-item-10"><a href="/about-us/">About us</a></li>
                            <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-157" id="menu-item-157"><a href="/faq/">FAQ</a></li>
                            <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-14" id="menu-item-14"><a href="/practitioners/">Practitioners</a></li>
                            <li class="menu-item menu-item-type-custom menu-item-object-custom menu-item-470" id="menu-item-470"><a href="#">Search</a></li></ul></div>                                    
                            <div style="display:none" id="hidden-search" class="hidden-search">
                               <form action="/" class="search-form" method="get">
                              	<input type="search" value="" placeholder="Enter search terms..." name="s">
                              	<input type="submit" value="Search">
                              </form>
                            </div>
                        </nav>
                </div>
           </header>     
             
             <section class="form-section">
                  <div class="container clearfix">
                    <div class="cont-left">
                    <h1>Oops! Something went wrong...</h1>

        <div class='gf_browser_gecko gform_wrapper' id='gform_wrapper_2' ><a id='gf_2' name='gf_2' class='gform_anchor' ></a>
                
        <form method='post' enctype='multipart/form-data'  id='gform_2'  action='<?php echo $failursubmitUrl?>'>
        
        <div class="gform_body">
        <div class="gform_page" id="gform_page_2_1">
        <div class="gform_page_fields">

        <h2 style="color:#00263C">Sorry, there was problem with your payment. Please ensure you have funds available and try again. Alternatively contact your bank for more information.</h2>

				</div>
				</div>
				</div>
			
        <div class='gform_page_footer top_label'>
        
            <input type='submit' name='failure_submit' id='gform_submit_button_1' class='gform_button button' value='Go back to Payment Page' tabindex='134'/>
            <input type='hidden' name='gform_ajax' value='form_id=2&amp;title=&amp;description=&amp;tabindex=' />
            <input type='hidden' class='gform_hidden' name='is_submit_2' value='1' />
            <input type='hidden' class='gform_hidden' name='gform_submit' value='2' />
            <input type='hidden' class='gform_hidden' name='gform_unique_id' value='' />
            <input type='hidden' class='gform_hidden' name='gform_target_page_number_2' id='gform_target_page_number_2' value='2' />
            <input type='hidden' class='gform_hidden' name='gform_source_page_number_2' id='gform_source_page_number_2' value='1' />
            <input type='hidden' name='gform_field_values' value='' />
            
        </div>

                        </form>
                        </div>
                    </div>
                    
<div class="cont-right">
  <div id="text-2" class="widget widget_text">
    <div id="text-2" class="widget widget_text"><h3>Help</h3><p>We require a minimum of 1/2 teaspoon of hair, cut as close to the scalp as possible. The closer to the scalp the more accurate the results will be. Hair samples should not be older than 4 weeks old.</p>
    <p>Please refer to our FAQ's for more information about taking hair samples.</p>
    <p>If you need any help, please email us at <a href="mailto:info@allergenics.co.nz">info@allergenics.co.nz</a>  or phone us on 0800 004 898.</p>
  </div>
  <h3>100% Secure Payments</h3>
  <img width="155" height="54" alt="Payment Processor" src="/wp-content/themes/allergenics/images/pe.png">
  <img alt="comodo" src="/wp-content/themes/allergenics/images/comodo.png">
  </div>
</div>
</div>
</section>


<footer id="footer">
  <div class="container">
    <div class="contact">
      <address>
      <strong class="title">Natural Health Consultants Ltd</strong>
      <dl><dt>Phone:</dt><dd><a href="tel:0800004898">0800 004 898</a></dd>
      <dt>Email:</dt><dd><a href="mailto:info@allergenics.co.nz">info@allergenics.co.nz</a></dd></dl>
      <span>PO BOX 60 156, 408 Titirangi Rd, Titirangi, Auckland</span>
      </address>
      </div>
      
      <nav class="add-nav">
        <ul>
          <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-86" id="menu-item-86"><a href="/partners/">Partners<span class="icon-btn-right"></span></a></li>
          <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-87" id="menu-item-87"><a href="/terms-and-conditions/">Terms and conditions<span class="icon-btn-right"></span></a></li>
          <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-88" id="menu-item-88"><a href="/privacy/">Privacy<span class="icon-btn-right"></span></a></li>
          <li class="menu-item menu-item-type-post_type menu-item-object-page menu-item-89" id="menu-item-89"><a href="/faq-2/">FAQ<span class="icon-btn-right"></span></a></li>
        </ul>
      </nav>
      <div class="connect">
        <h3>CONNECT</h3>
          <ul class="social-network">
            <li><a href=""><span class="icon-facebook"></span></a></li>
            <li><a href=""><span class="icon-twitter"></span></a></li>
          </ul>
        </div>
      </div>
    </footer>
    </main>
	 </div>
  </body>
</html>