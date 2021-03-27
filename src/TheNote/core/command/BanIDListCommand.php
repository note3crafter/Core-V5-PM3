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

class BanIDListCommand extends Command
{

    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("banids", $config->get("prefix") . "Siehe die ID List für Bansystem", "/banids");
        $this->setPermission("core.command.ban");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
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
            $form->setContent("§cBanIDs für Bannen:\n\n" .
                "§e1 - Hacking (30 Tage)\n" .
                "§e2 - Beleidigung (1 Tag)\n" .
                "§e3 - Respektloses Verhalten (2 Stunden)\n" .
                "§e4 - Provokantes Verhalten (1 Stunde)\n" .
                "§e5 - Spamming (1 Stunde)\n" .
                "§e6 - Werbung (3 Tage)\n" .
                "§e7 - Report Missbrauch (1 Stunde)\n" .
                "§e8 - Wortwahl / Drohung (14 Tage)\n" .
                "§e9 - Teaming (3 Tage)\n" .
                "§e10 - Bugusing (1 Tag)\n" .
                "§e99 - Ban von einem Admin (1 Jahr)");
            $form->addButton("§0OK", 0);
            $form->sendToPlayer($sender);
        }
        return false;
    }
}


