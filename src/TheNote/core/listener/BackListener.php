<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\utils\Config;
use TheNote\core\Main;

class BackListener implements Listener {

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }
    public function playerDeath(PlayerDeathEvent $event){
    $player = $event->getPlayer();
    $config = new Config($this->plugin->getDataFolder() . Main::$backfile . "Back.json", Config::JSON);
    $config->set($player->getName(), "{$player->getX()} {$player->getY()} {$player->getZ()} {$player->getLevel()->getName()}");
    $config->save();
    }
    public function playerQuit(PlayerQuitEvent $event){
        $player = $event->getPlayer();
        $config = new Config($this->plugin->getDataFolder() . Main::$backfile . "Back.json", Config::JSON);
        if ($config->exists($player->getName())){
            $config->remove($player->getName());
        }
    }
}