<?php

namespace YogurtStudio\Main\Helper;

class CommonHelper
{
	public static function numRound($time, $permission)
	: float|int {
		return round($time / $permission) * $permission < $time ? round($time / $permission) * $permission + $permission : round($time / $permission) * $permission;
	}

	public static function declensionOfNumerals($num, $titles)
	: string {
		$cases = [
			2,
			0,
			1,
			1,
			1,
			2
		];

		return $titles[($num % 100 > 4 && $num % 100 < 20) ? 2 : $cases[min($num % 10, 5)]];
	}
}