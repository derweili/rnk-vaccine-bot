<?php

namespace Derweili\RnkBot;

class Vaccines {


	public static function get_vaccines() {
		return [
			2 => "Biontech",
			// 3 => "Moderna"
		];
	}

	public static function get_vaccine_by_id( $id ) {
		$vaccines = self::get_vaccines();
		if ( isset( $vaccines[ $id ] ) ) {
			return $vaccines[ $id ];
		}
		return false;
	}

	public function get_all_ids() {
		return array_keys( self::get_vaccines() );
	}
}