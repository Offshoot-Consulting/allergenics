<?php
/*
Template Name: Home Template
*/
get_header(); ?>

<?php $learn_more_link = get_field( 'learn_more_link' , 'option' ); ?>
<?php $order_test_link = get_field( 'order_test_link' , 'option' ); ?>

	<section class="visual">
        <?php while ( have_posts()) : the_post(); ?>
            <?php if ( has_post_thumbnail()) : ?>
                <div class="bg-stretch"><?php echo preg_replace('#(width|height)=\"\d*\"\s#', "", wp_get_attachment_image( get_post_thumbnail_id(),'thumbnail_1600x583') ); ?></div>
            <?php endif; ?>
            <div class="container">
                <div class="holder-box">
                    <div class="wrap">
                        <?php the_title( '<h1>','</h1>' ); ?>
                        <?php if ( $order_test_link != ''): ?>
                            <a href="<?php echo esc_url( $order_test_link ); ?>" class="btn"><?php _e( 'ORDER NOW', 'allergenics' ); ?></a>
                        <?php endif; ?>
                        <?php if ( $learn_more_link != '' ): ?>
                            <a href="<?php echo esc_url( $learn_more_link ); ?>" class="more"><?php _e( 'Learn more &raquo;', 'allergenics' ); ?></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </section>
    <main id="main" role="main">
    
        <?php ///////// Practitioners Section /////////// ?>
        <?php if ( $pract_page = get_field( 'practitioners_page' )) : ?>
            <section class="text-info-block">
                <?php $pract = new WP_Query( array( 'page_id' => $pract_page->ID ) ); ?>
                    <?php while ( $pract->have_posts()) : $pract->the_post(); ?>
                        <?php the_title( '<h2>','</h2>' ); ?>
                        <?php echo apply_filters('the_excerpt',get_the_excerpt())?>
                        <p><a href="<?php echo esc_url( $learn_more_link ); ?>"><?php echo __( 'Read More', 'allergenics' ) ?></a></p>
                    <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            </section>
        <?php endif; ?>
        
        <?php ///////// Services Section /////////// ?>
        <?php if ( $services_heading || have_rows( 'services' ) || $services_heading ) : ?>
            <section class="services-block desktopview">
                <div class="container">
                    <?php if ( $services_heading ) echo '<h2>'. $services_heading .'</h2>'; ?>
                    <?php if ( have_rows( 'services' )) : ?>
                        <ul class="services-list">
                            <?php while ( have_rows( 'services' )) : the_row(); ?>
                                <?php $bg_image = get_sub_field( 'bg_image' );  ?>
                                <?php $icon = get_sub_field( 'icon' );  ?>
                                <?php $title = get_sub_field( 'title' );  ?>
                                <?php $content = get_sub_field( 'content' );  ?>
                                <?php if ( $bg_image || $icon || $title || $content ) : ?>
                                    <li>
                                        <div class="holder">
                                            <div class="front">
                                                <?php if ( $bg_image ) : ?>
                                                    <div class="bg-stretch"><?php echo preg_replace('#(width|height)=\"\d*\"\s#', "", wp_get_attachment_image( $bg_image,'thumbnail_246x246') ); ?></div>
                                                <?php endif; ?>
                                                <?php if ( $title || $icon ) : ?>
                                                    <div class="text-area">
                                                        <?php if ( $icon ) : ?>
                                                            <div class="ico"><?php echo preg_replace('#(width|height)=\"\d*\"\s#', "", wp_get_attachment_image( $icon,'thumbnail_97x75') ); ?></div>
                                                        <?php endif; ?>
                                                        <?php if ( $title ) echo '<strong class="title">'. $title .'</strong>'; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <?php if ( $content ) : ?>
                                                <div class="back">
                                                    <div class="text-box">
                                                        <?php echo $content; ?>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </li>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        </ul>
                    <?php endif; ?>
                    <a href="/hair-testing-services/" class="btn"><?php _e( 'SEE OUR HEAIR TESTS', 'allergenics' ); ?></a>
                </div>
            </section>  
            <section class="services-block mobileview">
                <div class="container">
                    <?php if ( $services_heading ) echo '<h2>'. $services_heading .'</h2>'; ?>
                    <?php if ( have_rows( 'services' )) : ?>
                        <ul class="services-list2">
                            <?php while ( have_rows( 'services' )) : the_row(); ?>
                                <?php $bg_image = get_sub_field( 'bg_image' );  ?>
                                <?php $icon = get_sub_field( 'icon' );  ?>
                                <?php $title = get_sub_field( 'title' );  ?>
                                <?php $content = get_sub_field( 'content' );  ?>
                                <?php $url = get_sub_field( 'url' );  ?>
                                <?php if ( $bg_image || $icon || $title || $content ) : ?>
                                    <li>
                                      <div class="holder">
                                       <div class="front">
                                                <?php if ( $bg_image ) : ?>
                                                   
                                                    <div class="bg-stretch">
                                                    <a href="<?php echo $url; ?>"> 
                                                    <?php echo preg_replace('#(width|height)=\"\d*\"\s#', "", wp_get_attachment_image( $bg_image,'thumbnail_246x246') ); ?>
                                                    </a>
                                                    </div>
                                                <?php endif; ?>
                                                <?php if ( $title || $icon ) : ?>
                                                    <div class="text-area">
                                                        <?php if ( $icon ) : ?>
                                                            <div class="ico">
                                                            <a href="<?php echo $url; ?>"> 
                                                            <?php echo preg_replace('#(width|height)=\"\d*\"\s#', "", wp_get_attachment_image( $icon,'thumbnail_97x75') ); ?>
                                                            </a>
                                                            </div>
                                                        <?php endif; ?>
                                                        <?php if ( $title ) echo '<strong class="title">'. '<a href="' . $url . '">' . $title .'</a></strong>'; ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                          </div>
                                    </li>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        </ul>
                    <?php endif; ?>
                    <a href="/hair-testing-services/" class="btn"><?php _e( 'SEE OUR HEAIR TESTS', 'allergenics' ); ?></a>
                </div>
            </section>
        <?php endif; ?>   
        
        <?php ///////// About Section /////////// ?>
        <?php if ( $about_page = get_field( 'about_page' )) : ?>
            <section class="services-block text-info-block desktopview">
              <div class="about-home">  
                <?php $about = new WP_Query( array( 'page_id' => $about_page->ID ) ); ?>
                    <?php while ( $about->have_posts()) : $about->the_post(); ?>
                        <?php the_title( '<h2>','</h2>' ); ?>
               <?php echo apply_filters('the_excerpt',get_the_excerpt())?>
                        <p><a href="<?php echo get_permalink(); ?>"><?php echo __( 'Read More', 'allergenics' ) ?></a></p>
                    <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
              </div>
              
              <ul class="services-list">
                                    <li>
                                        <div class="holder">
                                            <div class="front">
                                                    <div class="bg-stretch">
                                                      <img src="<?php bloginfo('template_url');?>/images/team1.jpg" alt="Natasha Berman" />
                                                    </div>
                                                    <div class="text-area">
                                                       <h3>Natasha Berman</h3>
                                                       <p>Managing Director/Naturopath/Medical Herbalist</p>
                                                    </div>
                                            </div>
                                                <div class="back">
                                                    <div class="text-box">
                                                        Natasha is the Managing Director of Allergenics – Health Assessment Services, Australasia's leading Hair Testing Service. She is also the founder of Qbaby.
                                                    </div>
                                                </div>
                                        </div>
                                    </li>
                                    
                                    <li>
                                        <div class="holder">
                                            <div class="front">
                                                    <div class="bg-stretch">
                                                      <img src="<?php bloginfo('template_url');?>/images/team2.jpg" alt="Brett Friedman" />
                                                    </div>
                                                    <div class="text-area">
                                                       <h3>Brett Friedman</h3>
                                                       <p>Allergenics Chief Health Officer/Nutritional Medicine Practitioner</p>
                                                    </div>
                                            </div>
                                                <div class="back">
                                                    <div class="text-box">
                                                        Brett Friedman is a qualified Nutritional Medicine Practitioner with a Master's Degree in Medical Research. He has over 17 years experience in the natural health industry.
                                                    </div>
                                                </div>
                                        </div>
                                    </li>
                                    
                                    
                                    <li>
                                        <div class="holder">
                                            <div class="front">
                                                    <div class="bg-stretch">
                                                      <img src="<?php bloginfo('template_url');?>/images/team3.jpg" alt="Stephanie Kercher" />
                                                    </div>
                                                    <div class="text-area">
                                                       <h3>Stephanie Kercher</h3>
                                                       <p>Practitioner Support Representative/Naturopath</p>
                                                    </div>
                                            </div>
                                                <div class="back">
                                                    <div class="text-box">
                                                        Stephanie is a qualified Naturopath and has a degree in Health Science from Charles Stuart University (N.S.W. Australia).
                                                    </div>
                                                </div>
                                        </div>
                                    </li>
            </ul>
            </section>
            
            <section class="services-block text-info-block mobileview">
              <div class="about-home">  
                <?php $about = new WP_Query( array( 'page_id' => $about_page->ID ) ); ?>
                    <?php while ( $about->have_posts()) : $about->the_post(); ?>
                        <?php the_title( '<h2>','</h2>' ); ?>
               <?php echo apply_filters('the_excerpt',get_the_excerpt())?>
                        <p><a href="<?php echo get_permalink(); ?>"><?php echo __( 'Read More &raquo;', 'allergenics' ) ?></a></p>
                    <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
              </div>
              
              <ul class="services-list2">
                                    <li>
                                        <div class="holder">
                                            <div class="front">
                                                    <div class="bg-stretch">
                                                      <img src="<?php bloginfo('template_url');?>/images/team1.jpg" alt="Natasha Berman" />
                                                    </div>
                                                    <div class="text-area">
                                                       <h3>Natasha Berman</h3>
                                                       <p>Managing Director/Naturopath/Medical Herbalist</p>
                                                    </div>
                                            </div>
                                        </div>
                                    </li>
                                    
                                    <li>
                                        <div class="holder">
                                            <div class="front">
                                                    <div class="bg-stretch">
                                                      <img src="<?php bloginfo('template_url');?>/images/team2.jpg" alt="Brett Friedman" />
                                                    </div>
                                                    <div class="text-area">
                                                       <h3>Brett Friedman</h3>
                                                       <p>Allergenics Chief Health Officer/Nutritional Medicine Practitioner</p>
                                                    </div>
                                    </li>
                                    
                                    
                                    <li>
                                        <div class="holder">
                                            <div class="front">
                                                    <div class="bg-stretch">
                                                      <img src="<?php bloginfo('template_url');?>/images/team3.jpg" alt="Stephanie Kercher" />
                                                    </div>
                                                    <div class="text-area">
                                                       <h3>Stephanie Kercher</h3>
                                                       <p>Practitioner Support Representative/Naturopath</p>
                                                    </div>
                                            </div>
                                        </div>
                                    </li>
            </ul>
            </section>
        <?php endif; ?>
        
        
        <?php ///////// FAQs Section /////////// ?>
        <?php $faqs_heading = get_field( 'faqs_heading' ); ?>
        <?php if ( $faqs_heading || have_rows( 'faqs' )) : ?>
            <section class="carousel-block">
                <div class="container">
                    <?php if ( $faqs_heading ) echo '<h2>'. $faqs_heading .'</h2>'; ?>
                    <?php //if ( have_rows( 'faqs' )) : ?>
					<?php $args = array(
          	'posts_per_page'   => 5,
          	'offset'           => 0,
          	'orderby'          => 'ID',
          	'order'            => 'DESC',
          	'post_type'        => 'testimonial',
          	'post_status'      => 'publish',
          	
          );
          $testimonials = get_posts( $args ); ?>

                        <div class="carousel">
                            <div class="mask">
                                <div class="slideset">
                                   					<?php foreach ( $testimonials as $testimonial ) { ?>
                                            <div class="slide">
                                                <blockquote>
                                                    <?php  echo '<q>'. $testimonial->post_content .'</q>'; ?>
                                                    <?php  echo '<cite>– '. get_post_meta($testimonial->ID,'_ikcf_client',true) .'</cite>'; ?>
                                                </blockquote>
                                            </div>
											
                                      <?php } wp_reset_postdata();?>
                                </div>
                            </div>
                            <a class="btn-prev" href="#"><span class="icon-btn-left"></span></a>
                            <a class="btn-next" href="#"><span class="icon-btn-right"></span></a>
                        </div>
						
                        <p><a href="/testimonials/"><?php echo __( 'Read More', 'allergenics' ) ?></a></p>
                    <?php //endif; ?>
                </div>
            </section>
            
            
          <!-- PRACTICIONERS SECTION -->    
            
            <section class="practitioner-home-bottom">
              <div class="container clearfix">
                <div class="pract-left">
                  <h3>Health Practitioners</h3>
                  <p>How can allergenics hair testing service help you as a practitioner?</p>
                  <a href="/practitioners/" class="btn practbtn">REGISTER HERE &raquo;</a>
                </div>
                <div class="pract-right">
                  <ul>
                    <li><img src="<?php bloginfo('template_url');?>/images/picon1.png" /><span>Provides you with additional information on your client's current state of health.</span></li>
                    <li class="last"><img src="<?php bloginfo('template_url');?>/images/picon2.png" /><span>Assists you in prioritising your client's treatment protocol.</span></li>
                    <li><img src="<?php bloginfo('template_url');?>/images/picon3.png" /><span>Provides your client with a reference point from which they can monitor their health progress.</span></li>
                    <li class="last"><img src="<?php bloginfo('template_url');?>/images/picon4.png" /><span>The test is simple and non-invasive, a benefit for both you and your clientele.</span></li>
                  </ul>
                </div>                  
              </div>
            </section>
            
            
        <?php endif; ?>
<?php get_footer(); ?>