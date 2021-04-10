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
use pocketmine\Server;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\level\Explosion;

class NukeCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("nuke", $config->get("prefix") . "Jage die Umgebung in die Luft", "/nuke");
        $this->setPermission("core.command.nuke");
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
            if ($sender->hasPermission("core.command.nuke.other")) {
                $victim = $this->plugin->getServer()->getPlayer($args[0]);
                $target = Server::getInstance()->getPlayer(strtolower($args[0]));
                if ($target == null) {
                    $sender->sendMessage($configs->get("error") . "Der Spieler ist nicht Online!");
                    return false;
                } else {
                    $explosion = new Explosion($sender->getPosition(),100, Block::TNT);
                    $explosion->explodeA();
                    $explosion->explodeB();
                    $victim->sendMessage($configs->get("prefix") . "§6Du wurdest genuket!!!.");
                    $sender->sendMessage($configs->get("prefix") . "§6Du hast " . $victim->getName() ." §6genuket.");
                    return false;
                }
            } else {
                $sender->sendMessage($configs->get("error") . "Du hast keine Berechtigung um anderen Spieler in die Luft zu jagen zu!");
                return false;
            }
        }
        $explosion = new Explosion($sender->getPosition(),100, Block::TNT);
        $explosion->explodeA();
        $explosion->explodeB();
        $sender->sendMessage($configs->get("prefix") . "§6Du hast eine Atombombe gelegt!");
        return true;
    }
}