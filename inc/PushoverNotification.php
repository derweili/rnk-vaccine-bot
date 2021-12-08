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

	public function __construct( $center_id, $vaccine_id, $available_dates = [] ) {
		$this->center_id = $center_id;
		$this->vaccine_id = $vaccine_id;
		$this->available_dates = $available_dates;
	}

	public function is_configured() {
		if( ! defined( 'PUSHOVER_APP_TOKEN' ) ) {
			return false;
		}
		if( ! defined( 'PUSHOVER_USER_KEY' ) ) {
			return false;
		}

		return true;
	}

	public function send_available_vaccine_notification() {
		
		if( ! $this->is_configured() )
			die('pushover not configured');

		$message = $this->get_available_vaccine_message();


		$pushy = new \Pushy\Client(PUSHOVER_APP_TOKEN);
		$user = new \Pushy\User(PUSHOVER_USER_KEY);

		$message = new \Pushy\Message($this->get_available_vaccine_message());
		$message->setTitle('Vaccine Available');
		$message->setUser($user);

		$pushy->sendMessage($message);
	}

	public function get_available_vaccine_message() {
		$center_name = ( new Centers() )->get_center_by_id( $this->center_id );
		$vaccine_name = ( new Vaccines() )->get_vaccine_by_id( $this->vaccine_id );

		$avialable_dates_text = $this->get_available_dates_message();

		$message = "Vaccine available: " . $center_name . " – " . $vaccine_name . ' ' . PHP_EOL;
		$message .= $avialable_dates_text ?? '';
		$message .= 'https://c19.rhein-neckar-kreis.de/impftermin';

		return $message;
	}

	public function available_dates_to_date_array() : array {
		$dates = [];

		foreach( $this->available_dates as $date ) {
			$date_string = '';
			if( isset($date['weekday']) ) $date_string .= $date['weekday'] . ' ';
			if( isset($date['date']) ) $date_string .= $date['date'] . ' ';
			$dates []= $date_string;
		}

		return $dates;
	}

	public function get_available_dates_message() {
		if( ! $this->available_dates )
			return false;
		
		$message = 'Available dates:' . PHP_EOL;

		$message .= implode(PHP_EOL, $this->available_dates_to_date_array() );

		return $message;
	}
}