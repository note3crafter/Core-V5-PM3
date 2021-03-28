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
use TheNote\core\formapi\SimpleForm;

class UserdataCommand extends Command
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("userdata", $config->get("prefix") . "Zeige die Userdaten anderer Spieler an", "/userdata", ["user", "ud"]);
        $this->setPermission("core.command.userdata");
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
        if (!isset($args[0])) {
            $sender->sendMessage($config->get("error") . "§cBitte gebe einen Spielernamen ein!");
            return false;
        }
        if (!file_exists($this->plugin->getDataFolder() . Main::$logdatafile . "$args[0].json")) {
            $sender->sendMessage($config->get("error") . "Dieser Spieler ist nicht regestriert. Überprüfe deine eingabe und achte drauf das alles kleingeschrieben wird!");
            return false;
        }
        if (isset($args[0])) {
            $ud = new Config($this->plugin->getDataFolder() . Main::$logdatafile . "$args[0].json", Config::JSON);
            if ($args[0]) {
                if (!file_exists($this->plugin->getDataFolder() . Main::$logdatafile . "$args[0].json")) {
                    $sender->sendMessage($config->get("error") . "Dieser Spieler ist nicht regestriert. Überprüfe deine eingabe und achte drauf das alles kleingeschrieben wird!");
                    return true;
                } else {
                    $form = new SimpleForm(function (Player $sender, $data) {
                        $result = $data;
                        if ($result === null) {
                            return true;
                        }
                        switch ($result) {
                            case 0:
                                break;
                        }
                    });
                    $form->setTitle($config->get("uiname"));
                    $form->setContent("§6Spielerdaten vom Spieler $args[0];\n" .
                        "Spielername : " . $ud->get("Name") . "\n" .
                        "IP-Adresse : " . $ud->get("IP") . "\n" .
                        "Xbox-ID : " . $ud->get("Xbox-ID") . "\n" .
                        "Gerät : " . $ud->get("Geraet") . "\n" .
                        "ID : " . $ud->get("ID") . "\n" .
                        "Letzter Join : " . $ud->get("Last_Join"));

                    $form->addButton("§0OK", 0);
                    $form->sendToPlayer($sender);
                }
            }
        } else {
            $sender->sendMessage($config->get("error") . "Bitte trage einen Spielernamen ein!");
        }
        return true;
    }
}