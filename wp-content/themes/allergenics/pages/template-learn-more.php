<?php
/*
Template Name: Learn More
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
                      <?php dynamic_sidebar( 'learn-more-sidebar' ); ?>
                    </div>
                  </div>
                </section>
        <?php endwhile; ?>
<?php get_footer(); ?>