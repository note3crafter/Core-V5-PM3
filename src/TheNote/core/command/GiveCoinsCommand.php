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

use pocketmine\Player;
use pocketmine\utils\Config;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class GiveCoinsCommand extends Command
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("givecoins", $config->get("prefix") . "§6Gebe einem Spieler Coins", "/givecoins <player> <coins>");
        $this->setPermission("core.command.givecoins");
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
        if(count($args) < 2){
            $sender->sendMessage($config->get("prefix") . "§r§cNutze: /givecoins <player> <coins>");
            return false;
        }
        if($this->plugin->getServer()->getPlayer($args[0])){
            $player = $this->plugin->getServer()->getPlayer($args[0]);
            $coins = new Config($this->plugin->getDataFolder() . Main::$userfile . $player->getLowerCaseName() . ".json", Config::JSON);
            $coins->set("coins", $coins->get("coins") + $args[1]);
            $coins->save();
            $sender->sendMessage($config->get("info") . "§r§6Du hast§c " . $player->getName() . " §r§6genau§c " . $args[1] . " Coins §6gezahlt.");
        }else{
            $sender->sendMessage($config->get("error") . "§cSpieler nicht gefunden");
            return false;
        }
    }
}