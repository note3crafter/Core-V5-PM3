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

use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;
use TheNote\core\Main;
use pocketmine\command\Command;

class KickCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("kick", $config->get("prefix") . "Kicke einen Spieler", "/kick <spieler> <grund>");
        $this->setPermission("core.command.kick");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        if(empty($args[0])) {
            $sender->sendMessage($config->get("info") . "Benutze: /kick {Spieler} [Nachrricht]");
        }
        if(isset($args[0])) {
            if(empty($args[1])) {
                if ($this->plugin->getServer()->getPlayer($args[0]) instanceof Player) {
                    $victim = $this->plugin->getServer()->getPlayer($args[0]);
                    $victim->kick("§cDu wurdest gekickt von " . $sender->getName(), false);
                }
            } elseif ($this->plugin->getServer()->getPlayer($args[0]) instanceof Player) {
                $victim = $this->plugin->getServer()->getPlayer($args[0]);
                $victim->kick("§cDu wurdest gekickt von " . $sender->getName() . "§c wegen " . $args[1] , false);
            }
        }
        return true;
    }
}

