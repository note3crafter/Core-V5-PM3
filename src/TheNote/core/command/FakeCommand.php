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

use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;

class FakeCommand extends Command {

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("fake", $config->get("prefix") . "Setzt die Zeit auf §bTag", "/fake", ["f"]);
        $this->setPermission("core.command.fake");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            return $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        if (empty($args[0])){
            $sender->sendMessage($config->get("prefix") . "/fake <join|leave>");
            return true;
        }
        if (isset($args[0])) {
            if ($args[0] == "join") {
                if ($sender instanceof Player) {
                    if ($sender->hasPermission("core.command.fake") || $sender->isOp()) {
                        $all = $this->plugin->getServer()->getOnlinePlayers();
                        $this->plugin->getServer()->broadcastMessage("§f[§a+§f] " . $sender->getNameTag() . " §ahat den Server betreten! §f[§a" . count($all) . "§f/§a100§f]");
                    }
                }
            }
            if ($args[0] == "leave") {
                if ($sender instanceof Player) {
                    if ($sender->hasPermission("core.command.fake") || $sender->isOp()) {
                        $all = $this->plugin->getServer()->getOnlinePlayers();
                        $this->plugin->getServer()->broadcastMessage("§f[§c-§f] " . $sender->getNameTag() . " §chat den Server verlassen! §f[§a" . count($all) . "§f/§a100§f]");
                    }
                }
            }
        }
        return true;
    }
}
//last edit by Rudolf2000 : 15.03.2021 19:49