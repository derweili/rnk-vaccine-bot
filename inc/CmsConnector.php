<?php

namespace Derweili\RnkBot;

/**
 * Connect to CMS
 * 
 * Sends Availability Status to CMS
*/
class CmsConnector {

	public function is_configured() {
		if( ! defined( 'CMS_BASE_URL' ) ) {
			return false;
		}

		return true;
	}

	public function get_cms_base_url() {
		if( ! $this->is_configured() ) return false;
		return CMS_BASE_URL;
	}

	public function get_cms_api_token() {
		if( defined( 'CMS_API_TOKEN' ) ) {
			return CMS_API_TOKEN;
		}
	}

	public function save_availability_status( $center_id, $vaccine_id, $is_available = false) {
		
		if( ! $this->is_configured() ) return;

		$request_body = [
			'data' => [
				'centerId' => $center_id,
				'vaccineId' => $vaccine_id,
				'isAvailable' => $is_available
			]
		];


		/**
		 * Send Post request with json body
		 */
		$url = $this->get_cms_base_url() . '/api/availability-statuses';

		$ch = curl_init( $url );
		
		$request_headers = array(
			'Content-Type: application/json',
			'Content-Length: ' . strlen( json_encode( $request_body ) ),
		);

		if( $this->get_cms_api_token() ) {
			$request_headers[] = 'Authorization: Bearer ' . $this->get_cms_api_token();
		}

		curl_setopt( $ch, CURLOPT_POST, 1);
		curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $request_body ) );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $request_headers );

		$response = curl_exec( $ch );

		if( $response === false ) {
			echo 'CMS Connection Error: ' . curl_error( $ch );
			return;
		} else {
			var_dump( $response );
		}

		curl_close( $ch );

	}

}