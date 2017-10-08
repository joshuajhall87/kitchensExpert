<div id="job-manager-review-moderate-board">

	<p><?php _e( 'Moderate your reviews below.', 'wp-job-manager-reviews' ); ?></p>

	<table class="job-manager-reviews">

		<thead>
			<tr>
				<th class="" style="width: 50%;"><?php _e( 'Review', 'wp-job-manager-reviews' ); ?></th>
				<th class="" style="width: 15%;"><?php _e( 'Author', 'wp-job-manager-reviews' ); ?></th>
				<th class="" style="width: 20%;"><?php _e( 'Ratings', 'wp-job-manager-reviews' ); ?></th>
				<th class="" style="width: 25%;"><?php _e( 'Actions', 'wp-job-manager-reviews' ); ?></th>
			</tr>
		</thead>

		<tbody>
			<?php if ( ! $reviews ) : ?>
				<tr>
					<td colspan="6"><?php _e( 'There are currently no reviews found for any of your listings.', 'wp-job-manager-reviews' ); ?></td>
				</tr>
			<?php else : ?>
				<?php foreach ( $reviews as $review ) : ?>
					<tr class='wp-job-manger-reviews-status-<?php echo $review->comment_approved; ?>'>
						<td>
							<div class='review-content'><?php
								$content = $review->comment_content;
								echo strlen( $content ) <= 200 ? $content : substr( $content, 0, strrpos( $content, ' ', -( strlen( $content ) - 200 ) ) );
								if ( 200 < strlen( $content ) ) :
									echo '...';
								endif;
							?></div>
							<div class='review-content-listing'><strong><?php
								$title = ! empty( $review->post_title ) ? $review->post_title : __( '(no title)' );
								echo sprintf( __( 'On listing %s', 'wp-job-manager-reviews' ), '<a href="' . get_permalink( $review->ID ) . '">' . $title . '</a>' );
							?></strong></div>
						</td>
						<td><?php echo $review->comment_author; ?></td>
						<td>
							<div id='wpjmr-list-reviews'><?php

								$ratings 	= WPJMR()->review->get_ratings( $review->comment_ID );
								$categories = WPJMR()->wpjmr_get_review_categories();
								foreach ( $ratings as $category => $rating ) : ?>
									<div class='star-rating'>
										<div class='star-rating-title'><?php echo isset( $categories[ $category ] ) ? $categories[ $category ] : $category; ?></div>
										<?php for ( $i = 0; $i < WPJMR()->wpjmr_get_count_stars(); $i++ ) : ?>
											<?php if ( $i < $rating ) : ?>
												<span class="dashicons dashicons-star-filled"></span><?php else : ?><span class="dashicons dashicons-star-empty"></span><?php endif; ?>
										<?php endfor; ?>
									</div>
								<?php endforeach; ?>
							</div>
						</td>

						<td><?php
							$status = '';
							if ( '0' == $review->comment_approved ) :
								$status = __( 'Unapproved', 'wp-job-manager-reviews' );
							elseif ( '1' == $review->comment_approved ) :
								$status = __( 'Approved', 'wp-job-manager-reviews' );
							elseif ( 'spam' == $review->comment_approved ) :
								$status = __( 'Spam', 'wp-job-manager-reviews' );
							elseif ( 'trash' == $review->comment_approved ) :
								$status = __( 'Deleted', 'wp-job-manager-reviews' );
							endif;

							?><div class='review-action-status'><strong><?php
								echo $status;
							?></strong></div>

							<?php
							$approve_href 	= wp_nonce_url( add_query_arg( array( 'c' => $review->comment_ID, 'action' => 'approve' ) ), 'moderate_comment', 'moderate_nonce' );
							$unapprove_href = wp_nonce_url( add_query_arg( array( 'c' => $review->comment_ID, 'action' => 'unapprove' ) ), 'moderate_comment', 'moderate_nonce' );
							$spam_href 		= wp_nonce_url( add_query_arg( array( 'c' => $review->comment_ID, 'action' => 'spam' ) ), 'moderate_comment', 'moderate_nonce' );
							$delete_href 	= wp_nonce_url( add_query_arg( array( 'c' => $review->comment_ID, 'action' => 'trash' ) ), 'moderate_comment', 'moderate_nonce' );

							?><div class='job-dashboard-actions'><?php
								if ( '1' != $review->comment_approved ) :
									?><div><a class='review-action review-action-approve' href='<?php echo esc_url( $approve_href ); ?>'>
										<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
											 width="512px" height="512px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
										<path d="M461.6,109.6l-54.9-43.3c-1.7-1.4-3.8-2.4-6.2-2.4c-2.4,0-4.6,1-6.3,2.5L194.5,323c0,0-78.5-75.5-80.7-77.7
											c-2.2-2.2-5.1-5.9-9.5-5.9c-4.4,0-6.4,3.1-8.7,5.4c-1.7,1.8-29.7,31.2-43.5,45.8c-0.8,0.9-1.3,1.4-2,2.1c-1.2,1.7-2,3.6-2,5.7
											c0,2.2,0.8,4,2,5.7l2.8,2.6c0,0,139.3,133.8,141.6,136.1c2.3,2.3,5.1,5.2,9.2,5.2c4,0,7.3-4.3,9.2-6.2L462,121.8
											c1.2-1.7,2-3.6,2-5.8C464,113.5,463,111.4,461.6,109.6z"/>
										</svg>&nbsp;<?php

										_e( 'Approve', 'wp-job-manager-reviews' );
									?></a></div><?php
								endif;

								if ( '0' != $review->comment_approved ) :
									?><div><a class='review-action review-action-unapprove' href='<?php echo esc_url( $unapprove_href ); ?>'>
										<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
											 width="512px" height="512px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
										<path d="M443.6,387.1L312.4,255.4l131.5-130c5.4-5.4,5.4-14.2,0-19.6l-37.4-37.6c-2.6-2.6-6.1-4-9.8-4c-3.7,0-7.2,1.5-9.8,4
											L256,197.8L124.9,68.3c-2.6-2.6-6.1-4-9.8-4c-3.7,0-7.2,1.5-9.8,4L68,105.9c-5.4,5.4-5.4,14.2,0,19.6l131.5,130L68.4,387.1
											c-2.6,2.6-4.1,6.1-4.1,9.8c0,3.7,1.4,7.2,4.1,9.8l37.4,37.6c2.7,2.7,6.2,4.1,9.8,4.1c3.5,0,7.1-1.3,9.8-4.1L256,313.1l130.7,131.1
											c2.7,2.7,6.2,4.1,9.8,4.1c3.5,0,7.1-1.3,9.8-4.1l37.4-37.6c2.6-2.6,4.1-6.1,4.1-9.8C447.7,393.2,446.2,389.7,443.6,387.1z"/>
										</svg>&nbsp;<?php

										_e( 'Unapprove', 'wp-job-manager-reviews' );
									?></a></div><?php
								endif;

								if ( 'spam' != $review->comment_approved ) :
									?><div><a class='review-action review-action-spam' href='<?php echo esc_url( $spam_href ); ?>'>
										<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
											 width="512px" height="512px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
										<g><path d="M476.7,422.2L270.1,72.7c-2.9-5-8.3-8.7-14.1-8.7c-5.9,0-11.3,3.7-14.1,8.7L35.3,422.2c-2.8,5-4.8,13-1.9,17.9
												c2.9,4.9,8.2,7.9,14,7.9h417.1c5.8,0,11.1-3,14-7.9C481.5,435.2,479.5,427.1,476.7,422.2z M288,400h-64v-48h64V400z M288,320h-64
												V176h64V320z"/>
										</g></svg>&nbsp;<?php
										_e( 'Spam', 'wp-job-manager-reviews' );
									?></a></div><?php
								endif;

								if ( 'trash' != $review->comment_approved ) :
									?><div><a class='review-action review-action-delete' href='<?php echo esc_url( $delete_href ); ?>'>
										<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
											 width="512px" height="512px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve">
										<g><path d="M413.7,133.4c-2.4-9-4-14-4-14c-2.6-9.3-9.2-9.3-19-10.9l-53.1-6.7c-6.6-1.1-6.6-1.1-9.2-6.8c-8.7-19.6-11.4-31-20.9-31
												h-103c-9.5,0-12.1,11.4-20.8,31.1c-2.6,5.6-2.6,5.6-9.2,6.8l-53.2,6.7c-9.7,1.6-16.7,2.5-19.3,11.8c0,0-1.2,4.1-3.7,13
												c-3.2,11.9-4.5,10.6,6.5,10.6h302.4C418.2,144.1,417,145.3,413.7,133.4z"/>
											<path d="M379.4,176H132.6c-16.6,0-17.4,2.2-16.4,14.7l18.7,242.6c1.6,12.3,2.8,14.8,17.5,14.8h207.2c14.7,0,15.9-2.5,17.5-14.8
												l18.7-242.6C396.8,178.1,396,176,379.4,176z"/>
										</g></svg>&nbsp;<?php

										_e( 'Delete', 'wp-job-manager-reviews' );
									?></a></div><?php
								endif;
							?></div>

						</td>
					</tr>
				<?php endforeach; ?>
			<?php endif; ?>
		</tbody>

	</table>

	<?php get_job_manager_template( 'pagination.php', array( 'max_num_pages' => $max_num_pages ) ); ?>

</div>