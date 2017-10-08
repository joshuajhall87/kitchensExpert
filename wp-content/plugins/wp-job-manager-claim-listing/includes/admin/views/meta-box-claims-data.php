<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

wp_enqueue_script( 'wc-enhanced-select' );
wp_enqueue_style( 'woocommerce_admin_styles' );

$post = get_post();

$statuses = wpjmcl()->claims->statuses;
$claim = wpjmcl_get_claim( $post->ID );
?>

<p class="form-field form-field-wide">
	<label for='listing'><?php _e( 'Listing', 'wp-job-manager-claim-listing' ); ?>
		<a href="<?php echo admin_url( sprintf( 'post.php?post=%s&action=edit', $claim->get_listing_id() ) ); ?>" class="wpjmcl-view-object"><?php _e( 'View', 'wp-job-manager-claim-listing' ); ?></a>
	</label>
	<?php if ( class_exists( 'WooCommerce' ) ) : ?>
		<input type="hidden" class="wc-product-search" data-multiple="false" name="listing_id" data-placeholder="<?php esc_attr_e( 'Search for a listing&hellip;', 'wp-job-manager-claim-listing' ); ?>" data-action="wpjmcl_json_search_listings" data-selected="<?php echo esc_attr( get_post( $claim->get_listing_id() )->post_title ); ?>" value="<?php echo esc_attr( $claim->get_listing_id() ); ?>" />
	<?php else : ?>
		<input type="text" name="listing_id" value="<?php echo esc_attr( $claim->get_listing_id() ); ?>" />
	<?php endif; ?>
</p><!-- Listing -->

<?php if ( class_exists( 'WooCommerce' ) && get_option( 'wpjmcl_paid_claiming' ) ) : ?>
<p class="form-field form-field-wide">
	<label for='order_id'><?php _e( 'Order ID', 'wp-job-manager-claim-listing' ); ?>
		<a href="<?php echo admin_url( sprintf( 'post.php?post=%s&action=edit', $claim->get_order_id() ) ); ?>" class="wpjmcl-view-object"><?php _e( 'View', 'wp-job-manager-claim-listing' ); ?></a>
	</label>
	<input style="width: 100%;"type="number" name="order_id" value="<?php echo esc_attr( $claim->get_order_id() ); ?>" />
</p><!-- Order -->
<?php endif; ?>

<p class="form-field form-field-wide wc-customer-user">
	<label for='listing'><?php _e( 'Claimer', 'wp-job-manager-claim-listing' ); ?>
		<a href="<?php echo admin_url( sprintf( 'user-edit.php?user_id=%s', $claim->get_user_id() ) ); ?>" style="float: right;"><?php _e( 'View', 'wp-job-manager-claim-listing' ); ?></a>
	</label>

	<?php if ( class_exists( 'WooCommerce' ) ) : ?>
		<?php $claimer_data = get_userdata( $claim->get_user_id() ); ?>
		<input type="hidden" class="wc-customer-search" name="user_id" data-placeholder="<?php esc_attr_e( 'Search for a customer&hellip;', 'wp-job-manager-claim-listing' ); ?>" data-action="woocommerce_json_search_customers" data-selected="<?php echo esc_attr( $claimer_data->display_name ); ?>" value="<?php echo esc_attr( $claim->get_user_id() ); ?>" />
	<?php else : ?>
		<input style="width: 100%;" type="number" name="user_id" value="<?php echo esc_attr( $claim->get_user_id() ); ?>" />
	<?php endif; ?>
</p><!-- Claimer -->

<?php if ( class_exists( 'WooCommerce' ) && get_option( 'wpjmcl_paid_claiming' ) && $claim->get_package_id() ) : ?>
<p class="form-field form-field-wide">
	<label for='package_id'><?php _e( 'Package', 'wp-job-manager-claim-listing' ); ?></label>
	<input type="text" disabled="disabled" style="width: 100%" value="<?php echo esc_attr( wc_get_product( $claim->get_package_id() )->get_title() ); ?>" />
</p><!-- Package -->
<?php endif; ?>

<p class="form-field form-field-wide">
	<label for='status'><?php _e( 'Status', 'wp-job-manager-claim-listing' ); ?></label>
	<select name='status'>
		<?php foreach ( $statuses as $key => $status ) : ?>
			<option <?php selected( $key, $claim->get_status() ); ?> value='<?php echo $key; ?>'><?php echo $status; ?></option>
		<?php endforeach; ?>
	</select>
</p><!-- Status -->

<?php wp_nonce_field( 'data_meta_box', 'data_meta_box_nonce' );
