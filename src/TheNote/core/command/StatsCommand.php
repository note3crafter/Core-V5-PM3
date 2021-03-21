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
use TheNote\core\formapi\SimpleForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class StatsCommand extends Command {

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("stats", $config->get("prefix") . "§6Schaue deine Stats an", "/stats");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            return $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
        }
        $stats = new Config($this->plugin->getDataFolder() . Main::$statsfile . $sender->getLowerCaseName() . ".json", Config::JSON);
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
                $form->setTitle($config->get("uiname"));
                $form->setContent("§6======§f[§eStatistiken§f]§6======\n" .
                    "§eDeine Statistiken\n" .
                    "Deine Joins : " . $stats->get("joins") . "\n" .
                    "Deine Sprünge : " . $stats->get("jumps") . "\n" .
                    "Deine Kicks : " . $stats->get("kicks") . "\n" .
                    "Deine Interacts : " . $stats->get("interact") . "\n" .
                    "Gelaufene Meter : " . $stats->get("movewalk") . "m\n" .
                    "Geflogene Meter : " . $stats->get("movefly") . "m\n" .
                    "Blöcke abgebaut : " . $stats->get("break") . "\n" .
                    "Blöcke gesetzt : " . $stats->get("place") . "\n" .
                    "Gedroppte Items : " . $stats->get("drop") . "\n" .
                    "Gesammelte Items : " . $stats->get("pick") . "\n" .
                    "Consumierte Items : " . $stats->get("consume") . "\n" .
                    "Deine Nachrrichten : " . $stats->get("messages") . "\n".
                    "Deine Votes : " . $stats->get("votes"));

                $form->addButton("§0OK", 0);
                $form->sendToPlayer($sender);
            }
        }
    }
}