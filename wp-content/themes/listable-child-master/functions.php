<?php







/**







 * Listable Child functions and definitions







 *







 * Bellow you will find several ways to tackle the enqueue of static resources/files







 * It depends on the amount of customization you want to do







 * If you either wish to simply overwrite/add some CSS rules or JS code







 * Or if you want to replace certain files from the parent with your own (like style.css or main.js)







 *







 * @package ListableChild







 */







































/**







 * Setup Listable Child Theme's textdomain.







 *







 * Declare textdomain for this child theme.







 * Translations can be filed in the /languages/ directory.







 */







function listable_child_theme_setup() {







	load_child_theme_textdomain( 'listable-child-theme', get_stylesheet_directory() . '/languages' );







}







add_action( 'after_setup_theme', 'listable_child_theme_setup' );















































/**







 *







 * 1. Add a Child Theme "style.css" file







 * ----------------------------------------------------------------------------







 *







 * If you want to add static resources files from the child theme, use the







 * example function written below.







 *







 */















function listable_child_enqueue_styles() {







	$theme = wp_get_theme();







	// use the parent version for cachebusting







	$parent = $theme->parent();















	if ( !is_rtl() ) {







		wp_enqueue_style( 'listable-style', get_template_directory_uri() . '/style.css', array(), $parent->get( 'Version' ) );







	} else {







		wp_enqueue_style( 'listable-style', get_template_directory_uri() . '/rtl.css', array(), $parent->get( 'Version' ) );







	}















	// Here we are adding the child style.css while still retaining







	// all of the parents assets (style.css, JS files, etc)







	wp_enqueue_style( 'listable-child-style',







		get_stylesheet_directory_uri() . '/style.css',







		array('listable-style') //make sure the the child's style.css comes after the parents so you can overwrite rules







	);







}















add_action( 'wp_enqueue_scripts', 'listable_child_enqueue_styles' );































/**







 *







 * 2. Overwrite Static Resources (eg. style.css or main.js)







 * ----------------------------------------------------------------------------







 *







 * If you want to overwrite static resources files from the parent theme







 * and use only the ones from the Child Theme, this is the way to do it.







 *







 */























/*















function listable_child_overwrite_files() {















	// 1. The "main.js" file







	//







	// Let's assume you want to completely overwrite the "main.js" file from the parent















	// First you will have to make sure the parent's file is not loaded







	// See the parent's function.php -> the listable_scripts_styles() function







	// for details like resources names















		wp_dequeue_script( 'listable-scripts' );























	// We will add the main.js from the child theme (located in assets/js/main.js)







	// with the same dependecies as the main.js in the parent







	// This is not required, but I assume you are not modifying that much :)















		wp_enqueue_script( 'listable-child-scripts',







			get_stylesheet_directory_uri() . '/assets/js/main.js',







			array( 'jquery' ),







			'1.0.0', true );































	// 2. The "style.css" file







	//







	// First, remove the parent style files







	// see the parent's function.php -> the hive_scripts_styles() function for details like resources names















		wp_dequeue_style( 'listable-style' );























	// Now you can add your own, modified version of the "style.css" file















		wp_enqueue_style( 'listable-child-style',







			get_stylesheet_directory_uri() . '/style.css'







		);







}















// Load the files from the function mentioned above:















	add_action( 'wp_enqueue_scripts', 'listable_child_overwrite_files', 11 );















// Notes:







// The 11 priority parameter is need so we do this after the function in the parent so there is something to dequeue







// The default priority of any action is 10















*/







/**







 * get_the_company_business_logo function.







 *







 * @access public







 * @param mixed $post (default: null)







 * @return string Image SRC







 */







function get_the_company_business_logo( $post = null ) {







	







	$post = get_post( $post );







	$logo = $post->_business_logo;















	if($logo){















		echo '<img class="company_logo" src="' . esc_attr( $logo ) . '" alt="' . esc_attr( get_the_company_name( $post ) ) . '" />';















	}















	return '';







}















/**







 * Override for ../wp-content/themes/listable/listable/inc/extras.php







 * Return the src of the post image. In the case of listings we will try and get the first image of the gallery first, then the featured image.







 *







 * @param null $post_id







 * @param string $size







 *







 * @return bool







 */







function listable_get_post_image_src( $post_id = null, $size = 'thumbnail' ) {















	if ( empty( $post_id ) ) {







		$post_id = get_the_ID();







	}















	//get the categories for this listing







	$categories = wp_get_post_terms($post_id, 'job_listing_category');















	//presume that the listing item belongs to a single category







	$category_img_url = listable_get_term_image_url( $categories[0]->term_id, 'large');















	$attach_id = listable_get_post_image_id( $post_id );















	// setting listing item image with no







	if ( empty( $attach_id ) || is_wp_error( $attach_id ) ) {















		return listable_get_inline_background_image( $category_img_url );















	}















	$data = wp_get_attachment_image_src( $attach_id, $size );















	// if this attachment has an url for this size, return it







	if ( isset( $data[0] ) && ! empty ( $data ) ) {







		return listable_get_inline_background_image( $data[0] );







	}















	return false;







}



















// Allow SVG



add_filter( 'wp_check_filetype_and_ext', function($data, $file, $filename, $mimes) {







  global $wp_version;



  if ( $wp_version !== '4.7.1' ) {



     return $data;



  }







  $filetype = wp_check_filetype( $filename, $mimes );







  return [



      'ext'             => $filetype['ext'],



      'type'            => $filetype['type'],



      'proper_filename' => $data['proper_filename']



  ];







}, 10, 4 );







function cc_mime_types( $mimes ){



  $mimes['svg'] = 'image/svg+xml';



  return $mimes;



}



add_filter( 'upload_mimes', 'cc_mime_types' );







function fix_svg() {



  echo '<style type="text/css">



        .attachment-266x266, .thumbnail img {



             width: 100% !important;



             height: auto !important;



        }



        </style>';



}



add_action( 'admin_head', 'fix_svg' );

 // only copy if needed

/**
 * Removes coupon form, order notes, and several billing fields if the checkout doesn't require payment
 * Tutorial: http://skyver.ge/c
 */
function sv_free_checkout_fields() {
	
	// Bail we're not at checkout, or if we're at checkout but payment is needed
	if ( function_exists( 'is_checkout' ) && ( ! is_checkout() || ( is_checkout() && WC()->cart->needs_payment() ) ) ) {
		return;
	}
	
	// remove coupon forms since why would you want a coupon for a free cart??
	remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
	
	// Remove the "Additional Info" order notes
	add_filter( 'woocommerce_enable_order_notes_field', '__return_false' );

	// Unset the fields we don't want in a free checkout
	function unset_unwanted_checkout_fields( $fields ) {
	
		// add or remove billing fields you do not want
		// list of fields: http://docs.woothemes.com/document/tutorial-customising-checkout-fields-using-actions-and-filters/#section-2
		$billing_keys = array(
			'billing_company',
			'billing_phone',
			'billing_address_1',
			'billing_address_2',
			'billing_city',
			'billing_postcode',
			'billing_country',
			'billing_state',
		);

		// unset each of those unwanted fields
		foreach( $billing_keys as $key ) {
			unset( $fields['billing'][$key] );
		}
		
		return $fields;
	}
	add_filter( 'woocommerce_checkout_fields', 'unset_unwanted_checkout_fields' );
	
	// A tiny CSS tweak for the account fields; this is optional
	function print_custom_css() {
		echo '<style>.create-account { margin-top: 6em; }</style>';
	}
	add_action( 'wp_head', 'print_custom_css' );
}
add_action( 'wp', 'sv_free_checkout_fields' );

