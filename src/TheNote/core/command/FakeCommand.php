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

    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("fake", $config->get("prefix") . "Setzt die Zeit auf §bTag", "/fake", ["f"]);
        $this->setPermission("core.command.fake");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$cloud . "Config.yml", Config::YAML);
        $settings = new Config($this->plugin->getDataFolder() . Main::$setup . "settings.json", Config::JSON);
        $playerdata = new Config($this->plugin->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        $gruppe = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $sender->getName() . ".json", Config::JSON);
        if (!$sender instanceof Player) {
             $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
             return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($settings->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        if (empty($args[0])){
            $sender->sendMessage($settings->get("prefix") . "/fake <join|leave>");
            return true;
        }
        if (isset($args[0])) {
            if ($args[0] == "join") {
                if ($sender->hasPermission("core.command.fake") || $sender->isOp()) {
                    $all = $this->plugin->getServer()->getOnlinePlayers();
                    $prefix = $playerdata->getNested($sender->getName() . ".groupprefix");
                    $slots = $settings->get("slots");
                    $spielername = $gruppe->get("Nickname");
                    $stp1 = str_replace("{player}", $spielername, $config->get("Joinmsg"));
                    $stp2 = str_replace("{count}", count($all), $stp1);
                    $stp3 = str_replace("{slots}", $slots , $stp2);
                    $joinmsg = str_replace("{prefix}", $prefix, $stp3);
                    $this->plugin->getServer()->broadcastMessage($joinmsg);
                }
            }
            if ($args[0] == "leave") {
                if ($sender->hasPermission("core.command.fake") || $sender->isOp()) {
                    $all = $this->plugin->getServer()->getOnlinePlayers();
                    $prefix = $playerdata->getNested($sender->getName() . ".groupprefix");
                    $slots = $settings->get("slots");
                    $spielername = $gruppe->get("Nickname");
                    $stp1 = str_replace("{player}", $spielername, $config->get("Quitmsg"));
                    $stp2 = str_replace("{count}", count($all), $stp1);
                    $stp3 = str_replace("{slots}", $slots , $stp2);
                    $quitmsg = str_replace("{prefix}", $prefix, $stp3);
                    $this->plugin->getServer()->broadcastMessage("§f[§c-§f] " . $sender->getNameTag() . " §chat den Server verlassen! §f[§a" . count($all) . "§f/§a" . $config->get("slots") . "§f]");
                }
            }
        }
        return true;
    }
}
