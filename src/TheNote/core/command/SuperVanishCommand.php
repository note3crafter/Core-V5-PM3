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

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class SuperVanishCommand extends Command
{
    public static $vanished = [];
    private static $instance;
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("supervanish", $config->get("prefix") . "§aAktiviert§f/§cDeaktiviert§6 Supervanish", "/supervanish", ["sv"]);
        $this->setPermission("core.command.supervanish");
    }
    public static function getInstance() : self {
        return self::$instance;
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$cloud . "Config.yml", Config::YAML);
        $settings = new Config($this->plugin->getDataFolder() . Main::$setup . "settings.json", Config::JSON);
        $playerdata = new Config($this->plugin->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        $gruppe = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $sender->getName() . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($settings->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($settings->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($settings->get("info") . "Nutze : /sv <on|off>");
            return false;
        }
        if (isset($args[0])) {
            if ($args[0] == "on") {
                $sender->sendMessage($settings->get("info") . "Dein §eSuperVanish §6wurde §aAktiviert§6.");
                self::$vanished[$sender->getName()] = $sender;
                $all = $this->plugin->getServer()->getOnlinePlayers();
                $prefix = $playerdata->getNested($sender->getName() . ".groupprefix");
                $slots = $settings->get("slots");
                $spielername = $gruppe->get("Nickname");
                $stp1 = str_replace("{player}", $spielername, $config->get("Quitmsg"));
                $stp2 = str_replace("{count}", count($all), $stp1);
                $stp3 = str_replace("{slots}", $slots , $stp2);
                $quitmsg = str_replace("{prefix}", $prefix, $stp3);
                $this->plugin->getServer()->broadcastMessage($quitmsg);
                $sender->getServer()->removePlayerListData($sender->getUniqueId());
                $sender->getServer()->removeOnlinePlayer($sender);

                foreach (Server::getInstance()->getOnlinePlayers() as $player) {
                    assert(true);

                    if (!$player->hasPermission("core.command.supervanish.see")  or $sender->isOp()) {
                        $player->hidePlayer($sender);
                    }
                }
            }
            if ($args[0] == "off") {
                $sender->sendMessage($settings->get("info") . "Dein §eSuperVanish §6wurde §cDeaktiviert§6.");
                unset(self::$vanished[$sender->getName()]);

                assert(true);
                $all = $this->plugin->getServer()->getOnlinePlayers();
                $prefix = $playerdata->getNested($sender->getName() . ".groupprefix");
                $slots = $settings->get("slots");
                $spielername = $gruppe->get("Nickname");
                $stp1 = str_replace("{player}", $spielername, $config->get("Joinmsg"));
                $stp2 = str_replace("{count}", count($all), $stp1);
                $stp3 = str_replace("{slots}", $slots , $stp2);
                $joinmsg = str_replace("{prefix}", $prefix, $stp3);
                $this->plugin->getServer()->broadcastMessage($joinmsg);
                $sender->getServer()->updatePlayerListData($sender->getUniqueId(), $sender->getId(), $sender->getDisplayName(), $sender->getSkin(), $sender->getXuid());

                foreach (Server::getInstance()->getOnlinePlayers() as $player) {
                    assert(true);
                    $player->showPlayer($sender);
                }
            }
        }
        return true;
    }
    public function onJoin(PlayerJoinEvent $event) {
        $player = $event->getPlayer();
        $name = $player->getName();

        if(isset(self::$vanished[$name])) {
            $event->setJoinMessage(null);
        }
    }

    public function onQuit(PlayerQuitEvent $event) {
        $player = $event->getPlayer();
        $name = $player->getName();

        if(isset(self::$vanished[$name])) {
            $event->setQuitMessage(null);
        }
    }
}
