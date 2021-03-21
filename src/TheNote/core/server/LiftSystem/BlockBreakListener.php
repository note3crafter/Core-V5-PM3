<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server\LiftSystem;

use pocketmine\event\Listener;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\block\Block;

use pocketmine\utils\Config;
use TheNote\core\server\LiftListener;
use TheNote\core\Main;

class BlockBreakListener extends LiftListener implements Listener {

    public function onBlockBreak(BlockBreakEvent $event) {
        $settings = new Config($this->getPlugin()->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $config = new Config($this->getPlugin()->getDataFolder() . Main::$setup . "Config" . ".yml", Config::YAML);
        if($event->isCancelled()) return;
        if($event->getBlock()->getId() !== Block::DAYLIGHT_SENSOR && $event->getBlock()->getId() !== Block::DAYLIGHT_SENSOR_INVERTED) return;
        if(($plot = $this->getPlugin()->myplot->getPlotByPosition($event->getPlayer()->getPosition())) === null) return;

        if($plot->owner !== $event->getPlayer()->getName() && !$event->getPlayer()->hasPermission("core.lift.admin.remove")) {
            if($config->get("helperprivateLift") !== true) {
                $event->getPlayer()->sendMessage($settings->get("lift") . "§cDu hast nicht die benötigten Berechtigungen, um ein Lift zu zerstören.");
                $event->setCancelled();
                return;
            }
            if(!$plot->isHelper($event->getPlayer()->getName())) {
                $event->getPlayer()->sendMessage($settings->get("lift") . "§cDu hast nicht die benötigten Berechtigungen, um ein Lift zu zerstören.");
                $event->setCancelled();
                return;
            }
        }

        $event->getPlayer()->sendMessage($settings->get("lift") . "Du hast diesen Lift erfolgreich zerstört.");
    }
}