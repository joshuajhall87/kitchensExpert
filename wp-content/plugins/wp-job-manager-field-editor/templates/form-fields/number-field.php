<?php
$key_class = "number-" . esc_attr( $key );
$classes = array( 'jmfe-number-field', 'input-number' );
$classes[] = $key_class;
?>
<input type="number" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" name="<?php echo esc_attr( isset( $field['name'] ) ? $field['name'] : $key ); ?>" id="<?php echo esc_attr( $key ); ?>" placeholder="<?php echo esc_attr( $field['placeholder'] ); ?>" value="<?php echo isset( $field['value'] ) ? esc_attr( $field['value'] ) : ''; ?>" maxlength="<?php echo ! empty( $field['maxlength'] ) ? $field['maxlength'] : ''; ?>" min="<?php echo ! empty($field['min']) ? $field['min'] : ''; ?>" max="<?php echo ! empty($field['max']) ? $field['max'] : ''; ?>" step="<?php echo ! empty($field['step']) ? $field['step'] : ''; ?>" <?php echo ! empty($field['pattern']) ? "pattern=\"" . esc_attr( $field['pattern'] ) . "\"" : ''; ?> <?php if ( ! empty( $field['required'] ) ) echo 'required'; ?> />
<?php if ( ! empty( $field['description'] ) ) : ?><small class="description <?php echo $key_class; ?>-description"><?php echo $field['description']; ?></small><?php endif; ?>