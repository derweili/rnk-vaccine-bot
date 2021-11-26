<?php

namespace Derweili\RnkBot;

class Centers {


	public static function get_centers() {
		return [
			51 => "Impfung Bammental - Vertusplatz 1, 69245 Bammental",
			36 => "Impfung Bretten - Breitenbachweg 3, 75015 Bretten",
			34 => "Impfung Bruchsal - Sporthalle, Sportzentrum 3, 76646 Bruchsal",
			28 => "Impfung Eberbach - Güterbahnhofstraße 15, 69412 Eberbach",
			35 => "Impfung Graben-Neudorf - Pestalozzi-Straße 2a, 76676 Graben-Neudorf",
			29 => "Impfung Heddesheim - An d. Fohlenweide 5, 68542 Heddesheim",
			30 => "Impfung Heidelberg - Alte Chirurgie - Im Neuenheimer Feld 110, 69120 Heidelberg",
			55 => "Impfung Hockenheim - Rathausstr. 3, 68766 Hockenheim",
			53 => "Impfung Leimen - Theodor-Heuss-Straße 41, 69181 Leimen-St.Ilgen",
			25 => "Impfung RNK - Kurfürstenanlage 38-40",
			31 => "Impfung Schwetzingen - Mannheimer Str. 35, 68723 Schwetzingen",
			32 => "Impfung Sinsheim - Breite Seite 3, 74889 Sinsheim",
			57 => "Impfung Weinheim - Bergstr. 49, 69469 Weinheim",
			33 => "Impfung Wiesloch - Parkstraße 5, 69168 Wiesloch",
		];
	}

	public static function get_center_by_id( $id ) {
		$centers = self::get_centers();
		if ( isset( $centers[ $id ] ) ) {
			return $centers[ $id ];
		}
		return false;
	}

	public function get_all_ids() {
		return array_keys( self::get_centers() );
	}
}