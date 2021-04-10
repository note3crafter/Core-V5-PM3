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

class ErfolgCommand extends Command
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("erfolg", $config->get("prefix") . "Siehe deinen Status zum Erfolg", "/erfolg");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $stats = new Config($this->plugin->getDataFolder() . Main::$statsfile . $sender->getLowerCaseName() . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }
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
        $form->setContent("§6======§f[§2Erfolge§f]§6======\n" .
            "§aErfolge Abgeschlossen §f:§2 " . $stats->get("erfolge") . "§f/§27\n" .
            "§aBereits Gesprungen §f:§e " . $stats->get("jumps") . "§f/§e10000\n".
            "§aDeine Kicks §f:§e " . $stats->get("kicks") . "§f/§e1000\n" .
            "§aDeine Joins §f:§e " . $stats->get("joins") . "§f/§e10000\n" .
            "§aAbgebaute Blöcke §f:§e " . $stats->get("break") . "§f/§e1000000\n" .
            "§aGesetzte Blöcke §f:§e " . $stats->get("place") . "§f/§e1000000\n" .
            "§aGeschriebene Nachrichten §f:§e " . $stats->get("messages") . "§f/§e1000000\n");
        $form->addButton("§0OK", 0);
        $form->sendToPlayer($sender);
        return true;
    }
}
//last edit by Rudolf2000 : 15.03.2021 19:45