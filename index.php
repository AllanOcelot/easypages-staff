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

//Hook JS in



//Hook in the Meta for the image upload
function easyStaff_image_enqueue() {
    global $typenow;
    if( $typenow == 'staff_members' ) {
        wp_enqueue_media();

        // Registers and enqueues the required javascript.
        wp_register_script( 'meta-box-image', plugin_dir_url( __FILE__ ) . '/assets/metabox-image.js', array( 'jquery' ) );
        wp_localize_script( 'meta-box-image', 'meta_image',
            array(
                'title' => __( 'Choose or Upload an Image', 'easyStaff-textdomain' ),
                'button' => __( 'Use this image', 'easyStaff-textdomain' ),
            )
        );
        wp_enqueue_script( 'meta-box-image' );
    }
}
add_action( 'admin_enqueue_scripts', 'easyStaff_image_enqueue' );

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
		'add_new'            => _x( 'Add New', 'Staff', 'your-plugin-textdomain' ),
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
		'rewrite'            => array( 'slug' => 'Staff' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => true,
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
#######################################################
#####  Metabox functions ##############################
#######################################################
/**
 * functions for creating our meta boxes
 */
function easyStaff_meta_callback_role( $post ) {
  wp_nonce_field( basename( __FILE__ ), 'easyStaff_nonce1' );
  $easyStaff_stored_meta1 = get_post_meta( $post->ID );
  ?>
  <p>
    <input type="text" name="job-role" id="job-role" value="<?php if ( isset ( $easyStaff_stored_meta1['job-role'] ) ) echo $easyStaff_stored_meta1['job-role'][0]; ?>" />
  </p>

  <?php
}

function easyStaff_meta_callback_banner( $post ) {
  wp_nonce_field( basename( __FILE__ ), 'easyStaff_nonce2' );
  $easyStaff_stored_meta2 = get_post_meta( $post->ID );
  ?>
  <p>
    <input type="text" name="meta-image" id="meta-image" value="<?php if ( isset ( $easyStaff_stored_meta2['job-banner'] ) ) echo $easyStaff_stored_meta2['job-banner'][0]; ?>" />
    <input type="button" id="job-banner" class="button" value="<?php _e( 'Choose or Upload an Image', 'easyStaff-textdomain' )?>" />
  </p>

  <?php
}


//Add our meta boxes to the actual editor
function easyStaff_custom_meta() {
   add_meta_box( 'easyStaff1', __( 'Job Title', 'easyStaff-role' ), 'easyStaff_meta_callback_role', 'staff_members' , 'side', 'low' );
   add_meta_box( 'easyStaff2', __( 'Banner Image', 'easyStaff-banner' ), 'easyStaff_meta_callback_banner', 'staff_members' , 'side', 'low' );
}
add_action( 'add_meta_boxes', 'easyStaff_custom_meta'  );


/**
 * Saves the custom meta input
 */
function easyStaff_meta_save( $post_id ) {

    // Checks save status
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'easyStaff_nonce' ] ) && wp_verify_nonce( $_POST[ 'easyStaff_nonce' ], basename( __FILE__ ) ) ) ? 'true' : 'false';

    // Exits script depending on save status
    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }

    // Checks for input and sanitizes/saves if needed
    if( isset( $_POST[ 'job-role' ] ) ) {
      update_post_meta( $post_id, 'job-role', sanitize_text_field( $_POST[ 'job-role' ] ) );
    }
    if( isset( $_POST[ 'meta-image' ] ) ) {
      update_post_meta( $post_id, 'job-banner', $_POST[ 'meta-image' ] );
    }

}
add_action( 'save_post', 'easyStaff_meta_save' );


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
function easyStaffQuery($atts){

  //Shortcode options
  $displayOptions = shortcode_atts( array(
    //How many items should there be on a single row?
      'staff_per_row'           =>  4,
    //Should the staff members name link to their post type?
      'staffname_is_link'        => true,
    //Should the staff member's roles be visible
      'rolesEnabled'             => true,
    //Display link to post type as button?
      'staffname_button_visible' => true,
    //Give the User the ability to order the staff from the top (new, old, random)
      'staff_order'             => "new",
    //If so, what text should it have?
      'staffname_button_text'    => "Read More",
  ), $atts );


  //We will change this query based on the options above
  //Show the new members of staff at the top
  if($displayOptions[ 'staff_order' ] == "new"){
    $args = array(
    	'post_type' => 'staff_members',
      'posts_per_page' => '-1', 
      'orderby' => 'meta_value',
      'order' => 'DESC'
    );
  }
  if($displayOptions[ 'staff_order' ] == "old"){
    $args = array(
    	'post_type' => 'staff_members',
      'posts_per_page' => '-1',
      'orderby' => 'meta_value',
      'order' => 'ASC'
    );
  }
  if($displayOptions[ 'staff_order' ] == "random"){
    $args = array(
    	'post_type' => 'staff_members',
      'posts_per_page' => '-1',
      'orderby' => 'rand'
    );
  }










  $the_query = new WP_Query( $args );

  // Loop Over Results
  if ( $the_query->have_posts() ) {
  	echo "<div class='easy-staff-container'>";
  	while ( $the_query->have_posts() ) {
  		$the_query->the_post();

      //Have they includes a role for the staff member?
      $staff_role = get_post_meta( get_the_ID(), 'job-role', true );
      //If the post has a featured image - get it and use it.
      $feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );

      ?>
      <div class="staff-member
        <?php
          if($displayOptions[ 'staff_per_row' ] == 1){
            echo "full";
          }
          if($displayOptions[ 'staff_per_row' ] == 2){
            echo "two";
          }
          if($displayOptions[ 'staff_per_row' ] == 3){
            echo "three";
          }
          if($displayOptions[ 'staff_per_row' ] == 4){
            //Echo nothing - because default is four to a row
        } ?>
      " itemscope itemtype="https://schema.org/Person">
        <div class="staff-photo">
          <div class="image"
            <?php
              if($feat_image != null){ ?>
               style="background-image:url(<?php echo $feat_image; ?>)"
            <?php }
            ?>
          ></div>
        </div>
        <div class="staff-block">
          <?php if($displayOptions[ 'staffname_is_link' ] == true){ ?>
              <a class="title link" href="<?php echo the_permalink(); ?>" itemType="name">
                <?php echo the_title(); ?></a>
          <?php }else{ ?>
          <div class="title" itemType="name"><?php echo the_title(); ?></div>
          <?php } ?>
          <?php if($displayOptions[ 'rolesEnabled' ] == true){ ?>
          <div class="role" itemtype="jobTitle">
            <?php if($staff_role == null || $staff_role == ""){
              echo "&nbsp;";
            }else{
              echo $staff_role;
            } ?>
          </div>
          <?php } ?>
          <div class="content">
            <?php echo the_excerpt(); ?>
            <?php if($displayOptions[ 'staffname_button_visible' ] == true){ ?>
            <a class="viewProfile" href="<?php echo the_permalink(); ?>">
              <?php echo $displayOptions['staffname_button_text']; ?>
            </a>
            <?php } ?>
          </div>
        </div>
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
function easyStaffRelatedStaff($atts){


  $pull_quote_atts = shortcode_atts( array(
      'current_post_id' => '0',
  ), $atts );

  //How many related staff members should it get?
  $numberOfRelated = '4';

  //Should the staff members name link to their post type?
  $staffname_is_link = false;

  //Have they enabled roles in theme settings?
  $rolesEnabled = true;

  //Display link to post type as button?
  $staffname_button_visible = true;
    //If so, what text should it have?
   $staffname_button_text = "Read More";

  $currentID = $pull_quote_atts[ 'current_post_id' ];

  $args = array(
  	'post_type' => 'staff_members',
    'post__not_in' => array($currentID),
    'posts_per_page' => $numberOfRelated
  );
  $the_query = new WP_Query( $args );

  // Loop Over Results
  if ( $the_query->have_posts() ) {
  	echo "<div class='easy-staff-container'>";
  	while ( $the_query->have_posts() ) {
  		$the_query->the_post();

      //Have they includes a role for the staff member?
      $staff_role = get_post_meta( get_the_ID(), 'job-role', true );
      //If the post has a featured image - get it and use it.
      $feat_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID) );

      ?>
      <div class="staff-member related" itemscope itemtype="https://schema.org/Person">
        <div class="staff-photo">
          <div class="image"
            <?php
              if($feat_image != null){ ?>
               style="background-image:url(<?php echo $feat_image; ?>)"
            <?php }
            ?>
          ></div>
        </div>
        <div class="staff-block">
          <div class="title" itemType="name"><?php echo the_title(); ?></div>
          <?php if($rolesEnabled == true){ ?>
          <div class="role" itemtype="jobTitle">
            <?php if($staff_role == null || $staff_role == ""){
              echo "&nbsp;";
            }else{
              echo $staff_role;
            } ?>
          </div>
          <?php } ?>
          <div class="content">
            <a class="viewProfile" href="<?php echo the_permalink(); ?>">
              <?php echo $staffname_button_text; ?>
            </a>
          </div>
        </div>
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
add_shortcode('easyStaffRelated' , 'easyStaffRelatedStaff');

########################################################
##### Create the single post type page for the staff members
########################################################
function easyStaffCustomTemplate($single) {
    global $wp_query, $post;

    /* Checks for single template by post type */
    if ($post->post_type == "staff_members"){
      return plugin_dir_path( __FILE__ ) . '/assets/single-staff-members.php';
    }
    return $single;
}
add_filter('single_template', 'easyStaffCustomTemplate');
#########################################################
#
#
#
#
#
#
#
#
#
#########################################################
##### Creat the archive page
########################################################
add_filter('template_include', 'easyStaffArchive');

function easyStaffArchive( $template ) {
  if ( is_post_type_archive('staff_members') ) {
    $theme_files = array('archive-staff_members.php', 'myplugin/archive-staff_members.php');
    $exists_in_theme = locate_template($theme_files, false);
    if ( $exists_in_theme != '' ) {
      return $exists_in_theme;
    } else {
      return plugin_dir_path(__FILE__) . 'archive-staff_members.php';
    }
  }
  return $template;
}
