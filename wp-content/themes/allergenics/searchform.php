<?php $sq = get_search_query() ? get_search_query() : __( 'Enter search terms&hellip;', 'allergenics' ); ?>
<form method="get" class="search-form" action="<?php echo home_url(); ?>">
	<input type="search" name="s" placeholder="<?php echo $sq; ?>" value="<?php echo get_search_query(); ?>" />
	<input type="submit" value="Search" />
</form>