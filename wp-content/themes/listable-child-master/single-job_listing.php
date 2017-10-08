<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Listable
 */

get_header();
?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post(); ?>

				<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> itemscope itemtype="http://schema.org/LocalBusiness">
					<?php

					if ( ! post_password_required() ) {
						$photos = listable_get_listing_gallery_ids();
						
					?>
							<div class="entry-featured-carousel">
									<div class="entry-featured-gallery">

										<?php
										// 	echo '<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d386950.6511603643!2d'.$post->geolocation_long.'!3d'.$post->geolocation_lat.'!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x89c24fa5d33f083b%3A0xc80b8f06e177fe62!2sNueva+York!5e0!3m2!1ses-419!2sus!4v1445032011908" width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>';
										echo do_shortcode('[display_map width="100%" height="345" zoom="18" language="en" map_type="ROADMAP" scroll_wheel="false" map_draggable="false" marker1="'.$post->geolocation_lat.' | '.$post->geolocation_long.' | title | Here is current position | marker9"]');
										?>

										<?php foreach ($photos as $key => $photo_id):
											$src = wp_get_attachment_image_src($photo_id, 'listable-carousel-image'); ?>
											<!--<img class="entry-featured-image" src="<?php echo $src[0]; ?>" itemprop="image" />-->
										<?php endforeach; ?>
									</div>

							</div>



						<div>
							<?php
							$job_manager = $GLOBALS['job_manager'];

							remove_filter( 'the_content', array( $job_manager->post_types, 'job_content' ) );

							ob_start();

							do_action( 'job_content_start' );

							get_job_manager_template_part( 'content-single', 'job_listing' );

							do_action( 'job_content_end' );

							$content = ob_get_clean();

							add_filter( 'the_content', array( $job_manager->post_types, 'job_content' ) );

							echo apply_filters( 'job_manager_single_job_content', $content, $post );

							wp_link_pages( array(
								'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'listable' ),
								'after'  => '</div>',
							) ); ?>
						</div><!-- .entry-content -->

						<footer class="entry-footer">
							<?php listable_entry_footer(); ?>
						</footer><!-- .entry-footer -->

						<?php
						listable_output_single_listing_icon();

					} else {
						echo '<div class="entry-content">';
						echo get_the_password_form();
						echo '</div>';
					} ?>
				</article><!-- #post-## -->

				<?php
				if ( ! post_password_required() ) the_post_navigation();
			endwhile; // End of the loop. ?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_sidebar();
get_footer();