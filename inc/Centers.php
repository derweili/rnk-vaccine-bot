<?php

namespace Derweili\RnkBot;

/**
 * Centers
 * 
 * @package Derweili\RnkBot
 */
class Centers {

	public static function get_centers() {
		return [
			25 => "Impfung RNK - Kurfürstenanlage 38-40",
			28 => "Impfung Eberbach - Güterbahnhofstraße 15, 69412 Eberbach",
			29 => "Impfung Heddesheim - An d. Fohlenweide 5, 68542 Heddesheim",
			30 => "Impfung Heidelberg - Alte Chirurgie - Im Neuenheimer Feld 110, 69120 Heidelberg",
			31 => "Impfung Schwetzingen - Mannheimer Str. 35, 68723 Schwetzingen",
			32 => "Impfung Sinsheim - Breite Seite 3, 74889 Sinsheim",
			33 => "Impfung Wiesloch - Parkstraße 5, 69168 Wiesloch",
			34 => "Impfung Bruchsal - Sporthalle, Sportzentrum 3, 76646 Bruchsal",
			35 => "Impfung Graben-Neudorf - Pestalozzi-Straße 2a, 76676 Graben-Neudorf",
			36 => "Impfung Bretten - Breitenbachweg 3, 75015 Bretten",
			49 => "Impfung Heidelberg Patrick-Henry-Village - South-Gettysburg-Avenue 45, 69124 Heidelberg",
			51 => "Impfung Bammental - Vertusplatz 1, 69245 Bammental",
			53 => "Impfung Leimen - Theodor-Heuss-Straße 41, 69181 Leimen-St.Ilgen",
			55 => "Impfung Hockenheim - Rathausstr. 3, 68766 Hockenheim",
			57 => "Impfung Weinheim - Bergstr. 49, 69469 Weinheim",
			60 => "Impfung Weingarten - Mützenau 2, 76356 Weingarten",
			61 => "Impfung Oberhausen-Rheinhausen - Rheinstrasse 24, 68794 Oberhausen-Rheinhausen",
			63 => "Impfung Ubstadt-Weiher - Schulstraße 1, 76698 Ubstadt-Weiher",
			64 => "Impfung Karlsdorf-Neuthard - Altenbürgzentrum 1, 76689 Karlsdorf-Neuthard",
			65 => "Impfung Waghäusel - Seppl-Herberger-Ring 6, 68753 Waghäusel",
			66 => "Impfung Stutensee - Badstraße 7, 76297 Stutensee",
			67 => "Impfung Oberderdingen - An der Hessel 4, 75038 Oberderdingen",
			78 => "Impfung Bad Schönborn - Schönborn-Allee 1, 76669 Bad-Schönborn",
			79 => "Impfung Pfinztal - Karlsruher Straße 84, 76327 Pfinztal",
			80 => "Impfung Philippsburg - Rathaus, Rote-Tor-Straße 6 - 10, 76661 Philippsburg",
			81 => "Impfung Östringen - Johann-Sebastian-Bach-Straße 24, 76684 Östringen",
			82 => "Impfung Linkenheim-Hochstetten - Rathausstraße 1, 76351 Linkenheim-Hochstetten",
			83 => "Impfung Forst - Hambrücker Straße 59, 76694 Forst",
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