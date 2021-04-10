<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//                        2017-2020

namespace TheNote\core\utils;

use pocketmine\math\Vector3;

final class SkullSideManager {

	private static $directions = [
		0 => 180,
		1 => 202.5,
		2 => 225,
		3 => 247.5,
		4 => 270,
		5 => 292.5,
		6 => 315,
		7 => 337.5,
		8 => 0,
		9 => 22.5,
		10 => 45,
		11 => 67.5,
		12 => 90,
		13 => 112.5,
		14 => 135,
		15 => 157.5,
	];

	public static function addAdditions(int $face, int $skullRotation): array {
		if ($face === Vector3::SIDE_UP) return [self::$directions[$skullRotation], new Vector3(0.5, 0, 0.5)];
		$baseVector = new Vector3(0, 0.23, 0);
		
		switch ($face) {
			case Vector3::SIDE_SOUTH:
				$baseVector->x += 0.5;
				$baseVector->z += 0.25;
				break;
				
			case Vector3::SIDE_NORTH:
				$baseVector->x += 0.5;
				$baseVector->z += 0.75;
				break;
			case Vector3::SIDE_EAST:
				$baseVector->x += 0.25;
				$baseVector->z += 0.5;
				break;
				
			case Vector3::SIDE_WEST:
				$baseVector->x += 0.75;
				$baseVector->z += 0.5;
				break;
		}
		
		return [self::getFaceYaw($face), $baseVector];
	}

	private static function getFaceYaw(int $face): int {
		switch ($face) {
			case Vector3::SIDE_SOUTH: return 0;
			case Vector3::SIDE_EAST: return 270;
			case Vector3::SIDE_WEST: return 90;
		}
		return 180;
	}
}