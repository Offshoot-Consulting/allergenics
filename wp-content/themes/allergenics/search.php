<?php get_header(); ?>
	<div class="container">
        <div id="content">
            <?php if ( have_posts() ) : ?>
                <div class="title">
                    <h1><?php printf( __( 'Search Results for: %s', 'allergenics' ), '<span>' . get_search_query() . '</span>'); ?></h1>
                </div>
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'blocks/content', get_post_type() ); ?>
                <?php endwhile; ?>
                <?php get_template_part( 'blocks/pager' ); ?>
            <?php else : ?>
                <?php get_template_part( 'blocks/not_found' ); ?>
            <?php endif; ?>
        </div>
        <?php get_sidebar(); ?>
    </div>
<?php get_footer(); ?>