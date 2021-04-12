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
use TheNote\core\formapi\SimpleForm;
use TheNote\core\Main;

class CreditsCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        parent::__construct("credits", "§f[§4Core§eV5§f] §6Credits", "/credits");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
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
        $form->setTitle("§0===§f[§6Credits§f]§0==§f[§eCore§4V5§f]§0===");
        $form->setContent("§e- TheNote/Rudolf2000 #Inhaber der Core\n" .
                    "§e- tim03we #BanSystem usw\n" .
                    "§e- xxflow #Heiraten,Payall\n" .
                    "§e- Aneoxic #Grundgerüst\n" .
                    "§e- FleekRush #Booster\n" .
                    "§e- JackMD #Discord\n" .
                    "§e- LookItsAku #Hilfe\n" .
                    "§e- Hagnbrain #homesystem\n" .
                    "§e- EnderDirt #füraltecodes\n" .
                    "§e- Crasher508 #Fixxer\n" .
                    "§e- CortexPE #Braustand und mehr\n" .
                    "§e- HimmelKreis4865 #AntiXray & BetterSkulls\n" .
                    "§e- MDevPmmP #GroupSystem/EconomySystem\n" .
                    "§e- TuranicTeam #Paar Items/Blöcke von Altay\n" .
                    "§e- jojoe77777 #FormAPI\n" .
                    "§e- muqsit #InvMenü\n" .
                    "§e- TheBalkanDev #ecinvsee, invsee\n" .
                    "§e- jasonwynn10 #Beacons");
        $form->addButton("§0OK", 0);
        $form->sendToPlayer($sender);
        return true;
    }
}