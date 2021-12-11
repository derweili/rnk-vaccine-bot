<?php

namespace Derweili\RnkBot;

class Bot {

	private $centers = null;
	private $vaccines = null;

	private $available_appointments = [];

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

	public function add_available_appointment( $center_id, $vaccine_id) {
		if( isset( $this->available_appointments[$center_id]) ) {
			$this->available_appointments[$center_id][] = $vaccine_id;
		} else {
			$this->available_appointments[$center_id] = [$vaccine_id];
		}
	}
	
	public function get_available_appointments() {
		return $this->available_appointments;
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
				// $is_available = true;
				
				/**
				 * Output result
				 */
				$center_name = $this->centers->get_center_by_id( $center_id );
				$vaccine_name = $this->vaccines->get_vaccine_by_id( $vaccine_id );
				
				if( $is_available ) {
					$this->add_available_appointment( $center_id, $vaccine_id ); 
					echo 'Vaccine Available: ' . $vaccine_name . ' - ' . $center_name . PHP_EOL;


					/**
					 * Send Push notification if vaccine is available
					 */
					// $this->send_notification( $center_id, $vaccine_id ); 

				} else {
					echo 'Vaccine NOT Available: ' . $vaccine_name . ' - ' . $center_name . PHP_EOL;
				}

				// wait for 1 second
				$this->sleep();
			}
		}

		$this->maybe_notify();
	}
	
	public function maybe_notify() {
		if( ! empty( $this->available_appointments ) ) {
			$this->send_combined_notification( $this->available_appointments );
		}
	}

	public function sleep() {
		sleep(0.5);
	}

	public function send_combined_notification( $available_appointments ) {
		$notification = new PushoverNotification(); 
		$notification->send_combined_available_vaccine_notification( $available_appointments );
	}

	public function send_notification( $center_id, $vaccine_id ) {
		$notification = new PushoverNotification( ); 
		$notification->send_available_vaccine_notification( $center_id, $vaccine_id  );
	}
}