<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//                        2017-2020

namespace TheNote\core\blocks\redstone\doors;

use pocketmine\Player;
use pocketmine\block\BlockToolType;
use pocketmine\item\Item;
use pocketmine\item\TieredTool;

class IronTrapdoor extends Trapdoor  {

    protected $id = self::IRON_TRAPDOOR;

    public function getName() : string {
        return "Iron Trapdoor";
    }

    public function getHardness() : float {
        return 5;
    }

    public function getToolType() : int {
        return BlockToolType::TYPE_PICKAXE;
    }

    public function getToolHarvestLevel() : int {
        return TieredTool::TIER_WOODEN;
    }

    public function getFuelTime() : int{
        return 0;
    }

    public function onActivate(Item $item, Player $player = null) : bool {
        return true;
    }
}