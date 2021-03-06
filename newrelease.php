<?php 
/*
Plugin Name:New release
Version: 0.1
Plugin URI: phoenix.sheridanc.on.ca/~ccit2721/
Description:  Testimonials is a plugin that lets you add  testimonials in WordPress
Author: Mayowa and Harinder
Author URI: phoenix.sheridanc.on.ca/~ccit2721/
*/




//this is the code to enque the style sheet to the plugin widget. this was the same exact code that was correct during the shortcode lab but it has refused to enque styles to my plugin

function add_my_stylesheet() 
   {
      wp_register_style( 'custom-style', plugins_url( 'plugin.css', __FILE__ ) );
  	wp_enqueue_style('custom-style');
  
  }
  add_action('wp_enqueue_scripts', 'add_my_stylesheet');

//this is the beginning of the this array below controls the wordings of the title of the custom page itself and all the other elements within it


add_action( 'init', 'register_cpt_cp_name' );
 
function register_cpt_cp_name() {
 
    $labels = array( 
  	'name'               => __( 'New releases', 'new releases' ),
		'singular_name'      => __( 'New releases', 'new releases' ),
		'add_new'            => __( 'Add New releases', 'new releases', 'new releases' ),
		'add_new_item'       => __( 'Add New releases', 'new releases' ),
		'edit_item'          => __( 'Edit New releases', 'new releases' ),
		'new_item'           => __( 'New car releases', 'new releases' ),
		'view_item'          => __( 'View New releases', 'new releases' ),
		'search_items'       => __( 'Search New releases', 'new releases' ),
		'not_found'          => __( 'No New releases found', 'new releases' ),
		'not_found_in_trash' => __( 'No New releases found in Trash', 'new releases' ),
		'parent_item_colon'  => __( 'Parent New releases:', 'new releases' ),
		'menu_name'          => __( 'New releases', 'new releases' ),
    );
 
  $args = array( 
        'labels' => $labels,
        'hierarchical' => false,
        
        'supports' => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'custom-fields', 'revisions' ),
        
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        
        
        'show_in_nav_menus' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'has_archive' => true,
        'query_var' => true,
        'can_export' => true,
        'rewrite' => true,
        'capability_type' => 'post'
    );
 
    register_post_type( 'cp_name', $args );
}

// function create_post_type_labels($singular, $plural = null) {
// 
// 	if ($plural === null) {
// 		$plural = $singular.'s';
// 	}
// 
// 	$labels = array(
// 		'name'               => __( $plural, 'text-domain'),
// 		'singular_name'      => __( $singular, 'text-domain'),
// 		'menu_name'          => __( $plural, 'text-domain'),
// 		'name_admin_bar'     => __( $singular, 'text-domain'),
// 		'add_new'            => __( 'Add New '.$singular, 'text-domain'),
// 		'add_new_item'       => __( 'Add New '.$singular, 'text-domain'),
// 		'new_item'           => __( 'New '.$singular, 'text-domain'),
// 		'edit_item'          => __( 'Edit '.$singular, 'text-domain'),
// 		'view_item'          => __( 'View '.$singular, 'text-domain'),
// 		'all_items'          => __( 'All '.$plural, 'text-domain'),
// 		'search_items'       => __( 'Search '.$plural, 'text-domain'),
// 		'parent_item_colon'  => __( 'Parent '.$plural.':', 'text-domain'),
// 		'not_found'          => __( 'No '.$plural.' found.', 'text-domain'),
// 		'not_found_in_trash' => __( 'No '.$plural.' found in Trash.', 'text-domain')
// 	);
// 
// 	return $labels;
// }

//end of the custom post type 

//the following codes below is where we begin to turn our plugin custom postype into a widget

function n2wp_latest_cpt_init() {
if ( !function_exists( 'register_sidebar_widget' ))
return;

function n2wp_latest_cpt($args) {
global $post;
extract($args);

// The following set of code below are our own options
$options = get_option( 'n2wp_latest_cpt' );
$title = $options['title']; // Widget title
$phead = $options['phead']; // Heading format
$ptype = $options['ptype']; // Post type
$pshow = $options['pshow']; // Number of Tweets

$beforetitle = '';
$aftertitle = '';

// this is what the page outputs
echo $before_widget;

if ($title) echo $beforetitle . $title . $aftertitle;


//this is where we control the posts the seen on the widget side bar

$pq = new WP_Query(array( 'post_type' => 'cp_name', 'showposts' => $pshow ));
if( $pq->have_posts() ) :

// //this code below is what makes the post titles in our side bar widget clickable and takes the user to the selected posts in out custom post type

//the_title code is what makes only the title appear in the post, if the code were to say the_content, then the entire content in the post will appear in the sidebar   
?>


<?php while($pq->have_posts()) : $pq->the_post(); ?>

    <li><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></li>
    
   <?php
    global $post;

if ( has_post_thumbnail( $post->ID ) )
  echo get_the_post_thumbnail( $post->ID, 'your-image-size' );
  
  ?>

<?php wp_reset_query();
endwhile; ?>

<?php endif; ?>




<?php
// this code below echos the widget closing tag
echo $after_widget;
}

/**
* Widget settings form function
*/
function n2wp_latest_cpt_control() {

// Get options. the code beloe also controls the amount of posts in the widget. no matter the amount of posts are in the custom post type:new releases, only 5 of those posts will show
$options = get_option( 'register_cpt_cp_name' );

if ( !is_array( $options ))
$options = array(
'title' => 'New Releases',
'phead' => 'h2',
'ptype' => 'post',
'pshow' => '5'
);

if ( $_POST ) {
$options['title'] = strip_tags( $_POST['latest-cpt-title'] );
$options['phead'] = $_POST['latest-cpt-phead'];
$options['ptype'] = $_POST['latest-cpt-ptype'];
$options['pshow'] = $_POST['latest-cpt-pshow'];
update_option( 'n2wp_latest_cpt', $options );
}
// Get options for form fields to show
$title = $options['title'];
$phead = $options['phead'];
$ptype = $options['ptype'];
$pshow = $options['pshow'];

// // The code below controls the widget form fields, when you go to appearance and then widgets and then select the widget "Latest custom posts:New releases" the form fields below will be visible 

?>

 
<label for="latest-cpt-title"><?php echo __( 'Widget Title' ); ?>
<input id="latest-cpt-title" type="text" name="latest-cpt-title" size="30" value="<?php echo $title; ?>" />
</label>

<label for="latest-cpt-phead"><?php echo __( 'Widget Heading Format' ); ?></label>


<select name="latest-cpt-phead"><option selected="selected" value="h2">H2 - <h2></h2></option><option selected="selected" value="h3">H3 - <h3></h3></option><option selected="selected" value="h4">H4 - <h4></h4></option><option selected="selected" value="h2">Bold - <strong></strong></option></select><select name="latest-cpt-ptype"><option value="">- <?php echo __( 'New releases' ); ?> -</option></select><?php  $args = array( 'public' => true );
$post_types = get_post_types( $args, 'names' );
foreach ($post_types as $post_type ) { ?>


<select name="latest-cpt-ptype"><option selected="selected" value="<?php echo $post_type; ?>"><?php echo $post_type;?></option></select><?php } ?>

<label for="latest-cpt-pshow"><?php echo __( 'Number of posts to show' ); ?>
<input id="latest-cpt-pshow" type="text" name="latest-cpt-pshow" size="2" value="<?php echo $pshow; ?>" />
</label>

<input id="latest-cpt-submit" type="hidden" name="latest-cpt-submit" value="1" />

<?php
}


wp_register_sidebar_widget( 'widget_latest_cpt', __('Latest Custom Posts'), 'n2wp_latest_cpt' );
wp_register_widget_control( 'widget_latest_cpt', __('Latest Custom Posts'), 'n2wp_latest_cpt_control', 300, 200 );

}
add_action( 'widgets_init', 'n2wp_latest_cpt_init' );

?>
