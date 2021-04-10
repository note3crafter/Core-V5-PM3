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
class AbenteuerCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        parent::__construct("gma", $config->get("prefix") . $lang->get("adventureprefix"), "/gma", ["abenteuer", "gm2"]);
        $this->setPermission("core.command.adventure");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $configs = new Config($this->plugin->getDataFolder() . Main::$setup . "settings.json", Config::JSON);
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($configs->get("error") . $lang->get("commandingame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($configs->get("error") . $lang->get("nopermission"));
            return false;
        }
        if (isset($args[0])) {
            if ($sender->hasPermission("core.command.adventure.other")) {
                $victim = $this->plugin->getServer()->getPlayer($args[0]);
                $target = Server::getInstance()->getPlayer(strtolower($args[0]));
                if ($target == null) {
                    $sender->sendMessage($configs->get("error") . $lang->get("playernotonline"));
                    return false;
                } else {
                    $victim->setGamemode(2);
                    $cfgmsg = str_replace("{victim}", $victim->getName(), $lang->get("adventuretarget2"));
                    $victim->sendMessage($configs->get("prefix") . $lang->get("adventuretarget1"));
                    $sender->sendMessage($configs->get("prefix") . $cfgmsg);
                    return false;
                }
            }
        }
        $sender->setGamemode(2);
        $sender->sendMessage($configs->get("prefix") . $lang->get("adventuresender"));
        return true;
    }

}