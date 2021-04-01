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

use pocketmine\Server;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;
class AbenteuerCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("gma", $config->get("prefix") . "Setzt den Spielmodus auf §aAbenteuer", "/gma", ["abenteuer", "gm2"]);
        $this->setPermission("core.command.adventure");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $configs = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($configs->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($configs->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        if (isset($args[0])) {
            if ($sender->hasPermission("core.command.adventure.other")) {
                $victim = $this->plugin->getServer()->getPlayer($args[0]);
                $target = Server::getInstance()->getPlayer(strtolower($args[0]));
                if ($target == null) {
                    $sender->sendMessage($configs->get("error") . "Der Spieler ist nicht Online!");
                    return false;
                } else {
                    $victim->setGamemode(2);
                    $victim->sendMessage($configs->get("prefix") . "§6Du bist nun im §aAbenteuer §6modus.");
                    $sender->sendMessage($configs->get("prefix") . "§6Der Spielmodus von " . $victim->getName() . " wurde auf Abenteuer gesetzt.");
                    return false;
                }
            } else {
                $sender->sendMessage($configs->get("error") . "Du hast keine Berechtigung um anderen Spielern den Abenteuermodus zu geben!");
                return false;
            }
        }
        $sender->setGamemode(2);
        $sender->sendMessage($configs->get("prefix") . "§6Du bist nun im §aAbenteuer §6modus.");
        return true;
    }

}