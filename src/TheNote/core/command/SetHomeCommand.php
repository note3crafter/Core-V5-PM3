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
use pocketmine\Player;
use pocketmine\utils\Config;
use TheNote\core\Main;

class SetHomeCommand extends Command
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("sethome", $config->get("prefix") . "Setze dein Home", "/sethome <Home>");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $configs = new Config($this->plugin->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($config->get("prefix") . "§cBenutze : /sethome [Homename]");
        }
        $user = new Config($this->plugin->getDataFolder() . Main::$userfile . $sender->getName() . ".json", Config::JSON);
        if ($user->get("homes") === $configs->get("maxhomes")) {
            $sender->sendMessage($config->get("error") . "Du hast deine Maximale anzahl deiner Homes erreicht!");
            return true;
        }
        if (isset($args[0])) {
            $x = $sender->getX();
            $y = $sender->getY();
            $z = $sender->getZ();
            $world = $sender->getLevel()->getName();
            $name = $args[0];
            $user = new Config($this->plugin->getDataFolder() . Main::$userfile . $sender->getName() . ".json", Config::JSON);
            $user->set("homes", $user->set("homes") + 1);
            $user->save();
            $home = new Config($this->plugin->getDataFolder() . Main::$homefile . $sender->getName() . ".json", Config::JSON);
            $home->set($name, ["X" => $x, "Y" => $y, "Z" => $z, "world" => $world]);
            $home->save();
            $sender->sendMessage($config->get("info") . "Du hast deinen Home erfolgreich bei X: $x Y: $y Z: $z in der Welt $world : Gesetzt");
        }
        return true;
    }
}