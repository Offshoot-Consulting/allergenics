<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en-AU">
	<head>
		<meta charset="UTF-8">	
        <meta name="viewport" content="width=device-width, initial-scale=1.0">	
		<script type="text/javascript">
			var pathInfo = {
				base: '/wp-content/themes/allergenics/',
				css: 'css/',
				js: 'js/',
				swf: 'swf/',
			}
		</script>
		<script language="javascript">
		function decide_action()
		{
			
			if(document.frm1.input_111.value=='choice01')
			{
				document.frm1.action="PxPay_Redirect.php";
			}
			else if(document.frm1.input_111.value=='choice02')
			{
				document.frm1.action="https://sec.paymentexpress.com/pxmi3/pxfusionauth";
			}
			
			
			//document.forms['frm1'].submit();
			document.subForm['0'].submit();
			
		}
</script>
		<title>Order your test now | Allergenics</title>
<link rel="alternate" type="application/rss+xml" title="Allergenics &raquo; Feed" href="/feed/" />
<link rel="alternate" type="application/rss+xml" title="Allergenics &raquo; Comments Feed" href="/comments/feed/" />
<link rel="alternate" type="application/rss+xml" title="Allergenics &raquo; Order your test now Comments Feed" href="/order-your-test-now/feed/" />
<link rel='stylesheet' id='open-sans-css'  href='//fonts.googleapis.com/css?family=Open+Sans%3A300italic%2C400italic%2C600italic%2C300%2C400%2C600&#038;subset=latin%2Clatin-ext&#038;ver=4.1.4' type='text/css' media='all' />
<link rel='stylesheet' id='dashicons-css'  href='/wp-includes/css/dashicons.min.css?ver=4.1.4' type='text/css' media='all' />
<link rel='stylesheet' id='admin-bar-css'  href='/wp-includes/css/admin-bar.min.css?ver=4.1.4' type='text/css' media='all' />
<link rel='stylesheet' id='base-style-css'  href='/wp-content/themes/allergenics/style.css?ver=4.1.4' type='text/css' media='all' />
<link rel='stylesheet' id='font-open-sans-css'  href='http://fonts.googleapis.com/css?family=Open+Sans%3A400%2C300%2C600%2C700&#038;ver=4.1.4' type='text/css' media='all' />
<link rel='stylesheet' id='base-theme-css'  href='/wp-content/themes/allergenics/theme.css?ver=4.1.4' type='text/css' media='all' />
<!--[if IE 9]>
<link rel='stylesheet' id='base-ie-css'  href='/wp-content/themes/allergenics/css/ie.css?ver=4.1.4' type='text/css' media='all' />
<![endif]-->
<link rel='stylesheet' id='gforms_reset_css-css'  href='/wp-content/plugins/gravityforms/css/formreset.min.css?ver=1.9.6.2' type='text/css' media='all' />
<link rel='stylesheet' id='gforms_formsmain_css-css'  href='/wp-content/plugins/gravityforms/css/formsmain.min.css?ver=1.9.6.2' type='text/css' media='all' />
<link rel='stylesheet' id='gforms_ready_class_css-css'  href='/wp-content/plugins/gravityforms/css/readyclass.min.css?ver=1.9.6.2' type='text/css' media='all' />
<link rel='stylesheet' id='gforms_browsers_css-css'  href='/wp-content/plugins/gravityforms/css/browsers.min.css?ver=1.9.6.2' type='text/css' media='all' />
<script type='text/javascript' src='/wp-includes/js/jquery/jquery.js?ver=1.11.1'></script>
<script type='text/javascript' src='/wp-includes/js/jquery/jquery-migrate.min.js?ver=1.2.1'></script>
<script type='text/javascript' src='/wp-content/themes/allergenics/js/jquery.main.js?ver=4.1.4'></script>
<script type='text/javascript' src='/wp-content/plugins/gravityforms/js/conditional_logic.min.js?ver=1.9.6.2'></script>
<script type='text/javascript' src='/wp-content/plugins/gravityforms/js/jquery.json-1.3.js?ver=1.9.6.2'></script>
<script type='text/javascript' src='/wp-content/plugins/gravityforms/js/gravityforms.min.js?ver=1.9.6.2'></script>
<script type='text/javascript' src='/wp-content/plugins/gravityforms/js/placeholders.jquery.min.js?ver=1.9.6.2'></script>
<link rel="EditURI" type="application/rsd+xml" title="RSD" href="/xmlrpc.php?rsd" />
<link rel="wlwmanifest" type="application/wlwmanifest+xml" href="/wp-includes/wlwmanifest.xml" /> 
<link rel='canonical' href='/order-your-test-now/' />
<link rel='shortlink' href='/?p=103' />
	<style type="text/css">.recentcomments a{display:inline !important;padding:0 !important;margin:0 !important;}</style>
<style type="text/css" media="print">#wpadminbar { display:none; }</style>

		<script type="text/javascript">
		  
		jQuery(document).ready(function() {
    
    jQuery("input[name$='input_111']").click(function() {
        var test = jQuery(this).val();
        //alert(test);
        
        if(test=='choice02') {
          jQuery('#ccform').show();
        }
        
        if(test=='choice01') {
          jQuery('#ccform').hide();
        }
        
    });
    
    });
		</script>

	
	</head>
	<body class="page page-id-103 page-template page-template-pages page-template-template-order page-template-pagestemplate-order-php no-customize-support">
        <div id="wrapper" class="full-width">
            <header id="header">
                <div class="holder">
                    <div class="logo"><a href=""><img src="/wp-content/themes/allergenics/images/logo.png" alt="Allergenics"></a></div>
                                            <strong class="slogan">Health Assessment Services</strong>
                                                                <nav id="main-nav">
                            <a href="#" class="opener"><span>Menu</span></a>
                            <div class="drop"><ul><li id="menu-item-82" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-82"><a href="/testing-services/">Testing services</a></li>
<li id="menu-item-83" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-83"><a href="/about-us/">About us</a></li>
<li id="menu-item-84" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-84"><a href="/faq/">FAQ</a></li>
<li id="menu-item-85" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-85"><a href="/practitioners-area/">Practitioners Area</a></li>
</ul></div>                        </nav>
                                    </div>
            </header>                        <section class="form-section">
                  <div class="container clearfix">
                    <div class="cont-left">
                    <h1>Order your test now</h1>


                <div class='gf_browser_gecko gform_wrapper' id='gform_wrapper_2' style='display:none'><a id='gf_2' name='gf_2' class='gform_anchor' ></a>
                
                <!--<form method='post' enctype='multipart/form-data' target='gform_ajax_frame_2' id='gform_2'  action='/order-your-test-now/#gf_2'>-->
                       
        
        <div class="gform_body">
        <div class="gform_page" id="gform_page_2_1">
        <div class="gform_page_fields">
        
        <form name='frm1' enctype='multipart/form-data'  onSubmit="javascript: decide_action();" action="javascript: decide_action();" method="post">
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
            
            
            <div id="ccform" style="display:none">
            <li class="gfield field">
            <label class="gfield_label">Card Number</label>
            <div class="ginput_container">
            <input type="text" name="CardNumber" value="4111111111111111" maxlength="16" />
            </div>
            </li>
            
            <li class="gfield field">
            <label class="gfield_label">Expiry (mm/yy)</label>
            <div class="ginput_container">
            <input type="text" name="ExpiryMonth" value="12" size="2" /> /
				    <input type="text" name="ExpiryYear" value="12" size="2" />
            </div>
            </li>
            
            <li class="gfield field">
            <label class="gfield_label">Card Security Code</label>
            <div class="ginput_container">
            <input type="text" name="Cvc2" value="123" size="4" />
            </div>
            </li>
            
            <li class="gfield field">
            <label class="gfield_label">Card Holder Name</label>
            <div class="ginput_container">
            <input type="text" name="CardHolderName" value="Joe Bloggs" />
            </div>
            </li>
            </div>

				  </ul>
				  
				  <script type="text/javascript">
				  </script>

				</div>
				</div>
				</div>
					
				
			
        <div class='gform_page_footer top_label'>
        
            <input type='submit' id='gform_submit_button_1' class='gform_button button' value='Pay' tabindex='134'/>
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
                        
               
                <script type='text/javascript'>jQuery(document).ready(function($){gformInitSpinner( 2, '/wp-content/plugins/gravityforms/images/spinner.gif' );jQuery('#gform_ajax_frame_2').load( function(){var contents = jQuery(this).contents().find('*').html();var is_postback = contents.indexOf('GF_AJAX_POSTBACK') >= 0;if(!is_postback){return;}var form_content = jQuery(this).contents().find('#gform_wrapper_2');var is_confirmation = jQuery(this).contents().find('#gform_confirmation_wrapper_2').length > 0;var is_redirect = contents.indexOf('gformRedirect(){') >= 0;var is_form = form_content.length > 0 && ! is_redirect && ! is_confirmation;if(is_form){jQuery('#gform_wrapper_2').html(form_content.html());setTimeout( function() { /* delay the scroll by 50 milliseconds to fix a bug in chrome */ jQuery(document).scrollTop(jQuery('#gform_wrapper_2').offset().top); }, 50 );if(window['gformInitDatepicker']) {gformInitDatepicker();}if(window['gformInitPriceFields']) {gformInitPriceFields();}var current_page = jQuery('#gform_source_page_number_2').val();gformInitSpinner( 2, '/wp-content/plugins/gravityforms/images/spinner.gif' );jQuery(document).trigger('gform_page_loaded', [2, current_page]);window['gf_submitting_2'] = false;}else if(!is_redirect){var confirmation_content = jQuery(this).contents().find('#gforms_confirmation_message_2').html();if(!confirmation_content){confirmation_content = contents;}setTimeout(function(){jQuery('#gform_wrapper_2').replaceWith('<' + 'div id=\'gforms_confirmation_message_2\' class=\'gform_confirmation_message_2 gforms_confirmation_message\'' + '>' + confirmation_content + '<' + '/div' + '>');jQuery(document).scrollTop(jQuery('#gforms_confirmation_message_2').offset().top);jQuery(document).trigger('gform_confirmation_loaded', [2]);window['gf_submitting_2'] = false;}, 50);}else{jQuery('#gform_2').append(contents);if(window['gformRedirect']) {gformRedirect();}}jQuery(document).trigger('gform_post_render', [2, current_page]);} );} );</script><script type='text/javascript'> if(typeof gf_global == 'undefined') var gf_global = {"gf_currency_config":{"name":"U.S. Dollar","symbol_left":"$","symbol_right":"","symbol_padding":"","thousand_separator":",","decimal_separator":".","decimals":2},"base_url":"http:\/\/allergenics.edyta.me\/wp-content\/plugins\/gravityforms","number_formats":[],"spinnerUrl":"http:\/\/allergenics.edyta.me\/wp-content\/plugins\/gravityforms\/images\/spinner.gif"};jQuery(document).bind('gform_post_render', function(event, formId, currentPage){if(formId == 2) {if(window['jQuery']){if(!window['gf_form_conditional_logic'])window['gf_form_conditional_logic'] = new Array();window['gf_form_conditional_logic'][2] = {'logic' : {26: {"field":{"actionType":"show","logicType":"all","rules":[{"fieldId":"11","operator":"is","value":"Male"}]},"nextButton":null,"section":null},27: {"field":{"actionType":"show","logicType":"all","rules":[{"fieldId":"11","operator":"is","value":"Female"}]},"nextButton":null,"section":null},42: {"field":{"actionType":"show","logicType":"any","rules":[{"fieldId":"43","operator":"is","value":"Yes"}]},"nextButton":null,"section":null},44: {"field":{"actionType":"show","logicType":"any","rules":[{"fieldId":"43","operator":"is","value":"Yes"}]},"nextButton":null,"section":null},51: {"field":{"actionType":"show","logicType":"all","rules":[{"fieldId":"50","operator":"is","value":"Yes"}]},"nextButton":null,"section":null} }, 'dependents' : {26: [26],27: [27],42: [42],44: [44],51: [51] }, 'animation' : 0 , 'defaults' : {"6":{"6.2":"","6.3":"","6.4":"","6.6":"","6.8":""},"7":{"7.1":"","7.2":"","7.3":"","7.4":"","7.5":"","7.6":"New Zealand"},"10":{"d":"","m":"","y":""},"12":{"d":"","m":"","y":""},"60":{"60.1":"","60.2":"","60.3":""},"57":{"57.1":"","57.2":"","57.3":""},"62":{"62.1":"","62.2":"","62.3":""}} }; if(!window['gf_number_format'])window['gf_number_format'] = 'decimal_dot';jQuery(document).ready(function(){gf_apply_rules(2, [26,27,42,44,51], true);jQuery('#gform_wrapper_2').show();jQuery(document).trigger('gform_post_conditional_logic', [2, null, true]);} );} if(window["gformInitPriceFields"]) jQuery(document).ready(function(){gformInitPriceFields();} );if(typeof Placeholders != 'undefined'){
                        Placeholders.enable();
                    }} } );jQuery(document).bind('gform_post_conditional_logic', function(event, formId, fields, isInit){} );</script><script type='text/javascript'> jQuery(document).ready(function(){jQuery(document).trigger('gform_post_render', [2, 1]) } ); </script>
 
                    </div>
                    
                    
                    <div class="cont-right">
                      <div class="widget widget_text" id="text-2">
                      
                      <div id="text-2" class="widget widget_text"><h3>Help</h3><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</p>
</div>
                      
                      <h3>100% Secure Payments</h3>
                      <img src="https://www.paymentexpress.com/DPS/media/Logo/logos_transparent/pxlogoclearstack_png.png" alt="Payment Processor" width="155" height="54" />
                      <img src="http://allergenicstesting.com/wp-content/themes/allergenics/images/comodo.png" alt="comodo">

                      
</div>                    </div>
                  </div>
                </section>
        			                                                                <footer id="footer">
                    <div class="container">
                                                                            <div class="contact">
                                <address>
                                    <strong class="title">Natural Health Consultants Ltd</strong>                                                                            <dl>
                                                                                            <dt>Phone:</dt>
                                                <dd><a href="tel:0800004898">0800 004 898</a></dd>
                                                                                                                                                                                        <dt>Email:</dt>
                                                <dd><a href="mailto:&#105;nfo&#64;al&#108;&#101;rge&#110;&#105;cs.co&#46;nz">&#105;nfo&#64;al&#108;&#101;rge&#110;&#105;cs.co&#46;nz</a></dd>
                                                                                    </dl>
                                                                        <span>PO BOX 60 156, 408 Titirangi Rd, Titirangi, Auckland</span>                                </address>
                            </div>
                                                                        <nav class="add-nav"><ul><li id="menu-item-86" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-86"><a href="/partners/">Partners<span class="icon-btn-right"></a></li>
<li id="menu-item-87" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-87"><a href="/terms-and-conditions/">Terms and conditions<span class="icon-btn-right"></a></li>
<li id="menu-item-88" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-88"><a href="/privacy/">Privacy<span class="icon-btn-right"></a></li>
<li id="menu-item-89" class="menu-item menu-item-type-post_type menu-item-object-page menu-item-89"><a href="/faq-2/">FAQ<span class="icon-btn-right"></a></li>
</ul></nav>                                                                            <div class="connect">
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