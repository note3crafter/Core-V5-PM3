<?php

namespace TheNote\core\item;

use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;

class EmptyMap extends Item{

    public const TYPE_EXPLORER_MAP = 2;

    public function __construct(int $meta = 0){
        parent::__construct(self::EMPTY_MAP, $meta, "Empty Map");
    }

    public function onClickAir(Player $player, Vector3 $directionVector) : bool{
        $map = new Map();
        $map->initMap($player, 0);

        if($this->getDamage() === self::TYPE_EXPLORER_MAP){
            $map->setMapDisplayPlayers(true);
        }

        if($player->getInventory()->canAddItem($map)){
            $player->getInventory()->addItem($map);
        }else{
            $player->dropItem($map);
        }

        $this->pop();

        return true;
    }

    public function getMaxStackSize() : int{
        return 1;
    }
}