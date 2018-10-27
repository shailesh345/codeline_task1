<?php
add_action( 'wp_enqueue_scripts', 'enqueue_child_theme_styles', PHP_INT_MAX);
function enqueue_child_theme_styles() {
  wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );
}

####### Add Type Post Films ########

function codeline_films_post_type()
{

$labels=[
    'name'=>'Films',
    'singular_name'=>'Film',
    'add_new'=>'Add Film',
    'all_items'=>'All Films',
    'add_new_item'=>'Add Films',
    'edit_item'=>'Edit Film',
    'new_item'=>'New Film',
    'view_item'=>'View Film',
    'search_itme'=>'Search Film',
    'not_found'=>'No Film Record Found',
    'not_found_in_trash'=>'No film record found in trash',
    'parent_item_colon'=>'Parent Film'
    ];


$args=[
    'labels'=>$labels,
    'public'=>TRUE,
    'has_archive'=>TRUE,
    'publicly_queryabe'=>TRUE,
    'query_var'=>TRUE,
    'rewrite'=>TRUE,
    'capability_type'=>'post',
    'hierarchical'=>FALSE,
    'supports'=>[
        'tite','editor','excerpt','thumbnail','revisions'
    ],
    'taxonomies'=>['category','post_tag'],
    'menu_position'=>2,
    'exclude_from_search'=>FALSE
];

register_post_type('films', $args);

}

add_action('init', 'codeline_films_post_type');
###### Add texanomy for Post Film ######

?>
