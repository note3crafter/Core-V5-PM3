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

use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\tile\Tile;

use TheNote\core\tile\RedstoneComparator;
use TheNote\core\utils\Facing;

class RedstoneComparatorUnpowered extends RedstoneDiode {

    protected $id = self::UNPOWERED_COMPARATOR;
    protected $itemId = Item::COMPARATOR;

    public function getName() : string {
        return "Unpowered Comparator";
    }
    
    public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool {
        $under = $this->getSide(Facing::DOWN);
        if (!$under->isSolid() || $under->isTransparent()) {
            return false;
        }

        $faces = [
            0 => 1,
            1 => 2,
            2 => 3,
            3 => 0
        ];
        $this->setDamage($faces[$player instanceof Player ? $player->getDirection() : 0]);
        $this->level->setBlock($this, $this);
        Tile::createTile("BlockEntityRedstoneComparator", $this->getLevel(), RedstoneComparator::createNBT($this));
        $this->updateAroundDiodeRedstone($this);
        return true;
    }
    
    public function onActivate(Item $item, Player $player = null) : bool {
        if ($this->getDamage() >= 4) {
            $this->setDamage($this->getDamage() - 4);
            $this->level->broadcastLevelEvent($this, LevelEventPacket::EVENT_REDSTONE_TRIGGER, 500);
        } else {
            $this->setDamage($this->getDamage() + 4);
            $this->level->broadcastLevelEvent($this, LevelEventPacket::EVENT_REDSTONE_TRIGGER, 550);
        }
        $this->level->setBlock($this, $this);
        $this->onRedstoneUpdate();
        return true;
    }

    public function onScheduledUpdate() : void {
        $comparator = $this->getBlockENtity();
        $power = $comparator->recalculateOutputPower();
        $comparator->setOutputSignal($power);

        if ($this->getOutputPower() <= 0) {
            return;
        }
        
        $this->getLevel()->setBlock($this, new RedstoneComparatorPowered($this->getDamage()));
        $this->updateAroundDiodeRedstone($this);
    }
    
    public function getBlockEntity() : RedstoneComparator {
        $tile = $this->getLevel()->getTile($this);
        $comparator = null;
        if($tile instanceof RedstoneComparator){
            $comparator = $tile;
        }else{
            $comparator = Tile::createTile("RedstoneComparator", $this->getLevel(), RedstoneComparator::createNBT($this));
        }
        return $comparator;
    }

    public function isComparisonMode() : bool {
        return $this->getDamage() < 4;
    }

    public function isSubtractionMode() : bool {
        return $this->getDamage() >= 4;
    }

    public function getOutputPower() : int {
        return $this->getBlockEntity()->getOutputSignal();
    }

    public function onRedstoneUpdate() : void {
        $comparator = $this->getBlockEntity();
        if ($comparator->getOutputSignal() == $comparator->recalculateOutputPower()) {
            return;
        }

        $this->getLevel()->scheduleDelayedBlockUpdate($this, 2);
    }
}