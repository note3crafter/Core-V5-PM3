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

class PayMoneyCommand extends Command implements Listener
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("pay", $config->get("prefix") . "Zahle einen Spieler Geld", "/pay {player} {value}");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $money = new Config($this->plugin->getDataFolder() . Main::$cloud . "Money.yml", Config::YAML);

        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage("Nutze : /pay {player} {value}");
            return false;
        }
        if (empty($args[1])) {
            $sender->sendMessage($config->get("money") . "Nutze : /pay {player} {value}");
            return false;
        }
        if (!is_numeric($args[1])) {
            $sender->sendMessage($config->get("error") . "Bitte gebe eine Numeriche Zahl an!");
            return false;
        }
        $target = Server::getInstance()->getPlayer(strtolower($args[0]));

        if ($target == null) {
            $sender->sendMessage($config->get("error") . "Der Spieler ist nicht Online");
            return false;
        }
        if ($args[1] > $money->getNested("money." . $sender->getName())) {
            //$sender->sendMessage($this->plugin->getPrefix().$lang->get("pay-not-enought-money")); ##Future Language
            $sender->sendMessage($config->get("error") . "Du hast zu wenig Geld!");
            return false;
        }
        $money->setNested("money." . $target->getName(), $money->getNested("money." . $target->getName()) + (int)$args[1]);
        $money->setNested("money." . $sender->getName(), $money->getNested("money." . $sender->getName()) - (int)$args[1]);
        $money->save();
        $sender->sendMessage($config->get("money") . "§6Du hast §e" . $target->getName() . " §f:§e " . $args[1] . "$ §6gesendet.");
        $target->sendMessage($config->get("money") . "§6Du hast von §e" . $sender->getName() . " §f:§e " . $args[1] . "$ §6erhalten.");

        /*$stepone = str_replace("{amount}", $args[1], $lang->get("pay-success-sender"));
        $steptwo = str_replace("{player}", $player->getName(), $stepone);

        $sone = str_replace("{amount}", $args[1], $lang->get("pay-success-target"));
        $stwo = str_replace("{player}", $sender->getName(), $sone);

        $sender->sendMessage($this->plugin->getPrefix().$steptwo);
        $player->sendMessage($this->plugin->getPrefix().$stwo);*/ ##Future Language
        return true;
    }
}