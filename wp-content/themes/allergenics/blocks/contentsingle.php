<div class="post clearfix postsingle" id="post-<?php the_ID(); ?>">
	
	<h1><?php the_title(); ?></h2>		
			
			<?php if ( function_exists( 'ADDTOANY_SHARE_SAVE_KIT' ) ) { ADDTOANY_SHARE_SAVE_KIT(); } ?>
			
      <p class="info">
        | Posted at <span class="date"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_time( 'F jS, Y' ) ?></a></span>
        <?php _e( 'by', 'allergenics' ); ?> <?php the_author(); ?> | 
        <?php comments_popup_link( __( 'No Comments', 'allergenics' ), __( '1 Comment', 'allergenics' ), __( '% Comments', 'allergenics' ) ); ?>
      </p>   
      
  <?php if (has_post_thumbnail()) { ?>
      <?php the_post_thumbnail( 'single-thumb' ); ?>
  <?php } ?>
			
  <?php the_content(); ?>
	
	<?php wp_link_pages(); ?>

  <div class="meta">
		<ul>
			<li><?php _e( '<b>Posted in:</b> ', 'allergenics' ); ?> <?php the_category( ' ' ) ?></li>
			<?php the_tags( __( '<li><b>Tags:</b> ', 'allergenics' ), ' ', '</li>' ); ?>
			<?php //edit_post_link( __( 'Edit', 'allergenics' ), '<li>', '</li>' ); ?>
		</ul>
	</div>
	
	
    
    <?php
        $orig_post = $post;
        global $post;
        $tags = wp_get_post_tags($post->ID);
         
        if ($tags) { 
        $tag_ids = array();
        foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
        $args=array(
          'tag__in' => $tag_ids,
          'post__not_in' => array($post->ID),
          'posts_per_page' => 3, // Number of related posts to display.
        );
         
        $my_query = new wp_query( $args );
        
        if( $my_query->have_posts() ) { ?>
        
        <div class="relatedposts">
          <h2>Related posts</h2>
          
        <?php } 
     
        while( $my_query->have_posts() ) {
         $my_query->the_post();
        ?>
         
        <div class="relatedthumb">
            <a rel="external" href="<?php the_permalink()?>">
              <?php if (has_post_thumbnail()) { ?>
                <?php the_post_thumbnail('listing-thumb'); ?>
              <?php } else { ?>
                <img class="attachment-listing-thumb wp-post-image" src="<?php bloginfo('template_url');?>/images/placeholder.jpg" alt="<?php the_title(); ?>" />
              <?php } ?>
            </a>
            <h3><a rel="external" href="<?php the_permalink()?>"><?php the_title(); ?></a></h3>
            <p class="info">
                <span class="date"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_time( 'F jS, Y' ) ?></a></span>
                <?php _e( 'by', 'allergenics' ); ?> <?php the_author(); ?> | 
                <?php comments_popup_link( __( 'No Comments', 'allergenics' ), __( '1 Comment', 'allergenics' ), __( '% Comments', 'allergenics' ) ); ?>
              </p>
            <span class="text"><?php the_excerpt(); ?></span>
            </a>
        </div>
         
        <?php }
        
        if( $my_query->have_posts() ) { echo '</div>'; }
        
        }
        $post = $orig_post;
        wp_reset_query();
        ?>


	
	
</div>
