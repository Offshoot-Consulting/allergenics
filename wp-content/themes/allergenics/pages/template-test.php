<?php
/*
Template Name: Template Single Test
*/

get_header(); ?>

  <?php while ( have_posts()) : the_post(); ?>

    <section class="form-section innerpage single single-product woocommerce woocommerce-page">
      <div class="container clearfix">
        <div class="product type-product has-post-thumbnail">          
          <div class="images">
            <?php the_post_thumbnail('large'); ?>
          </div>          
                  
          <div class="summary entry-summary">
            <h1 class="product_title entry-title" itemprop="name"><?php the_title(); ?></h1>
              <p class="price"><span class="amount">$<?php the_field('price'); ?></span></p>
                <div itemprop="description">
                  <?php the_content(); ?> 
                </div>
          </div>
      </div>
    </section>
<?php endwhile; ?>    

<?php get_footer(); ?>