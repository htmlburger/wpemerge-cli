<?php

namespace WPEmerge\Cli\Helpers;

class Boolean {
	/**
	 * Convert a string to boolean
	 *
	 * @param  string  $string
	 * @return boolean
	 */
	public static function fromString( $string ) {
		$values = [
			'1'     => true,
			'y'     => true,
			'yes'   => true,
			'true'  => true,

			'0'     => false,
			'n'     => false,
			'no'    => false,
			'false' => false,
		];

		foreach ( $values as $value => $boolean ) {
			if ( strtolower( $value ) === strtolower( $string ) ) {
				return $boolean;
			}
		}

		// rely on PHP's string to boolean conversion in all other cases
		return (bool) $string;
	}
}
