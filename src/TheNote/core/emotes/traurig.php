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

class traurig extends Command {
    
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("traurig", $config->get("prefix"). "§6Traurig Emote", "/traurig");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $dcsettings = new Config($this->plugin->getDataFolder() . Main::$setup . "discordsettings.yml", Config::YAML);
        if (!$sender instanceof Player) {
            return $this->plugin->getServer()->broadcastMessage("§1Der Server ist traurig :(");
        }
        $nickname = $sender->getNameTag();
        $name = $sender->getName();
        if (!$this->testPermission($sender)){
            return false;
        }
		$this->plugin->getServer()->broadcastMessage("§1$nickname §1ist traurig :(");
        if ($dcsettings->get("DC") == true) {
            $ar = getdate();
            $time = $ar['hours'] . ":" . $ar['minutes'];
            $format = Main::$dcname . " : {time} : {player} ist traurig :(";
            $msg = str_replace("{time}", $time, str_replace("{player}", $name, $format));
            $this->plugin->sendMessage($name, $msg);
        }
        return false;
    }
}