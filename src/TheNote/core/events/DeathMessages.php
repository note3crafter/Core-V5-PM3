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

use pocketmine\entity\Entity;
use pocketmine\entity\Living;
use pocketmine\event\entity\EntityDamageByBlockEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\Listener;
use pocketmine\level\Level;
use pocketmine\level\particle\DestroyBlockParticle;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\Main;

class DeathMessages implements Listener
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onPlayerDeath(PlayerDeathEvent $event)
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings.json", Config::JSON);
        $player = $event->getPlayer();
        $name = $player->getNameTag();
        $p = $event->getEntity();
        $this->Lightning($event->getPlayer());
        if ($player instanceof Player) {
            $cause = $player->getLastDamageCause();
            $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings.json", Config::JSON);

            if ($cause->getCause() == EntityDamageEvent::CAUSE_CONTACT) {
                $event->setDeathMessage($name . $config->get("zuhoch"));
            } elseif ($cause->getCause() == EntityDamageEvent::CAUSE_ENTITY_ATTACK) {
                $event->setDeathMessage($name . $config->get("entityattacke"));
                $this->plugin->addStrike($player, true);
            } elseif ($cause->getCause() == EntityDamageEvent::CAUSE_PROJECTILE) {
                $event->setDeathMessage($name . $config->get("abgeschossen"));
                $this->plugin->addStrike($player, true);
            } elseif ($cause->getCause() == EntityDamageEvent::CAUSE_SUFFOCATION) {
                $event->setDeathMessage($name . $config->get("erstickte"));
            } elseif ($cause->getCause() == EntityDamageEvent::CAUSE_FIRE) {
                $event->setDeathMessage($name . $config->get("verbrannteimstehen"));
                $this->plugin->addStrike($player, true);
            } elseif ($cause->getCause() == EntityDamageEvent::CAUSE_FIRE_TICK) {
                $event->setDeathMessage($name . $config->get("verbrannte"));
                $this->plugin->addStrike($player, true);
            } elseif ($cause->getCause() == EntityDamageEvent::CAUSE_LAVA) {
                $event->setDeathMessage($name . $config->get("lava"));
            } elseif ($cause->getCause() == EntityDamageEvent::CAUSE_DROWNING) {
                $event->setDeathMessage($name . $config->get("ertrinken"));
            } elseif ($cause->getCause() == EntityDamageEvent::CAUSE_ENTITY_EXPLOSION || $cause->getCause() == EntityDamageEvent::CAUSE_BLOCK_EXPLOSION) {
                $event->setDeathMessage($name . $config->get("hochgejagt"));
                $this->plugin->addStrike($player, true);
            } elseif ($cause->getCause() == EntityDamageEvent::CAUSE_VOID) {
                $event->setDeathMessage($name . $config->get("void"));
            } elseif ($cause->getCause() == EntityDamageEvent::CAUSE_SUICIDE) {
                $event->setDeathMessage($name . $config->get("selbstmord"));
                $this->plugin->addStrike($player, true);
            } elseif ($cause->getCause() == EntityDamageEvent::CAUSE_MAGIC) {
                $event->setDeathMessage($name . $config->get("magic"));
            }
        }
        return true;
    }

    public function Lightning(Player $player): void
    {
        $light = new AddActorPacket();
        $light->type = "minecraft:lightning_bolt";
        $light->entityRuntimeId = Entity::$entityCount++;
        $light->metadata = [];
        $light->motion = null;
        $light->yaw = $player->getYaw();
        $light->pitch = $player->getPitch();
        $light->position = new Vector3($player->getX(), $player->getY(), $player->getZ());
        Server::getInstance()->broadcastPacket($player->getLevel()->getPlayers(), $light);
        $block = $player->getLevel()->getBlock($player->getPosition()->floor()->down());
        $particle = new DestroyBlockParticle(new Vector3($player->getX(), $player->getY(), $player->getZ()), $block);
        $player->getLevel()->addParticle($particle);
        $sound = new PlaySoundPacket();
        $sound->soundName = "ambient.weather.thunder";
        $sound->x = $player->getX();
        $sound->y = $player->getY();
        $sound->z = $player->getZ();
        $sound->volume = 1;
        $sound->pitch = 1;
        Server::getInstance()->broadcastPacket($player->getLevel()->getPlayers(), $sound);
    }

}