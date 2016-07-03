<?php
//Does this staff member have an image to use for the banner?
$staff_banner = get_post_meta( get_the_ID(), 'job-banner', true );

//Have they includes a role for the staff member?
$staff_role = get_post_meta( get_the_ID(), 'job-role', true );
?>

<?php get_header(); ?>
  	<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <div class="staff-member-banner"

        style="background-image:url(<?php echo $staff_banner; ?>)"

      >
        <div class="text">
          <h1><?php echo the_title(); ?></h1>
          <h3><?php if($staff_role == null || $staff_role == ""){
            echo "&nbsp;";
          }else{
            echo $staff_role;
          } ?></h3>
        </div>
      </div>
      <div class="staff-member-container">
        <div class="main-staff-information">
          <?php echo the_content(); ?>
        </div>
      </div>
    <?php endwhile; ?>

    <?php else : ?>
      <div class="staff-member-container">
        <h3>
          Oh no! This page does not exist, maybe try the <a href="<?php echo site_url(); ?>/Staff">Staff page</a>?
        </h3>
      </div>
	  <?php endif; ?>

   <div class="staff-member-container">
     <h3 class="related-staff-members">Related Staff Members:</h3>
   <?php
    #Get related staff members
    echo do_shortcode('[easyStaffRelated current_post_id="'. get_the_ID() .'"]');
    ?>
    </div>

<?php get_footer(); ?>
