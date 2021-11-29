<?php

namespace Derweili\RnkBot;

class RequestDate {

	private $request_dates_base_path = 'https://c19.rhein-neckar-kreis.de/data/getFreeDates';
	private $request_times_base_path = 'https://c19.rhein-neckar-kreis.de/data/getFreeTimes';

	private $center_id;
	private $vaccine_id;

	/**
	 * Store if available dates are already requested
	 */
	private $requested_date = false;

	/**
	 * Store the available dates
	 */
	private $available_dates = [];

	/**
	 * Store the requested dates response body
	 */
	private $request_dates_response = false;

	/**
	 * Store if available times were requested
	 */
	private $requested_times = false;

	/**
	 * Store the available times
	 */
	private $available_times = [];

	/**
	 * Store the requested times response body
	 */
	private $request_times_response = false;

	/**
	 * Construct Request
	 */
	public function __construct( $center_id, $vaccine_id ) {
		$this->center_id = $center_id;
		$this->vaccine_id = $vaccine_id;
	}
	
	private function get_all_headers() {
		$headers = [
			'authority: c19.rhein-neckar-kreis.de',
			'pragma: no-cache',
			'cache-control: no-cache',
			'sec-ch-ua: " Not A;Brand";v="99", "Chromium";v="96", "Google Chrome";v="96"',
			'accept: */*',
			'content-type: application/x-www-form-urlencoded; charset=UTF-8',
			'x-requested-with: XMLHttpRequest',
			'sec-ch-ua-mobile: ?0',
			'user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.55 Safari/537.36',
			'sec-ch-ua-platform: "macOS"',
			'origin: https://c19.rhein-neckar-kreis.de',
			'sec-fetch-site: same-origin',
			'sec-fetch-mode: cors',
			'sec-fetch-dest: empty',
			'referer: https://c19.rhein-neckar-kreis.de/impftermin',
			'accept-language: de-DE,de;q=0.9,en-US;q=0.8,en;q=0.7',
			// 'cookie: PatientTicketServiceHash=mkmkxo0vhsekgiy0eygnp1nj3ljn7b'
		];

		return $headers;
	}

	public function get_request_data() {
		$data = [
			'teststationId' => $this->center_id,
			'vaccineId' => $this->vaccine_id,
			'selfService' => true,
		];

		return $data;
	}

	/**
	 * Request available dates for a vaccine
	 */
	public function send_available_dates_request() {

		$data = $this->get_request_data();

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->request_dates_base_path);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->get_all_headers());

		
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec ($ch);

		curl_close ($ch);

		$data = json_decode($server_output, true);
		
		return $data;
	}

	/**
	 * Request available times for a vaccine and a date
	 */
	public function send_available_times_request( string $date ) {

		$data = [
			'teststationId' => $this->center_id,
			'date' => $date,
			'selfService' => true,
		];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->request_times_base_path);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->get_all_headers());

		
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec ($ch);

		curl_close ($ch);

		$data = json_decode($server_output, true);
		
		return $data;
	}

	public function is_request_successfull( $request_response ) {
		if( ! isset( $request_response['status'] ) || $request_response['status'] != 'OK' )
				return false;
			
		// if no items
		if( ! isset( $request_response['items'] ) )
			return false;

		return true;
	}

	/**
	 * Get the available dates
	 */
	public function get_available_dates () {
		if ( ! $this->requested_date ) {
			$this->request_dates_response = $this->send_available_dates_request();
			$this->requested_date = true;

			if( ! $this->is_request_successfull( $this->request_dates_response ) ) {
				return [];
			}

			$this->available_dates = $this->request_dates_response['items'];
		}

		return $this->available_dates;
	}

	
	public function has_dates() : bool {
		$available_dates = $this->get_available_dates();

		return count(  $available_dates ) > 0;
	}

	public function get_available_times( string $date ) {
		if ( ! $this->requested_times ) {
			$this->request_times_response = $this->send_available_times_request( $date );
			$this->requested_times = true;

			if( ! $this->is_request_successfull( $this->request_times_response ) ) {
				return [];
			}

			$this->available_times = $this->request_times_response['items'];
		}

		return $this->available_times;
	}

	public function has_times(string $date) : bool {
		$available_times = $this->get_available_times( $date );

		return count(  $available_times ) > 0;
	}
}