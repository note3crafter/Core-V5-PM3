<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\utils;

class Facing{

	public const AXIS_Y = 0;
	public const AXIS_Z = 1;
	public const AXIS_X = 2;

	public const FLAG_AXIS_POSITIVE = 1;

	/* most significant 2 bits = axis, least significant bit = is positive direction */
	public const DOWN =   self::AXIS_Y << 1;
	public const UP =    (self::AXIS_Y << 1) | self::FLAG_AXIS_POSITIVE;
	public const NORTH =  self::AXIS_Z << 1;
	public const SOUTH = (self::AXIS_Z << 1) | self::FLAG_AXIS_POSITIVE;
	public const WEST =   self::AXIS_X << 1;
	public const EAST =  (self::AXIS_X << 1) | self::FLAG_AXIS_POSITIVE;

	public const ALL = [
		self::DOWN,
		self::UP,
		self::NORTH,
		self::SOUTH,
		self::WEST,
		self::EAST
	];

	public const HORIZONTAL = [
		self::NORTH,
		self::SOUTH,
		self::WEST,
		self::EAST
	];

	private const CLOCKWISE = [
		self::AXIS_Y => [
			self::NORTH => self::EAST,
			self::EAST => self::SOUTH,
			self::SOUTH => self::WEST,
			self::WEST => self::NORTH
		],
		self::AXIS_Z => [
			self::UP => self::EAST,
			self::EAST => self::DOWN,
			self::DOWN => self::WEST,
			self::WEST => self::UP
		],
		self::AXIS_X => [
			self::UP => self::NORTH,
			self::NORTH => self::DOWN,
			self::DOWN => self::SOUTH,
			self::SOUTH => self::UP
		]
	];

	public static function axis(int $direction) : int{
		return $direction >> 1; //shift off positive/negative bit
	}

	public static function isPositive(int $direction) : bool{
		return ($direction & self::FLAG_AXIS_POSITIVE) === self::FLAG_AXIS_POSITIVE;
	}

	public static function opposite(int $direction) : int{
		return $direction ^ self::FLAG_AXIS_POSITIVE;
	}

	public static function rotate(int $direction, int $axis, bool $clockwise) : int{
		if(!isset(self::CLOCKWISE[$axis])){
			throw new \InvalidArgumentException("Invalid axis $axis");
		}
		if(!isset(self::CLOCKWISE[$axis][$direction])){
			throw new \InvalidArgumentException("Cannot rotate direction $direction around axis $axis");
		}

		$rotated = self::CLOCKWISE[$axis][$direction];
		return $clockwise ? $rotated : self::opposite($rotated);
	}
}
