<?php
/*
Template Name: Order Test Template
*/
get_header(); ?>
        <?php while ( have_posts()) : the_post(); ?>
                <section class="form-section">
                  <div class="container clearfix">
                    <div class="cont-left">
                    <?php the_content(); ?> 
                   
                    </div>
                    <div class="cont-right">
                      <?php dynamic_sidebar( 'contact-sidebar' ); ?>
                    </div>
                  </div>
                </section>
        <?php endwhile; ?>
<?php get_footer(); ?>