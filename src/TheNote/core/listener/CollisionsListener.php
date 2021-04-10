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
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\utils\Config;
use TheNote\core\Main;

class CollisionsListener implements Listener
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onMove(PlayerMoveEvent $ev)
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        if ($config->get("collision") == true) {
            $player = $ev->getPlayer();
            foreach ($player->getViewers() as $viewer) {
                if ($player->distance($viewer) > 0.5) continue;
                $speed = abs($player->getMotion()->x) + abs($player->getMotion()->z);
                if ($speed > 2) {
                    $viewer->knockBack($player, 0, $viewer->x - $player->x, $viewer->z - $player->z, 0.3);
                    $player->knockBack($viewer, 0, $player->x - $viewer->x, $player->z - $viewer->z, 0.1);
                    break;
                }
                if ($speed < 2) {
                    $viewer->knockBack($player, 0, $viewer->x - $player->x, $viewer->z - $player->z, 0.2);
                    $player->knockBack($viewer, 0, $player->x - $viewer->x, $player->z - $viewer->z, 0.1);
                    break;
                }
            }
        }
    }
}