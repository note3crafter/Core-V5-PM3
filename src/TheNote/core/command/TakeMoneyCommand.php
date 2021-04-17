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
use pocketmine\Player;
use pocketmine\Server;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;

class TakeMoneyCommand extends Command implements Listener
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("takemoney", $config->get("prefix") . "Hole das Geld eines Spielers", "/takemoney {player} {value}");
        $this->setPermission("core.command.takemoney");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $money = new Config($this->plugin->getDataFolder() . Main::$cloud . "Money.yml", Config::YAML);
        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        if (!isset($args[0])) {
            $sender->sendMessage($config->get("money") . "Nutze : /takemoney {player} {value}");
            return false;
        }
        if (!isset($args[1])) {
            $sender->sendMessage($config->get("money") . "Nutze : /takemoney {player} {value}");
            return false;
        }
        if (!is_numeric($args[1])) {
            $sender->sendMessage($config->get("error") . "Bitte gebe eine Numeriche Zahl an!");
            return false;
        }
        $player = $sender->getServer()->getPlayer(strtolower($args[0]));
        if($player === $sender){
            $sender->sendMessage($config->get("error") . "Du kannst dir nicht selbst geld Abziehen!");
            return false;
        }

        $target = Server::getInstance()->getPlayer(strtolower($args[0]));
        if ($target == null) {
            $sender->sendMessage($config->get("error") . "Der Spieler ist nicht Online");
            return false;
        }
        $old = $money->getNested("money." . $target->getName());
        $money->setNested("money." . $target->getName(), $old - (int)$args[1]);
        $money->save();
        $sender->sendMessage($config->get("money") . "§6Du hast§e " . $target->getName() . " §f:§e " . $args[1] . "$ §6abgezogen!");
        $target->sendMessage($config->get("money") . "§6Du hast von §e" . $sender->getName() . " §f:§e " . $args[1] . "$ §6abgezogen bekommen!");
        return true;
    }
}
