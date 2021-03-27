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
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\events\PlayerBurnEvent;

class BurnCommand extends Command
{

    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("burn", $config->get("prefix") . "Zünde einen anderen Spieler an", "/burn");
        $this->setPermission("core.command.burn");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
             $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
             return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . "Dazu bist du nicht berechtigt");
            return true;
        }
        if (empty($args[0])) {
            $sender->sendMessage($config->get("info") . "Nutze : /burn {spielername} [sekunden]");
            return true;
        }

        if (isset($args[0])) {
            $target = Server::getInstance()->getPlayer(strtolower($args[0]));
            if ($target == null) {
                $sender->sendMessage($config->get("error") . "§cDer Spieler mit dem namen " . $args[0] . " §cwurde nicht gefunden!");
                return true;
            }
            $player = $target;
        } else {
            $player = $sender;
        }
        if (!$player instanceof Player) {
            $sender->sendMessage($config->get("error") . "§cEs muss ein Spieler sein!.");
            return true;
        }
        if (!isset($args[1])) {
            $time = 10;
        } elseif (is_numeric($args[0]) /*&& $args[0]*/ >= 0) {
            $time = floor(abs($args[1]));
        } else {
            $sender->sendMessage($config->get("error") . "§cBitte gebe die Sekundenzahl ein die ein Spieler Brennen soll!");
            return true;
        }
        $ev = new PlayerBurnEvent($player, $sender, $time);
        if ($ev->isCancelled()) {
            return true;
        }
        $player->setOnFire($ev->getSeconds());
        if ($player === $sender) {
            $sender->sendMessage($config->get("info") . "§6Du hast dich selbst angezündet für §c" . $ev->getSeconds() . "§6 sekunden.");
        } else {
            $sender->sendMessage($config->get("info") . "§6Du hast den Spieler " . $player->getName() . "§r§6 für §c" . $ev->getSeconds() . "§6 sekunden angezündet.");
        }
        return true;
    }
}