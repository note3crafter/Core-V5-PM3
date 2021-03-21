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

class DatenbankCommand extends Command
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("datenbank", $config->get("prefix") . "Datenbank", "/datenbank", ["db"]);
        $this->setPermission("core.command.datenbank");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            return $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        if (!isset($args[0])) {
            $sender->sendMessage($config->get("error") . "§cBitte gebe einen Spielernamen ein!");
            return false;
        }
        if (isset($args[0])) {
            $clan = new Config($this->plugin->getDataFolder() . Main::$clanfile . "$args[0].json", Config::JSON);
            $friend = new Config($this->plugin->getDataFolder() . Main::$freundefile . "$args[0].json", Config::JSON);
            $gruppe = new Config($this->plugin->getDataFolder() . Main::$gruppefile . "$args[0].json", Config::JSON);
            $hei = new Config($this->plugin->getDataFolder() . Main::$heifile . "$args[0].json", Config::JSON);
            $home = new Config($this->plugin->getDataFolder() . Main::$homefile . "$args[0].json", Config::JSON);
            $inv = new Config($this->plugin->getDataFolder() . Main::$inventarfile . "$args[0].json", Config::JSON);
            $log = new Config($this->plugin->getDataFolder() . Main::$logdatafile . "$args[0].json", Config::JSON);
            $stats = new Config($this->plugin->getDataFolder() . Main::$statsfile . "$args[0].json", Config::JSON);
            $user = new Config($this->plugin->getDataFolder() . Main::$userfile . "$args[0].json", Config::JSON);

            if ($args[0]) {
                if (!file_exists($this->plugin->getDataFolder() . Main::$logdatafile . "$args[0].json")) {
                    $sender->sendMessage($config->get("error") . "Dieser Spieler ist nicht regestriert. Überprüfe deine eingabe und achte drauf das alles kleingeschrieben wird!");
                    return true;
                }
                if ($sender instanceof Player) {
                    $form = new SimpleForm(function (Player $sender, $data) {
                        $result = $data;
                        if ($result === null) {
                            return true;
                        }
                        switch ($result) {
                            case 0:
                                $this->hauptmenu($sender);
                                break;

                        }
                    });
                    $form->setTitle($config->get("uiname"));
                    $form->setContent("§6Spielerdaten vom Spieler $args[0];\n");
                    $form->addButton("Hauptmenü", 0);
                    $form->addButton("§0Schließen", 0);
                    $form->sendToPlayer($sender);
                    return $form;
                }
            }

        } else {
            $sender->sendMessage($config->get("error") . "Bitte trage einen Spielernamen ein!");
        }
        return true;
    }
    public function hauptmenu($sender)
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $form = new SimpleForm(function (Player $sender, $data) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    $this->clan($sender);
                    break;
                case 1:
                    $this->freunde($sender);
                    break;
                case 2:
                    $this->gruppe($sender);
                    break;
                case 3:
                    $this->heirat($sender);
                    break;
                case 4:
                    $this->homes($sender);
                    break;
                case 5:
                    $this->inventare($sender);
                    break;
                case 6:
                    $this->logdata($sender);
                    break;
                case 7:
                    $sender->stats($sender);
                    break;
                case 8:
                    $sender->userdata($sender);
                    break;

            }
        });
        $form->setTitle($config->get("uiname"));
        $form->addButton("Clan", 0);
        $form->addButton("Freunde", 0);
        $form->addButton("Gruppe", 0);
        $form->addButton("Heirat", 0);
        $form->addButton("Homes", 0);
        $form->addButton("Inventare", 0);
        $form->addButton("Logdaten", 0);
        $form->addButton("Stats", 0);
        $form->addButton("Userdaten", 0);
        $form->addButton("§0Schließen", 0);
        $form->sendToPlayer($sender);
        return $form;
    }
    public function clan($sender) {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $form = new SimpleForm(function (Player $sender, $data) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    $this->clan($sender);
                    break;
            }
        });
        $form->setTitle($config->get("uiname"));
        $form->setContent("");
        $form->addButton("§0Schließen", 0);
        $form->sendToPlayer($sender);
        return $form;
    }
}
//last edit by Rudolf2000 : 15.03.2021 18:55