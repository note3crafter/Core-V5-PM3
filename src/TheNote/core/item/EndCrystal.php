<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\item;

use pocketmine\block\Block;
use pocketmine\block\Obsidian;
use pocketmine\entity\Entity;
use TheNote\core\entity\obejct\EnderCrystal;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;

class EndCrystal extends Item{

    public function __construct(int $meta = 0){
        parent::__construct(self::END_CRYSTAL, $meta, "End Crystal");
    }

    public function onActivate(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector) : bool{
        if($player->level->getBlock($blockReplace->down()) instanceof Obsidian){
            $crystal = new EnderCrystal($player->level, Entity::createBaseNBT($blockReplace->add(0.5, 0.5, 0.5)));
            $crystal->spawnToAll();

            if($player->isSurvival()){
                $this->pop();
            }
            return true;
        }
        return false;
    }
}
