<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\emotes;

use pocketmine\Player;
use pocketmine\utils\Config;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class geil extends Command {
    
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("geil", $config->get("prefix") . "§6Geil Emote", "/geil");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!$sender instanceof Player) {
            return $this->plugin->getServer()->broadcastMessage("§cDer Server ist geil O_o");
        }
        $dcsettings = new Config($this->plugin->getDataFolder() . Main::$setup . "discordsettings" . ".yml", Config::YAML);
        $playerdata = new Config($this->plugin->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        $nickname = $sender->getNameTag();
        $name = $sender->getName();
        $prefix = $playerdata->getNested($sender->getName() . ".group");
        $chatprefix = $dcsettings->get("chatprefix");
        $this->plugin->getServer()->broadcastMessage("§c$nickname §cist geil O_o");
        if ($dcsettings->get("DC") == true) {
            $ar = getdate();
            $time = $ar['hours'] . ":" . $ar['minutes'];
            $format = $chatprefix . " : {time} : $prefix {player} ist geil O_o";
            $msg = str_replace("{time}", $time, str_replace("{player}", $name, $format));
            $this->plugin->sendMessage($name, $msg);
        }
        return true;
    }
}