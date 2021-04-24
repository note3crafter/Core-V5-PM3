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
class TpaCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("tpa", $config->get("prefix") . "Sende eine Teleportanfrage an einem Spieler", "/tpa");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $configs = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($configs->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($configs->get("info") . "Nutze : /tpa {player}");
            return false;
        }
        $target = Server::getInstance()->getPlayer(strtolower($args[0]));
        if ($target === $sender){
            $sender->sendMessage($configs->get("error") . "§cDu kannst dir keine TPA´s schicken!");
            return false;
        }
        if ($target instanceof Player) {
            $this->plugin->setInvite($sender, $target);
            $target->sendMessage($configs->get("tpa") . "§e" . $sender->getName() . " §6hat dir eine TPA-Anfrage gesendet! Nehme sie mit /tpaccept an oder lehne sie mit /tpadeny ab!");
            $sender->sendMessage($configs->get("tpa") . " §6Du hast §e". $target->getName() . " §6eine TPA-Anfrage gesendet!");
        } else {
            $sender->sendMessage($configs->get("tpa") . "Bitte gebe ein Spieler an!");
        }
        return true;
    }
}


