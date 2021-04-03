<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\events;

use DateInterval;
use Exception;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\utils\Config;
use TheNote\core\Main;
use pocketmine\event\Listener;
use DateTime;

class BanEventListener implements Listener
{
    public $bansGerman = array(
        1 => ['Reason' => 'Hacking', 'Duration' => '0:30:D'],
        2 => ['Reason' => 'Beleidigung', 'Duration' => '0:1:D'],
        3 => ['Reason' => 'Respektloses Verhalten', 'Duration' => 'T:2:H'],
        4 => ['Reason' => 'Provokantes Verhalten', 'Duration' => 'T:1:H'],
        5 => ['Reason' => 'Spamming', 'Duration' => 'T:1:H'],
        6 => ['Reason' => 'Werbung', 'Duration' => '0:3:D'],
        7 => ['Reason' => 'Report Missbrauch', 'Duration' => 'T:1:H'],
        8 => ['Reason' => 'Wortwahl / Drohung', 'Duration' => '0:14:D'],
        9 => ['Reason' => 'Teaming', 'Duration' => '0:3:D'],
        10 => ['Reason' => 'Bugusing', 'Duration' => '0:1:D'],
        99 => ['Reason' => 'Ban von einem Admin', 'Duration' => '0:12:M']
    );

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onLogin(PlayerPreLoginEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $banlist = new Config($this->plugin->getDataFolder() . "banned-players.json", Config::JSON);
        $banneduuid = new Config($this->plugin->getDataFolder() . Main::$cloud . "banneduuid.yml");
        if ($player->getUniqueId() == $banneduuid->getNested("uuids.banneduuids.")) {
            $player->kick("§cDu bist permament vom Server gebannt!", false);
        }
        if (!$banlist->get(strtolower($name))) {
            return true;
        } else {
            $check = explode(', ', $banlist->get(strtolower($name)));
            $id = $check[0];
            $bannedBy = $check[1];
            try {
                $bantime = new DateTime($check[2]);
            } catch (Exception $e) {
            }
            $banid = $this->bansGerman[$id];
            if (new DateTime("now") < $bantime) {
                $time = new DateTime("now");
                $tFormat = $time->format('Y:m:d:H:i:s');
                $zone = explode(":", $tFormat);
                try {
                    $bantime->sub(new DateInterval("P" . $zone[0] . "Y" . $zone[1] . "M" . $zone[2] . "DT" . $zone[3] . "H" . $zone[4] . "M" . $zone[5] . "S"));
                } catch (Exception $e) {
                }
                $bFormat = $bantime->format('m:d:H:i:s');
                $duration = explode(":", $bFormat);
                $month = $duration[0];
                $day = $duration[1];
                $hour = $duration[2];
                $minute = $duration[3];
                $second = $duration[4];

                if ($check[0] === "1") {
                    $message = $day . " Tage, " . $hour . " Stunden, " . $minute . " Minuten, " . $second . " Sekunden.";
                    $player->kick("§4Du wurdest vom Netzwerk verbannt!\n§cGrund:§7 " . $banid['Reason'] . " §cGebannt von:§7 " . $bannedBy . "\n§cZeitraum:§7 " . $message, false);
                } else if ($check[0] === "2") {
                    $message = $day . " Tage, " . $hour . " Stunden, " . $minute . " Minuten, " . $second . " Sekunden.";
                    $player->kick("§4Du wurdest vom Netzwerk verbannt!\n§cGrund:§7 " . $banid['Reason'] . " §cGebannt von:§7 " . $bannedBy . "\n§cZeitraum:§7 " . $message, false);
                } else if ($check[0] === "3") {
                    $message = $hour . " Stunden, " . $minute . " Minuten, " . $second . " Sekunden.";
                    $player->kick("§4Du wurdest vom Netzwerk verbannt!\n§cGrund:§7 " . $banid['Reason'] . " §cGebannt von:§7 " . $bannedBy . "\n§cZeitraum:§7 " . $message, false);
                } else if ($check[0] === "4") {
                    $message = $hour . " Stunden, " . $minute . " Minuten, " . $second . " Sekunden.";
                    $player->kick("§4Du wurdest vom Netzwerk verbannt!\n§cGrund:§7 " . $banid['Reason'] . " §cGebannt von:§7 " . $bannedBy . "\n§cZeitraum:§7 " . $message, false);
                } else if ($check[0] === "5") {
                    $message = $hour . " Stunden, " . $minute . " Minuten, " . $second . " Sekunden.";
                    $player->kick("§4Du wurdest vom Netzwerk verbannt!\n§cGrund:§7 " . $banid['Reason'] . " §cGebannt von:§7 " . $bannedBy . "\n§cZeitraum:§7 " . $message, false);
                } else if ($check[0] === "6") {
                    $message = $day . " Tage, " . $hour . " Stunden, " . $minute . " Minuten, " . $second . " Sekunden.";
                    $player->kick("§4Du wurdest vom Netzwerk verbannt!\n§cGrund:§7 " . $banid['Reason'] . " §cGebannt von:§7 " . $bannedBy . "\n§cZeitraum:§7 " . $message, false);
                } else if ($check[0] === "7") {
                    $message = $day . " Tage, " . $hour . " Stunden, " . $minute . " Minuten, " . $second . " Sekunden.";
                    $player->kick("§4Du wurdest vom Netzwerk verbannt!\n§cGrund:§7 " . $banid['Reason'] . " §cGebannt von:§7 " . $bannedBy . "\n§cZeitraum:§7 " . $message, false);
                } else if ($check[0] === "8") {
                    $message = $day . " Tage, " . $hour . " Stunden, " . $minute . " Minuten, " . $second . " Sekunden.";
                    $player->kick("§4Du wurdest vom Netzwerk verbannt!\n§cGrund:§7 " . $banid['Reason'] . " §cGebannt von:§7 " . $bannedBy . "\n§cZeitraum:§7 " . $message, false);
                } else if ($check[0] === "9") {
                    $message = $day . " Tage, " . $hour . " Stunden, " . $minute . " Minuten, " . $second . " Sekunden.";
                    $player->kick("§4Du wurdest vom Netzwerk verbannt!\n§cGrund:§7 " . $banid['Reason'] . " §cGebannt von:§7 " . $bannedBy . "\n§cZeitraum:§7 " . $message, false);
                } else if ($check[0] === "10") {
                    $message = $day . " Tage, " . $hour . " Stunden, " . $minute . " Minuten, " . $second . " Sekunden.";
                    $player->kick("§4Du wurdest vom Netzwerk verbannt!\n§cGrund:§7 " . $banid['Reason'] . " §cGebannt von:§7 " . $bannedBy . "\n§cZeitraum:§7 " . $message, false);
                } else if ($check[0] === "99") {
                    $message = $month . " Monate, " . $day . " Tage, " . $hour . " Stunden, " . $minute . " Minuten, " . $second . " Sekunden.";
                    $player->kick("§4Du wurdest vom Netzwerk verbannt!\n§cGrund:§7 " . $banid['Reason'] . " §cGebannt von:§7 " . $bannedBy . "\n§cZeitraum:§7 " . $message, false);
                }
            }
        }
    }
}