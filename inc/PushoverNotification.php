<?php

namespace Derweili\RnkBot;

class PushoverNotification {

	private $center_id;
	private $vaccine_id;

	public function __construct( $center_id, $vaccine_id ) {
		$this->center_id = $center_id;
		$this->vaccine_id = $vaccine_id;
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

		$message = "Vaccine available: " . $center_name . " – " . $vaccine_name . ' ' . PHP_EOL;
		$message .= 'https://c19.rhein-neckar-kreis.de/impftermin';

		return $message;
	}
}