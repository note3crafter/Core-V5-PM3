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

class DelHomeCommand extends Command
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("delhome", $config->get("prefix") . "Löscht das angegebene Home aus deiner Liste", "/delhome <home>");
    }
    public function execute(CommandSender $sender, string $label, array $args)
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            return $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($config->get("prefix") . "§cBenutze : /delhome [Homename]");
            return true;
        }
        if (isset($args[0])) {
            $home = new Config($this->plugin->getDataFolder() . Main::$homefile . $sender->getName() . ".json", Config::JSON);

            if($home->exists($args[0])){
                $home->remove($args[0]);
                $home->save();
                $user = new Config($this->plugin->getDataFolder() . Main::$userfile . $sender->getName() . ".json", Config::JSON);
                $user->set("homes", $user->set("homes") - 1);
                $user->save();
                $sender->sendMessage($config->get("prefix") . "Dein Home wurde erfolgreich gelöscht.");
            }else{
                $sender->sendMessage($config->get("prefix") . "§cDas Home mit dem Namen " . $args[0] . " konnte nicht gefunden werden");
            }
        }
        return true;
    }
}
//last edit by Rudolf2000 : 15.03.2021 19:32