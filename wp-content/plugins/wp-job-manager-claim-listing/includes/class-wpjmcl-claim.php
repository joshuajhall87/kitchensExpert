<?php
/**
 * A single claim.
 *
 * @since 2.5.0
 */
class WPJMCL_Claim {

	/**
	 * @var int $listing_id
	 */
	public $listing_id;

	public function __construct( $claim = false ) {
		if ( is_array( $claim ) ) {
			$this->create( $claim );
		} else if ( is_int( $claim ) ) {
			$claim = get_post( $claim );

			$this->populate( $claim );
		} else if ( $claim instanceOf WPJMCL_Claim ) {
			$this->populate( $claim );
		}
	}

	/**
	 * Populate the object's properties.
	 *
	 * @since 2.5.0
	 *
	 * @param object $claim
	 * @return void
	 */
	private function populate( $claim ) {
		$this->ID = $claim->ID;
		$this->data = $claim;

		$this->listing_id = isset( $claim->_listing_id ) ? $claim->_listing_id : false;
		$this->order_id = isset( $claim->_order_id ) ? $claim->_order_id : false;
		$this->user_id = isset( $claim->_user_id ) ? $claim->_user_id : false;
		$this->package_id = isset( $claim->_package_id ) ? $claim->_package_id : false;
		$this->status = isset( $claim->_status ) ? $claim->_status : false;
	}

    /**
	 * Create a claim.
	 *
	 * This method simply creates a published post object with a title.
	 *
	 * @since 2.5.0
	 *
	 * @param array $args
	 * @return int $claim_id
     */
    public function create( $args = array() ) {
		$defaults = array(
			'listing_id' => false,
			'order_id' => false,
			'user_id' => false,
			'package_id' => false,
			'status' => 'pending'
		);

		$args = wp_parse_args( $args, $defaults );

        $claim_args = array(
            'post_status' => 'publish',
            'post_type' => 'claim',
        );

		// only set a title if the listing exists
		if ( $args[ 'listing_id' ] ) {
			$claim_args[ 'post_title' ] = get_post( $args[ 'listing_id' ] )->post_title;
		}

        $claim_id = wp_insert_post( $claim_args );

		// use the order owner if an order exists
		if ( isset( $args[ 'order_id' ] ) && ! isset( $args[ 'user_id' ] ) ) {
			$args[ 'user_id' ] = get_post_meta( $args[ 'order_id' ], '_customer_user', true );
		}

		$this->ID = $claim_id;
		$this->data = get_post( $this->ID );
		$this->update( $args );

        do_action( 'wpjmcl_insert_claim', $claim_id, $this, $args );

		return $this;
	}

	/**
	 * Update a claim.
	 * 
	 * This method does not set any data. Any updates should hook in to the action.
	 *
	 * @since 2.5.0
	 *
	 * @param int $claim_id
	 * @param array $args
	 * @return int $claim_id
	 */
	public function update( $args = array(), $claim = false ) {
		if ( $claim ) {
			$claim = new self( $args );
		}

		foreach ( $args as $key => $value ) {
			if ( $value ) {
				$func = "update_{$key}";

				$this->$func( $value );
			}
		}

        do_action( 'wpjmcl_update_claim', $claim, $args );

		return $this;
	}

	/**
	 * Get the status.
	 *
	 * @since 2.5.0
	 *
	 * @param object $claim
	 * @return string $status
	 */
	public function get_status( $claim = false ) {
		if ( $claim ) {
			$claim = new self( $claim );
		}

		return $this->status;
	}

	/**
	 * Update the status.
	 *
	 * If the new status is different than the current status then fire an action.
	 *
	 * Example: `wpjmcl_claim_status_update_to_approved` will fire when a claim is officially approved.
	 * This should be used to charge the user, assign the new owner, and anything else that is "final".
	 *
	 * @since 2.5.0
	 *
	 * @param object $claim
	 * @param array $args
	 * @return void
	 */
	public function update_status( $status, $claim = false ) {
		if ( $claim ) {
			$claim = new self( $claim );
		}

		if ( ! $status ) {
			return;
		}

		if ( ! in_array( $status, array_keys( wpjmcl()->claims->statuses ) ) ) {
			return;
		}

		$this->status = $status;
		
		update_post_meta( $this->ID, '_status', $status );

		do_action( 'wpjmcl_claim_status_update_to_' . $status, $status, $this );
	}
	
	/**
	 * Get the author.
	 *
	 * @since 2.5.0
	 *
	 * @param $claim
	 * @return int $author
	 */
	public function get_user_id( $claim = false ) {
		if ( $claim ) {
			$claim = new self( $claim );
		}

		return isset ( $this->data->post_author ) ? $this->data->post_author : $this->user_id;
	}

	/**
	 * Update the author.
	 *
	 * @since 2.5.0
	 *
	 * @param object $claim
	 * @param array $args
	 * @return void
	 */
	public function update_user_id( $author, $claim = false ) {
		if ( $claim ) {
			$claim = new self( $claim );
		}

		if ( ! $author ) {
			return;
		}

		$this->user_id = $author;

		// keep in meta just in case
		update_post_meta( $this->ID, '_user_id', $author );

		wp_update_post( array(
			'ID'			=> $this->ID,
			'post_author'	=> $author
		) );
	}

	/**
	 * Get the listing.
	 *
	 * @since 2.5.0
	 *
	 * @param $claim
	 * @return int $listing_id
	 */
	public function get_listing_id( $claim = false ) {
		if ( $claim ) {
			$claim = new self( $claim );
		}

		return $this->listing_id;
	}

	/**
	 * Update the listing.
	 *
	 * This also updates the listing_id associated with the item in an order. So if a claim is
	 * edited before an order is processed they will stay in sync.
	 *
	 * @since 2.5.0
	 *
	 * @param object $claim
	 * @param array $args
	 * @return void
	 */
	public function update_listing_id( $listing_id, $claim = false ) {
		if ( $claim ) {
			$claim = new self( $claim );
		}

		if ( ! $listing_id ) {
			return;
		}

		$this->listing_id = $listing_id;

		$listing = get_post( $listing_id );

		// update the claims title
		$listing = wp_update_post( array(
			'ID'			=> $this->ID,
			'post_title' 	=> $listing->post_title
		) );

		// update data property with new data
		$this->data = get_post( $listing );
		
		// update the claim's meta
		update_post_meta( $this->ID, '_listing_id', $listing_id );

		// update the order item meta
		if ( ! $this->get_order_id() ) {
			return;
		}

        $order = wc_get_order( $this->get_order_id() );

        foreach ( $order->get_items() as $item_id => $item ) {
            $product = wc_get_product( $item[ 'product_id' ] );

            if ( ! $product->is_type( array( 'job_package', 'job_package_subscription' ) ) ) {
				continue;
			}

			if ( ! isset( $item[ 'listing_id' ] ) ) {
				$continue;
			}

			wc_update_order_item_meta( $item_id, '_listing_id', $listing_id );
        }
	}

	/**
	 * Get the Order ID
	 *
	 * @since 2.5.0
	 *
	 * @param $claim
	 * @return int $author
	 */
	public function get_order_id( $claim = false ) {
		if ( $claim ) {
			$claim = new self( $claim );
		}

		return isset( $this->order_id ) ? absint( $this->order_id ) : false;
	}

	/**
	 * Update the order.
	 *
	 * @since 2.5.0
	 *
	 * @param object $claim
	 * @param array $args
	 * @return void
	 */
	public function update_order_id( $order_id, $claim = false ) {
		if ( $claim ) {
			$claim = new self( $claim );
		}

		if ( ! $order_id ) {
			return;
		}

		$this->order_id = $order_id;
		
		update_post_meta( $this->ID, '_order_id', $order_id );
	}

	/**
	 * Get the package ID.
	 *
	 * @since 2.5.0
	 *
	 * @param $claim
	 * @return int $pacakge_id
	 */
	public function get_package_id( $claim = false ) {
		if ( $claim ) {
			$claim = new self( $claim );
		}

		return isset( $this->package_id ) ? $this->package_id : false;
	}

	/**
	 * Update package.
	 *
	 * Assign a user package based on the package ID.
	 *
	 * @since 2.5.0
	 *
	 * @param object $claim
	 * @param array $args
	 * @return void
	 */
	public function update_package_id( $package_id, $claim = false ) {
		if ( $claim ) {
			$claim = new self( $claim );
		}

		if ( ! $package_id ) {
			return;
		}

		$this->package_id = $package_id;

		// update the claim
		update_post_meta( $this->ID, '_package_id', $package_id );
    }

	/**
	 * Generate a claim title.
	 *
	 * @since 2.5.0
	 *
	 * @param int $listing_id
	 * @return string
	 */
	public function generate_title( $claim = false ) {
		if ( $claim ) {
			$claim = new self( $claim );
		}

		$listing = get_post( $this->get_listing_id() );

		return $listing->post_title;
	}

}
