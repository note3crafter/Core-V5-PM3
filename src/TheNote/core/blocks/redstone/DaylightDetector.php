<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//                        2017-2020

namespace TheNote\core\blocks\redstone;

use pocketmine\block\BlockFactory;
use pocketmine\Player;
use pocketmine\block\Block;
use pocketmine\block\DaylightSensor;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\tile\Tile;

use TheNote\core\tile\DaylightDetector as Detector;
use TheNote\core\tile\Tiles;

class DaylightDetector extends DaylightSensor implements IRedstone {
    use RedstoneTrait;
    
    public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool {
        $this->getLevel()->setBlock($this, $this, true, true);
        Tiles::createTile("DaylightDetector", $this->getLevel(), Detector::createNBT($this));
        return true;
    }

    public function onActivate(Item $item, Player $player = null) : bool {
        $this->getLevel()->setBlock($this, new DaylightDetectorInverted(), true, true);
        $this->getLevel()->getBlock($this)->updatePower();
        return true;
    }
    
    public function onBreak(Item $item, Player $player = null) : bool {
        $this->getLevel()->setBlock($this, BlockFactory::get(Block::AIR));
        $this->updateAroundRedstone($this);
        return true;
    }
    
    public function getBlockEntity() : Detector {
        $tile = $this->getLevel()->getTile($this);
        $detector = null;
        if($tile instanceof Detector){
            $detector = $tile;
        }else{
            $detector = Tiles::createTile("DaylightDetector", $this->getLevel(),  Detector::createNBT($this));
        }
        return $detector;
    }

    public function updatePower() : void {
        $power = $this->getBlockEntity()->getPower();
        $this->setDamage($power);
        $this->getLevel()->setBlock($this, $this);

        $this->updateAroundRedstone($this);
    }
    
    public function getStrongPower(int $face) : int {
        return 0;
    }

    public function getWeakPower(int $face) : int {
        return $this->getDamage();
    }

    public function isPowerSource() : bool {
        return $this->getDamage() > 0;
    }

    public function onRedstoneUpdate() : void {
    }
}