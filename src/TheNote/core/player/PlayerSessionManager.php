<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//                        2017-2020

namespace TheNote\core\player;

use pocketmine\Player;
use TheNote\core\Main;
use TheNote\core\task\SessionManagerTask;

final class PlayerSessionManager {

    public static $players = [];
    public static $ticking = [];
    
    public static function init(): void{
        Main::getInstance()->getScheduler()->scheduleRepeatingTask(new SessionManagerTask(), 1);
    }
    
    public static function create(Player $player): void{
        self::$players[$player->getId()] = new PlayerSession($player);
    }
    
    public static function destroy(Player $player): void{
        self::stopTicking($player);
        unset(self::$players[$player->getId()]);
    }
    
    public static function stopTicking(Player $player): void{
        unset(self::$ticking[$player->getId()]);
    }
    
    public static function get(Player $player): ?PlayerSession{
        return self::$players[$player->getId()] ?? null;
    }
    
    public static function scheduleTicking(Player $player): void{
        $playerID = $player->getId();
        self::$ticking[$playerID] = $playerID;
    }
}