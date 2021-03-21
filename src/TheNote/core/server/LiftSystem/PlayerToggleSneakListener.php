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
use pocketmine\event\player\PlayerToggleSneakEvent;
use pocketmine\math\Vector3;
use pocketmine\block\Block;
use pocketmine\level\Position;

use pocketmine\level\sound\EndermanTeleportSound;
use pocketmine\level\sound\AnvilUseSound;

use pocketmine\utils\Config;
use TheNote\core\server\LiftListener;
use TheNote\core\Main;

class PlayerToggleSneakListener extends LiftListener implements Listener {

    public function onPlayerToggleSneak(PlayerToggleSneakEvent $event) {
        $settings = new Config($this->getPlugin()->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $config = new Config($this->getPlugin()->getDataFolder() . Main::$setup . "Config" . ".yml", Config::YAML);
        if(!$event->getPlayer()->isSneaking()) return;
        $block = $event->getPlayer()->getLevel()->getBlock(new Vector3($event->getPlayer()->getX(), $event->getPlayer()->getY(), $event->getPlayer()->getZ()));
        if($block->getId() !== Block::DAYLIGHT_SENSOR && $block->getId() !== Block::DAYLIGHT_SENSOR_INVERTED) return;
        if(($plot = $this->getPlugin()->myplot->getPlotByPosition($event->getPlayer()->getPosition())) === null) return;


        if (isset($this->getPlugin()->cooldown[$event->getPlayer()->getName()])) {
            if ($this->getPlugin()->cooldown[$event->getPlayer()->getName()] > time()) return;
        }

        $searchForPrivate = true;
        if($plot->owner !== $event->getPlayer()->getName() && !$event->getPlayer()->hasPermission("noteland.lift.admin.use")) {
            if($config->get("helperprivateLift") !== true) {
                $searchForPrivate = false;
            }else if(!$plot->isHelper($event->getPlayer()->getName())) {
                $searchForPrivate = false;
            }
        }


        if($this->getPlugin()->getElevators($block, "down", $searchForPrivate) === 0) {
            $event->getPlayer()->getLevel()->addSound(new AnvilUseSound($event->getPlayer()));
            $event->getPlayer()->sendMessage($settings->get("lift") . "§cDu befindest dich bereits in der niedrigsten Etage.");
            return;
        }

        $nextElevator = $this->getPlugin()->getNextElevator($block, "down", $searchForPrivate);
        if($nextElevator === null) {
            $event->getPlayer()->getLevel()->addSound(new AnvilUseSound($event->getPlayer()));
            $event->getPlayer()->sendMessage($settings->get("lift") . "§cDie nächste Etage wurde nicht gefunden.");
            return;
        }
        if($nextElevator === $block) {
            $event->getPlayer()->getLevel()->addSound(new AnvilUseSound($event->getPlayer()));
            $event->getPlayer()->sendMessage($settings->get("lift") . "§cDie nächste Etage ist nicht sicher! Daher kannst du diese nicht Betreten!");
            return;
        }
        $pos = new Position($nextElevator->getX() + 0.5, $nextElevator->getY() + 1, $nextElevator->getZ() + 0.5, $nextElevator->getLevel());
        $event->getPlayer()->teleport($pos, $event->getPlayer()->getYaw(), $event->getPlayer()->getPitch());

        $elevators = $this->getPlugin()->getElevators($block, "", $searchForPrivate);
        $floor = $this->getPlugin()->getFloor($nextElevator, $searchForPrivate);
        $event->getPlayer()->getLevel()->addSound(new EndermanTeleportSound($event->getPlayer()));
        $event->getPlayer()->sendTip($settings->get("lift") . "Du bist nun in der §f[§e" . $floor . "] §6Etage. §f[§e" . $floor . "§f/§e" . $elevators . "§f]");

        $this->getPlugin()->cooldown[$event->getPlayer()->getName()] = time() + 1;
    }
}