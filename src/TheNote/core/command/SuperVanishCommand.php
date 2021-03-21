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
    public $vanish = [];

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
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            return $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        $Spieler = new Config($this->plugin->getDataFolder() . "vanish.json", Config::JSON);
        if (!$this->testPermission($sender)) {
            $Spieler->set("SuperVanish");
            return false;
        }
        $level = $sender->getLevel()->getFolderName();
        if (empty($args[0])) {
            $sender->sendMessage($config->get("info") . "Nutze : /sv <on|off>");
            return true;
        }

        if (isset($args[0])) {

            if ($args[0] == "on") {
                if ($sender instanceof Player) {
                    $sender->sendMessage($config->get("info") . "Dein §eSuperVanish §6wurde §aAktiviert§6.");
                    self::$vanished[$sender->getName()] = $sender;


                    $all = $this->plugin->getServer()->getOnlinePlayers();
                    $this->plugin->getServer()->broadcastMessage("§f[§c-§f] " . $sender->getNameTag() . " §chat den Server verlassen! §f[§a" . count($all) . "§f/§a100§f]");
                    $sender->getServer()->removePlayerListData($sender->getUniqueId());
                    $sender->getServer()->removeOnlinePlayer($sender);

                    foreach (Server::getInstance()->getOnlinePlayers() as $player) {
                        assert($sender instanceof Player);

                        if (!$player->hasPermission("core.command.supervanish.see")  or $sender->isOp()) {
                            $player->hidePlayer($sender);
                        }
                    }

                }
            }
            if ($args[0] == "off") {
                $sender->sendMessage($config->get("info") . "Dein §eSuperVanish §6wurde §cDeaktiviert§6.");
                unset(self::$vanished[$sender->getName()]);

                assert($sender instanceof Player);
                $all = $this->plugin->getServer()->getOnlinePlayers();
                $this->plugin->getServer()->broadcastMessage("§f[§a+§f] " . $sender->getNameTag() . " §ahat den Server betreten! §f[§a" . count($all) . "§f/§a100§f]");
                $sender->getServer()->u($sender->getUniqueId(), $sender->getId(), $sender->getDisplayName(), $sender->getSkin(), $sender->getXuid());

                foreach (Server::getInstance()->getOnlinePlayers() as $player) {
                    assert($sender instanceof Player);
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
