<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerBlockPickEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerItemConsumeEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerJumpEvent;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\utils\Config;
use TheNote\core\Main;

class Stats implements Listener
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getLowerCaseName();
        $stats = new Config($this->plugin->getDataFolder() . Main::$statsfile . $name . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $stats->set("joins", $stats->get("joins") + 1);
        $stats->save();
        $serverstats = new Config($this->plugin->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
        $serverstats->set("joins", $serverstats->get("joins") + 1);
        $serverstats->save();
        $joins = $stats->get("joins");
        if ($joins == 10000) {
            $player->sendMessage($config->get("erfolg") . "Toll du bist dem Server schon 10000x Beigetreten!");
            $this->plugin->addStrike($player);
            $this->plugin->screenanimation($player, 26);
            $volume = mt_rand();
            $player->getLevel()->broadcastLevelSoundEvent($player, LevelSoundEventPacket::SOUND_LEVELUP, (int)$volume);
            $stats->set("joinerfolg", true);
            $stats->save();
        }
    }

    public function break(BlockBreakEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getLowerCaseName();
        $stats = new Config($this->plugin->getDataFolder() . Main::$statsfile . $name . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $stats->set("break", $stats->get("break") + 1);
        $stats->save();
        $serverstats = new Config($this->plugin->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
        $serverstats->set("break", $serverstats->get("break") + 1);
        $serverstats->save();
        $breaks = $stats->get("break");
        $coins = new Config($this->plugin->getDataFolder() . Main::$userfile . $name . ".json", Config::JSON);
        if ($breaks == 1000000) {
            $player->sendMessage($config->get("erfolg") . "Absoluter hammer! Du hast 1.000.000 Blöcke abgebaut! Das heißt, dass du ein Meisterminer bist! Glückwunsch! Als Belohnung bekommst du dafür 2500 Coins!");
            $this->plugin->addStrike($player);
            $this->plugin->screenanimation($player, 3);
            $volume = mt_rand();
            $player->getLevel()->broadcastLevelSoundEvent($player, LevelSoundEventPacket::SOUND_LEVELUP, (int)$volume);
            $coins->set("coins", $coins->get("coins") + 2500);
            $coins->save();
            $stats->set("breakerfolg", true);
            $stats->save();
        }
    }

    public function place(BlockPlaceEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getLowerCaseName();
        $stats = new Config($this->plugin->getDataFolder() . Main::$statsfile . $name . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $stats->set("place", $stats->get("place") + 1);
        $stats->save();
        $serverstats = new Config($this->plugin->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
        $serverstats->set("place", $serverstats->get("place") + 1);
        $serverstats->save();
        $place = $stats->get("break");
        $coins = new Config($this->plugin->getDataFolder() . Main::$userfile . $name . ".json", Config::JSON);
        if ($place == 1000000) {
            $player->sendMessage($config->get("erfolg") . "Absoluter hammer! Du hast 1.000.000 Blöcke gesetzt! Das heißt, dass du ein Meisterbauer bist! Glückwunsch! Als Belohnung bekommst du dafür 2500 Coins!");
            $this->plugin->addStrike($player);
            $this->plugin->screenanimation($player, 3);
            $volume = mt_rand();
            $player->getLevel()->broadcastLevelSoundEvent($player, LevelSoundEventPacket::SOUND_LEVELUP, (int)$volume);
            $coins->set("coins", $coins->get("coins") + 2500);
            $coins->save();
            $stats->set("placeerfolg", true);
            $stats->save();
        }
    }

    public function onKick(PlayerKickEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getLowerCaseName();
        $stats = new Config($this->plugin->getDataFolder() . Main::$statsfile . $name . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $stats->set("kicks", $stats->get("kicks") + 1);
        $stats->save();
        $serverstats = new Config($this->plugin->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
        $serverstats->set("kicks", $serverstats->get("kicks") + 1);
        $serverstats->save();
        $kick = $stats->get("kick");
        if ($kick == 1000) {
            $this->plugin->getServer()->broadcastMessage($config->get("erfolg") . "Wow $name hat nun seine 1000 Kicks voll...");
            $stats->set("kickerfolg", true);
            $stats->save();
        }
    }

    public function onDeath(PlayerDeathEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getLowerCaseName();
        $stats = new Config($this->plugin->getDataFolder() . Main::$statsfile . $name . ".json", Config::JSON);
        $stats->set("deaths", $stats->get("deaths") + 1);
        $stats->save();
        $serverstats = new Config($this->plugin->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
        $serverstats->set("deaths", $serverstats->get("deaths") + 1);
        $serverstats->save();
    }

    public function onDrop(PlayerDropItemEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getLowerCaseName();
        $stats = new Config($this->plugin->getDataFolder() . Main::$statsfile . $name . ".json", Config::JSON);

        $stats->set("drop", $stats->get("drop") + 1);
        $stats->save();
        $serverstats = new Config($this->plugin->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
        $serverstats->set("drop", $serverstats->get("drop") + 1);
        $serverstats->save();
    }

    public function onChat(PlayerChatEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getLowerCaseName();
        $stats = new Config($this->plugin->getDataFolder() . Main::$statsfile . $name . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $stats->set("messages", $stats->get("messages") + 1);
        $stats->save();
        $serverstats = new Config($this->plugin->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
        $serverstats->set("messages", $serverstats->get("messages") + 1);
        $serverstats->save();
        $message = $stats->get("messages");
        if ($message == 1000000) {
            $player->sendMessage($config->get("erfolg") . "Du hast soeben deine 1.000.000ste Nachrricht geschickt! Glückwunsch :D");
            $this->plugin->addStrike($player);
            $this->plugin->screenanimation($player, 27);
            $volume = mt_rand();
            $player->getLevel()->broadcastLevelSoundEvent($player, LevelSoundEventPacket::SOUND_LEVELUP, (int)$volume);
            $stats->set("messageerfolg", true);
            $stats->save();
        }
    }

    public function onPick(PlayerBlockPickEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getLowerCaseName();
        $stats = new Config($this->plugin->getDataFolder() . Main::$statsfile . $name . ".json", Config::JSON);
        $stats->set("pick", $stats->get("pick") + 1);
        $stats->save();
        $serverstats = new Config($this->plugin->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
        $serverstats->set("pick", $serverstats->get("pick") + 1);
        $serverstats->save();
    }

    public function onConsume(PlayerItemConsumeEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getLowerCaseName();
        $stats = new Config($this->plugin->getDataFolder() . Main::$statsfile . $name . ".json", Config::JSON);
        $stats->set("consume", $stats->get("consume") + 1);
        $stats->save();
        $serverstats = new Config($this->plugin->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
        $serverstats->set("consume", $serverstats->get("consume") + 1);
        $serverstats->save();
    }

    public function onInteract(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getLowerCaseName();
        $stats = new Config($this->plugin->getDataFolder() . Main::$statsfile . $name . ".json", Config::JSON);
        $stats->set("interact", $stats->get("interact") + 1);
        $stats->save();
        $serverstats = new Config($this->plugin->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
        $serverstats->set("interact", $serverstats->get("interact") + 1);
        $serverstats->save();
    }

    public function onJump(PlayerJumpEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getLowerCaseName();
        $stats = new Config($this->plugin->getDataFolder() . Main::$statsfile . $name . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $stats->set("jumps", $stats->get("jumps") + 1);
        $stats->save();
        $serverstats = new Config($this->plugin->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
        $serverstats->set("jumps", $serverstats->get("jumps") + 1);
        $serverstats->save();
        $jumps = $stats->get("jumps");
        if ($jumps == 10000) {
            $player->sendMessage($config->get("erfolg") . "Wow du hast nun 10000 Sprünge! Das hast du gut gemacht!");
            $this->plugin->addStrike($player);
            $this->plugin->screenanimation($player, 8);
            $volume = mt_rand();
            $player->getLevel()->broadcastLevelSoundEvent($player, LevelSoundEventPacket::SOUND_LEVELUP, (int)$volume);
        }
    }

    public function onmove(PlayerMoveEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getLowerCaseName();
        $to = $event->getTo()->round();
        $from = $event->getFrom()->round();
        $blocks = $from->distance($to);
        //$blocks = intval($blocks);

        if ($player->isFlying()) {
            $stats = new Config($this->plugin->getDataFolder() . Main::$statsfile . $name . ".json", Config::JSON);
            $stats->set("movefly", $stats->get("movefly") + $blocks);
            $stats->save();
            $serverstats = new Config($this->plugin->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
            $serverstats->set("movefly", $serverstats->get("movefly") + $blocks);
            $serverstats->save();
        } else {
            $stats = new Config($this->plugin->getDataFolder() . Main::$statsfile . $name . ".json", Config::JSON);
            $stats->set("movewalk", $stats->get("movewalk") + $blocks);
            $stats->save();
            $serverstats = new Config($this->plugin->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
            $serverstats->set("movewalk", $serverstats->get("movewalk") + $blocks);
            $serverstats->save();
        }
    }
}