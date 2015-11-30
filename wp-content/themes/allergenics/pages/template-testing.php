<?php
/*
Template Name: Template Testing
*/
get_header(); ?>

        <?php while ( have_posts()) : the_post(); ?>
                <section class="form-section innerpage" style="border-bottom:none">
                  <div class="container clearfix">
                    <div class="cont-left" style="width:100%">
                    <h1><?php the_title(); ?></h1>
                    <?php the_content(); ?> 
                    </div>
                  </div>
               </section>                         
        <?php endwhile; ?>
        
        <section class="testing-list woocommerce-page woocommerce">
        <div class="container clearfix team">
        
        <h2 style="margin-top:-14px">Our Testing Services</h3>
        
        <ul class="products">
        <?php
          $args = array(
              'post_type'      => 'page',
              'posts_per_page' => 4,
              'post_parent'    => $post->ID,
              'order'          => 'ASC',
              'orderby'        => 'menu_order'
           );
          $parent = new WP_Query( $args );
          
          if ( $parent->have_posts() ) : ?>
          
              <?php while ( $parent->have_posts() ) : $parent->the_post(); ?>
                 <li class="product">
                   <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('large'); ?></a>
                   <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><h3><?php the_title(); ?></h3></a>
                   <span class="price">$<?php the_field('price'); ?></span>
                   <div itemprop="description"><?php the_excerpt(); ?></div>
                   <div class="read_more"><a href="<?php the_permalink(); ?>" class="more">Read More</a></div>
                   <div class="gridlist-buttonwrap">
	                   <div class="add_to_cart_div">
                      <a class="button add_to_cart_button product_type_simple" href="#">ORDER YOUR TEST NOW</a>
                     </div>
                   </div>
                </li>
              <?php endwhile; ?>
          
          <?php endif; wp_reset_query(); ?>
        </ul>
        
        <h2>Other Services</h3>  
        
        <ul class="products">
        <?php
          $other = new WP_Query( array( 'pagename' => 'hair-testing-services/personal-consultation' ) );
          if ( $other->have_posts() ) : ?>
          
              <?php while ( $other->have_posts() ) : $other->the_post(); ?>
                 <li class="product">
                   <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_post_thumbnail('large'); ?></a>
                   <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><h3><?php the_title(); ?></h3></a>
                   <span class="price">$<?php the_field('price'); ?></span>
                   <div itemprop="description"><?php the_excerpt(); ?></div>
                   <div class="read_more"><a href="<?php the_permalink(); ?>" class="more">Read More</a></div>
                   <div class="gridlist-buttonwrap">
	                   <div class="add_to_cart_div">
                      <a class="button add_to_cart_button product_type_simple" href="#">ORDER YOUR CONSULTATION</a>
                     </div>
                   </div>
                </li>
              <?php endwhile; ?>
          
          <?php endif; wp_reset_query(); ?>
        </ul>
        
        </div>
        
        <hr />
        
        </section>

<?php get_footer(); ?>