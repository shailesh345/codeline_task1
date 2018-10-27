<?php
/**
 * Template Name: Films Template
 *
 * This is the template that displays full width page without sidebar
 *
 * @package unite
 */

get_header(); ?>
	<div id="primary" class="content-area col-sm-12 col-md-8 <?php echo of_get_option( 'site_layout' ); ?>">
		<main id="main" class="site-main" role="main">
<?php

$args=[
    'post_type'=>'films',
    'post_per_page'=>3
];


$loop=new WP_Query($args);

if($loop->have_posts()):

		while ( $loop->have_posts() ) : $loop->the_post(); ?>
       
<?php get_template_part( 'content', 'archive' ); ?>
 <div class='film-data'>
       <?php 
       
      echo "<h3>Description</h3>";
      echo get_the_term_list( $loop->ID, 'year', 'Year: ', ', ', '' ); 
      echo "<br>";
      echo get_the_term_list( $loop->ID, 'country', 'Country: ', ', ', '' ); 
      echo "<br>";
      echo get_the_term_list( $loop->ID, 'genre', 'Genres : ', ', ', '' ); 
      echo "<br>";
      echo get_the_term_list( $loop->ID, 'actor', 'Actors : ', ', ', '' ); 
       echo "<br>";
      $custom = get_post_custom();
if(isset($custom['ticketprice'])) {
    echo 'Ticket Price : '.$custom['ticketprice'][0];
}
echo "<br>";
      if(isset($custom['releasedate'])) {
    echo 'Release Date : '.$custom['releasedate'][0];
}
   
      ?> 
   <hr class="films-section-divider">
</div>

		<?php endwhile;
      endif;
      ?>
	</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>