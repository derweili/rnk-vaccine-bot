<?php

namespace Derweili\RnkBot;

class Bot {

	private $centers = null;
	private $vaccines = null;
	/**
	 * Constructor
	 */
	public function __construct( Centers $centers, Vaccines $vaccines ) {
		$this->centers = $centers;
		$this->vaccines = $vaccines;
	}

	public function get_request_date_instance( $center_id, $vaccine_id ) {
		return new RequestDate( $center_id, $vaccine_id );
	}

	public function run() {
				
		/**
		 * Loop over centers
		 */
		foreach ( $this->centers->get_all_ids() as $center_id ) {

			/**
			 * Loop over vaccines
			 */
			foreach ( $this->vaccines->get_all_ids() as $vaccine_id ) {

				/**
				 * Call API and test if dates are available
				 */
				$request = $this->get_request_date_instance( $center_id, $vaccine_id );
				$is_available =  $request->has_dates();
				
				/**
				 * Output result
				 */
				$center_name = $this->centers->get_center_by_id( $center_id );
				$vaccine_name = $this->vaccines->get_vaccine_by_id( $vaccine_id );
				
				if( $is_available ) {
					echo 'Vaccine Available: ' . $vaccine_name . ' - ' . $center_name . PHP_EOL;


					/**
					 * Send Push notification if vaccine is available
					 */
					$this->send_notification( $center_id, $vaccine_id ); 

				} else {
					echo 'Vaccine NOT Available: ' . $vaccine_name . ' - ' . $center_name . PHP_EOL;
				}

				// wait for 5 seconds
				$this->sleep();
			}
		}
	}

	public function sleep() {
		sleep(2);
	}

	public function send_notification( $center_id, $vaccine_id ) {
		$notification = new PushoverNotification( $center_id, $vaccine_id ); 
		$notification->send_available_vaccine_notification();
	}
}