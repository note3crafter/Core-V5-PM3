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

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\utils\Config;
use TheNote\core\Main;

class HomeCommand extends Command
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("home", $config->get("prefix") . "Teleportiere dich zu deinem Home", "/home <home>");
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($config->get("info") . "§cBenutze : /home [Homename]");
        }
        if (isset($args[0])) {
            $name = $args[0];
            $home = new Config($this->plugin->getDataFolder() . Main::$homefile . $sender->getName() . ".json", Config::JSON);
            $x = $home->getNested($args[0] . ".X");
            $y = $home->getNested($args[0] . ".Y");
            $z = $home->getNested($args[0] . ".Z");
            $world = $home->getNested($args[0] . ".world");
            if ($name === null) {
                $sender->sendMessage($config->get("info") . "§cBenutze : /sethome [Homename] für ein Home zu erstellen");
                return false;
            } else {
                if($world == null){
                    $sender->sendMessage($config->get("error") . "§6Das angegebene Home §c$args[0] §6existiert nicht.");
                    return false;
                }
                $sender->teleport(new Position($x , $y , $z, $this->plugin->getServer()->getLevelByName($world)));
                return false;
            }
        }
        return true;
    }
}