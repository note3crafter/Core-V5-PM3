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
use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\Main;
use pocketmine\command\utils\InvalidCommandSyntaxException;

class TellCommand extends Command
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("tell", $config->get("prefix") . "Sende eine Privatnachrricht", "/tell <Spieler> <Nachrricht>", ["msg", "whisper", "w"]);
        $this->plugin = $plugin;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            return $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
        }
        if (count($args) < 2) {
            throw new InvalidCommandSyntaxException();
        }

        $player = $sender->getServer()->getPlayer(strtolower($args[0]));
        unset($args[0]);
        if ($player == null) {
            $sender->sendMessage($config->get("error") . "Der Spieler ist nicht Online!");
            return true;
        }
        $cfg = new Config($this->plugin->getDataFolder() . Main::$userfile . $sender->getLowerCaseName() . ".json", Config::JSON);
        $stats = new Config($this->plugin->getDataFolder() . Main::$statsfile . $player->getLowerCaseName() . ".json", Config::JSON);
        $vote = new Config($this->plugin->getDataFolder() . Main::$setup . "vote.yml", Config::YAML);
        if ($vote->get("votes") == true) {
            if ($stats->get("votes") < 1) {
                $player->sendMessage($config->get("error") . "§cDu musst mindestens 1x Gevotet haben um auf dem Server Schreiben zu können! §f-> §e" . $config->get("votelink"));
                return true;
            }
        }
        if($cfg->get("nodm") === true) {
            $sender->sendMessage($config->get("error") . "Dieser Spieler hat seine MSGs Ausgeschaltet!");
            return true;
        }
        if($player === $sender){
            $sender->sendMessage($config->get("error") . "Du kannst dir nicht selbst eine Nachricht senden!");
            return true;
        }
        if ($player == null) {
            $sender->sendMessage($config->get("error") . "Der Spieler ist nicht Online!");
            return true;
        }
        if ($player instanceof Player) {
            $sender->sendMessage(Main::$msg . "{$sender->getNameTag()} §f-> {$player->getNameTag()} §f| §b" . implode(" ", $args));
            $name = $sender instanceof Player ? $config->get("msg") . $sender->getNameTag() : $sender->getNameTag() . "§f";
            $player->sendMessage($config->get("msg") . "$name §f-> zu dir | §b" . implode(" ", $args));
            $this->plugin->onMessage($sender, $player);
        }
        return true;
    }
}