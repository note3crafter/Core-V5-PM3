<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//                        2017-2020

namespace TheNote\core\events;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerBucketEmptyEvent;
use pocketmine\event\player\PlayerBucketFillEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\utils\Config;
use TheNote\core\Main;

class Eventsettings implements Listener
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }
    public function onBreak(BlockBreakEvent $event) {
        $player = $event->getPlayer();
        $cfg = new Config($this->plugin->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        $level = $cfg->getNested("Break", []);
        if ($player->getLevel()->getFolderName() == $level) {
            if ($player->hasPermission("core.events.blockbreak") or $player->isOp()) {
                $event->setCancelled(false);
            } else {
                $event->setCancelled(true);
                $player->sendPopup("§cNo Permissons to do that!");
            }
        }
    }
    public function onPlace(BlockPlaceEvent $event) {
        $player = $event->getPlayer();
        $cfg = new Config($this->plugin->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        $level = $cfg->getNested("Place", []);
        if ($player->getLevel()->getFolderName() == $level) {
            if ($player->hasPermission("core.events.blockplace") or $player->isOp()) {
                $event->setCancelled(false);
            } else {
                $event->setCancelled(true);
                $player->sendPopup("§cNo Permissons to do that!");
            }
        }
    }
    public function onChat(PlayerChatEvent $event) {
        $player = $event->getPlayer();
        $cfg = new Config($this->plugin->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        $level = $cfg->getNested("Chat", []);
        if ($player->getLevel()->getFolderName() == $level) {
            if ($player->hasPermission("core.events.chat") or $player->isOp()) {
                $event->setCancelled(false);
            } else {
                $event->setCancelled(true);
                $player->sendPopup("§cNo Permissons to do that!");
            }
        }
    }
    public function onDrop(PlayerDropItemEvent $event){
        $player = $event->getPlayer();
        $cfg = new Config($this->plugin->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        $level = $cfg->getNested("Drop", []);
        if ($player->getLevel()->getFolderName() == $level) {
            if ($player->hasPermission("core.events.drop") or $player->isOp()) {
                $event->setCancelled(false);
            } else {
                $event->setCancelled(true);
                $player->sendPopup("§cNo Permissons to do that!");
            }
        }
    }
    public function bucketemty(PlayerBucketEmptyEvent $event) {
        $player = $event->getPlayer();
        $cfg = new Config($this->plugin->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        $level = $cfg->getNested("Bucketempty", []);
        if ($player->getLevel()->getFolderName() == $level) {
            if ($player->hasPermission("core.events.bucketempty") or $player->isOp()) {
                $event->setCancelled(false);
            } else {
                $event->setCancelled(true);
                $player->sendPopup("§cNo Permissons to do that!");
            }
        }
    }
    public function bucketfill(PlayerBucketFillEvent $event) {
        $player = $event->getPlayer();
        $cfg = new Config($this->plugin->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        $level = $cfg->getNested("Bucketfill", []);
        if ($player->getLevel()->getFolderName() == $level) {
            if ($player->hasPermission("core.events.bucketfill") or $player->isOp()) {
                $event->setCancelled(false);
            } else {
                $event->setCancelled(true);
                $player->sendPopup("§cNo Permissons to do that!");
            }
        }
    }
}