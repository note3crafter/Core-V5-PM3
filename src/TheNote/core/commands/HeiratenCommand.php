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

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\network\mcpe\protocol\OnScreenTextureAnimationPacket;
use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use TheNote\core\Main;

class HeiratenCommand extends Command implements Listener
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("heiraten", $config->get("prefix") . "Heirate andere Spieler", "/heiraten", ["hei"]);
        $this->plugin = $plugin;

    }

    public function execute(CommandSender $sender, string $label, array $args)
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            return $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
        }
        if (isset($args[0])) {
            if ($this->plugin->getServer()->getPlayer($args[0]) instanceof Player) {
                $victim = $this->plugin->getServer()->getPlayer($args[0]);
                $ba = $this->getPCFG($victim->getLowerCaseName(), "antrag");
                $antrag = $this->plugin->getServer()->getPlayer($ba);
                if (isset($antrag) and $antrag instanceof Player) {
                    $sender->sendMessage($config->get("heirat") . "§c" . $victim->getName() . " §6hat bereits einen §aAntrag §6von §c" . $antrag->getName() . "§6 am laufen.");
                } else {
                    if ($victim === $sender) {
                        $sender->sendMessage($config->get("heirat") . "§cDu kannst dich nicht selbst heiraten!");
                    } else {
                        $bh = $this->getPCFG($victim->getLowerCaseName(), "heiraten");
                        $hochzeit = $this->plugin->getServer()->getPlayer($bh);
                        if (isset($hochzeit) and $hochzeit instanceof Player) {
                            $sender->sendMessage($config->get("heirat") . "§c" . $victim->getName() . "§6 ist bereits mit §c" . $hochzeit->getName() . "§a verheiratet.");
                        } else {
                            $this->addPCFG($victim->getLowerCaseName(), "antrag", $sender->getName());
                            $this->plugin->getServer()->broadcastMessage($config->get("heirat") . "§c" . $sender->getName() . "§6 macht gerade §c" . $victim->getName() . "§6 einen §aHeiratsantrag! ");
                            $victim->sendMessage($config->get("heirat") . $sender->getName() . " §6hat dir gerade einen §aHeiratsantrag §6gemacht! Nimm diesen mit §e/heiraten annehmen §6an oder lehne ihn mit §e/heiraten ablehnen §6ab.");
                        }
                    }
                }
            } else {
                switch (strtolower($args[0])) {
                    case "annehmen":
                        $antrag = $this->getPCFG($sender->getLowerCaseName(), "antrag");
                        $victim = $this->plugin->getServer()->getPlayer($antrag);
                        $hei = new Config($this->plugin->getDataFolder() . Main::$heifile . $sender->getLowerCaseName() . ".json", Config::JSON);
                        $user = new Config($this->plugin->getDataFolder() . Main::$userfile . $sender->getLowerCaseName() . ".json", Config::JSON);
                        if (isset($victim) and $victim instanceof Player) {
                            $this->plugin->getServer()->broadcastMessage($config->get("heirat") . "§a" . $sender->getName() . "§6 und §a" . $victim->getName() . "§6 sind jetzt §averheiratet!");
                            $this->addPCFG($victim->getLowerCaseName(), "heiraten", $sender->getName());
                            $this->addPCFG($sender->getLowerCaseName(), "heiraten", $victim->getName());
                            $packet = new OnScreenTextureAnimationPacket();
                            $packet->effectId = 10;
                            $sender->sendDataPacket($packet);
                            $victim->sendDataPacket($packet);

                            $x = $this->getPCFG($sender->getLowerCaseName(), "antrag-angenommen");
                            $this->addPCFG($sender->getLowerCaseName(), "antrag-angenommen", ($x + 1));
                            $hei->set("Heiraten", $hei->get("Heiraten") + 1);
                            $this->addPCFG($sender->getLowerCaseName(), "antrag", NULL);
                            $hei = new Config($this->plugin->getDataFolder() . Main::$userfile . $sender->getLowerCaseName() . ".json", Config::JSON);
                            $heiv = new Config($this->plugin->getDataFolder() . Main::$userfile . $victim->getLowerCaseName() . ".json", Config::JSON);
                            $user->set("heistatus", true);
                            $user->save();
                            $heiv->set("heistatus", true);
                            $heiv->save();
                        } else {
                            $sender->sendMessage($config->get("error") . "§cDu hast derzeit keine §aAnträge.");
                        }
                        break;
                    case "ablehnen":
                        $antrag = $this->getPCFG($sender->getLowerCaseName(), "antrag");
                        $victim = $this->plugin->getServer()->getPlayer($antrag);
                        if (isset($victim) and $victim instanceof Player) {
                            $this->plugin->getServer()->broadcastMessage($config->get("heirat") . "§a" . $sender->getName() . "§6 hat den §aAntrag §6von §a" . $victim->getName() . "§c abgelehnt! :-(");

                            $x = $this->getPCFG($sender->getLowerCaseName(), "antrag-abgelehnt");
                            $this->addPCFG($sender->getLowerCaseName(), "antrag-abgelehnt", ($x + 1));
                            $this->addPCFG($sender->getLowerCaseName(), "antrag", NULL);

                        } else {
                            $sender->sendMessage($config->get("error") . "§cDu hast derzeit keine §aAnträge.");
                        }
                        break;
                    case "scheidung":
                        $scheidung = $this->getPCFG($sender->getLowerCaseName(), "heiraten");
                        $victim = $this->plugin->getServer()->getPlayer($scheidung);
                        if (isset($victim) and $victim instanceof Player) {
                            $this->setScheidung($victim);
                            $hei = new Config($this->plugin->getDataFolder() . Main::$userfile . $sender->getLowerCaseName() . ".json", Config::JSON);
                            $heiv = new Config($this->plugin->getDataFolder() . Main::$userfile . $victim->getLowerCaseName() . ".json", Config::JSON);
                            $hei->set("heistatus", false);
                            $hei->save();
                            $heiv->set("heistatus", false);
                            $heiv->save();
                            $victim->sendMessage($config->get("heirat") . "§cLeider hat sich " . $sender->getName() . "§c von dir Getrennt :c");
                            $sender->sendMessage($config->get("heirat") . "§aDu hast dich von " . $victim->getNameTag() . "§aGetrennt.");
                            $packet = new OnScreenTextureAnimationPacket();
                            $packet->effectId = 20;
                            $sender->sendDataPacket($packet);
                            $victim->sendDataPacket($packet);
                        } else {
                            $sender->sendMessage($config->get("error") . "§cDu bist derzeit nicht §averheiratet.");
                        }
                        break;
                    case "hilfe":
                        $sender->getName();
                        $sender->sendMessage($config->get("info") . "§aBenutze : /hei [name] scheidung/suprise/annehmen/ablehnen");
                        break;
                    case "surprise":
                        $surprise = $this->getPCFG($sender->getLowerCaseName(), "heiraten");
                        $victim = $this->plugin->getServer()->getPlayer($surprise);
                        if ($victim instanceof Player) {
                            $aname = $victim->getNameTag();
                            $bname = $sender->getNameTag();
                            $b = [
                                "§a$aname §6und §a$bname §6laufen Hand in Hand richtung Sonnenuntergang!",
                                "§a$aname §6und §a$bname §6schauen sich tief in die Augen!",
                                "§a$aname §6und §a$bname §6spitzen die Lippen und ... ",
                                "§a$aname §6und §a$bname §6liegen gemeinsam im Bett...Quitch ",
                                "§a$aname §6und §a$bname §6geben sich ein Surprisefick ",
                                "§a$aname §6und §a$bname §6sind Glücklich miteinander ",
                                "§a$aname §6und §a$bname §6machen ein arschfick ",
                                "§a$aname §6und §a$bname §6spielen sich an die Glocken "
                            ];
                            $surprise = $b[rand(0, 7)];
                            $this->plugin->getServer()->broadcastMessage(Main::$hr . $surprise);
                        } else {
                            $sender->sendMessage($config->get("error") . "§cDu bist derzeit nicht verheiratet.");
                        }
                        break;
                }
            }
        } else {
            $name = $sender->getLowerCaseName();
            $x = new Config($this->plugin->getDataFolder() . Main::$heifile . "$name.json", Config::JSON);
            $antrag = $x->get("antrag");
            $antragabgelehnt = $x->get("antrag-abgelehnt");
            $hochzeit = $x->get("heiraten");
            $hochzeithits = $x->get("heiraten-hit");
            $geschieden = $x->get("geschieden");
            $sender->sendMessage("§f======§f[§6Heiratsübersicht§f]======");
            $sender->sendMessage("§eAntrag von: §a" . $antrag);
            $sender->sendMessage("§eAnträge abgelehnt: §a" . $antragabgelehnt);
            $sender->sendMessage("§eVerheirat mit: §a" . $hochzeit);
            $sender->sendMessage("§eAktuelle Hits: §a" . $hochzeithits);
            $sender->sendMessage("§eBisher geschieden: §c" . $geschieden);
        }
        return true;
    }

    public function getPCFG($player, $a)
    {
        $pcfg = new Config($this->plugin->getDataFolder() . Main::$heifile . strtolower($player) . ".json", Config::JSON);
        $x = $pcfg->get($a);
        return $x;
    }

    public function addPCFG($player, $a, $b)
    {
        $pcfg = new Config($this->plugin->getDataFolder() . Main::$heifile . strtolower($player) . ".json", Config::JSON);
        $pcfg->set($a, $b);
        $pcfg->save();
        return true;
    }

    public function setScheidung($a)
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $player = $a->getLowerCaseName();
        $x = new Config($this->plugin->getDataFolder() . Main::$heifile . strtolower($player) . ".json", Config::JSON);
        $hochzeit = $x->get("heiraten");
        $got = $this->plugin->getServer()->getPlayer($hochzeit);
        $victim = $got->getLowerCaseName();
        $v = new Config($this->plugin->getDataFolder() . Main::$heifile . strtolower($victim) . ".json", Config::JSON);
        $hei = new Config($this->plugin->getDataFolder() . Main::$userfile . $player . ".json", Config::JSON);
        $heiv = new Config($this->plugin->getDataFolder() . Main::$userfile . $victim . ".json", Config::JSON);
        $v->set("heiraten", NULL);
        $v->set("heiraten-hit", 0);
        $vgesch = $v->get("geschieden");
        $v->set("geschieden", $vgesch + 1);
        $v->save();
        $x->set("heiraten", NULL);
        $x->set("heiraten-hit", 0);
        $xgesch = $x->get("geschieden");
        $x->set("geschieden", $xgesch + 1);
        $x->save();
        $this->plugin->getServer()->broadcastMessage($config->get("heirat") . "§a" . $player . "§6 und §a" . $victim . "§6 haben sich grade geschiden!");
        $hei->set("heistatus", false);
        $hei->save();
        $heiv->set("heistatus", false);
        $heiv->save();
        return true;
    }
}