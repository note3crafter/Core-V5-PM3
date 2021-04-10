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

class SetMoneyCommand extends Command implements Listener
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("setmoney", $config->get("prefix") . "Setze ein Geldstand", "/setmoney {player} {value}");
        $this->setPermission("core.command.setmoney");
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
        if(!isset($args[0])) {
            $sender->sendMessage($config->get("money") . "Nutze : /setmoney {player} {value}");
            return false;
        }
        if(!isset($args[1])) {
            $sender->sendMessage($config->get("money") . "Nutze : /setmoney {player} {value}");
            return false;
        }
        if(!is_numeric($args[1])) {
            $sender->sendMessage($config->get("error") . "Bitte gebe eine Numeriche Zahl an!");
            return false;
        }
        $target = Server::getInstance()->getPlayer(strtolower($args[0]));
        if ($target == null) {
            $sender->sendMessage($config->get("error") . "Der Spieler ist nicht Online");
            return false;
        }

        $money->setNested("money." . $target->getName() , (int)$args[1]);
        $money->save();

        //$stepone = str_replace("{player}", $player->getName(), $lang->get("eco-set-success-sender"));
        //$steptwo = str_replace("{amount}", $args[2], $stepone); ##Future

        $sender->sendMessage($config->get("money") . "§6Der Geldstand von§e " . $target->getName() . " §6wurde auf §f:§e " . $args[1] . "$ §6gesetzt!");
        $target->sendMessage($config->get("money") . "§6Dein Geldstand wurde auf §f:§e " . $args[1] . "$ §6gesetzt!");
        return true;
    }

}