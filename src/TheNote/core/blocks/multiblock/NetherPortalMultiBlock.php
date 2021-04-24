<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//                        2017-2020

namespace TheNote\core\blocks\multiblock;

use pocketmine\block\Block;
use pocketmine\block\Obsidian;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\Player;
use TheNote\core\Main;

class NetherPortalMultiBlock extends PortalMultiBlock {
    
    private $frameID;
    
    public function __construct(){
        parent::__construct();
        $this->frameID = (new Obsidian())->getId();
    }
    
    public function getTargetWorldInstance(): Level{
        return Main::$netherLevel;
    }
    
    public function update(Block $block): bool{
        return false;
    }
    
    public function isValid(Block $block): bool{
        $blockId = $block->getId();
        return $blockId === $this->frameID || $blockId === Block::PORTAL;
    }
    
    public function interact(Block $wrapping, Player $player, Item $item, int $face): bool{
        return false;
    }
}