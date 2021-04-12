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
use pocketmine\block\Solid;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\tile\Tile;


use TheNote\core\blocks\redstone\IRedstone;
use TheNote\core\blocks\redstone\RedstoneTrait;
use TheNote\core\tile\PistonArm;
use TheNote\core\utils\Facing;
use TheNote\core\blocks\redstone\RedstoneWire;

class Piston extends Solid implements IRedstone {
    use RedstoneTrait;

    protected $id = self::PISTON;

    public function __construct(int $meta = 0){
        $this->meta = $meta;
    }

    public function getName() : string {
        return "Piston";
    }

    public function getVariantBitmask() : int {
        return 0;
    }

    public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool {
        $damage = 0;
        if($player !== null) {
            $faces = [5, 3, 4, 2];
            $damage = $faces[$player->getDirection()];
            if ($player->getPitch() > 45) {
                $damage = 1;
            } else if ($player->getPitch() < -45) {
                $damage = 0;
            }
        }

        $this->setDamage($damage);
        $this->level->setBlock($this, $this, true, true);

        $nbt = PistonArm::createNBT($this);
        $nbt->setByte("Sticky", $this->isSticky() ? 1 : 0);
        Tile::createTile("PistonArm", $this->getLevel(), $nbt);

        $this->onRedstoneUpdate();

        return true;
    }

    public function getBlockEntity() : PistonArm {
        $tile = $this->getLevel()->getTile($this);
        $arm = null;
        if($tile instanceof PistonArm){
            $arm = $tile;
        }else{
            $nbt = PistonArm::createNBT($this);
            $nbt->setByte("Sticky", $this->isSticky() ? 1 : 0);
            $arm = Tile::createTile("PistonArm", $this->getLevel(), $nbt);
        }
        return $arm;
    }

    public function isSticky() : bool {
        return false;
    }

    public function getFace() : int {
        $damage = $this->getDamage();
        if ($damage == Facing::UP || $damage == Facing::DOWN) {
            return $damage;
        }
        return Facing::opposite($damage);
    }

    public function getStrongPower(int $face) : int {
        return 0;
    }

    public function getWeakPower(int $face) : int {
        return 0;
    }

    public function isPowerSource() : bool {
        return false;
    }

    public function onRedstoneUpdate() : void {
        $power = $this->isBlockPowered($this->asVector3(), $this->getFace());
        if (!$power) {
            $direction = Facing::HORIZONTAL;
            for ($i = 0; $i < count($direction); ++$i) {
                $face = $direction[$i];
                if ($face == $this->getFace()) {
                    continue;
                }

                $block = $this->getSide($face);
                if ($block instanceof RedstoneWire && $block->getDamage() > 0) {
                    $power = true;
                }
            }
        }
        $this->getBlockEntity()->extend($power);
    }
    public function getBreakTime(Item $item) : float{
        return 0.8;
    }
}