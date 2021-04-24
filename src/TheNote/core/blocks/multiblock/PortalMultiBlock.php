<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//                        2017-2020

namespace TheNote\core\blocks\multiblock;

use pocketmine\block\Block;
use pocketmine\level\Level;
use pocketmine\Player;
use TheNote\core\player\PlayerSessionManager;

abstract class PortalMultiBlock implements MultiBlock {

    public function __construct(){
    }

    final public function getTeleportationDuration(Player $player): int{
        return $player->isAdventure() || $player->isSurvival() ? 80 : 1;
    }

    abstract public function getTargetWorldInstance(): Level;

    public function onPlayerMoveInside(Player $player, Block $block): void{
        PlayerSessionManager::get($player)->onEnterPortal($this);
    }

    public function onPlayerMoveOutside(Player $player, Block $block): void{
        PlayerSessionManager::get($player)->onLeavePortal();
    }
}