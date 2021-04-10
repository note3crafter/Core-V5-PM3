<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//                        2017-2020

namespace TheNote\core\blocks\redstone\piston;

use pocketmine\Player;
use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\Transparent;
use pocketmine\item\Item;

use TheNote\core\utils\Facing;

class Pistonarmcollision extends Transparent {

    protected $id = self::PISTONARMCOLLISION;
    
    public function __construct(int $meta = 0){
        $this->meta = $meta;
    }

    public function getName() : string {
        return "pistonarmcollision";
    }
 
    public function onBreak(Item $item, Player $player = null) : bool {
        $this->getLevel()->setBlock($this, BlockFactory::get(Block::AIR));
        $face = $this->getDamage();
        if ($face == Facing::UP || $face == Facing::DOWN) {
            $face = Facing::opposite($face);
        }
        $block = $this->getSide($face);
        if ($block instanceof Piston) {
            $this->getLevel()->useBreakOn($block);
        }
        return true;
    }

    public function onNearbyBlockChange() : void {
        $face = $this->getDamage();
        if ($face == Facing::UP || $face == Facing::DOWN) {
            $face = Facing::opposite($face);
        }
        $block = $this->getSide($face);
        if (!($block instanceof Piston)) {
            $this->getLevel()->useBreakOn($this);
        }
    }

    public function getDrops(Item $item) : array {
        return [];
    }
}