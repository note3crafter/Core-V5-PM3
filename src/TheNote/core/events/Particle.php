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
use pocketmine\event\player\PlayerJumpEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\level\particle\AngryVillagerParticle;
use pocketmine\level\particle\PortalParticle;
use pocketmine\level\particle\LavaParticle;
use pocketmine\level\particle\HeartParticle;
use pocketmine\level\particle\RedstoneParticle;
use pocketmine\level\particle\SmokeParticle;
use pocketmine\level\particle\FlameParticle;
use pocketmine\level\particle\BubbleParticle;
use pocketmine\level\particle\ExplodeParticle;
use pocketmine\level\particle\SporeParticle;
use pocketmine\level\particle\SplashParticle;
use pocketmine\math\Vector3;
use pocketmine\utils\Config;
use TheNote\core\Main;

class Particle implements Listener
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }
    public function onMove(PlayerMoveEvent $event)
    {

        $level = $event->getPlayer()->getLevel();
        $player = $event->getPlayer();
        $player->setFood(20);
        $x = $player->getX();
        $y = $player->getY();
        $z = $player->getZ();
        $pf =  new Config($this->plugin->getDataFolder() . Main::$userfile . $player->getLowerCaseName() . ".json", Config::JSON);
        if ($pf->get("explode") === true) {

            $level->addParticle(new ExplodeParticle(new Vector3($x, $y, $z)));

        } else if ($pf->get("angry") === true) {

            $level->addParticle(new AngryVillagerParticle(new Vector3($x, $y, $z)));

        } else if ($pf->get("redstone") === true) {

            $level->addParticle(new RedstoneParticle(new Vector3($x, $y, $z)));

        } else if ($pf->get("smoke") === true) {

            $level->addParticle(new SmokeParticle(new Vector3($x, $y, $z)));

        } else if ($pf->get("lava") === true) {

            $level->addParticle(new LavaParticle(new Vector3($x, $y, $z)));

        } else if ($pf->get("heart") === true) {

            $level->addParticle(new HeartParticle(new Vector3($x, $y, $z)));

        } else if ($pf->get("flame") === true) {

            $level->addParticle(new FlameParticle(new Vector3($x, $y, $z)));

        } else if ($pf->get("portal") === true) {

            $level->addParticle(new PortalParticle(new Vector3($x, $y, $z)));

        } else if ($pf->get("spore") === true) {

            $level->addParticle(new SporeParticle(new Vector3($x, $y, $z)));

        } else if ($pf->get("splash") === true) {

            $level->addParticle(new SplashParticle(new Vector3($x, $y, $z)));

        }
    }
    public function onJump(PlayerJumpEvent $event) {

        $player = $event->getPlayer();
        $pf =  new Config($this->plugin->getDataFolder() . Main::$userfile . $player->getLowerCaseName() . ".json");
        if ($pf->get("DJ") === true) {

            $yaw = $player->getYaw();
            if ($yaw < 45 && $yaw > 0 || $yaw < 360 && $yaw > 315) {

                $player->setMotion(new Vector3(0, 1, 1));

            } else if ($yaw < 135 && $yaw > 45) {

                $player->setMotion(new Vector3(-1, 1, 0));

            } else if ($yaw < 225 && $yaw > 135) {

                $player->setMotion(new Vector3(0, 1, -1));

            } elseif($yaw < 315 && $yaw > 225){

                $player->setMotion(new Vector3(1, 1, 0));

            }

        }

    }
}