<?php

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

if (get_current_user_id() < 1) {
  wp_redirect( get_site_url() );
  exit;
}
?>

<?php get_header(); ?>

<div id="create" class="avia-section main_color avia-section-default avia-no-shadow avia-bg-style-scroll  avia-builder-el-0  el_before_av_section  avia-builder-el-first   container_wrap fullsize">
	<div class="container">
		<main role="main" itemprop="mainContentOfPage" class="template-page content  av-content-full alpha units">
      <div class="post-entry post-entry-type-page post-entry-260">
        <div class="entry-content-wrapper clearfix">
          <?php echo do_shortcode('[hometown_step_1]'); ?>
          <?php echo do_shortcode('[hometown_step_2]'); ?>
          <?php echo do_shortcode('[hometown_step_3]'); ?>
</main>
<!-- close content main element -->
</div>
</div>

<?php get_footer(); ?>