<?php
declare(strict_types=1);

namespace TheNote\core\blocks\multiblock;

use pocketmine\block\Block;
use pocketmine\level\Level;
use pocketmine\Player;
use TheNote\core\player\PlayerSessionManager;
use TheNote\core\Main;

/**
 * Class PortalMultiBlock
 * @package Xenophilicy\TableSpoon\block\multiblock
 */
abstract class PortalMultiBlock implements MultiBlock {
    
    /**
     * PortalMultiBlock constructor.
     */
    public function __construct(){
    }
    
    final public function getTeleportationDuration(Player $player): int{
        return $player->isAdventure() || $player->isSurvival() ? 80 : 1;
    }
    
    abstract public function getTargetWorldInstance(): Level;

}