<?php

namespace Derweili\RnkBot;

class RequestDate {

	private $base_url = 'https://c19.rhein-neckar-kreis.de/data/getFreeDates';

	private $center_id;
	private $vaccine_id;

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
			'cookie: PatientTicketServiceHash=mkmkxo0vhsekgiy0eygnp1nj3ljn7b'
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

	public function send_request() {

		$data = $this->get_request_data();

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->base_url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->get_all_headers());

		
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$server_output = curl_exec ($ch);

		curl_close ($ch);

		$data = json_decode($server_output, true);
		
		return $data;
	}

	
	public function has_dates() : bool {
		$return_data = $this->send_request();

		if( ! isset( $return_data['status'] ) || $return_data['status'] != 'OK' )
		return false;
		
		// if no items
		if( ! isset( $return_data['items'] ) || empty( $return_data['items'] ) )
			return false;


		return count(  $return_data['items'] ) > 0;
	}
}