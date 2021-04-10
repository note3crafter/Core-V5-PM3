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

use pocketmine\block\Block;
use TheNote\core\blocks\redstone\piston\Piston;
use TheNote\core\blocks\redstone\IRedstone;

class RedstoneUtils {

    public static function isNormalBlock(Block $block) : bool {
        return !$block->isTransparent() && $block->isSolid() && !RedstoneUtils::isPowerSource($block) && !($block instanceof Piston);
    }

    public static function getStrongPower(Block $block, int $face) : int {
        if ($block instanceof IRedstone){
            return $block->getStrongPower($face);
        } else {
            return 0;
        }
    }

    public static function getWeakPower(Block $block, int $face) : int {
        if ($block instanceof IRedstone) {
            return $block->getWeakPower($face);
        } else {
            return 0;
        }
    }

    public static function isPowerSource(Block $block) : bool {
        if ($block instanceof IRedstone) {
            return $block->isPowerSource();
        }
        return false;
    }
}