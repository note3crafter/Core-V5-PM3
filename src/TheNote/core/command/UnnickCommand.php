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

use pocketmine\Player;
use pocketmine\utils\Config;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class UnnickCommand extends Command
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("unnick", $config->get("prefix") . "Setze deinen Spielernamen zurück", "/unick");
        $this->setPermission("core.command.nick");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $playerdata = new Config($this->plugin->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        $groups = new Config($this->plugin->getDataFolder(). Main::$cloud . "groups.yml", Config::YAML);
        if (!$sender instanceof Player) {
             $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
             return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        $pf = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $sender->getName() . ".json", Config::JSON);
        $hei = new Config($this->plugin->getDataFolder() . Main::$heifile . $sender->getLowerCaseName() . ".json", Config::JSON);
        if ($pf->get("Nick") === false) {
            $sender->sendMessage($config->get("error") . "Du hast keinen Nickname!");
            return true;
        }
        if ($pf->get("Nick") === true) {
            $sender->sendMessage($config->get("info") . "Du hast dich wieder entnickt!");
            $pf->set("Nick", false);
            $pf->set("Nickplayer", false);
            $pf->set("nicket". false);
            $pf->set("Nickname", $sender->getName());
            $pf->save();
            $name = $sender->getName();
            $playergroup = $playerdata->getNested($name . ".group");
            $nametag = str_replace("{name}", $pf->get("Nickname"), $groups->getNested("Groups.{$playergroup}.nametag"));
            $displayname = str_replace("{name}", $pf->get("Nickname"), $groups->getNested("Groups.{$playerdata->getNested($name.".group")}.displayname"));
            $sender->setDisplayName($displayname);
            $sender->setNameTag($nametag);
        }
        return true;
    }
}