<?php
wp_enqueue_script( 'wp-job-manager-multiselect' );
$key_class = "multiselect-" . esc_attr( $key );
$classes = array( 'jmfe-multiselect-field', 'job-manager-multiselect' );
$classes[] = $key_class;
?>
<select multiple="multiple" name="<?php echo esc_attr( isset($field['name']) ? $field['name'] : $key ); ?>[]" id="<?php echo esc_attr( $key ); ?>" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" <?php if( ! empty($field['required']) ) echo 'required'; ?> data-no_results_text="<?php _e( 'No results match', 'wp-job-manager-field-editor' ); ?>" data-multiple_text="<?php _e( 'Select Some Options', 'wp-job-manager-field-editor' ); ?>">
	<?php
	$no_values = isset( $field['value'] ) ? false : true;
	foreach ( $field['options'] as $key => $value ) :
		$key = str_replace( '*', '', $key, $replace_default );
		$key = str_replace( '~', '', $key, $replace_disabled );
		$field_value = isset( $field['value'] ) ? $field['value'] : array();

		if( $no_values && $replace_default > 0) $field[ 'value' ][ ] = $key;

		$disabled_option = $replace_disabled > 0 ? 'disabled="disabled"' : '';
	?>
		<option value="<?php echo esc_attr( $key ); ?>" <?php if ( ! empty( $field['value'] ) && is_array( $field['value'] ) ) selected( in_array( $key, $field['value'] ), true ); ?> <?php echo $disabled_option; ?>><?php echo esc_html( $value ); ?></option>
	<?php endforeach; ?>
</select>
<?php if ( ! empty( $field['description'] ) ) : ?><small class="description <?php echo $key_class; ?>-description"><?php echo $field['description']; ?></small><?php endif; ?>
