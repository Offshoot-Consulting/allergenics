<?php get_header(); ?>
	<div class="container indexpage clearfix">
        <div class="cont-left">
            <?php the_archive_title( '<h1>', '</h1>' ); ?>
            <?php if ( have_posts() ) : ?>
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'blocks/content', get_post_type() ); ?>
                <?php endwhile; ?>
                
                <?php if(function_exists('wp_pagenavi')) { wp_pagenavi(); } ?>
                
            <?php else : ?>
                <?php get_template_part( 'blocks/not_found' ); ?>
            <?php endif; ?>
        </div>
        <div class="cont-right">
          <?php dynamic_sidebar('blog-sidebar'); ?>
        </div>
    </div>
<?php get_footer(); ?>