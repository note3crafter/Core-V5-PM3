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
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\block\Block;

use pocketmine\utils\Config;
use TheNote\core\server\LiftListener;
use TheNote\core\Main;

class PlayerInteractListener extends LiftListener implements Listener
{
    public function onPlayerInteract(PlayerInteractEvent $event) {
        $settings = new Config($this->getPlugin()->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $config = new Config($this->getPlugin()->getDataFolder() . Main::$setup . "Config" . ".yml", Config::YAML);
        if($event->isCancelled()) return;
        if($event->getBlock()->getId() !== Block::DAYLIGHT_SENSOR && $event->getBlock()->getId() !== Block::DAYLIGHT_SENSOR_INVERTED) return;
        if(($plot = $this->getPlugin()->myplot->getPlotByPosition($event->getPlayer()->getPosition())) === null) return;

        if($plot->owner !== $event->getPlayer()->getName() && !$event->getPlayer()->hasPermission("core.lift.admin.interact")) {
            if($config->get("helperprivateLift") !== true) {
                $event->getPlayer()->sendMessage($settings->get("lift") . "§cDu kannst den Lift nicht umstellen.");
                return;
            }
            if(!$plot->isHelper($event->getPlayer()->getName())) {
                $event->getPlayer()->sendMessage($settings->get("lift") . "§cDu kannst den Lift nicht umstellen.");
                return;
            }
        }

        if (isset($this->getPlugin()->interactCooldown[$event->getPlayer()->getName()])) {
            if ($this->getPlugin()->interactCooldown[$event->getPlayer()->getName()] > time()) {
                $event->getPlayer()->sendMessage($settings->get("lift") . "§cWarte 3 Sekunden bevor du den Lift umstellen kannst!");
                return;
            }
        }

        if($event->getBlock()->getId() === Block::DAYLIGHT_SENSOR) {
            $event->getBlock()->getLevel()->setBlock($event->getBlock()->asVector3(), Block::get(Block::DAYLIGHT_SENSOR_INVERTED));
            $event->getPlayer()->sendMessage($settings->get("lift") . "Du hast diesen Lift auf §aPrivat §6umgestellt.");
            $this->getPlugin()->interactCooldown[$event->getPlayer()->getName()] = time() + 3;
        }else{
            $event->getBlock()->getLevel()->setBlock($event->getBlock()->asVector3(), Block::get(Block::DAYLIGHT_SENSOR));
            $event->getPlayer()->sendMessage($settings->get("lift") . "Du hast diesen Lift auf §aÖffentlich §6umgestellt");
            $this->getPlugin()->interactCooldown[$event->getPlayer()->getName()] = time() + 3;
        }
    }
}