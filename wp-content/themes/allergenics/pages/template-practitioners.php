<?php

/*
Template Name: Template Practitioners
*/

get_header(); ?>

        <?php while ( have_posts()) : the_post(); ?>

                <section class="form-section innerpage">

                  <div class="container clearfix">
                    <div class="cont-left">
                      <h1><?php the_title(); ?></h1>
                      <?php the_content(); ?> 
                    </div>
                    
                    <div class="cont-right">
                     <div class="widget widget_text testimonial-widget">
                      <h3>Health Practitioners</h3>
                      <p class="practp">How can allergenics hair testing service help you as a practitioner?</p>
                        <ul class="practlist">
                          <li><img src="<?php bloginfo('template_url');?>/images/picon1.png" /><span>Provides you with additional information on your client's current state of health.</span></li>
                          <li><img src="<?php bloginfo('template_url');?>/images/picon2.png" /><span>Assists you in prioritising your client's treatment protocol.</span></li>
                          <li><img src="<?php bloginfo('template_url');?>/images/picon3.png" /><span>Provides your client with a reference point from which they can monitor their health progress.</span></li>
                          <li><img src="<?php bloginfo('template_url');?>/images/picon4.png" /><span>The test is simple and non-invasive, a benefit for both you and your clientele.</span></li>
                        </ul>
                     </div>
                    </div>
                  </div>  
                </section>

        <?php endwhile; ?>

<?php get_footer(); ?>