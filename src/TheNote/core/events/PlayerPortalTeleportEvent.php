<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//                        2017-2020

namespace TheNote\core\events;

use pocketmine\event\Cancellable;
use pocketmine\level\Position;
use pocketmine\Player;
use TheNote\core\blocks\multiblock\PortalMultiBlock;

class PlayerPortalTeleportEvent extends DimensionPortalsEvent implements Cancellable {

    private $player;
    private $block;
    private $target;

    public function __construct(Player $player, PortalMultiBlock $block, Position $target){
        $this->player = $player;
        $this->block = $block;
        $this->target = $target;
    }

    public function getPlayer(): Player{
        return $this->player;
    }

    public function getBlock(): PortalMultiBlock{
        return $this->block;
    }

    public function getTarget(): Position{
        return $this->target->asPosition();
    }

    public function setTarget(Position $target): void{
        $this->target = $target->asPosition();
    }
}
