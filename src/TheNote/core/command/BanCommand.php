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

use DateInterval;
use DateTime;
use Exception;
use pocketmine\command\CommandSender;
use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\Main;
use pocketmine\command\Command;

class BanCommand extends Command
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

    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("ban", $config->get("prefix") . "Banne einen Spieler", "/ban", ["pun"]);
        $this->setPermission("core.command.ban");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        if (isset($args[0])) {
            if (isset($args[1])) {
                if (array_key_exists($args[1], $this->bansGerman)) {
                    $banlist = new Config($this->plugin->getDataFolder() . "banned-players.json", Config::JSON);
                    $sender2 = $this->plugin->getServer()->getPlayer(strtolower($args[0]));
                    $idList = $this->bansGerman[$args[1]];
                    $duration = explode(':', $idList['Duration']);
                    $date = new DateTime('now');
                    if ($duration[0] == 'T') {

                        try {
                            $date->add(new DateInterval('PT' . $duration[1] * "1" . $duration[2]));
                        } catch (Exception $e) {
                        }
                    } else {
                        try {
                            $date->add(new DateInterval('P' . $duration[1] * "1" . $duration[2]));
                        } catch (Exception $e) {
                        }
                    }
                    $banneduuid = new Config($this->plugin->getDataFolder() . Main::$cloud . "banneduuid.yml");

                    $target = Server::getInstance()->getPlayer(strtolower($args[0]));
                    $format = $date->format('Y-m-d H:i:s');
                    $by = $sender->getName();
                    $id = $args[1];
                    if ($target == null) {
                        $banlist->set(strtolower($args[0]), $id . ", " . $by . ", " . $format);
                        $sender->sendMessage($config->get("error") . "Der Spieler " . strtolower($args[0]) . " konnte nicht gefunden werden oder ist bereits gebannt!.");
                    } else {
                        $banneduuid->setNested("uuids.banneduuids." . $target->getUniqueId(), $target->getName());
                        $banneduuid->save();
                        $banlist->set(strtolower($sender2->getName()), $id . ", " . $by . ", " . $format);
                        $msg = "Der Spieler §2 {banned-player} §awurde erfolgreich für§2 {reason} §agebannt.";
                        $msg = str_replace("{reason}", $idList['Reason'], $msg);
                        $msg = str_replace("{banned-player}", strtolower($sender2->getName()), $msg);
                        $sender->sendMessage($config->get("ban") . $msg);
                        $this->plugin->sendBanMessage($target->getName(), $sender->getName(), $idList['Reason']);
                        $target->kick("§cDu wurdest vom Netzwerk verbannt!\n§cRejoine um mehr Infos zu bekommen.", false);
                    }
                    $banlist->save();
                    $banlist->reload();
                } else {
                    $sender->sendMessage($config->get("ban") . "Nutze : /ban <player|id> Hilfe : /banids");
                }
            } else {
                $sender->sendMessage($config->get("ban") . "Nutze : /ban <player|id> Hilfe : /banids");
            }
        } else {
            $sender->sendMessage($config->get("ban") . "Nutze : /ban <player|id> Hilfe : /banids");
        }
        return false;
    }
}
