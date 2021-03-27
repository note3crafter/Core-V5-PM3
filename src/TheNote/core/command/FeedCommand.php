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


use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;

class FeedCommand extends Command
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("feed", $config->get("prefix") . "Stillt dein Hunger", "/feed");
        $this->setPermission("core.command.feed");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) :bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
             $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
             return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        $nickname = $sender->getNameTag();
        if (!$this->testPermission($sender)) {
            return false;
        }
        $sender->setAllowFlight(true);
        $sender->setFood(20);
        $volume = mt_rand();
        $sender->getLevel()->broadcastLevelSoundEvent($sender, LevelSoundEventPacket::SOUND_EAT, (int) $volume);
        $sender->sendMessage($config->get("prefix") . "Dein §eHunger §6wurde gestillt.");
        $this->plugin->getServer()->broadcastMessage($config->get("prefix") . "§c$nickname §6hat sich vollgefressen und ist nun Satt!");
        return false;
    }
}
