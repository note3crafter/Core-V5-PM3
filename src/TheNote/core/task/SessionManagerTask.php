<?php
declare(strict_types=1);

namespace TheNote\core\task;

use pocketmine\scheduler\Task;
use TheNote\core\player\PlayerSessionManager;

class SessionManagerTask extends Task {
    
    public function onRun(int $currentTick){
        foreach(PlayerSessionManager::$ticking as $playerID){
            PlayerSessionManager::$players[$playerID]->tick();
        }
    }
}