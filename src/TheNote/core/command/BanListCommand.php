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

class BanListCommand extends Command
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("banlist", $config->get("prefix") . "Siehe die liste für gebannte Spieler", "/banlist");
        $this->setPermission("core.command.banlist");
    }


    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        if ($sender instanceof Player) {
            if ($sender instanceof Player) {
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
                $banlist = new Config($this->plugin->getDataFolder() . "banned-players.json", Config::JSON);
                $list = array();
                foreach ($banlist->getAll(true) as $players) {
                    array_push($list, $players);
                }
                $form->setTitle($config->get("uiname"));
                $form->setContent("§cGebannte Spieler§f : §e\n\n" .
                    implode(", ", $list));
                $form->addButton("§0OK", 0);
                $form->sendToPlayer($sender);
            }
        }
        return false;
    }
}
//last edit by Rudolf2000 : 15.03.2021 18:05