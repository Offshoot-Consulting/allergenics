<?php
session_start();
//print_r($_SESSION);
?>
<!DOCTYPE html>
<html lang="en-AU">

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


		<script language="javascript">
		function validateform()
		{
			//alert ("intotest");
			
			if(document.frm1.input_111.value=='' )
			{
				alert("Please select a payment option");
				return false;
			}
			
			return true;
		}
</script>

<script>
jQuery(document).ready(function($) {
    var form = jQuery('form[name="frm1"]'),
        radio = jQuery('input[name="input_111"]'),
        choice = '';

    radio.change(function(e) {
        choice = this.value;

        if (choice === 'choice01') {
            form.attr('action', 'PxPay_Redirect.php');
        } else {
            form.attr('action', 'ccform.php');
			//form.attr('action', 'myccform2.php');
        }
    });
});
</script>

		<meta charset="UTF-8">	
    <meta name="viewport" content="width=device-width, initial-scale=1.0">	



<title>Order your test now | Allergenics</title>
	
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
                    <h1>Order your test now</h1>


                <div class='gf_browser_gecko gform_wrapper' id='gform_wrapper_2' style=''><a id='gf_2' name='gf_2' class='gform_anchor' ></a>
                

                       
        
        <div class="gform_body">
        <div class="gform_page" id="gform_page_2_1">
        <div class="gform_page_fields">
        
        <form name='frm1' enctype='multipart/form-data' onSubmit='javascript: return validateform();' action="#" method="post">
				<input type="hidden" name="SessionId" value="<?php echo $_SESSION['sessid']; ?>" />
				<input type="hidden" name="Action" value="Add" />
				<input type="hidden" name="Object" value="DpsPxPay" />
        
        <ul class="gform_fields top_label form_sublabel_below description_below" id="gform_fields_2">
        
        <li class="gfield gfield_html gfield_html_formatted gfield_no_follows_desc field_sublabel_below field_description_below" id="field_1_77"><div class="progress">
          <div class="circle done">
            <span class="label ">&#10003;</span>
            <span class="title">Contact details</span>
          </div>
          <span class="bar"></span>
          <div class="circle done">
            <span class="label done">&#10003;</span>
            <span class="title">Symptoms</span>
          </div>
          <span class="bar"></span>
          <div class="circle done">
            <span class="label done">&#10003;</span>
            <span class="title">Choose Test</span>
          </div>
          <span class="bar"></span>
          <div class="circle active">
            <span class="label active">4</span>
            <span class="title">Payment</span>
          </div>
        </div></li>
        

            
            <li class="gfield gfield_contains_required field_sublabel_below field_description_below" id="field_1_11">
            <h3>How would you like to pay?<span class="gfield_required">*</span></h3>
            
            
            <div class="ginput_container">
              <ul id="input_1_11" class="gfield_radio">
              
              <li class="gchoice_1_11_0 myhoice_1_11_0">
                <input type="radio" tabindex="15" id="choice01" value="choice01" name="input_111">
                <label id="label_1_11_0" for="choice01">Pay by Internet Banking </label>
              </li>
              
              <li class="gchoice_1_11_1 myhoice_1_11_1">
                <input type="radio" tabindex="16" id="choice02" value="choice02" name="input_111">
                <label id="label_1_11_1" for="choice02">Pay by Credit Card</label>
              </li>
              
            </ul>
            </div>
            </li>
            
            <li class="gfield field">
            <label class="gfield_label">Amount</label>
            <div class="ginput_container">
            <!--<input type="text" name="Amount" value="<?php echo $_SESSION['amnt']; ?>" />-->
            $<?php echo $_SESSION['amnt']; ?>
            </div>
            </li>

				  </ul>


				</div>
				</div>
				</div>
					
				
			
        <div class='gform_page_footer top_label'>
        
            <input type='submit' id='gform_submit_button_1' class='gform_button button' value='Choose Payment Method' tabindex='134'/>
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