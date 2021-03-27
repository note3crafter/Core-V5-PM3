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
use pocketmine\command\utils\InvalidCommandSyntaxException;

class ReplyCommand extends Command
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("reply", $config->get("prefix") . "Antworte auf deine Letzte Privatnachricht", "/reply <message>", ["r"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
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
        if(count($args) < 1) {
            throw new InvalidCommandSyntaxException();
        }

        if(!empty($this->plugin->getLastSent($sender->getName()))) {
            $player = $this->plugin->getServer()->getPlayer($this->plugin->getLastSent($sender->getName()));
                if($player instanceof CommandSender) {
                    $msg = implode(" ", $args);
                    $sName = $sender->getName();
                    $pName = $player->getName();
                    $sender->sendMessage(Main::$msg . $pName . " §b->§f " . $sName . " §f|§f ". $msg);
                    $player->sendMessage(Main::$msg . $pName . " §b->§f " . $sName . " §f|§f ". $msg);
                    $this->plugin->onMessage($sender, $player);
                }else{
                    $sender->sendMessage($config->get("error") . "§r§cDer Spieler konnte nicht gefunden werden!");
                }
        }else{
            $sender->sendMessage($config->get("error") . "§r§cDer Spieler konnte nicht gefunden werden!");
        }
        return true;
    }
}