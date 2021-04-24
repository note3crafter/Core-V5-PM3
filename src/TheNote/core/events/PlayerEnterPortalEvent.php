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
use pocketmine\Player;
use TheNote\core\blocks\multiblock\PortalMultiBlock;

class PlayerEnterPortalEvent extends DimensionPortalsEvent implements Cancellable {

    private $player;
    private $block;
    private $teleportDuration;

    public function __construct(Player $player, PortalMultiBlock $block, int $teleportDuration){
        $this->player = $player;
        $this->block = $block;
        $this->teleportDuration = $teleportDuration;
    }

    public function getPlayer(): Player{
        return $this->player;
    }

    public function getBlock(): PortalMultiBlock{
        return $this->block;
    }

    public function getTeleportDuration(): int{
        return $this->teleportDuration;
    }

    public function setTeleportDuration(int $teleportDuration): void{
        $this->teleportDuration = $teleportDuration;
    }
}
