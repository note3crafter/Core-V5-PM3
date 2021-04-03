<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\utils\Config;
use TheNote\core\Main;

class RegelEvent implements Listener {

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }
    public function onMove(PlayerMoveEvent $event) {
        $name = $event->getPlayer();
        $player = $event->getPlayer()->getLowerCaseName();
        $usr = new Config($this->plugin->getDataFolder() . Main::$userfile . $player . ".json", Config::JSON);
        $settings = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if ($usr->get("rulesaccpet") === false){
            $event->setCancelled(true);
            $name->sendTip($settings->get("error")  . "§cDu musst die Regeln Bestätigen um auf dem Server Spielen zu können!!\n §r§6/regeln");
        }
    }
    public function onChat(PlayerChatEvent $event) {
        $name = $event->getPlayer();
        $player = $event->getPlayer()->getLowerCaseName();
        $usr = new Config($this->plugin->getDataFolder() . Main::$userfile . $player . ".json", Config::JSON);
        $settings = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if ($usr->get("rulesaccpet") === false){
            $event->setCancelled(true);
            $name->sendTip($settings->get("error") . "§cDu musst die Regeln Bestätigen um auf dem Server Spielen zu können!!\n §r§6/regeln");
        }
    }
    public function onInteract(PlayerInteractEvent $event) {
        $name = $event->getPlayer();
        $player = $event->getPlayer()->getLowerCaseName();
        $usr = new Config($this->plugin->getDataFolder() . Main::$userfile . $player . ".json", Config::JSON);
        $settings = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if ($usr->get("rulesaccpet") === false){
            $event->setCancelled(true);
            $name->sendTip($settings->get("error")  . "§cDu musst die Regeln Bestätigen um auf dem Server Spielen zu können!!\n §r§6/regeln");
        }
    }
}