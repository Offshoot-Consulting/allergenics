<?php get_header(); ?>
	<div class="container indexpage clearfix">
        <div class="cont-left">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'blocks/contentsingle', get_post_type() ); ?>
                    <?php comments_template(); ?>
                <?php //get_template_part( 'blocks/pager-single', get_post_type() ); ?>
                <?php endwhile; ?>
        </div>
        <div class="cont-right">
          <?php dynamic_sidebar('blog-sidebar'); ?>
        </div>
    </div>
<?php get_footer(); ?>