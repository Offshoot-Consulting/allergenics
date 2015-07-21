<?php

/*

Template Name: Template Testing

*/

get_header(); ?>







        <?php while ( have_posts()) : the_post(); ?>

                <section class="form-section innerpage">

                  <div class="container clearfix">

                    <div class="cont-left" style="width:100%">

                    <h1><?php the_title(); ?></h1>

                    <?php the_content(); ?> 

                    </div>    

                  </div>

                </section>

        <?php endwhile; ?>

               

<?php get_footer(); ?>