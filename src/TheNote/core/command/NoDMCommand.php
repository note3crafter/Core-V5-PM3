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

use pocketmine\utils\Config;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class NoDMCommand extends Command
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("notell", $config->get("prefix") . "§aAktiviere§f/§cDeaktiviere§6 Privatnachrrichten", "/notell <on|off>", ["dm", "pn", "nodm"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($config->get("info") . "/notell <on|off>");
            return true;
        }
        $cfg = new Config($this->plugin->getDataFolder() . Main::$userfile . $sender->getLowerCaseName() . ".json", Config::JSON);
        if (isset($args[0])) {
            if ($args[0] == "on") {
                $cfg->set("nodm", true);
                $cfg->save();
                $sender->sendMessage($config->get("info") . "Du hast deine §ePrivatnachrrichten §aAktiviert");
            }
            if ($args[0] == "off") {
                $cfg->set("nodm", false);
                $cfg->save();
                $sender->sendMessage($config->get("info") . "Du hast deine §ePrivatnachrrichten §cDeaktiviert");
            }
        }
        return true;
    }
}