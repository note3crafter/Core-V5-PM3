<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗ 
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝ 
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\blocks;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\level\particle\ExplodeParticle;
use pocketmine\level\particle\FlameParticle;
use pocketmine\level\sound\AnvilBreakSound;
use pocketmine\level\sound\AnvilFallSound;
use pocketmine\level\sound\AnvilUseSound;
use pocketmine\level\sound\BlazeShootSound;
use pocketmine\level\sound\ClickSound;
use pocketmine\level\sound\DoorBumpSound;
use pocketmine\level\sound\DoorCrashSound;
use pocketmine\level\sound\DoorSound;
use pocketmine\level\sound\EndermanTeleportSound;
use pocketmine\level\sound\FizzSound;
use pocketmine\level\sound\GenericSound;
use pocketmine\level\sound\GhastShootSound;
use pocketmine\level\sound\GhastSound;
use pocketmine\level\sound\LaunchSound;
use pocketmine\level\sound\PopSound;
use pocketmine\math\Vector3;
use pocketmine\utils\Config;
use TheNote\core\Main;

class PowerBlock implements Listener
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onMove(PlayerMoveEvent $event)
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "powerblock" . ".yml", Config::YAML);
        $player = $event->getPlayer();
        $x = $player->getX();
        $y = $player->getY();
        $z = $player->getZ();
        $level = $player->getLevel();
        $block = $level->getBlock($player->getSide(0));
        if ($block->getID() == $config->get("BlockID1")) {
            $direction = $player->getDirectionVector();
            $dx = $direction->getX();
            $dz = $direction->getZ();
            $level->addParticle(new ExplodeParticle($player));
            $level->addParticle(new ExplodeParticle(new Vector3($x - 0.3, $y, $z)));
            $level->addParticle(new ExplodeParticle(new Vector3($x, $y, $z - 0.3)));
            $level->addParticle(new ExplodeParticle(new Vector3($x + 0.3, $y, $z)));
            $level->addParticle(new ExplodeParticle(new Vector3($x, $y, $z + 0.3)));
            if ($config->get("BlockSound1") == "AnvilFallSound") {
                $player->getLevel()->addSound(new AnvilFallSound(new Vector3($player->x, $player->y, $player->z, $player->getLevel())));
            } elseif ($config->get("BlockSound1") == "ClickSound") {
                $player->getLevel()->addSound(new ClickSound(new Vector3($player->x, $player->y, $player->z, $player->getLevel())));
            } elseif ($config->get("BlockSound1") == "EndermanTeleportSound") {
                $player->getLevel()->addSound(new EndermanTeleportSound(new Vector3($player->x, $player->y, $player->z, $player->getLevel())));
            } elseif ($config->get("BlockSound1") == "GhastShootSound") {
                $player->getLevel()->addSound(new ClickSound(new Vector3($player->x, $player->y, $player->z, $player->getLevel())));
            } elseif ($config->get("BlockSound1") == "PopSound") {
                $player->getLevel()->addSound(new PopSound(new Vector3($player->x, $player->y, $player->z, $player->getLevel())));
            }
            $player->knockBack($player, 0, $dx, $dz, $config->get("BlockStaerke1"));
        }
        if ($block->getID() == $config->get("BlockID2")) {
            $direction = $player->getDirectionVector();
            $dx = $direction->getX();
            $dz = $direction->getZ();
            $level->addParticle(new FlameParticle($player));
            $level->addParticle(new FlameParticle(new Vector3($x - 0.3, $y, $z)));
            $level->addParticle(new FlameParticle(new Vector3($x, $y, $z - 0.3)));
            $level->addParticle(new FlameParticle(new Vector3($x + 0.3, $y, $z)));
            $level->addParticle(new FlameParticle(new Vector3($x, $y, $z + 0.3)));
            if ($config->get("BlockSound2") == "AnvilFallSound") {
                $player->getLevel()->addSound(new AnvilFallSound(new Vector3($player->x, $player->y, $player->z, $player->getLevel())));
            } elseif ($config->get("BlockSound2") == "ClickSound") {
                $player->getLevel()->addSound(new ClickSound(new Vector3($player->x, $player->y, $player->z, $player->getLevel())));
            } elseif ($config->get("BlockSound2") == "EndermanTeleportSound") {
                $player->getLevel()->addSound(new EndermanTeleportSound(new Vector3($player->x, $player->y, $player->z, $player->getLevel())));
            } elseif ($config->get("BlockSound2") == "GhastShootSound") {
                $player->getLevel()->addSound(new ClickSound(new Vector3($player->x, $player->y, $player->z, $player->getLevel())));
            } elseif ($config->get("BlockSound2") == "PopSound") {
                $player->getLevel()->addSound(new PopSound(new Vector3($player->x, $player->y, $player->z, $player->getLevel())));
            }
            $player->knockBack($player, 0, $dx, $dz, $config->get("BlockStaerke2"));
        }
    }
}
