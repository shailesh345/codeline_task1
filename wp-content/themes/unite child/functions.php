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
    'publicly_queryable'=>TRUE,
    'query_var'=>TRUE,
    'rewrite'=>TRUE,
    'capability_type'=>'post',
    'hierarchical'=>FALSE,
    'supports'=>[
        'title','editor','thumbnail','revisions'
    ],
    //'taxonomies'=>['category','post_tag'],
    'menu_position'=>2,
    'menu_icon' => 'dashicons-editor-video',
    'register_meta_box_cb' => 'codeline_add_films_metaboxes',
    'exclude_from_search'=>FALSE
];

register_post_type('films', $args);

}

add_action('init', 'codeline_films_post_type');

###### Add following taxonimies to films: Genre, Country, Year and Actors ######

function codeline_custom_taxonomies()
{
//Task
$taxonomiesBucket=['Genre','Country','Year','Actor'];
//Create taxonomies
foreach($taxonomiesBucket as $taxonomy)
{
$labels=[
    'name'=>$taxonomy.'s',
    'singular_name'=>$taxonomy,
    'search_items'=>"Search $taxonomy",
    'all_items'=>"All $taxonomy",
    'parent_item'=>"Parent $taxonomy",
    'parent_item_colon'=>"Parent $taxonomy:",
    'edit_item'=>"Edit $taxonomy",
    'update_item'=>"Update $taxonomy",
    'add_new_item'=>"Add New $taxonomy",
    'new_item_name'=>"New $taxonomy Name",
    'menu_name'=>"$taxonomy"
];
$args=[
    'hierarchical'=>TRUE,
    'labels'=>$labels,
    'show_ui'=>TRUE,
    'show_admin_column'=>TRUE,
    'query_var'=>TRUE,
    'rewrite'=>['slug'=> strtolower($taxonomy)]   
];

register_taxonomy(strtolower($taxonomy),['films'], $args);

}
}

add_action('init','codeline_custom_taxonomies');

//Add custom text fields "Ticket Price" and "Release Date".

//HTML data for Ticket Price 
function codeline_films_ticketprice() {
	global $post;
	// Nonce field to validate form request came from current site
	wp_nonce_field( basename( __FILE__ ), 'films_fields' );
	// Get the ticket price data if it's already been entered
	$ticketprice = get_post_meta( $post->ID, 'ticketprice', true );
	// Output the field
	echo '<input type="text" name="ticketprice" value="' . esc_textarea($ticketprice)  . '" class="widefat">';
}

function codeline_films_releasedate() {
	global $post;
	// Nonce field to validate form request came from current site
	wp_nonce_field( basename( __FILE__ ), 'films_fields' );
	// Get the ticket price data if it's already been entered
	$releasedate = get_post_meta( $post->ID, 'releasedate', true );
	// Output the field
	echo '<input type="date" name="releasedate" value="' . esc_textarea($releasedate)  . '" class="widefat">';
}

function codeline_add_films_metaboxes() {
//Ticket Price
	add_meta_box(
		'codeline_films_ticketprice',
		'Ticket Price',
		'codeline_films_ticketprice',
		'films',
		'normal',
		'default'
	);
   
   //Release Date
   
   	add_meta_box(
		'codeline_films_releasedate',
		'Release Date',
		'codeline_films_releasedate',
		'films',
		'normal',
		'default'
	);
   
}
add_action('add_meta_boxes','codeline_add_films_metaboxes');

##### Save the metabox(Ticket Price and Release Date) data ########

function codeline_save_films_meta($post_id,$post) {
	// Return if the user doesn't have edit permissions.
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	}
	// Verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times.
	if ( ! isset( $_POST['ticketprice'] ) || ! wp_verify_nonce( $_POST['films_fields'], basename(__FILE__) ) ) {
		return $post_id;
	}
	// This sanitizes the data from the field and saves it into an array $films_meta.
	$films_meta['ticketprice'] = esc_textarea( $_POST['ticketprice'] );
   
   	if ( ! isset( $_POST['releasedate'] ) || ! wp_verify_nonce( $_POST['films_fields'], basename(__FILE__) ) ) {
		return $post_id;
	}
	// This sanitizes the data from the field and saves it into an array $films_meta.
	$films_meta['releasedate'] = esc_textarea( $_POST['releasedate'] );
   
	foreach ( $films_meta as $key => $value ) :
		// Don't store custom data twice
		if ( 'revision' === $post->post_type ) {
			return;
		}
		if ( get_post_meta( $post_id, $key, false ) ) {
			// If the custom field already has a value, update it.
			update_post_meta( $post_id, $key, $value );
		} else {
			// If the custom field doesn't have a value, add it.
			add_post_meta( $post_id, $key, $value);
		}
		if ( ! $value ) {
			// Delete the meta key if there's no value
			delete_post_meta( $post_id, $key );
		}
	endforeach;
}
add_action('save_post','codeline_save_films_meta', 1, 2 );
?>
