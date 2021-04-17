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
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\utils\Config;
use TheNote\core\Main;

class ServermuteCommand extends Command implements Listener
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("servermute", $config->get("prefix") . "Mute den Chat auf dem Server", "/servermute", ["smute"]);
        $this->setPermission("core.command.servermute");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $configs = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($configs->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        $players = $this->plugin->getServer()->getOnlinePlayers();
        if(count($args) === 0){
            $reason = "Unbekannt";
        }else{
            $reason = implode(" ", $args);
        }
        foreach($players as $player){
            if($this->plugin->isMuted()){
                $this->plugin->setMuted(false);
                $player->sendMessage($configs->get("info") . "ServerMute wurde Deaktiviert");
                return false;
            }
            $this->plugin->setMuted();
            $message = str_replace("{reason}", $reason, $configs->get("info") ."Der Chat wurde Gemutet für ALLE!");
            $player->sendMessage($message);
        }
        return true;
    }
    public function onChat(PlayerChatEvent $event){
        $configs = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $player = $event->getPlayer();
        if($this->plugin->isMuted()){
            $event->setCancelled();
            $player->sendMessage($configs->get("error") . "Der Serverchat ist derzeit Stummgeschaltet!");
            return;
        }
    }
}