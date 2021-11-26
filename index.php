<?php

namespace Derweili\RnkBot;

/**
 * Import Composer
 */
require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/config.php';

$centers = new Centers();
$vaccines = new Vaccines();

/**
 * Set max execution time
 */
set_time_limit(300);



/**
 * Loop over centers
 */
foreach ( $centers->get_all_ids() as $center_id ) {

	/**
	 * Loop over vaccines
	 */
	foreach ( $vaccines->get_all_ids() as $vaccine_id ) {

		/**
		 * Call API and test if dates are available
		 */
		$request = new RequestDate( $center_id, $vaccine_id );
		$is_available =  $request->has_dates();
		
		/**
		 * Output result
		 */
		$center_name = $centers->get_center_by_id( $center_id );
		$vaccine_name = $vaccines->get_vaccine_by_id( $vaccine_id );
		
		if( $is_available ) {
			echo 'Vaccine Available: ' . $vaccine_name . ' - ' . $center_name . PHP_EOL;


			/**
			 * Send Push notification if vaccine is available
			 */
			$notification = new PushoverNotification( $center_id, $vaccine_id ); 
			$notification->send_available_vaccine_notification();

		} else {
			echo 'Vaccine NOT Available: ' . $vaccine_name . ' - ' . $center_name . PHP_EOL;
		}

		// wait for 5 seconds
		sleep(2);
	}
}