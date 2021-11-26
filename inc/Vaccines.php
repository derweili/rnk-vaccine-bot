<?php

namespace Derweili\RnkBot;

class Vaccines {


	public static function get_vaccines() {
		return [
			2 => "Biontech",
			3 => "Moderna",
			5 => "Johnson & Johnson",
		];
	}

	public static function get_vaccine_by_id( $id ) {
		$centers = self::get_vaccines();
		if ( isset( $centers[ $id ] ) ) {
			return $centers[ $id ];
		}
		return false;
	}

	public function get_all_ids() {
		return array_keys( self::get_vaccines() );
	}
}