<div class="post clearfix" id="post-<?php the_ID(); ?>">
	
  	<div class="post-left">
  	 <a href="<?php the_permalink(); ?>">	
       <?php if (has_post_thumbnail()) { ?>
        <?php the_post_thumbnail('listing-thumb'); ?>
      <?php } else { ?>
        <img class="attachment-listing-thumb wp-post-image" src="<?php bloginfo('template_url');?>/images/placeholder.jpg" alt="<?php the_title(); ?>" />
      <?php } ?>
     </a>
  	</div>

	<div class="post-right"> 	
			<?php the_title( '<h2><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>		
			<p class="info">
        <span class="date"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_time( 'F jS, Y' ) ?></a></span>
        <?php _e( 'by', 'allergenics' ); ?> <?php the_author(); ?> | 
        <?php comments_popup_link( __( 'No Comments', 'allergenics' ), __( '1 Comment', 'allergenics' ), __( '% Comments', 'allergenics' ) ); ?>
      </p>
			<?php the_excerpt(); ?>
			
			<?php if ( function_exists( 'ADDTOANY_SHARE_SAVE_KIT' ) ) { ADDTOANY_SHARE_SAVE_KIT(); } ?>
			<a class="readmore" href="<?php the_permalink() ?>" rel="bookmark">Read More &raquo;</a>
	</div>
	
	<?php wp_link_pages(); ?>
	
  <!--
  <div class="meta">
		<ul>
			<li><?php _e( 'Posted in', 'allergenics' ); ?> <?php the_category( ', ' ) ?></li>
			<li><?php comments_popup_link( __( 'No Comments', 'allergenics' ), __( '1 Comment', 'allergenics' ), __( '% Comments', 'allergenics' ) ); ?></li>
			<?php the_tags( __( '<li>Tags: ', 'allergenics' ), ', ', '</li>' ); ?>
			<?php edit_post_link( __( 'Edit', 'allergenics' ), '<li>', '</li>' ); ?>
		</ul>
	</div>
	-->
	
	
</div>
