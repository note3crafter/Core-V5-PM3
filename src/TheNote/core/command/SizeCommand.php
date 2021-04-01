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

use pocketmine\block\Block;
use pocketmine\level\Explosion;
use pocketmine\Server;
use TheNote\core\events\PlayerBurnEvent;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;

class SizeCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("size", $config->get("prefix") . "Verändere deine Größe", "/size [Zahl] {player}");
        $this->setPermission("core.command.size");
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
        if (empty($args[0])) {
            $sender->setScale(1);
            $sender->sendMessage("größe zurückgesetzt");
            return false;
        }
        $victim = $this->plugin->getServer()->getPlayer($args[0]);
        $target = Server::getInstance()->getPlayer(strtolower($args[0]));
        if (!isset($args[0])) {
            if (is_numeric((float)$args[0]) && (float)$args[0] > 0) {
                $sender->setScale((float)$args[0]);
                $sender->sendMessage("deine größe ist " . $args[0]);
            } else {
                $sender->sendMessage("größer als 0 eingeben");
            }
            if ($sender->hasPermission("core.command.size.other")) {
                if (is_numeric((float)$args[1]) && (float)$args[1] > 0) {
                    if ($target == null) {
                        $sender->sendMessage($configs->get("error") . "Der Spieler ist nicht Online!");
                        return false;
                    } else {
                        $victim->setScale((float)$args[0]);
                        $victim->sendMessage($configs->get("prefix") . "§6Deine Spielergröße wurde auf§e " . $args[0] . " §6gesetzt.");
                        $sender->sendMessage($configs->get("prefix") . "§6Die Spielgröße von §e" . $victim->getName() . " §6wurde auf§e " . $args[0] . " §6gesetzt.");
                        return false;
                    }
                } else {
                    $sender->sendMessage("größere zahl als 0 victim");
                }
            } else {
                $sender->sendMessage("keine berechtigung");
            }

        }
        return true;
    }
}