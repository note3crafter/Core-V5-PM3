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
use pocketmine\entity\Entity;
use pocketmine\level\Explosion;
use pocketmine\network\mcpe\protocol\SetActorDataPacket;
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
            $sender->sendMessage($configs->get("prefix") . "§6Du hast deine Größe zurückgesetzt!");
            return false;
        }
        if (isset($args[0])) {
            if (is_numeric($args[0])) {
                if ($args[0] > 10) {
                    $sender->sendMessage($configs->get("error") . "§cDu kannst nicht größer wie §e10 §cwerden");
                    return true;
                } elseif ($args[0] < 0.05) {
                    $sender->sendMessage($configs->get("error") . "§cDu kannst nicht kleiner wie §e0.05 §cwerden");
                    return true;
                }
                $sender->setScale((float)$args[0]);
                $sender->sendMessage($configs->get("prefix") . "§6Du hast deine Größe zu §e" . $args[0] . " §6geändert!");
                return true;
            } else {
                $sender->sendMessage($configs->get("error") . "Deine eingabe war falsch überprüfe sie nochmal!");
            }
        }
        return true;
    }
}