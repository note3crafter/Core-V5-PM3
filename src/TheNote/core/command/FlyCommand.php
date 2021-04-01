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

use pocketmine\event\Listener;
use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class FlyCommand extends Command implements Listener
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("fly", $config->get("prefix") . "§aAktiviert§f/§cDeaktiviert§6 Fliegen", "/fly");
        $this->setPermission("core.command.fly");
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
        if (isset($args[0])) {
            if ($sender->hasPermission("core.command.fly.other")) {
                $victim = $this->plugin->getServer()->getPlayer($args[0]);
                $target = Server::getInstance()->getPlayer(strtolower($args[0]));
                if ($target == null) {
                    $sender->sendMessage($config->get("error") . "Der Spieler ist nicht Online!");
                    return false;
                }
                if ($victim->getAllowFlight() === true) {
                    $victim->setAllowFlight(false);
                    $victim->setFlying(false);
                    $victim->sendMessage($config->get("prefix") . "§6Dein §eFlugmodus §6wurde §cDeaktiviert§6 von " . $sender->getNameTag());
                    $sender->sendMessage($config->get("prefix") . "§6Du hast den §eFlugmodus §6von " . $victim->getName() . " §r§cDeaktiviert.");
                } else {
                    $victim->setAllowFlight(true);
                    $victim->setFlying(true);
                    $victim->sendMessage($config->get("prefix") . "§6Dein §eFlugmodus §6wurde §aAktiviert§6 von " . $sender->getNameTag());
                    $sender->sendMessage($config->get("prefix") . "§6Du hast den §eFlugmodus §6von " . $victim->getName() . " §r§aAktiviert.");
                }
                return false;
            } else {
                $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um andere Spieler den Flugmodus zu geben!");
                return false;
            }
        }
        if ($sender->getAllowFlight() === true) {
            $sender->setAllowFlight(false);
            $sender->setFlying(false);
            $sender->sendMessage($config->get("prefix") . "§6Dein §eFlugmodus §6wurde §cDeaktiviert§6.");
        } else {
            $sender->setAllowFlight(true);
            $sender->setFlying(true);
            $sender->sendMessage($config->get("prefix") . "§6Dein §eFlugmodus §6wurde §aAktiviert§6.");
        }
        return false;
    }
}