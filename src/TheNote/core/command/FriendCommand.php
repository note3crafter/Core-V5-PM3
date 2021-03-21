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

class FriendCommand extends Command{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("friend", $config->get("prefix") . "Sehe die Freundes Befehle!", "/friend", ["freund", "freunde"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            return $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
        }
        if ($sender instanceof Player) {
            $playerfile = new Config($this->plugin->getDataFolder() . Main::$freundefile . $sender->getName() . ".json", Config::JSON);
            if (empty($args[0])) {
                $sender->sendMessage("§f======[§aFreundeSystem Hilfe§f]======");
                $sender->sendMessage("§c/friend » §7accept » §f Aktzeptiere eine Anfrage");
                $sender->sendMessage("§c/friend » §7add » §fLade ein Freund ein");
                $sender->sendMessage("§c/friend » §7list » §fZeigt Deine Freunde an");
                $sender->sendMessage("§c/friend » §7deny » §f Lehne eine Anfrage ab");
                $sender->sendMessage("§c/friend » §7remove » §fEntferne einen Freund");
                $sender->sendMessage("§c/friend » §7block » §fDeaktiviere Freundschaftsanfragen");
            } else {
                if ($args[0] == "add") {
                    if (empty($args[1])) {
                        $sender->sendMessage($config->get("friend") . "§7Benutze: §c/friend add [name]");
                    } else {
                        if (file_exists($this->plugin->getDataFolder() . Main::$freundefile . $args[1] . ".json")) {
                            $vplayerfile = new Config($this->plugin->getDataFolder() . Main::$freundefile . $args[1] . ".json", Config::JSON);
                            if ($vplayerfile->get("blocked") == false) {
                                $einladungen = $vplayerfile->get("Invitations");
                                $einladungen[] = $sender->getName();
                                $vplayerfile->set("Invitations", $einladungen);
                                $vplayerfile->save();
                                $sender->sendMessage($config->get("friend") . "§aDeine Freundschaftsanfrage wurde gesendet zu  " . $args[1]);
                                $v = $this->plugin->getServer()->getPlayerExact($args[1]);
                                if (!$v == null) {
                                    $v->sendMessage($config->get("friend") . "§a" . $sender->getName() . " hat Dir eine Freundschafts Anfrage gesendet akzeptier sie mit §e/friend accept " . $sender->getName() . "§a oder lehne sie ab mit §e /friend deny " . $sender->getName() . "§a!");
                                }
                            } else {
                                $sender->sendMessage($config->get("friend") . "§aDieser Spieler hat Deine Freundschaftsanfrage nicht angenommen!");
                            }
                        }
                    }
                }
                if ($args[0] == "accept") {
                    if (empty($args[1])) {
                        $sender->sendMessage($config->get("friend") . "§7Benutze: §c/friend accept [name]");
                    } else {
                        if (file_exists($this->plugin->getDataFolder() . Main::$freundefile . $args[1] . ".json")) {
                            $vplayerfile = new Config($this->plugin->getDataFolder() . Main::$freundefile . $args[1] . ".json", Config::JSON);
                            if (in_array($args[1], $playerfile->get("Invitations"))) {
                                $old = $playerfile->get("Invitations");
                                unset($old[array_search($args[1], $old)]);
                                $playerfile->set("Invitations", $old);
                                $newfriend = $playerfile->get("Friend");
                                $newfriend[] = $args[1];
                                $playerfile->set("Friend", $newfriend);
                                $playerfile->save();
                                $vplayerfile = new Config($this->plugin->getDataFolder() . Main::$freundefile . $args[1] . ".json", Config::JSON);
                                $newfriend = $vplayerfile->get("Friend");
                                $newfriend[] = $sender->getName();
                                $vplayerfile->set("Friend", $newfriend);
                                $vplayerfile->get("friends", $vplayerfile->set("friends") + 1);
                                $vplayerfile->save();
                                if (!$this->plugin->getServer()->getPlayerExact($args[1]) == null) {
                                    $this->plugin->getServer()->getPlayerExact($args[1])->sendMessage(Main::$friend . "§a" . $sender->getName() . " hat Deine Freundschaffts Anfrage angenommen!");
                                }
                                $sender->sendMessage($config->get("friend") . "§a" . $args[1] . " ist jetzt Dein Freund!");
                            } else {
                                $sender->sendMessage($config->get("friend") . "§aDieser Spieler hat Dir keine Freundschafts Anfrage gesendet!");
                            }
                        } else {
                            $sender->sendMessage($config->get("friend") . "§aDiesen Spieler gibt es nicht!");
                        }
                    }
                }
                if ($args[0] == "deny") {
                    if (empty($args[1])) {
                        $sender->sendMessage($config->get("friend") . "§7Benutze: §c/friend deny [name]");
                    } else {
                        if (file_exists($this->plugin->getDataFolder() . Main::$freundefile . $args[1] . ".json")) {
                            $vplayerfile = new Config($this->plugin->getDataFolder() . Main::$freundefile . $args[1] . ".json", Config::JSON);
                            if (in_array($args[1], $playerfile->get("Invitations"))) {
                                $old = $playerfile->get("Invitations");
                                unset($old[array_search($args[1], $old)]);
                                $playerfile->set("Invitations", $old);
                                $playerfile->save();
                                $sender->sendMessage($config->get("friend") . "§aDie Anfrage von " . $args[1] . " wurde abgelehnt!");
                            } else {
                                $sender->sendMessage($config->get("friend") . "§aDieser Spieler hat Dir keine Freundschafts Anfrage gesendet!");
                            }
                        } else {
                            $sender->sendMessage($config->get("friend") . "§aDiesen Spieler gibt es nicht!");
                        }
                    }
                }
                if ($args[0] == "remove") {
                    if (empty($args[1])) {
                        $sender->sendMessage($config->get("friend") . "§7Benutze: §c/friend remove [name]");
                    } else {
                        if (file_exists($this->plugin->getDataFolder() . Main::$freundefile . $args[1] . ".json")) {
                            $vplayerfile = new Config($this->plugin->getDataFolder() . Main::$freundefile . $args[1] . ".json", Config::JSON);
                            if (in_array($args[1], $playerfile->get("Friend"))) {
                                $old = $playerfile->get("Friend");
                                unset($old[array_search($args[1], $old)]);
                                $playerfile->set("Friend", $old);
                                $playerfile->save();
                                $vplayerfile = new Config($this->plugin->getDataFolder() . Main::$freundefile . $args[1] . ".json", Config::JSON);
                                $old = $vplayerfile->get("Friend");
                                unset($old[array_search($sender->getName(), $old)]);
                                $vplayerfile->set("Friend", $old);
                                $vplayerfile->get("friends", $vplayerfile->set("friends") - 1);
                                $vplayerfile->save();
                                $sender->sendMessage($config->get("friend") . "§a" . $args[1] . " ist nicht mehr Dein Freund!");
                            } else {
                                $sender->sendMessage($config->get("friend") . "§aDieser Spieler ist nicht Dein Freund!");
                            }
                        } else {
                            $sender->sendMessage($config->get("friend") . "§aDiesen Spieler gibt es nicht!");
                        }
                    }
                }
                if ($args[0] == "list") {
                    if (empty($playerfile->get("Friend"))) {
                        $sender->sendMessage($config->get("friend") . "§aDu hast keine Freunde!");
                    } else {
                        $sender->sendMessage("§f=======[§aDeine Freunde§f]=======");
                        foreach ($playerfile->get("Friend") as $f) {
                            if ($this->plugin->getServer()->getPlayerExact($f) == null) {
                                $sender->sendMessage("§b" . $f . " » §7(§cOffline§7)");
                            } else {
                                $sender->sendMessage("§b" . $f . " » §7(§aOnline§7)");
                            }
                        }
                    }
                }
                if ($args[0] == "block") {
                    if ($playerfile->get("blocked") === false) {
                        $playerfile->set("blocked", true);
                        $playerfile->save();
                        $sender->sendMessage($config->get("friend") . "§aDu wirst nun keine Freundschaftsanfrage mehr bekommen!");
                    } else {
                        $sender->sendMessage($config->get("friend") . "§aDu wirst nun wieder Freundschaftsanfragen bekommen!");
                        $playerfile->set("blocked", false);
                        $playerfile->save();
                    }
                }
            }
        } else {
            $this->plugin->getLogger()->info($config->get("friend") . "§aDie Console hat keine Freunde!");
        }
        return true;
    }
}
//last edit by Rudolf2000 : 15.03.2021 20:29