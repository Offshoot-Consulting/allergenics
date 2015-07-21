<?php
/*
Template Name: Home Template
*/
get_header(); ?>
	<section class="visual">
        <?php while ( have_posts()) : the_post(); ?>
            <?php if ( has_post_thumbnail()) : ?>
                <div class="bg-stretch"><?php echo preg_replace('#(width|height)=\"\d*\"\s#', "", wp_get_attachment_image( get_post_thumbnail_id(),'thumbnail_1600x583') ); ?></div>
            <?php endif; ?>
            <div class="container">
                <div class="holder-box">
                    <div class="wrap">
                        <?php the_title( '<h1>','</h1>' ); ?>
                        <?php if ( $order_test_link = get_field( 'order_test_link','option' )): ?>
                            <a href="<?php echo esc_url( $order_test_link ); ?>" class="btn"><?php _e( 'ORDER YOUR TEST NOW', 'allergenics' ); ?></a>
                        <?php endif; ?>
                        <?php if ( $read_more_link = get_field( 'read_more_link' )): ?>
                            <a href="<?php echo esc_url( $read_more_link ); ?>" class="more"><?php _e( 'Learn More', 'allergenics' ); ?></a>
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
                        <?php echo apply_filters('the_excerpt',get_the_excerpt().' <a href="'. get_permalink() .'">'. __( 'Read More', 'allergenics' ) .'</a>')?>
                    <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            </section>
        <?php endif; ?>
        
        <?php ///////// Services Section /////////// ?>
        <?php $services_heading = get_field( 'services_heading' ); ?>
        <?php $learn_more_link = get_field( 'learn_more_link' ); ?>
        <?php if ( $services_heading || have_rows( 'services' ) || $services_heading ) : ?>
            <section class="services-block">
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
                    <?php if ( $order_test_link = get_field( 'order_test_link','option' )): ?>
                        <a href="<?php echo esc_url( $order_test_link ); ?>" class="btn"><?php _e( 'ORDER YOUR TEST NOW', 'allergenics' ); ?></a>
                    <?php endif; ?>
                    <?php if ( $learn_more_link = get_field( 'learn_more_link' )): ?>
                        <a href="<?php echo esc_url( $learn_more_link ); ?>" class="more"><?php _e( 'Learn More', 'allergenics' ); ?></a>
                    <?php endif; ?>
                </div>
            </section>
        <?php endif; ?>   
        
        <?php ///////// About Section /////////// ?>
        <?php if ( $about_page = get_field( 'about_page' )) : ?>
            <section class="text-info-block">
                <?php $about = new WP_Query( array( 'page_id' => $about_page->ID ) ); ?>
                    <?php while ( $about->have_posts()) : $about->the_post(); ?>
                        <?php the_title( '<h2>','</h2>' ); ?>
                        <?php echo apply_filters('the_excerpt',get_the_excerpt().' <a href="'. get_permalink() .'">'. __( 'Read More', 'allergenics' ) .'</a>')?>
                    <?php endwhile; ?>
                <?php wp_reset_postdata(); ?>
            </section>
        <?php endif; ?>
        <?php ///////// FAQs Section /////////// ?>
        <?php $faqs_heading = get_field( 'faqs_heading' ); ?>
        <?php if ( $faqs_heading || have_rows( 'faqs' )) : ?>
            <section class="carousel-block">
                <div class="container">
                    <?php if ( $faqs_heading ) echo '<h2>'. $faqs_heading .'</h2>'; ?>
                    <?php if ( have_rows( 'faqs' )) : ?>
                        <div class="carousel">
                            <div class="mask">
                                <div class="slideset">
                                    <?php while ( have_rows( 'faqs' )) : the_row(); ?>
                                        <?php $quotes = get_sub_field( 'quotes' ); ?>
                                        <?php $author = get_sub_field( 'author' ); ?>
                                        <?php if ( $quotes || $author ) : ?>
                                            <div class="slide">
                                                <blockquote>
                                                    <?php if ( $quotes ) echo '<q>'. $quotes .'</q>'; ?>
                                                    <?php if ( $author ) echo '<cite>– '. $author .'</cite>'; ?>
                                                </blockquote>
                                            </div>
                                        <?php endif; ?>
                                    <?php endwhile; ?>
                                </div>
                            </div>
                            <a class="btn-prev" href="#"><span class="icon-btn-left"></span></a>
                            <a class="btn-next" href="#"><span class="icon-btn-right"></span></a>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        <?php endif; ?>
<?php get_footer(); ?>