<?php

/*
Plugin Name: WP All Import - Listable Add-On
Plugin URI: http://www.wpallimport.com/
Description: Supporting imports into the Listable theme.
Version: 1.0.3
Author: Soflyy
*/


include "rapid-addon.php";

add_action( 'pmxi_saved_post', 'listable_addon_set_author', 10, 1 );
add_action( 'pmxi_before_xml_import', 'listable_addon_que_scripts', 10, 1 );

$listable_addon = new RapidAddon( 'Listable Add-On', 'listable_addon' );
$listable_addon->disable_default_images();
$listable_addon->import_images( 'listable_addon_listing_gallery', 'Gallery Images' );
$listable_addon->add_field( '_company_tagline', 'Company Tagline', 'text' );
$listable_addon->add_field(
	'_job_location',
	'Location',
	'radio',
	array(
		'search_by_address' => array(
			'Search by Address',
			$listable_addon->add_options(
				$listable_addon->add_field(
					'job_address',
					'Job Address',
					'text'
				),
				'Google Geocode API Settings',
				array(
					$listable_addon->add_field(
						'address_geocode',
						'Request Method',
						'radio',
						array(
							'address_no_key' => array(
								'No API Key',
								'Limited number of requests.'
							),
							'address_google_developers' => array(
								'Google Maps Standard API Key - <a href="https://developers.google.com/maps/documentation/geocoding/get-api-key#key">Get free API key</a>',
								$listable_addon->add_field(
									'address_google_developers_api_key',
									'API Key',
									'text'
								),
								'Up to 2500 requests per day and 5 requests per second.'
							),
							'address_google_for_work' => array(
								'Google Maps Premium Client ID & Digital Signature - <a href="https://developers.google.com/maps/premium/">Sign up for Google Maps Premium Plan</a>',
								$listable_addon->add_field(
									'address_google_for_work_client_id',
									'Google Maps Premium Client ID',
									'text'
								),
								$listable_addon->add_field(
									'address_google_for_work_digital_signature',
									'Google Maps Premium Digital Signature',
									'text'
								),
								'Up to 100,000 requests per day and 10 requests per second'
							)
						) // end Request Method options array
					), // end Request Method nested radio field

				) // end Google Geocode API Settings fields
			) // end Google Gecode API Settings options panel
		), // end Search by Address radio field
		'search_by_coordinates' => array(
			'Search by Coordinates',
			$listable_addon->add_field(
				'job_lat',
				'Latitude',
				'text',
				null,
				'Example: 34.0194543'
			),
			$listable_addon->add_options(
				$listable_addon->add_field(
					'job_lng',
					'Longitude',
					'text',
					null,
					'Example: -118.4911912'
				),
				'Google Geocode API Settings',
				array(
					$listable_addon->add_field(
						'coord_geocode',
						'Request Method',
						'radio',
						array(
							'coord_no_key' => array(
								'No API Key',
								'Limited number of requests.'
							),
							'coord_google_developers' => array(
								'Google Maps Standard API Key - <a href="https://developers.google.com/maps/documentation/geocoding/get-api-key#key">Get free API key</a>',
								$listable_addon->add_field(
									'coord_google_developers_api_key',
									'API Key',
									'text'
								),
								'Up to 2500 requests per day and 5 requests per second.'
							),
							'coord_google_for_work' => array(
								'Google Maps Premium Client ID & Digital Signature - <a href="https://developers.google.com/maps/premium/">Sign up for Google Maps Premium Plan</a>',
								$listable_addon->add_field(
									'coord_google_for_work_client_id',
									'Google Maps Premium Client ID',
									'text'
								),
								$listable_addon->add_field(
									'coord_google_for_work_digital_signature',
									'Google Maps Premium Digital Signature',
									'text'
								),
								'Up to 100,000 requests per day and 10 requests per second'
							)
						) // end Geocode API options array
					), // end Geocode nested radio field

				) // end Geocode settings
			) // end coordinates Option panel
		) // end Search by Coordinates radio field
	) // end Job Location radio field
);
$listable_addon->add_field( '_job_hours', 'Hours', 'textarea', null, 'Mon - Fri 09:00 - 23:00&lt;br /&gt;Sat - Sun 17:00 - 23:00' );
$listable_addon->add_field( '_company_phone', 'Phone:', 'text', null, 'e.g +42-898-4364' );
$listable_addon->add_field( '_company_website', 'Company Website', 'text' );
$listable_addon->add_field( '_company_twitter', 'Company Twitter', 'text' );
$listable_addon->add_field( '_filled', 'Filled', 'radio',
    array(
        '0' => 'No',
        '1' => 'Yes'
    ),
    'Filled listings will no longer accept applications.'
);
$listable_addon->add_field( '_featured', 'Featured Listing', 'radio',
    array(
        '0' => 'No',
        '1' => 'Yes'
    ),
    'Featured listings will be sticky during searches, and can be styled differently.'
);
$listable_addon->add_field( '_job_expires', 'Listing Expiry Date', 'text', null, 'Import date in any strtotime compatible format.');
$listable_addon->add_field( '_post_author', 'Posted by', 'text', null, 'Enter the ID of the user, or leave blank if submitted by a guest' );
$listable_addon->set_import_function( 'listable_addon_import' );

$listable_addon->admin_notice(
    'The Listable Add-On requires WP All Import <a href="http://www.wpallimport.com/order-now/?utm_source=free-plugin&utm_medium=dot-org&utm_campaign=listable" target="_blank">Pro</a> or <a href="http://wordpress.org/plugins/wp-all-import" target="_blank">Free</a>, and the <a href="http://themeforest.net/item/listable-a-friendly-directory-wordpress-theme/13398377">Listable</a> theme.',
    array(
        "themes" => array( "Listable" ),
) );

$listable_addon->run( array(
        "themes" => array( "Listable" ),
        'post_types' => array( 'job_listing' )
) );

function listable_addon_listing_gallery( $post_id, $attachment_id, $image_filepath, $import_options ) {
        $images_array = array(); // Image IDs array
        $images_urls = array(); // Image URLs array
        if ( $current_images = get_post_meta( $post_id, 'main_image', true ) ) { // Get current images, if any.
            $current_images = explode( ",", $current_images );
             foreach ( $current_images as $image ) {
                $images_array[$image] = $image;
                $images_urls[] = wp_get_attachment_url( $image );
            }
        }
        $images_array[$attachment_id] = $attachment_id;
        $images_urls[] = wp_get_attachment_url( $attachment_id );
        $final_images = implode( ",", $images_array );
        update_post_meta( $post_id, 'main_image', $final_images ); // Add image IDs
        update_post_meta( $post_id, '_main_image', $images_urls ); // Add image URLs
}

function listable_addon_import( $post_id, $data, $import_options, $article ) {

    global $listable_addon;

    // build fields array
    $fields = array(
        '_company_tagline',
        '_job_hours',
        '_company_phone',
        '_company_website',
        '_company_twitter',
        '_filled',
        '_featured',
        '_job_expires'
    );

    // update everything in fields arrays
    foreach ( $fields as $field ) {

        if ( empty( $article['ID'] ) or $listable_addon->can_update_meta( $field, $import_options ) ) {
                update_post_meta( $post_id, $field, $data[$field] );
            }
        }

            // clear image fields to override import settings
    $fields = array(
        'main_image',
        '_main_image'
    );
    if ( empty( $article['ID'] ) or $listable_addon->can_update_image( $import_options ) ) {
        foreach ($fields as $field) {
            delete_post_meta($post_id, $field);
        }
    }

     // update listing expiration date
    $field = '_job_expires';
    $date = $data[$field];
    $date = strtotime( $date );

     if ( empty( $article['ID'] ) or $listable_addon->can_update_meta( $field, $import_options ) ) {
        if( !empty( $date ) ) {
            $date = date( 'Y-m-d', $date );
            update_post_meta( $post_id, $field, $date );
        }
    }

    // This meta field is used in the listable_addon_set_author function.
    $field = '_post_author';

    if ( empty( $article['ID'] ) or $listable_addon->can_update_meta( $field, $import_options ) ) {
        update_post_meta( $post_id, '_post_author', $data[$field] );
        update_post_meta( $post_id, '_post_author_can_update', 'yes' );
    } else {
        update_post_meta( $post_id, '_post_author_can_update', 'no' );
    }

    // update job location
    $field   = 'job_address';

    $address = $data[$field];

    $lat  = $data['job_lat'];

    $long = $data['job_lng'];

    //  build search query
    if ( $data['_job_location'] == 'search_by_address' ) {

    	$search = ( !empty( $address ) ? 'address=' . rawurlencode( $address ) : null );

    } else {

    	$search = ( !empty( $lat ) && !empty( $long ) ? 'latlng=' . rawurlencode( $lat . ',' . $long ) : null );

    }

    // build api key
    if ( $data['_job_location'] == 'search_by_address' ) {

    	if ( $data['address_geocode'] == 'address_google_developers' && !empty( $data['address_google_developers_api_key'] ) ) {

	        $api_key = '&key=' . $data['address_google_developers_api_key'];

	    } elseif ( $data['address_geocode'] == 'address_google_for_work' && !empty( $data['address_google_for_work_client_id'] ) && !empty( $data['address_google_for_work_signature'] ) ) {

	        $api_key = '&client=' . $data['address_google_for_work_client_id'] . '&signature=' . $data['address_google_for_work_signature'];

	    }

    } else {

    	if ( $data['coord_geocode'] == 'coord_google_developers' && !empty( $data['coord_google_developers_api_key'] ) ) {

	        $api_key = '&key=' . $data['coord_google_developers_api_key'];

	    } elseif ( $data['coord_geocode'] == 'coord_google_for_work' && !empty( $data['coord_google_for_work_client_id'] ) && !empty( $data['coord_google_for_work_signature'] ) ) {

	        $api_key = '&client=' . $data['coord_google_for_work_client_id'] . '&signature=' . $data['coord_google_for_work_signature'];

	    }

    }

	// Store _job_location value for later use

    if ( $data['_job_location'] == 'search_by_address' ) {

    	$job_location = $address;

    } else {

    	$job_location = $lat . ', ' . $long;

    }

    // if all fields are updateable and $search has a value
    if (  empty( $article['ID'] ) or ( $listable_addon->can_update_meta( $field, $import_options ) && $listable_addon->can_update_meta( '_job_location', $import_options ) && !empty ( $search ) ) ) {

        // build $request_url for api call
        $request_url = 'https://maps.googleapis.com/maps/api/geocode/json?' . $search . $api_key;
        $curl        = curl_init();

        curl_setopt( $curl, CURLOPT_URL, $request_url );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );

        $listable_addon->log( '- Getting location data from Geocoding API: '.$request_url );

        $json = curl_exec( $curl );

        curl_close( $curl );

        // parse api response
        if ( !empty( $json ) ) {

            $details = json_decode( $json, true );

            $address_data = array();

			foreach ( $details[results][0][address_components] as $type ) {
				// Went for type_name here to try to make the if statement a bit shorter,
				// and hopefully clearer as well
				$type_name = $type[types][0];

				if ($type_name == "administrative_area_level_1" || $type_name == "administrative_area_level_2" || $type_name == "country") {
					// short_name & long_name must be stored for these three field types, as
					// the short & long names are stored by WP Job Manager
					$address_data[ $type_name . "_short_name" ] = $type[short_name];
					$address_data[ $type_name . "_long_name" ] = $type[long_name];
				} else {
					// The rest of the data from Google Maps can be returned in long format,
					// as the other fields only store data in that format
					$address_data[ $type_name ] = $type[long_name];
				}

			}

			// It's a long list, but this is what WP Job Manager stores in the database
			$geo_status = ($details[status] == "ZERO_RESULTS") ? 0 : 1;

			$latitude  = $details[results][0][geometry][location][lat];

            $longitude = $details[results][0][geometry][location][lng];

        	$formatted_address = $details[results][0][formatted_address];

			$street_number = $address_data[street_number];

			$street = $address_data[route];

        	$city = $address_data[locality];

        	$country_short = $address_data[country_short_name];

			$country_long = $address_data[country_long_name];

        	$zip = $address_data[postal_code];

			// Important because the "geolocation_state_short" & "geolocation_state_long" fields
			// can get data from "administrative_area_level_1" or "administrative_area_level_2",
			// depending on the address that's provided
			$state_short = !empty( $address_data[administrative_area_level_1_short_name] ) ? $address_data[administrative_area_level_1_short_name] : $address_data[administrative_area_level_2_short_name];

			$state_long = !empty( $address_data[administrative_area_level_1_long_name] ) ? $address_data[administrative_area_level_1_long_name] : $address_data[administrative_area_level_2_long_name];

			// Checks for empty location elements

        	if ( empty( $zip ) ) {

			    $listable_addon->log( '<b>WARNING:</b> Google Maps has not returned a Postal Code for this job location.' );

        	}

        	if ( empty( $country_short ) && empty( $country_long ) ) {

			    $listable_addon->log( '<b>WARNING:</b> Google Maps has not returned a Country for this job location.' );

        	}

        	if ( empty( $state_short ) && empty( $state_long ) ) {

			    $listable_addon->log( '<b>WARNING:</b> Google Maps has not returned a State for this job location.' );

        	}

        	if ( empty( $city ) ) {

			    $listable_addon->log( '<b>WARNING:</b> Google Maps has not returned a City for this job location.' );

        	}

        	if ( empty( $street_number ) ) {

			    $listable_addon->log( '<b>WARNING:</b> Google Maps has not returned a Street Number for this job location.' );

        	}

        	if ( empty( $street ) ) {

			    $listable_addon->log( '<b>WARNING:</b> Google Maps has not returned a Street Name for this job location.' );

        	}

        } else {
			$listable_addon->log( '<b>WARNING:</b> Could not retrieve response data from Google Maps API.' );
		}

    }

    // List of location fields to update
	$fields = array(
		'geolocation_lat' => $latitude,
		'geolocation_long' => $longitude,
		'geolocation_formatted_address' => $formatted_address,
		'geolocation_street_number' => $street_number,
		'geolocation_street' => $street,
		'geolocation_city' => $city,
		'geolocation_state_short' => $state_short,
		'geolocation_state_long' => $state_long,
		'geolocation_postcode' => $zip,
		'geolocation_country_short' => $country_short,
		'geolocation_country_long' => $country_long,
		'_job_location' => $job_location
	);

    $listable_addon->log( '- Updating location data' );

	// Check if "geolocated" field should be created or deleted
	if ($geo_status == "0") {
		delete_post_meta( $post_id, "geolocated" );
	} elseif ($geo_status == "1") {
		update_post_meta( $post_id, "geolocated", $geo_status );
	} else {
		// Do nothing, it's possible that we didn't get a response from the Google Maps API
	}

    foreach ( $fields as $key => $value ) {

        if ( empty( $article['ID'] ) or $listable_addon->can_update_meta( $key, $import_options ) && !is_null($value) ) {
			// If the field can be updated, and the value isn't NULL, update the field
            update_post_meta( $post_id, $key, $value );
        } elseif ( empty( $article['ID'] ) or $listable_addon->can_update_meta( $key, $import_options ) ) {
			// Else, if the value for the field returns NULL, delete the field
			delete_post_meta( $post_id, $key, $value );
		} else {
			// Else, do nothing
		}
    }
}

function listable_addon_que_scripts( $import_id ) {
    if ( !wp_script_is( 'google-maps' ) ) {
        if ( '' == listable_get_option( 'mapbox_token', '' ) ) {
            wp_deregister_script('google-maps');
            wp_enqueue_script( 'google-maps', '//maps.google.com/maps/api/js?v=3.exp&amp;libraries=places' . $google_maps_key, array(), '3.22', true );
            $listable_scripts_deps[] = 'google-maps';
        } elseif ( wp_script_is( 'google-maps' ) || listable_using_facetwp() ) {
            wp_deregister_script('google-maps');
            wp_enqueue_script( 'google-maps', '//maps.google.com/maps/api/js?v=3.exp&amp;libraries=places' . $google_maps_key, array(), '3.22', false );
            $listable_scripts_deps[] = 'google-maps';
        }
    }
}

function listable_addon_set_author( $post_id ) {
    // Set the author to Guest "0" if the field is empty, otherwise check if the user ID exists and then set the author.
    global $listable_addon;
    $can_update = get_post_meta( $post_id, '_post_author_can_update', true );
    $new_author = get_post_meta( $post_id, '_post_author', true );

    if ( empty( $new_author ) ) { $new_author == 0; }

    if ( $can_update == 'yes' ) {
        if ( listable_addon_user_exists( $post_id, $new_author ) ) {
                $update_array = array(
                    'ID'           => $post_id,
                    'post_author'   => $new_author
                );
                wp_update_post( $update_array );
                $listable_addon->log( "- Author updated according to 'Posted By' setting" );
        }
    } else {

        $listable_addon->log( "- Author failed to update." );

    }
}

function listable_addon_user_exists( $post_id, $user_id = null ) {
    // Make sure a user exists before we change the author.
    if ( $user_id == null ) {
        update_post_meta( $post_id, '_post_author', 0 );
        return true;
    } else {
        global $wpdb;
        $data = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(ID) FROM $wpdb->users WHERE ID = %d", $user_id ) );
        return empty( $data ) || 1 > $data ? false : true;
    }
}
