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
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("burn", $config->get("prefix") . $lang->get("burnprefix"), "/burn");
        $this->setPermission("core.command.burn");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        if (!$sender instanceof Player) {
             $sender->sendMessage($config->get("error") . $lang->get("commandingame"));
             return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . $lang->get("nopermission"));
            return true;
        }
        if (empty($args[0])) {
            $sender->sendMessage($config->get("info") . $lang->get("burnusage"));
            return true;
        }

        if (isset($args[0])) {
            $target = Server::getInstance()->getPlayer(strtolower($args[0]));
            if ($target == null) {
                $sender->sendMessage($config->get("error") . $lang->get("playernotonline"));
                return true;
            }
            $player = $target;
        } else {
            $player = $sender;
        }
        if (!isset($args[1])) {
            $time = 10;
        } elseif (is_numeric($args[0]) /*&& $args[0]*/ >= 0) {
            $time = floor(abs($args[1]));
        } else {
            $sender->sendMessage($config->get("error") . $lang->get("burnseconds"));
            return true;
        }
        $ev = new PlayerBurnEvent($player, $sender, $time);
        if ($ev->isCancelled()) {
            return true;
        }
        $player->setOnFire($ev->getSeconds());
        if ($player === $sender) {
            $cfgmsg = str_replace("{seconds}", $ev->getSeconds(), $lang->get("burnyourself"));
            $sender->sendMessage($config->get("info") . $cfgmsg);
        } else {
            $stp1 = str_replace("{seconds}", $ev->getSeconds(), $lang->get("burnmessage"));
            $msg = str_replace("{player}" , $player->getName(), $stp1);
            $sender->sendMessage($config->get("info") . $msg);
        }
        return true;
    }
}