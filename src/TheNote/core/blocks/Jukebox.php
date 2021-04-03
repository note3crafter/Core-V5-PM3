<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

declare(strict_types = 1);

namespace TheNote\core\blocks;

use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\tile\Tile;
use pocketmine\block\Block;
use pocketmine\block\Solid;
use pocketmine\math\Vector3;
use pocketmine\block\BlockToolType;

use TheNote\core\tile\JBTile;
use TheNote\core\Main;

class Jukebox extends Solid{

    private $plugin;

    public function __construct(){
        parent::__construct(84, 0, "Jukebox", 84);

    }

    public function getFlammability() : int{
        return 2;
    }

    public function getHardness() : float{
        return 2.0;
    }

    public function getToolType() : int{
        return BlockToolType::TYPE_AXE;
    }

    public function verifyTile(Item $item, Player $player) : int{
        if($this->getLevel()->getTile($this) === null){
            Tile::createTile("Jukebox", $this->getLevel(), JBTile::createNBT($this, 0, $item, $player));
            return 1;
        }
        return 0;
    }

    public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool{
        $this->getLevel()->setBlock($this, $this, true, true);
        $this->verifyTile($item, $player);
        return true;
    }

    public function onActivate(Item $item, Player $player = null) : bool{
        if(!$player instanceof Player){
            return false;
        }
        $this->verifyTile($item, $player);
        $JBTile = $this->getLevel()->getTile($this);
        $JBTile->handleInteract($item, $player);
        return true;
    }

    public function onBreak(Item $item, Player $player = null) : bool{
        $this->verifyTile($item, $player);
        $JBTile = $this->getLevel()->getTile($this);
        $JBTile->handleBreak($item, $player);
        return parent::onBreak($item, $player);
    }
}