<?php

namespace Derweili\RnkBot;

/**
 * Pushover.net Notifications
 * 
 * Implements the Pushover.net API
 * Takes the center_id and vaccine_id as input
 * Builds the message and sends it to the Pushover.net API
 */
class PushoverNotification {

	private $center_id;
	private $vaccine_id;
	private $available_dates = [];

	public function __construct() {}

	public function is_configured() {
		if( ! defined( 'PUSHOVER_APP_TOKEN' ) ) {
			return false;
		}
		if( ! defined( 'PUSHOVER_USER_KEY' ) ) {
			return false;
		}

		return true;
	}

	public function send_available_vaccine_notification( $center_id, $vaccine_id ) {
		
		if( ! $this->is_configured() ) return;

		$pushy = new \Pushy\Client(PUSHOVER_APP_TOKEN);
		$user = new \Pushy\User(PUSHOVER_USER_KEY);

		$message = new \Pushy\Message($this->get_available_vaccine_message( $center_id, $vaccine_id ));
		$message->setTitle('Vaccine Available');
		$message->setUser($user);

		$pushy->sendMessage($message);
	}

	public function send_combined_available_vaccine_notification( $available_appointments ) {
		
		if( ! $this->is_configured() ) return;

		// $message = $this->get_available_vaccine_message();


		$pushy = new \Pushy\Client(PUSHOVER_APP_TOKEN);
		$user = new \Pushy\User(PUSHOVER_USER_KEY);

		$message = new \Pushy\Message( $this->get_combined_available_vaccine_message( $available_appointments ) );
		$message->setTitle('Vaccine/s Available');
		$message->setUser($user);

		$pushy->sendMessage($message);
	}

	public function get_combined_available_vaccine_message( $available_appointments ) {
		// var_dump('get_combined_available_vaccine_message');
		// var_dump($available_appointments);
		$message = '';

		$message .= 1 <= count( $available_appointments ) ? ' Vaccine Available: ' : 'Vaccines Available: ';
		$message .= PHP_EOL;

		foreach ($available_appointments as $center_id => $vaccine_ids) {
			$center_name = ( new Centers() )->get_center_by_id( $center_id );
			$message .= $center_name . ': ';	
			foreach ($vaccine_ids as $vaccine_id) {
				$vaccine_name = ( new Vaccines() )->get_vaccine_by_id( $vaccine_id );
				$message .= $vaccine_name . ', ';
			}

			$message .= PHP_EOL;
			$message .= ' ' . PHP_EOL;
		}

		return $message;
	}

	public function get_available_vaccine_message( $center_id, $vaccine_id ) {
		$center_name = ( new Centers() )->get_center_by_id( $this->center_id );
		$vaccine_name = ( new Vaccines() )->get_vaccine_by_id( $this->vaccine_id );

		// $avialable_dates_text = $this->get_available_dates_message();

		$message = "Vaccine available: " . $center_name . " – " . $vaccine_name . ' ' . PHP_EOL;
		$message .= $avialable_dates_text ?? '';
		$message .= 'https://c19.rhein-neckar-kreis.de/impftermin';

		return $message;
	}

	public function available_dates_to_date_array( $available_dates = [] ) : array {
		$dates = [];

		foreach( $available_dates as $date ) {
			$date_string = '';
			if( isset($date['weekday']) ) $date_string .= $date['weekday'] . ' ';
			if( isset($date['date']) ) $date_string .= $date['date'] . ' ';
			$dates []= $date_string;
		}

		return $dates;
	}

	public function get_available_dates_message( $available_dates = [] ) {
		if( empty ( $available_dates ) ) {
			return false;
		}
		
		$message = 'Available dates:' . PHP_EOL;

		$message .= implode(PHP_EOL, $this->available_dates_to_date_array( $available_dates ) );

		return $message;
	}
}