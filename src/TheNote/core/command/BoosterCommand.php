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

use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\Player;
use pocketmine\utils\Config;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use onebone\economyapi\EconomyAPI;

class BoosterCommand extends Command
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("booster", $config->get("prefix") . "Booster", "/booster <speed|jump|minespeed|nightvision>");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        $mymoney = $this->plugin->getServer()->getPluginManager()->getPlugin("EconomyAPI");
        if (empty($args[0])){
            $sender->sendMessage($config->get("booster") . "/booster <speed|jump|minespeed|nightvision>");
            return true;
        }
        if (isset($args[0])) {
            if ($args[0] == "speed") {
                if ($mymoney->myMoney($sender) < 2000) {
                    $sender->sendMessage($config->get("error") . "Du hast zu wenig Geld um den Speedbooster nutzen zu können!");
                } else if ($mymoney->myMoney($sender) >= 2000) {
                    $mymoney->reduceMoney($sender, 2000);
                    $sender->sendMessage($config->get("booster") . "§6Du hast den Speedbooster für 2000€ Gekauft für eine Länge von 10 Minuten!");
                    $sender->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), (12000), (1), (false)));
                    $this->plugin->getServer()->broadcastMessage($config->get("booster") . "§6Der Spieler " . $sender->getName() . " hast sich den Speedbooster für 10 Minuten gegönnt!");
                }
            }
            if ($args[0] == "jump") {
                if ($mymoney->myMoney($sender) < 3000) {
                    $sender->sendMessage($config->get("error") . "Du hast zu wenig Geld um den Jumpbooster nutzen zu können!");
                } else if ($mymoney->myMoney($sender) >= 3000) {
                    $mymoney->reduceMoney($sender, 3000);
                    $sender->sendMessage($config->get("booster") . "§6Du hast den Jumpbooster für 3000€ Gekauft für eine Länge von 10 Minuten!");
                    $sender->addEffect(new EffectInstance(Effect::getEffect(8), (12000), (2), (false)));
                    $this->plugin->getServer()->broadcastMessage($config->get("booster") . "§6Der Spieler " . $sender->getName() . " hast sich den Jumpbooster für 10 Minuten gegönnt!");

                }
            }
            if ($args[0] == "minespeed") {
                if ($mymoney->myMoney($sender) < 3000) {
                    $sender->sendMessage($config->get("error") . "Du hast zu wenig Geld um den Minespeedbooster nutzen zu können!");
                } else if ($mymoney->myMoney($sender) >= 3000) {
                    $mymoney->reduceMoney($sender, 3000);
                    $sender->sendMessage($config->get("booster") . "§6Du hast den Minebooster für 4000€ Gekauft für eine Länge von 5 Minuten!");
                    $sender->addEffect(new EffectInstance(Effect::getEffect(Effect::HASTE), (6000), (1), (false)));
                    $this->plugin->getServer()->broadcastMessage($config->get("booster") . "§6Der Spieler " . $sender->getName() . " hast sich den Minespeedbooster für 5 Minuten gegönnt!");

                }
            }
            if ($args[0] == "nightvision") {
                if ($mymoney->myMoney($sender) < 4000) {
                    $sender->sendMessage($config->get("error") . "Du hast zu wenig Geld um den Nachtsichtbooster nutzen zu können!");
                } else if ($mymoney->myMoney($sender) >= 4000) {
                    $mymoney->reduceMoney($sender, 4000);
                    $sender->sendMessage($config->get("booster") . "§6Du hast den Nachtsichtbooster für 4000€ Gekauft für eine Länge von 20 Minuten!");
                    $sender->addEffect(new EffectInstance(Effect::getEffect(16), (24000), (1), (false)));
                    $this->plugin->getServer()->broadcastMessage($config->get("booster") . "§6Der Spieler " . $sender->getName() . " hast sich den Nachtsichtbooster für 20 Minuten gegönnt!");

                }
            }
        }
        return true;
    }
}
//last edit by Rudolf2000 : 15.03.2021 : 18:11
/*
    foreach ($players as $pl) {
                    $pl->addEffect(new EffectInstance(Effect::getEffect(8), (12000), (2), (false)));
                    break;
 */
