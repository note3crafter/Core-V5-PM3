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


class ClanCommand extends Command
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("clan", $config->get("prefix") . "Erstelle ein Clan", "/clan");
    }

    public function execute(CommandSender $sender, string $label, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            return $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
        }
        if (isset($args[0])) {
            if (strtolower($args[0]) === "make") {
                if (isset($args[1])) {
                    if (file_exists($this->plugin->getDataFolder() . Main::$clanfile . $args[1] . ".json")) {
                        $sender->sendMessage($config->get("error") . "§cDiesen Clan gibt es schon!");
                    } else {
                        $clan = new Config($this->plugin->getDataFolder() . Main::$clanfile . $args[1] . ".json", Config::JSON);
                        $clan->set("Owner1", $sender->getName());
                        $clan->set("Owner2", "");
                        $clan->set("Owner3", "");
                        $clan->set("player1", $sender->getName());
                        $clan->set("player2", "");
                        $clan->set("player3", "");
                        $clan->set("player4", "");
                        $clan->set("player5", "");
                        $clan->set("player6", "");
                        $clan->set("player7", "");
                        $clan->set("player8", "");
                        $clan->set("player9", "");
                        $clan->set("player10", "");
                        $clan->set("player11", "");
                        $clan->set("player12", "");
                        $clan->set("player13", "");
                        $clan->set("player14", "");
                        $clan->set("player15", "");
                        $clan->set("Member", 1);
                        $clan->save();
                        $pf = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $sender->getName() . ".json", Config::JSON);
                        $pf->set("Clan", $args[1]);
                        $pf->set("ClanStatus", true);
                        $pf->save();
                        $sender->sendMessage($config->get("clans") . "§6Der Clan§f:§d " . $pf->get("Clan") . " §6wurde erfolgreich erstellt§f!");
                    }
                }
            } else if (strtolower($args[0]) === "list") {
                if (isset($args[1])) {
                    $clan = new Config($this->plugin->getDataFolder() . Main::$clanfile . $args[1] . ".json");
                    $clanexist = new Config($this->plugin->getDataFolder() . Main::$clanfile . $args[1] . ".json", Config::JSON);
                    if (file_exists($clanexist)) {
                        $sender->sendMessage($config->get("error") . "§cDiesen Clan gibt es nicht! Überprüfe deine Eingabe nochmal");
                        return true;
                    }
                    $sender->sendMessage("=====§f[§dClan§f]=====\n" .
                        "§aClanname : " . $args[1] . "\n" .
                        "§4ClanGründer : " . $clan->get("Owner1") . "\n" .
                        "§cLeader1 : " . $clan->get("Owner2") . "\n" .
                        "§cLeader2 : " . $clan->get("Owner3") . "\n" .
                        "§eMitglied1 : " . $clan->get("player2") . "\n" .
                        "§eMitglied2 : " . $clan->get("player3") . "\n" .
                        "§eMitglied3 : " . $clan->get("player4") . "\n" .
                        "§eMitglied4 : " . $clan->get("player5") . "\n" .
                        "§eMitglied5 : " . $clan->get("player6") . "\n" .
                        "§eMitglied6 : " . $clan->get("player7") . "\n" .
                        "§eMitglied7 : " . $clan->get("player8") . "\n" .
                        "§eMitglied8 : " . $clan->get("player9") . "\n" .
                        "§eMitglied9 : " . $clan->get("player10") . "\n" .
                        "§eMitglied10 : " . $clan->get("player11") . "\n" .
                        "§eMitglied11 : " . $clan->get("player12") . "\n" .
                        "§eMitglied12 : " . $clan->get("player13") . "\n" .
                        "§eMitglied13 : " . $clan->get("player14") . "\n" .
                        "§eMitglied14 : " . $clan->get("player15"));

                }
                $pf = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $sender->getName() . ".json");
                $clanname = $pf->get("Clan");
                $clan = new Config($this->plugin->getDataFolder() . Main::$clanfile . $clanname . ".json");
                $sender->sendMessage("=====§f[§dDein Clan§f]=====\n" .
                    "§aClanname : " . $clanname . "\n" .
                    "§4ClanGründer : " . $clan->get("Owner1") . "\n" .
                    "§cLeader1 : " . $clan->get("Owner2") . "\n" .
                    "§cLeader2 : " . $clan->get("Owner3") . "\n" .
                    "§eMitglied1 : " . $clan->get("player2") . "\n" .
                    "§eMitglied2 : " . $clan->get("player3") . "\n" .
                    "§eMitglied3 : " . $clan->get("player4") . "\n" .
                    "§eMitglied4 : " . $clan->get("player5") . "\n" .
                    "§eMitglied5 : " . $clan->get("player6") . "\n" .
                    "§eMitglied6 : " . $clan->get("player7") . "\n" .
                    "§eMitglied7 : " . $clan->get("player8") . "\n" .
                    "§eMitglied8 : " . $clan->get("player9") . "\n" .
                    "§eMitglied9 : " . $clan->get("player10") . "\n" .
                    "§eMitglied10 : " . $clan->get("player11") . "\n" .
                    "§eMitglied11 : " . $clan->get("player12") . "\n" .
                    "§eMitglied12 : " . $clan->get("player13") . "\n" .
                    "§eMitglied13 : " . $clan->get("player14") . "\n" .
                    "§eMitglied14 : " . $clan->get("player15"));
            } else if (strtolower($args[0]) === "accept") {
                $pf = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $sender->getName() . ".json", Config::JSON);
                if ($pf->get("ClanAnfrage") === "") {
                    $sender->sendMessage($config->get("error") . "§cDu wurdest von keinem Clan eingeladen");
                } else {
                    $clan = new Config($this->plugin->getDataFolder() . Main::$clanfile . $pf->get("ClanAnfrage") . ".json", Config::JSON);
                    if ($clan->get("player2") === "") {
                        $clan->set("player2", $sender->getName());
                        $clan->set("Member", $clan->get("Member") + 1);
                        $clan->save();
                        $pf->set("Clan", $pf->get("ClanAnfrage"));
                        $pf->set("ClanStatus", true);
                        $pf->set("ClanAnfrage", "");
                        $pf->save();
                        $sender->sendMessage($config->get("clans") . "§6Du bist §aerfolgreich§6 den Clan beigetreten");
                    } else {
                        if ($clan->get("player3") === "") {
                            $clan->set("player3", $sender->getName());
                            $clan->set("Member", $clan->get("Member") + 1);
                            $clan->save();
                            $pf->set("Clan", $pf->get("ClanAnfrage"));
                            $pf->set("ClanStatus", true);
                            $pf->set("ClanAnfrage", "");
                            $pf->save();
                            $sender->sendMessage($config->get("clans"). "§6Du bist §aerfolgreich§6 den Clan beigetreten");
                        } else {
                            if ($clan->get("player4") === "") {
                                $clan->set("player4", $sender->getName());
                                $clan->set("Member", $clan->get("Member") + 1);
                                $clan->save();
                                $pf->set("Clan", $pf->get("ClanAnfrage"));
                                $pf->set("ClanStatus", true);
                                $pf->set("ClanAnfrage", "");
                                $pf->save();
                                $sender->sendMessage($config->get("clans") . "§6Du bist §aerfolgreich§6 den Clan beigetreten");
                            } else {
                                if ($clan->get("player5") === "") {
                                    $clan->set("player5", $sender->getName());
                                    $clan->set("Member", $clan->get("Member") + 1);
                                    $clan->save();
                                    $pf->set("Clan", $pf->get("ClanAnfrage"));
                                    $pf->set("ClanStatus", true);
                                    $pf->set("ClanAnfrage", "");
                                    $pf->save();
                                    $sender->sendMessage($config->get("clans") . "§6Du bist §aerfolgreich§6 den Clan beigetreten");
                                } else {
                                    if ($clan->get("player6") === "") {
                                        $clan->set("player6", $sender->getName());
                                        $clan->set("Member", $clan->get("Member") + 1);
                                        $clan->save();
                                        $pf->set("Clan", $pf->get("ClanAnfrage"));
                                        $pf->set("ClanStatus", true);
                                        $pf->set("ClanAnfrage", "");
                                        $pf->save();
                                        $sender->sendMessage($config->get("clans") . "§6Du bist §aerfolgreich§6 den Clan beigetreten");
                                    } else {
                                        if ($clan->get("player7") === "") {
                                            $clan->set("player7", $sender->getName());
                                            $clan->set("Member", $clan->get("Member") + 1);
                                            $clan->save();
                                            $pf->set("Clan", $pf->get("ClanAnfrage"));
                                            $pf->set("ClanStatus", true);
                                            $pf->set("ClanAnfrage", "");
                                            $pf->save();
                                            $sender->sendMessage($config->get("clans") . "§6Du bist §aerfolgreich§6 den Clan beigetreten");
                                        } else {
                                            if ($clan->get("player8") === "") {
                                                $clan->set("player8", $sender->getName());
                                                $clan->set("Member", $clan->get("Member") + 1);
                                                $clan->save();
                                                $pf->set("Clan", $pf->get("ClanAnfrage"));
                                                $pf->set("ClanStatus", true);
                                                $pf->set("ClanAnfrage", "");
                                                $pf->save();
                                                $sender->sendMessage($config->get("clans") . "§6Du bist §aerfolgreich§6 den Clan beigetreten");
                                            } else {
                                                if ($clan->get("player9") === "") {
                                                    $clan->set("player9", $sender->getName());
                                                    $clan->set("Member", $clan->get("Member") + 1);
                                                    $clan->save();
                                                    $pf->set("Clan", $pf->get("ClanAnfrage"));
                                                    $pf->set("ClanStatus", true);
                                                    $pf->set("ClanAnfrage", "");
                                                    $pf->save();
                                                    $sender->sendMessage($config->get("clans") . "§6Du bist §aerfolgreich§6 den Clan beigetreten");
                                                } else {
                                                    if ($clan->get("player10") === "") {
                                                        $clan->set("player10", $sender->getName());
                                                        $clan->set("Member", $clan->get("Member") + 1);
                                                        $clan->save();
                                                        $pf->set("Clan", $pf->get("ClanAnfrage"));
                                                        $pf->set("ClanStatus", true);
                                                        $pf->set("ClanAnfrage", "");
                                                        $pf->save();
                                                        $sender->sendMessage($config->get("clans") . "§6Du bist §aerfolgreich§6 den Clan beigetreten");
                                                    } else {
                                                        if ($clan->get("player11") === "") {
                                                            $clan->set("player11", $sender->getName());
                                                            $clan->set("Member", $clan->get("Member") + 1);
                                                            $clan->save();
                                                            $pf->set("Clan", $pf->get("ClanAnfrage"));
                                                            $pf->set("ClanStatus", true);
                                                            $pf->set("ClanAnfrage", "");
                                                            $pf->save();
                                                            $sender->sendMessage($config->get("clans") . "§6Du bist §aerfolgreich§6 den Clan beigetreten");
                                                        } else {
                                                            if ($clan->get("player12") === "") {
                                                                $clan->set("player12", $sender->getName());
                                                                $clan->set("Member", $clan->get("Member") + 1);
                                                                $clan->save();
                                                                $pf->set("Clan", $pf->get("ClanAnfrage"));
                                                                $pf->set("ClanStatus", true);
                                                                $pf->set("ClanAnfrage", "");
                                                                $pf->save();
                                                                $sender->sendMessage($config->get("clans") . "§6Du bist §aerfolgreich§6 den Clan beigetreten");
                                                            } else {
                                                                if ($clan->get("player13") === "") {
                                                                    $clan->set("player13", $sender->getName());
                                                                    $clan->set("Member", $clan->get("Member") + 1);
                                                                    $clan->save();
                                                                    $pf->set("Clan", $pf->get("ClanAnfrage"));
                                                                    $pf->set("ClanStatus", true);
                                                                    $pf->set("ClanAnfrage", "");
                                                                    $pf->save();
                                                                    $sender->sendMessage($config->get("clans") . "§6Du bist §aerfolgreich§6 den Clan beigetreten");
                                                                } else {
                                                                    if ($clan->get("player14") === "") {
                                                                        $clan->set("player14", $sender->getName());
                                                                        $clan->set("Member", $clan->get("Member") + 1);
                                                                        $clan->save();
                                                                        $pf->set("Clan", $pf->get("ClanAnfrage"));
                                                                        $pf->set("ClanStatus", true);
                                                                        $pf->set("ClanAnfrage", "");
                                                                        $pf->save();
                                                                        $sender->sendMessage($config->get("clans") . "§6Du bist §aerfolgreich§6 den Clan beigetreten");
                                                                    } else {
                                                                        if ($clan->get("player15") === "") {
                                                                            $clan->set("player15", $sender->getName());
                                                                            $clan->set("Member", $clan->get("Member") + 1);
                                                                            $clan->save();
                                                                            $pf->set("Clan", $pf->get("ClanAnfrage"));
                                                                            $pf->set("ClanStatus", true);
                                                                            $pf->set("ClanAnfrage", "");
                                                                            $pf->save();
                                                                            $sender->sendMessage($config->get("clans") . "§6Du bist §aerfolgreich§6 den Clan beigetreten");
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } else if (strtolower($args[0]) === "delete") {
                if (empty($args[1])) {
                    $sender->sendMessage($config->get("clans") . "§6Nutze §f: §e/clan delete confirm§6 um deinen Clan §centgültig §6zu löschen!");
                }
                if (isset($args[1]) and $args[1] == "confirm") {
                    $pf = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $sender->getName() . ".json", Config::JSON);
                    $clan = new Config($this->plugin->getDataFolder() . Main::$clanfile . $pf->get("Clan") . ".json", Config::JSON);
                    if ($pf->get("ClanStatus") === false) {
                        $sender->sendMessage($config->get("error") . "Du bist in kein Clan!");
                        return true;
                    }
                    if ($pf->get("ClanStatus") === true) {
                        if ($clan->get("Owner1") === $sender->getName()) {
                            $deleteclan = $pf->get("Clan");
                            $sender->sendMessage($config->get("clans") . "§6Da du der Inhaber des Clans warst wurde dieser nun Gelöscht!");
                            $members = array("player1", "player2", "player3", "player4", "player5", "player6", "player7", "player8", "player9", "player10", "player11", "player12", "player13", "player14", "player15");
                            foreach($members as $member){
                                if($clan->get($member) !== ""){
                                    $pf = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $clan->get($member) . ".json", Config::JSON);
                                    $pf->set("Clan", "");
                                    $pf->set("ClanStatus", false);
                                    $pf->save();
                                }
                            }
                            $sender->sendMessage($config->get("clans") . "§6Dein Clan §f[§d" . $deleteclan . "§r§f] §6wurde erfolgreich gelöscht!");
                            unlink($this->plugin->getDataFolder() . Main::$clanfile . $deleteclan . ".json");
                        } else {
                            $sender->sendMessage($config->get("error") . "Du kannst den Clan nicht löschen.!");
                        }
                    }
                }
            } else if (strtolower($args[0]) === "leave") {
                if (empty($args[1])) {
                    $sender->sendMessage($config->get("clans") . "§6Nutze §f: §e/clan leave confirm§6 um deinen Clan §centgültig §6zu verlassen!");
                }
                if (isset($args[1]) and $args[1] == "confirm") {
                    $pf = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $sender->getName() . ".json", Config::JSON);
                    $clan = new Config($this->plugin->getDataFolder() . Main::$clanfile . $pf->get("Clan") . ".json", Config::JSON);
                    if ($pf->get("ClanStatus") === false) {
                        $sender->sendMessage($config->get("clans") . "§cDu bist in keinem Clan!");
                        return true;
                    }
                    if ($clan->get("Owner1") === $sender->getName()) {
                        $sender->sendMessage($config->get("error") . "§6Du kannst deinen Clan nicht verlassen da du der Gründer bist! Lösche in wen nötig mit §f/clan delete <clanname>");
                    } else {
                        if ($clan->get("Owner2") === $sender->getName()) {
                            $clan->set("Owner2", "");
                            $clan->set("Member", $clan->get("Member") - 1);
                            $clan->save();
                            $pf->set("Clan", "");
                            $pf->set("ClanStatus", false);
                            $pf->save();
                            $sender->sendMessage($config->get("clans") . "§6Du hast §aerfolgreich§6 den Clan verlassen");
                        } else {
                            if ($clan->get("Owner3") === $sender->getName()) {
                                $clan->set("Owner3", "");
                                $clan->set("Member", $clan->get("Member") - 1);
                                $clan->save();
                                $pf->set("Clan", "");
                                $pf->set("ClanStatus", false);
                                $pf->save();
                                $sender->sendMessage($config->get("clans") . "§6Du hast §aerfolgreich§6 den Clan verlassen");
                            } else {
                                if ($clan->get("player1") === $sender->getName()) {
                                    $clan->set("player1", "");
                                    $clan->set("Member", $clan->get("Member") - 1);
                                    $clan->save();
                                    $pf->set("Clan", "");
                                    $pf->set("ClanStatus", false);
                                    $pf->save();
                                    $sender->sendMessage($config->get("clans") . "§6Du hast §aerfolgreich§6 den Clan verlassen");
                                } else {
                                    if ($clan->get("player2") === $sender->getName()) {
                                        $clan->set("player2", "");
                                        $clan->set("Member", $clan->get("Member") - 1);
                                        $clan->save();
                                        $pf->set("Clan", "");
                                        $pf->set("ClanStatus", false);
                                        $pf->save();
                                        $sender->sendMessage($config->get("clans") . "§6Du hast §aerfolgreich§6 den Clan verlassen");
                                    } else {
                                        if ($clan->get("player3") === $sender->getName()) {
                                            $clan->set("player3", "");
                                            $clan->set("Member", $clan->get("Member") - 1);
                                            $clan->save();
                                            $pf->set("Clan", "");
                                            $pf->set("ClanStatus", false);
                                            $pf->save();
                                            $sender->sendMessage($config->get("clans") . "§6Du hast §aerfolgreich§6 den Clan verlassen");
                                        } else {
                                            if ($clan->get("player4") === $sender->getName()) {
                                                $clan->set("player4", "");
                                                $clan->set("Member", $clan->get("Member") - 1);
                                                $clan->save();
                                                $pf->set("Clan", "");
                                                $pf->set("ClanStatus", false);
                                                $pf->save();
                                                $sender->sendMessage($config->get("clans") . "§6Du hast §aerfolgreich§6 den Clan verlassen");
                                            } else {
                                                if ($clan->get("player5") === $sender->getName()) {
                                                    $clan->set("player5", "");
                                                    $clan->set("Member", $clan->get("Member") - 1);
                                                    $clan->save();
                                                    $pf->set("Clan", "");
                                                    $pf->set("ClanStatus", false);
                                                    $pf->save();
                                                    $sender->sendMessage($config->get("clans") . "§6Du hast §aerfolgreich§6 den Clan verlassen");
                                                } else {
                                                    if ($clan->get("player6") === $sender->getName()) {
                                                        $clan->set("player6", "");
                                                        $clan->set("Member", $clan->get("Member") - 1);
                                                        $clan->save();
                                                        $pf->set("Clan", "");
                                                        $pf->set("ClanStatus", false);
                                                        $pf->save();
                                                        $sender->sendMessage($config->get("clans") . "§6Du hast §aerfolgreich§6 den Clan verlassen");
                                                    } else {
                                                        if ($clan->get("player7") === $sender->getName()) {
                                                            $clan->set("player7", "");
                                                            $clan->set("Member", $clan->get("Member") - 1);
                                                            $clan->save();
                                                            $pf->set("Clan", "");
                                                            $pf->set("ClanStatus", false);
                                                            $pf->save();
                                                            $sender->sendMessage($config->get("clans") . "§6Du hast §aerfolgreich§6 den Clan verlassen");
                                                        } else {
                                                            if ($clan->get("player8") === $sender->getName()) {
                                                                $clan->set("player8", "");
                                                                $clan->set("Member", $clan->get("Member") - 1);
                                                                $clan->save();
                                                                $pf->set("Clan", "");
                                                                $pf->set("ClanStatus", false);
                                                                $pf->save();
                                                                $sender->sendMessage($config->get("clans") . "§6Du hast §aerfolgreich§6 den Clan verlassen");
                                                            } else {
                                                                if ($clan->get("player9") === $sender->getName()) {
                                                                    $clan->set("player9", "");
                                                                    $clan->set("Member", $clan->get("Member") - 1);
                                                                    $clan->save();
                                                                    $pf->set("Clan", "");
                                                                    $pf->set("ClanStatus", false);
                                                                    $pf->save();
                                                                    $sender->sendMessage($config->get("clans") . "§6Du hast §aerfolgreich§6 den Clan verlassen");
                                                                } else {
                                                                    if ($clan->get("player10") === $sender->getName()) {
                                                                        $clan->set("player10", "");
                                                                        $clan->set("Member", $clan->get("Member") - 1);
                                                                        $clan->save();
                                                                        $pf->set("Clan", "");
                                                                        $pf->set("ClanStatus", false);
                                                                        $pf->save();
                                                                        $sender->sendMessage($config->get("clans") . "§6Du hast §aerfolgreich§6 den Clan verlassen");
                                                                    } else {
                                                                        if ($clan->get("player11") === $sender->getName()) {
                                                                            $clan->set("player11", "");
                                                                            $clan->set("Member", $clan->get("Member") - 1);
                                                                            $clan->save();
                                                                            $pf->set("Clan", "");
                                                                            $pf->set("ClanStatus", false);
                                                                            $pf->save();
                                                                            $sender->sendMessage($config->get("clans") . "§6Du hast §aerfolgreich§6 den Clan verlassen");
                                                                        } else {
                                                                            if ($clan->get("player12") === $sender->getName()) {
                                                                                $clan->set("player12", "");
                                                                                $clan->set("Member", $clan->get("Member") - 1);
                                                                                $clan->save();
                                                                                $pf->set("Clan", "");
                                                                                $pf->set("ClanStatus", false);
                                                                                $pf->save();
                                                                                $sender->sendMessage($config->get("clans") . "§6Du hast §aerfolgreich§6 den Clan verlassen");
                                                                            }
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            } else if (strtolower($args[0]) === "leader1") {
                $pf = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $sender->getName() . ".json", Config::JSON);
                if (isset($args[1])) {
                    if ($pf->get("ClanStatus") === false) {
                        $sender->sendMessage($config->get("error") . "§cDu bist in keinem Clan!");
                    } else {
                        $clan = new Config($this->plugin->getDataFolder() . Main::$clanfile . $pf->get("Clan") . ".json", Config::JSON);
                        if (file_exists($this->plugin->getDataFolder() . Main::$gruppefile . $args[1] . ".json")) {
                            if ($sender->getName() === $clan->get("Owner1")) {
                                $sf = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $args[1] . ".json", Config::JSON);
                                if ($sf->get("Clan") === $pf->get("Clan")) {
                                    $clan->set("Owner2", $args[1]);
                                    $clan->save();
                                    $sender->sendMessage($config->get("clans") . "Der Spieler wurde erfolgreich zu einem Leader befoerdert");
                                } else {
                                    $sender->sendMessage($config->get("error") . "Dieser Spieler befindet sich nicht in deinem Clan");
                                }
                            } else {
                                $sender->sendMessage($config->get("error") . "Du bist kein Leader dieses Clans");
                            }
                        } else {
                            $sender->sendMessage($config->get("error") . "Diesen Spieler gibt es nicht");
                        }
                    }
                }
            } else if (strtolower($args[0]) === "leader2") {
                $pf = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $sender->getName() . ".json", Config::JSON);
                if (isset($args[1])) {
                    if ($pf->get("ClanStatus") === false) {
                        $sender->sendMessage($config->get("error") . "§cDu bist in keinem Clan!§f");
                    } else {
                        $clan = new Config($this->plugin->getDataFolder() . Main::$clanfile . $pf->get("Clan") . ".json", Config::JSON);
                        if (file_exists($this->plugin->getDataFolder() . Main::$gruppefile . $args[1] . ".yml")) {
                            if ($sender->getName() === $clan->get("Owner1")) {
                                $sf = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $args[1] . ".json", Config::JSON);
                                if ($sf->get("Clan") === $pf->get("Clan")) {
                                    $clan->set("Owner3", $args[1]);
                                    $clan->save();
                                    $sender->sendMessage($config->get("clans") . "§6Der Spieler wurde erfolgreich zu einem §eLeader §6befoerdert");
                                } else {
                                    $sender->sendMessage($config->get("error") . "§cDieser Spieler befindet sich nicht in deinem Clan");
                                }
                            } else {
                                $sender->sendMessage($config->get("error") . "§cDu bist kein Leader dieses Clans");
                            }
                        } else {
                            $sender->sendMessage($config->get("error") . "§cDiesen Spieler gibt es nicht");
                        }
                    }
                }
            } else if (strtolower($args[0]) === "add") {
                $pf = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $sender->getName() . ".json", Config::JSON);
                if (isset($args[1])) {
                    if ($pf->get("ClanStatus") === false) {
                        $sender->sendMessage($config->get("error") . "§cDu bist in keinem Clan!");
                    } else {
                        $clan = new Config($this->plugin->getDataFolder() . Main::$clanfile . $pf->get("Clan") . ".json", Config::JSON);
                        if (file_exists($this->plugin->getDataFolder() . Main::$gruppefile . $args[1] . ".json")) {
                            if ($sender->getName() === $clan->get("Owner1")) {
                                if ($clan->get("Member") === 15) {
                                    $sender->sendMessage($config->get("error") . "§cDein Clan ist Voll!");
                                } else {
                                    $sf = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $args[1] . ".json", Config::JSON);
                                    if ($sf->get("ClanStatus") === false) {
                                        $v = $this->plugin->getServer()->getPlayerExact($args[1]);
                                        if (!$v == null) {
                                            $sf->set("ClanAnfrage", $pf->get("Clan"));
                                            $sf->save();
                                            $v->sendMessage($config->get("clans") . "§6Der §dClan §e" . $sf->get("ClanAnfrage") . " §6hat dir eine §dClan §6einladung geschickt§f!");
                                            $v->sendMessage($config->get("clans") . "§6Benutze §e/clan accept §6um die Clanabfrage anzunehmen");
                                            $sender->sendMessage($config->get("clans") . "§6Die Clan Einladung wurde §aerfolgreich §6verschickt§f!");
                                        } else {
                                            $sender->sendMessage($config->get("error") . "§cDieser Spieler ist nicht Online");
                                        }
                                    } else {
                                        $sender->sendMessage($config->get("error") . "§cDieser Spieler ist schon in einem Clan");
                                    }
                                }
                            } else {
                                $sender->sendMessage($config->get("error") . "§cDu bist kein Leader von diesem Clan");
                            }
                        } else {
                            $sender->sendMessage($config->get("error") . "§cDiesen Spieler gibt es nicht");
                        }
                    }
                }
            }
        } else {
            $sender->sendMessage("§6=====§f[§dClans§f]§6======");
            $sender->sendMessage("§e/clan make <ClanName>");
            $sender->sendMessage("§e/clan delete confirm");
            $sender->sendMessage("§e/clan leave confirm");
            $sender->sendMessage("§e/clan add <SpielerName>");
            $sender->sendMessage("§e/clan accept <ClanName>");
            $sender->sendMessage("§e/clan list <Zeigt deine Mitglieder>");
            //$sender->sendMessage("§e/clan list [Clanname] <Zeige die Mitglieder des jeweiligen clans>");
            $sender->sendMessage("§e/clan leader1 <ClanSpielerName>");
            $sender->sendMessage("§e/clan leader2 <ClanSpielerName>");
        }
        return true;
    }
}
//last edit by Rudolf2000 : 15.03.2021 18:28