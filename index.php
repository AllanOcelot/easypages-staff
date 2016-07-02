<?php
/*
 * Plugin Name: Easypages Staff
 * Plugin URI:
 * Description: This plugin adds a "Meet the team" and "our Staff" functionality to Wordpress
 * Version: 1.0.0
 * Author: Allan McKernan
 * Author URI:
 * License: GPL2
 */


 ##########################################################
 ##### Add our Styles and Our Javascript to Wordpress #####
 ##########################################################
 function easyStaffCSS() {
   //Insert our stylesheet
   //echo "<link rel='stylesheet'  href='/wp-content/plugins/easyfaq/assets/main.css' type='text/css' media='all'>";
   wp_register_style( 'easyStaffstyles', plugins_url( '/assets/main.css', __FILE__ ), array(), '20120208', 'all' );
   wp_enqueue_style('easyStaffstyles');
 }
add_action( 'wp_enqueue_scripts', 'easyStaffCSS' );

//Hook JS into footer
function easyStaffJSFiles(){
  wp_register_script( 'easyStaffJS', plugins_url( '/assets/easyStaff.js', __FILE__ ) );
  wp_enqueue_script('jquery');
  wp_enqueue_script( 'easyStaffJS' );
}
add_action( 'wp_enqueue_scripts', 'easyStaffJSFiles' );

############################################################
#
#
#
#
#
#
#
#
#
#
#
###############################################
##### Create Custom Post Type for our FAQ #####
###############################################
function easyStaffPostType() {
	$labels = array(
		'name'               => _x( 'Staff', 'post type general name', 'your-plugin-textdomain' ),
		'singular_name'      => _x( 'Staff', 'post type singular name', 'your-plugin-textdomain' ),
		'menu_name'          => _x( 'Staff', 'admin menu', 'your-plugin-textdomain' ),
		'name_admin_bar'     => _x( 'Staff', 'add new on admin bar', 'your-plugin-textdomain' ),
		'add_new'            => _x( 'Add New', 'FAQ', 'your-plugin-textdomain' ),
		'add_new_item'       => __( 'Add New Staff member', 'your-plugin-textdomain' ),
		'new_item'           => __( 'New Staff member', 'your-plugin-textdomain' ),
		'edit_item'          => __( 'Edit Staff member', 'your-plugin-textdomain' ),
		'view_item'          => __( 'View Staff member', 'your-plugin-textdomain' ),
		'all_items'          => __( 'All Staff members', 'your-plugin-textdomain' ),
		'search_items'       => __( 'Search Staff members', 'your-plugin-textdomain' ),
		'not_found'          => __( 'No staff found.', 'your-plugin-textdomain' ),
		'not_found_in_trash' => __( 'No staff found in trash. (Ha!)', 'your-plugin-textdomain' )
	);

	$args = array(
		'labels'             => $labels,
    'description'        => __( 'Description.', 'your-plugin-textdomain' ),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'FAQs' ),
		'capability_type'    => 'post',
		'has_archive'        => false,
		'hierarchical'       => false,
    'menu_icon'          => 'dashicons-groups',
		'supports'           => array( 'title', 'editor', 'author', 'custom-fields' , 'excerpt', 'thumbnail')
	);

	register_post_type( 'staff_members', $args );
}
add_action( 'init', 'easyStaffPostType' );
########################################################
#
#
#
#
#
#
#
#
#
#
########################################################
##### Query the Staff Members and put them into our UL ###########
########################################################
function easyStaffQuery(){


  //Should the staff members name link to their post type?
  $staffname_is_link = false;

  //Display link to post type as button?
  $staffname_button_visible = true;
    //If so, what text should it have?
    $staffname_button_text = "Read More";


  $args = array(
  	'post_type' => 'staff_members',
    'posts_per_page' => '-1'
  );
  $the_query = new WP_Query( $args );

  // Loop Over Results
  if ( $the_query->have_posts() ) {
  	echo "<div class='easy-staff-container'>";
  	while ( $the_query->have_posts() ) {
  		$the_query->the_post();

      //If the post has a featured image - get it and use it.
      $feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );

       ?>
      <div class="staff-member" itemscope itemtype="https://schema.org/Person">
        <div class="staff-photo">
          <div class="image"
            <?php
              if($feat_image != null){ ?>
               style="background-image:url(<?php echo $feat_image; ?>)"
            <?php }
            ?>
          ></div>
        </div>
        <div class="title" itemType="name"><?php echo the_title(); ?></div>
        <div class="role" itemtype="jobTitle">Example role</div>
        <div class="content">
          <?php echo the_excerpt(); ?>
        </div>
        <?php if($staffname_button_visible == true){ ?>
        <a class="viewProfile" href="<?php echo the_permalink(); ?>">
          <?php echo $staffname_button_text; ?>
        </a>
        <?php } ?>
      </div>
      <?php
  	}
  	echo '</div>';
  } else {
  	// no posts found
  }
  /* Restore original Post Data */
  wp_reset_postdata();
}

## Add funciton as shortcode so user can call it ##
add_shortcode('easyStaff' , 'easyStaffQuery');

########################################################
#
#
#
#
#
#
#
#
#
#
#
########################################################
