<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\blocks;

use TheNote\core\Main as Beacons;
use TheNote\core\tile\Beacon as BeaconTile;
use pocketmine\Achievement;
use pocketmine\block\Block;
use pocketmine\block\BlockToolType;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;

class Beacon extends Block {
    protected $id = self::BEACON;

    public function __construct(int $meta = 0){
        parent::__construct(self::BEACON, $meta);
    }
    public function getHardness() : float{
        return 3;
    }
    public function getBlastResistance() : float {
        return 15;
    }
    public function getLightLevel() : int {
        return 15;
    }
    public function getName() : string {
        return "Beacon";
    }

    public function getToolType() : int {
        return BlockToolType::TYPE_NONE;
    }
    public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool {
        $beacon = BeaconTile::createTile(BeaconTile::BEACON, $this->getLevel(), BeaconTile::createNBT($this, $face, $item, $player));
        if($beacon->getLayers() > 3) {
            Achievement::broadcast($player, "create_full_beacon");
        }
        return parent::place($item, $blockReplace, $blockClicked, $face, $clickVector, $player);
    }
    public function onActivate(Item $item, Player $player = null) : bool {
        if($player instanceof Player) {
            $t = $this->getLevel()->getTile($this);
            $beacon = null;
            if($t instanceof BeaconTile) {
                $beacon = $t;
            }else {
                $beacon = BeaconTile::createTile(BeaconTile::BEACON, $this->getLevel(), BeaconTile::createNBT($this));
            }
            Beacons::setBeaconInventory($player, $beacon);
            $player->addWindow($beacon->getInventory());
        }
        return true;
    }
    public function onBreak(Item $item, Player $player = null) : bool {
        $t = $this->getLevel()->getTile($this);
        $beacon = null;
        if($t instanceof BeaconTile) {
            $beacon = $t;
        }else {
            $beacon = BeaconTile::createTile(BeaconTile::BEACON, $this->getLevel(), BeaconTile::createNBT($this));
        }
        if(!$beacon->isMovable())
            return false;
        return parent::onBreak($item, $player);
    }
}