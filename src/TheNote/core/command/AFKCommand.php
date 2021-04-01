<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\command;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;

class AFKCommand extends Command implements Listener
{
    private $plugin;
    private $afk;



    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("afk", $config->get("prefix") . "Setze dich afk", "/afk");
        $this->afk = array();
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }
        if ($sender instanceof Player) {
            if (isset($this->afk[strtolower($sender->getName())])) {
                $cfg = new Config($this->plugin->getDataFolder() . Main::$userfile . $player->getLowerCaseName(), Config::JSON);
                unset($this->afk[strtolower($sender->getName())]);
                $sender->sendMessage($config->get("afk") . "Du bist nun nicht mehr AFK!");
                $sender->setImmobile(false);
                $cfg->set($cfg->get("afkmove") == false);
                $cfg->set($cfg->get("afkchat") == false);
                $cfg->save();
            } else {
                $cfg = new Config($this->plugin->getDataFolder() . Main::$userfile . $player->getLowerCaseName(), Config::JSON);
                $this->afk[strtolower($sender->getName())] = strtolower($sender->getName());
                $sender->sendMessage($config->get("afk") . "Du bist nun AFK!");
                $sender->setImmobile(true);
                $sender->setDisplayName("§f[§eAFK§f]");
                $cfg->set($cfg->get("afkmove") == true);
                $cfg->set($cfg->get("afkchat") == true);
                $cfg->save();
            }
            return true;
        }
    }
    public function onQuit(PlayerQuitEvent $event){
        $player = $event->getPlayer();
        $cfg = new Config($this->plugin->getDataFolder() . Main::$userfile . $player->getLowerCaseName(), Config::JSON);
        $cfg->set($cfg->get("afkmove") == false);
        $cfg->set($cfg->get("afkchat") == false);
        $cfg->save();
    }


    public function onMove(PlayerMoveEvent $event) {
        $player = $event->getPlayer();
        $cfg = new Config($this->plugin->getDataFolder() . Main::$userfile . $player->getLowerCaseName(), Config::JSON);
        if($cfg->get("afk") == true) {
                $player->sendMessage("You can't move while AFK!");
                $player->sendMessage("Type /afk to start moving!");
                $event->setCancelled(true);
        }
    }

    public function onChat(PlayerChatEvent $event) {
        $player = $event->getPlayer();
        $cfg = new Config($this->plugin->getDataFolder() . Main::$userfile . $player->getLowerCaseName(), Config::JSON);
        if($cfg->get("afk") == true) {
                $player->sendMessage("You can't chat while AFK!");
                $player->sendMessage("Type /afk to start chatting!");
                $event->setCancelled(true);
        }
    }
    public function onDamage(EntityDamageEvent $event) {
        if($event->getEntity() instanceof Player) {
            $cfg = new Config($this->plugin->getDataFolder() . Main::$userfile . $sender->getLowerCaseName(), Config::JSON);
            if($cfg->get("afk") == true){
                $event->setCancelled(true);
            }
        }
    }
}