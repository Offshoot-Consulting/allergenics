<?php
#-----------------------------------------------------------------
# Columns
#-----------------------------------------------------------------

$jw_faq_shortcode = array();


$cats = array(__('All Categories', 'jeweltheme'));
foreach(get_terms('faq_cat', 'orderby=count&hide_empty=0&post_type=faq') as $term ){
    $cats[$term->slug] = $term->name;
}

$tags = array(__('All Tags', 'jeweltheme'));
foreach(get_terms('faq_tags', 'orderby=count&hide_empty=0') as $term ){
    $tags[$term->slug] = $term->name;
}

// Custom FAQ
$jw_faq_shortcode['colorful_faq'] = array( 
    'type'=>'radios', 
    'title'=>__('Custom FAQ\'s', 'jeweltheme'),
    'attr'=>array(

        'cat'=>array(
            'type'=>'select', 
            'title'=> __('Category', 'jeweltheme'), 
            'values'=> $cats
            ),        
        'tag'=>array(
            'type'=>'select', 
            'title'=> __('Tags', 'jeweltheme'), 
            'values'=> $tags
            ),
        'items'=>array(
            'type'=>'text', 
            'title'=> __('Number Of Posts', 'jeweltheme'), 
            'value'=> '-1'
            ),        
        'order'=>array(
            'type'=>'select', 
            'title'=> __('Order', 'jeweltheme'), 
            'values'=>array(
                'DESC'=>'Descending',
                'ASC'=>'Ascending',
                )
            )
        )
);


// Nested FAQ
$jw_faq_shortcode['nested_faq'] = array( 
    'type'=>'radios', 
    'title'=>__('Nested FAQ\'s', 'jeweltheme'),
    'attr'=>array(

        'cat'=>array(
            'type'=>'select', 
            'title'=> __('Nested FAQ Category', 'jeweltheme'), 
            'values'=> $cats
            ),        

        'items'=>array(
            'type'=>'text', 
            'title'=> __('Number Of Posts(For All -1)', 'jeweltheme'), 
            'value'=> '3'
            ),        
        'order'=>array(
            'type'=>'select', 
            'title'=> __('Order', 'jeweltheme'), 
            'values'=>array(
                'DESC'=>'Descending',
                'ASC'=>'Ascending',
                )
            )
        )
);

