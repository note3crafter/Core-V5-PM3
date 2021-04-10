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

use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;

class KickallCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("kickall", $config->get("prefix") . "Kickt alle Spieler vom Server!", "/kickall");
        $this->setPermission("core.command.kickall");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($config->get("info") . "Nutze: /kickall {reason}");
        }

            if (isset($args[0])) {
                $onlinePlayers = $this->plugin->getServer()->getOnlinePlayers();
                if ($sender->hasPermission("core.command.kickall") || $sender->isOp()) {
                    foreach ($this->plugin->getServer()->getOnlinePlayers() as $players) {
                        $name = $sender->getDisplayName();
                        if (count($onlinePlayers) === 0 || (count($onlinePlayers) === 1)) {
                            $sender->sendMessage($config->get("error") . "§cEs sind keine Spieler Online zum kicken");
                        } elseif ($players !== $sender) {
                            $players->kick($config->get("info") . "Jeder wurde vom Server gekickt!\n§cGrund : $args[0]", false);
                            $this->plugin->getServer()->broadcastMessage($config->get("info") . "§c$name §6hat alle Spieler vom Server gekickt!");
                        }
                    }
                }
            }

        return true;
    }
}