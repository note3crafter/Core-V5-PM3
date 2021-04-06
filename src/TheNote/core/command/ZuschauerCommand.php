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

class ZuschauerCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("gmspc", $config->get("prefix") . "Setzt den Spielmodus auf §aZuschauer", "/gmspc", ["spectator", "zuschauer", "gm3"]);
        $this->setPermission("core.command.spectator");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        if (isset($args[0])) {
            if ($sender->hasPermission("core.command.spectator.other")) {
                $victim = $this->plugin->getServer()->getPlayer($args[0]);
                $target = Server::getInstance()->getPlayer(strtolower($args[0]));
                if ($target == null) {
                    $sender->sendMessage($config->get("error") . "Der Spieler ist nicht Online!");
                    return false;
                } else {
                    $victim->setGamemode(3);
                    $victim->sendMessage($config->get("prefix") . "§6Du bist nun im §eZuschauer §6modus.");
                    $sender->sendMessage($config->get("prefix") . "§6Der Spielmodus von " . $victim->getName() . " wurde auf §eZuschauer gesetzt.");
                    return false;
                }
            } else {
                $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um anderen Spielern den Zuschauermodus zu geben!");
                return false;
            }
        }
        $sender->setGamemode(3);
        $sender->sendMessage($config->get("prefix") . "Du bist nun im §eZuschauer §6modus.");
        return true;
    }
}