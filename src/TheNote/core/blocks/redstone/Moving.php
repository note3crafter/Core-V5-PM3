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
use pocketmine\block\Block;
use pocketmine\block\Transparent;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\tile\Tile;

use TheNote\core\tile\MovingBlock;

class Moving extends Transparent {

    protected $id = self::MOVINGBLOCK;
    
    public function __construct(int $meta = 0){
        $this->meta = $meta;
    }

    public function getName() : string {
        return "Movingblock";
    }

    public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool {
        $this->getLevel()->setBlock($this, $this, true, true);
        Tile::createTile("MovingBlock", $this->getLevel(), MovingBlock::createNBT($this));
        return true;
    }
    
    public function getDrops(Item $item) : array {
        $tile = $this->getBlockEntity();
        return [$tile->getDrops($item)];
    }

    public function getBlockEntity() : MovingBlock {
        $tile = $this->getLevel()->getTile($this);
        $moving = null;
        if($tile instanceof MovingBlock){
            $moving = $tile;
        }else{
            $moving = Tile::createTile("MovingBlock", $this->getLevel(), MovingBlock::createNBT($this));
        }
        return $moving;
    }

    public function setData(Block $piston, Block $sourceBlock, ?Tile $tile) : void {
        $this->getBlockEntity()->setData($piston, $sourceBlock, $tile);
    }

    public function setMovedBlock() : void {
        $this->getBlockEntity()->setBlock();
    }
}