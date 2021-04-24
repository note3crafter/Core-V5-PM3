<?php

namespace TheNote\core\utils;

use pocketmine\block\Air;
use pocketmine\block\Block;
use pocketmine\level\Level;
use pocketmine\level\Position;
use pocketmine\math\Vector3;
use TheNote\core\blocks\Obsidian;
use TheNote\core\blocks\Portal;

class Utils
{

    public static function genNetherSpawn(Position $pos, Level $level): Position
    {
        $x = (int)ceil($pos->getX() / 8);
        $y = (int)ceil($pos->getY());
        $z = (int)ceil($pos->getZ() / 8);
        $top = $level->getBlockAt($x, $y, $z);
        $bottom = $level->getBlockAt($x, $y - 1, $z);
        if (!self::checkBlock($top) || !self::checkBlock($bottom)) {
            for ($y = 125; $y >= 0; $y--) {
                $top = $level->getBlockAt($x, $y, $z);
                $bottom = $level->getBlockAt($x, $y - 1, $z);
                if (self::checkBlock($top) && self::checkBlock($bottom)) break;
            }
            if ($y <= 0) {
                $y = mt_rand(10, 125);
            }
        }
        $pos = new Vector3($x, $y, $z);
        $obsidian = [[0, 0], [-1, 0], [-1, 1], [-1, 2], [-1, 3], [-1, 4], [0, 4], [1, 4], [2, 4], [2, 3], [2, 2], [2, 1], [2, 0], [1, 0]];
        foreach ($obsidian as $add) {
            $level->setBlock(new Vector3($pos->x + $add[0], $pos->y + $add[1], $pos->z), new Obsidian());
        }
        $portal = [[0, 1], [0, 2], [0, 3], [1, 1], [1, 2], [1, 3]];
        foreach ($portal as $add) {
            $level->setBlock(new Vector3($pos->x + $add[0], $pos->y + $add[1], $pos->z), new Portal());
        }
        for ($x = -1; $x <= 2; $x++) {
            for ($z = -1; $z <= 1; $z++) {
                $level->setBlock(new Vector3($pos->x + $x, $pos->y - 1, $pos->z + $z), new Obsidian());
            }
        }
        return new Position($pos->x - 1, $pos->y, $pos->z - 1, $level);
    }
    private static function checkBlock(Block $block): bool{
        if($block instanceof Air) return true;
        return false;
    }
}
