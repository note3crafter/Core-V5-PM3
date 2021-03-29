<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\utils\Config;
use pocketmine\Item\Item;
use pocketmine\Player;
use TheNote\core\Main;
use TheNote\Proxy\Proxy;

class GroupListener implements Listener
{
    private $defaultGroup;


    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onChat(PlayerChatEvent $event)
    {

        $player = $event->getPlayer();
        $pf = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $player->getName() . ".json", Config::JSON);
        $clan = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $player->getName() . ".json", Config::JSON);
        $hei = new Config($this->plugin->getDataFolder() . Main::$userfile . $player->getLowerCaseName() . ".json", Config::JSON);
        $settings = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);

        $clan2 = $clan->get("Clan");
        $clan3 = "§f[§d" . $clan2 . "§r§f] ";
        $nick = $pf->get("Nickname");
        $heirat = " §f[§ao§f] ";
        $msg = $event->getMessage();
        if ($pf->get("Nick") === true) {
            if ($clan->get("ClanStatus") === true) {
                if ($hei->get("heistatus") === true) {
                    if ($pf->get("Owner")) {
                        $event->setFormat($settings->get("owner") . $heirat . $clan3 . "§c" . $nick . " §r§f| §c" . $msg);
                    } else if ($pf->get("Admin")) {
                        $event->setFormat($settings->get("admin") . $heirat . $clan3 . "§c" . $nick . " §r§f| §c" . $msg);
                    } else if ($pf->get("Developer")) {
                        $event->setFormat($settings->get("developer") . $heirat . $clan3 . "§d" . $nick . " §r§f| §d" . $msg);
                    } else if ($pf->get("Builder")) {
                        $event->setFormat($settings->get("builder") . $heirat . $clan3 . "§a" . $nick . " §r§f| §a" . $msg);
                    } else if ($pf->get("Moderator")) {
                        $event->setFormat($settings->get("moderator") . $heirat . $clan3 . "§b" . $nick . " §r§f| §b" . $msg);
                    } else if ($pf->get("Supporter")) {
                        $event->setFormat($settings->get("supporter") . $heirat . $clan3 . "§b" . $nick . " §r§f| §b" . $msg);
                    } else if ($pf->get("Hero")) {
                        $event->setFormat($settings->get("hero") . $heirat . $clan3 . "§d" . $nick . " §r§f| §d" . $msg);
                    } else if ($pf->get("Suppremium")) {
                        $event->setFormat($settings->get("suppremium") . $heirat . $clan3 . "§3" . $nick . " §r§f| §3" . $msg);
                    } else if ($pf->get("Premium")) {
                        $event->setFormat($settings->get("premium") . $heirat . $clan3 . "§6" . $nick . " §R§f| §6" . $msg);
                    } else if ($pf->get("YouTuber")) {
                        $event->setFormat($settings->get("youtuber") . $heirat . $clan3 . "§f" . $nick . " §r§f| §f" . $msg);
                    } else if ($pf->get("Default")) {
                        $event->setFormat($settings->get("spieler") . $heirat . $clan3 . "§7" . $nick . " §r§f| §7" . $msg);
                    }
                } elseif ($hei->get("heistatus") === false) {
                    if ($pf->get("Owner")) {
                        $event->setFormat($settings->get("owner") . " ". $clan3 . "§c" . $nick . " §r§f| §c" . $msg);
                    } else if ($pf->get("Admin")) {
                        $event->setFormat($settings->get("admin") . " ". $clan3 . "§c" . $nick . " §r§f| §c" . $msg);
                    } else if ($pf->get("Developer")) {
                        $event->setFormat($settings->get("developer") . " " . $clan3 . "§d" . $nick . " §r§f| §d" . $msg);
                    } else if ($pf->get("Builder")) {
                        $event->setFormat($settings->get("builder") . " " . $clan3 . "§a" . $nick . " §r§f| §a" . $msg);
                    } else if ($pf->get("Moderator")) {
                        $event->setFormat($settings->get("moderator") . " " . $clan3 . "§b" . $nick . " §r§f| §b" . $msg);
                    } else if ($pf->get("Supporter")) {
                        $event->setFormat($settings->get("supporter") . " " . $clan3 . "§b" . $nick . " §r§f| §b" . $msg);
                    } else if ($pf->get("Hero")) {
                        $event->setFormat($settings->get("hero") . " " . $clan3 . "§d" . $nick . " §r§f| §d" . $msg);
                    } else if ($pf->get("Suppremium")) {
                        $event->setFormat($settings->get("suppremium") . " " . $clan3 . "§3" . $nick . " §r§f| §3" . $msg);
                    } else if ($pf->get("Premium")) {
                        $event->setFormat($settings->get("premium") . " " . $clan3 . "§6" . $nick . " §r§f| §6" . $msg);
                    } else if ($pf->get("YouTuber")) {
                        $event->setFormat( $settings->get("youtuber") . " " . $clan3 . "§f" . $nick . " §r§f| §f" . $msg);
                    } else if ($pf->get("Default")) {
                        $event->setFormat($settings->get("spieler") . " " . $clan3 . "§7" . $nick . " §r§f| §7" . $msg);
                    }
                }
            } elseif ($clan->get("ClanStatus") === false) {
                if ($hei->get("heistatus") === true) {
                    if ($pf->get("Owner")) {
                        $event->setFormat($settings->get("owner") . $heirat . "§c" . $nick . " §r§f| §c" . $msg);
                    } else if ($pf->get("Admin")) {
                        $event->setFormat($settings->get("admin") . $heirat . "§c" . $nick . " §r§f| §c" . $msg);
                    } else if ($pf->get("Developer")) {
                        $event->setFormat($settings->get("developer") . $heirat . "§d" . $nick . " §r§f| §d" . $msg);
                    } else if ($pf->get("Builder")) {
                        $event->setFormat($settings->get("builder") . $heirat . "§a" . $nick . " §r§f| §a" . $msg);
                    } else if ($pf->get("Moderator")) {
                        $event->setFormat($settings->get("moderator") . $heirat . "§b" . $nick . " §r§f| §b" . $msg);
                    } else if ($pf->get("Supporter")) {
                        $event->setFormat($settings->get("supporter") . $heirat . "§b" . $nick . " §r§f| §b" . $msg);
                    } else if ($pf->get("Hero")) {
                        $event->setFormat($settings->get("hero") . $heirat . "§d" . $nick . " §r§f| §d" . $msg);
                    } else if ($pf->get("Suppremium")) {
                        $event->setFormat($settings->get("suppremium") . $heirat . "§3" . $nick . " §r§f| §3" . $msg);
                    } else if ($pf->get("Premium")) {
                        $event->setFormat($settings->get("premium") . $heirat . "§6" . $nick . " §r§f| §6" . $msg);
                    } else if ($pf->get("YouTuber")) {
                        $event->setFormat($settings->get("youtuber") . $heirat . "§f" . $nick . " §r§f| §f" . $msg);
                    } else if ($pf->get("Default")) {
                        $event->setFormat($settings->get("spieler") . $heirat . "§7" . $nick . " §r§f| §7" . $msg);
                    }
                } elseif ($hei->get("heistatus") === false) {
                    if ($pf->get("Owner")) {
                        $event->setFormat($settings->get("owner") . " §c" . $nick . " §r§f| §c" . $msg);
                    } else if ($pf->get("Admin")) {
                        $event->setFormat($settings->get("admin") . " §c" . $nick . " §r§f| §c" . $msg);
                    } else if ($pf->get("Developer")) {
                        $event->setFormat($settings->get("developer") . " §d" . $nick . " §r§f| §d" . $msg);
                    } else if ($pf->get("Builder")) {
                        $event->setFormat($settings->get("builder") . " §a" . $nick . " §r§f| §a" . $msg);
                    } else if ($pf->get("Moderator")) {
                        $event->setFormat($settings->get("moderator") . " §b" . $nick . " §r§f| §b" . $msg);
                    } else if ($pf->get("Supporter")) {
                        $event->setFormat($settings->get("supporter") . " §b" . $nick . " §r§f| §b" . $msg);
                    } else if ($pf->get("Hero")) {
                        $event->setFormat($settings->get("hero") . " §d" . $nick . " §r§f| §d" . $msg);
                    } else if ($pf->get("Suppremium")) {
                        $event->setFormat($settings->get("suppremium") . " §3" . $nick . " §r§f| §3" . $msg);
                    } else if ($pf->get("Premium")) {
                        $event->setFormat($settings->get("premium") . " §6" . $nick . " §r§f| §6" . $msg);
                    } else if ($pf->get("YouTuber")) {
                        $event->setFormat($settings->get("youtuber") . " §f" . $nick . " §r§f| §f" . $msg);
                    } else if ($pf->get("Default")) {
                        $event->setFormat($settings->get("spieler") . " §7" . $nick . " §r§f| §7" . $msg);
                    }
                }
            }
        }
        if ($pf->get("Nick") === false or null) {
            if ($clan->get("ClanStatus") === true) {
                if ($hei->get("heistatus") === true) {
                    if ($pf->get("Owner")) {
                        $event->setFormat($settings->get("owner") . " [§ao§f] " . $clan3 . "§c" . $player->getName() . " §f| §c" . $msg);
                    } else if ($pf->get("Admin")) {
                        $event->setFormat($settings->get("admin") . " [§ao§f]" . $clan3 . "§c" . $player->getName() . " §f| §c" . $msg);
                    } else if ($pf->get("Developer")) {
                        $event->setFormat($settings->get("developer") . " [§ao§f]" . $clan3 . "§d" . $player->getName() . " §f| §d" . $msg);
                    } else if ($pf->get("Builder")) {
                        $event->setFormat($settings->get("builder") . " [§ao§f]" . $clan3 . "§a" . $player->getName() . " §f| §a" . $msg);
                    } else if ($pf->get("Moderator")) {
                        $event->setFormat($settings->get("moderator") . " [§ao§f]" . $clan3 . "§b" . $player->getName() . " §f| §b" . $msg);
                    } else if ($pf->get("Supporter")) {
                        $event->setFormat($settings->get("supporter") . " [§ao§f]" . $clan3 . "§b" . $player->getName() . " §f| §b" . $msg);
                    } else if ($pf->get("Hero")) {
                        $event->setFormat($settings->get("hero") . " [§ao§f]" . $clan3 . "§d" . $player->getName() . " §f| §d" . $msg);
                    } else if ($pf->get("Suppremium")) {
                        $event->setFormat($settings->get("suppremium") . " [§ao§f]" . $clan3 . "§3" . $player->getName() . " §f| §3" . $msg);
                    } else if ($pf->get("Premium")) {
                        $event->setFormat($settings->get("premium") . " [§ao§f]" . $clan3 . "§6" . $player->getName() . " §f| §6" . $msg);
                    } else if ($pf->get("YouTuber")) {
                        $event->setFormat($settings->get("youtuber") . " [§ao§f]" . $clan3 . "§f" . $player->getName() . " §f| §f" . $msg);
                    } else if ($pf->get("Default")) {
                        $event->setFormat($settings->get("spieler") . " [§ao§f]" . $clan3 . "§f" . $player->getName() . " §f| §7" . $msg);
                    }
                }
                if ($hei->get("heistatus") === false) {
                    if ($pf->get("Owner")) {
                        $event->setFormat($settings->get("owner") . " " . $clan3 . "§c" . $player->getName() . " §f| §c" . $msg);
                    } else if ($pf->get("Admin")) {
                        $event->setFormat($settings->get("admin") . " " . $clan3 . "§c" . $player->getName() . " §f| §c" . $msg);
                    } else if ($pf->get("Developer")) {
                        $event->setFormat($settings->get("developer") . " " . $clan3 . "§d" . $player->getName() . " §f| §d" . $msg);
                    } else if ($pf->get("Builder")) {
                        $event->setFormat($settings->get("builder") . " " . $clan3 . "§a" . $player->getName() . " §f| §a" . $msg);
                    } else if ($pf->get("Moderator")) {
                        $event->setFormat($settings->get("moderator") . " " . $clan3 . "§b" . $player->getName() . " §f| §b" . $msg);
                    } else if ($pf->get("Supporter")) {
                        $event->setFormat($settings->get("supporter") . " " . $clan3 . "§b" . $player->getName() . " §f| §b" . $msg);
                    } else if ($pf->get("Hero")) {
                        $event->setFormat($settings->get("hero") . " " . $clan3 . "§d" . $player->getName() . " §f| §d" . $msg);
                    } else if ($pf->get("Suppremium")) {
                        $event->setFormat($settings->get("suppremium") . " " . $clan3 . "§3" . $player->getName() . " §f| §3" . $msg);
                    } else if ($pf->get("Premium")) {
                        $event->setFormat($settings->get("premium") . " " . $clan3 . "§6" . $player->getName() . " §f| §6" . $msg);
                    } else if ($pf->get("YouTuber")) {
                        $event->setFormat($settings->get("youtuber") . " " . $clan3 . "§f" . $player->getName() . " §f| §f" . $msg);
                    } else if ($pf->get("Default")) {
                        $event->setFormat($settings->get("spieler") . " " . $clan3 . "§f" . $player->getName() . " §f| §7" . $msg);
                    }
                }
            }
            if ($clan->get("ClanStatus") === false) {
                if ($hei->get("heistatus") === true) {
                    if ($pf->get("Owner")) {
                        $event->setFormat($settings->get("owner") . " [§ao§f] " . "§c" . $player->getName() . " §f| §c" . $msg);
                    } else if ($pf->get("Admin")) {
                        $event->setFormat($settings->get("admin") . " [§ao§f] " . "§c" . $player->getName() . " §f| §c" . $msg);
                    } else if ($pf->get("Developer")) {
                        $event->setFormat($settings->get("developer") . " [§ao§f] " . "§d" . $player->getName() . " §f| §d" . $msg);
                    } else if ($pf->get("Builder")) {
                        $event->setFormat($settings->get("builder") . " [§ao§f] " . "§a" . $player->getName() . " §f| §a" . $msg);
                    } else if ($pf->get("Moderator")) {
                        $event->setFormat($settings->get("moderator") . " [§ao§f] " . "§b" . $player->getName() . " §f| §b" . $msg);
                    } else if ($pf->get("Supporter")) {
                        $event->setFormat($settings->get("supporter") . " [§ao§f] " . "§b" . $player->getName() . " §f| §b" . $msg);
                    } else if ($pf->get("Hero")) {
                        $event->setFormat($settings->get("hero") . " [§ao§f] " . "§d" . $player->getName() . " §f| §d" . $msg);
                    } else if ($pf->get("Suppremium")) {
                        $event->setFormat($settings->get("suppremium") . " [§ao§f] " . "§3" . $player->getName() . " §f| §3" . $msg);
                    } else if ($pf->get("Premium")) {
                        $event->setFormat($settings->get("premium") . " [§ao§f] " . "§6" . $player->getName() . " §f| §6" . $msg);
                    } else if ($pf->get("YouTuber")) {
                        $event->setFormat($settings->get("youtuber") . " [§ao§f] " . "§f" . $player->getName() . " §f| §f" . $msg);
                    } else if ($pf->get("Default")) {
                        $event->setFormat($settings->get("spieler") . " [§ao§f] " . "§f" . $player->getName() . " §f| §7" . $msg);
                    }
                } elseif ($hei->get("heistatus") === false) {
                    if ($pf->get("Owner")) {
                        $event->setFormat($settings->get("owner") . " §c" . $player->getName() . " §f| §c" . $msg);
                    } else if ($pf->get("Admin")) {
                        $event->setFormat($settings->get("admin") . " §c" . $player->getName() . " §f| §c" . $msg);
                    } else if ($pf->get("Developer")) {
                        $event->setFormat($settings->get("developer") . " §d" . $player->getName() . " §f| §d" . $msg);
                    } else if ($pf->get("Builder")) {
                        $event->setFormat($settings->get("builder") . " §a" . $player->getName() . " §f| §a" . $msg);
                    } else if ($pf->get("Moderator")) {
                        $event->setFormat($settings->get("moderator") . " §b" . $player->getName() . " §f| §b" . $msg);
                    } else if ($pf->get("Supporter")) {
                        $event->setFormat($settings->get("supporter") . " §b" . $player->getName() . " §f| §b" . $msg);
                    } else if ($pf->get("Hero")) {
                        $event->setFormat($settings->get("hero") . " §d" . $player->getName() . " §f| §d" . $msg);
                    } else if ($pf->get("Suppremium")) {
                        $event->setFormat($settings->get("suppremium") . " §3" . $player->getName() . " §f| §3" . $msg);
                    } else if ($pf->get("Premium")) {
                        $event->setFormat($settings->get("premium") . " §6" . $player->getName() . " §f| §6" . $msg);
                    } else if ($pf->get("YouTuber")) {
                        $event->setFormat($settings->get("youtuber") . " §f" . $player->getName() . " §f| §f" . $msg);
                    } else if ($pf->get("Default")) {
                        $event->setFormat($settings->get("spieler") . " §f" . $player->getName() . " §f| §7" . $msg);
                    }
                }
            }
        }
        return true;
    }

    public function onJoin(PlayerJoinEvent $event)
    {

        $player = $event->getPlayer();
        $name = $player->getName();
        $playerdata = new Config($this->plugin->getDataFolder().Main::$cloud . "players.yml", Config::YAML);
        $playergroup = $playerdata->getNested($name.".group");
        $groups = new Config($this->plugin->getDataFolder() . Main::$cloud ."groups.yml", Config::YAML);
        $permissionlist = (array)$groups->getNested("Groups.".$playergroup.".permissions", []);

        foreach($permissionlist as $name => $data) {
            var_dump($name);
            $player->addAttachment($this->plugin)->setPermission($data, true);
        }
        $perms = (array)$playerdata->getNested("{$name}.permissions",[]);
        foreach($perms as $name => $data) {
            var_dump($name);
            $player->addAttachment($this->plugin)->setPermission($data, true);
        }

        //GruppenSystem Chat
        $player = $event->getPlayer();
        $hei = new Config($this->plugin->getDataFolder() . Main::$userfile . $player->getLowerCaseName() . ".json", Config::JSON);
        $pf = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $player->getName() . ".json", Config::JSON);
        $settings = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);

        $pf->set("Name", $player->getName());
        $pf->save();
        $clan2 = $pf->get("Clan");
        $clan = "§f[§d" . $clan2 . "§f]";
        $nickname = $pf->get("Nickname");
        if ($pf->get("Default") === null) {
            $playerfile = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $player->getName() . ".json", Config::JSON);
            $playerfile->set("Default", true);
            $playerfile->set("Owner", false);
            $playerfile->set("Admin", false);
            $playerfile->set("Developer", false);
            $playerfile->set("Moderator", false);
            $playerfile->set("Builder", false);
            $playerfile->set("Supporter", false);
            $playerfile->set("YouTuber", false);
            $playerfile->set("Hero", false);
            $playerfile->set("Suppremium", false);
            $playerfile->set("Premium", false);
            $playerfile->set("Nick", false);
            $playerfile->set("NickP", false);
            $playerfile->set("NickPlayer", false);
            $playerfile->set("Nickname", null);
            $playerfile->save();
        }
        if ($pf->get("Default") === true) {

            $player->setDisplayName("§eS§f:§7" . $player->getName() . "§7");
            if ($hei->get("heistatus") === true) {
                $player->setNameTag($settings->get("spieler") . " [§ao§f] §7" . $player->getName() . "§7");
            } else if ($hei->get("heistatus") === false or NULL) {
                $player->setNameTag($settings->get("spieler") . " §7" . $player->getName() . "§7");
            }

        } else if ($pf->get("Owner") === true) {

            if ($pf->get("Nickplayer") === true) {
                $player->setDisplayName("§eS§f:§7" . $nickname . "§7");
                $player->setNameTag($settings->get("spieler") . " §7" . $nickname . "§7");
            } elseif ($pf->get("Nick") === true) {
                $player->setDisplayName("§4O§f:§c" . $nickname . "§c");
                if ($hei->get("heistatus") === true) {
                    $player->setNameTag($settings->get("owner") . " [§ao§f] §c" . $nickname . "§c");
                } elseif ($pf->get("heistatus") === false or NULL) {
                    $player->setNameTag($settings->get("owner") . " §c" . $nickname . "§c");
                }
            } else {

                $player->setDisplayName("§4O§f:§c" . $player->getName());
                if ($hei->get("heistatus") === true) {
                    $player->setNameTag($settings->get("owner") . " [§ao§f] §c" . $player->getName());
                } else if ($hei->get("heistatus") === false or NULL) {
                    $player->setNameTag($settings->get("owner") . " §c" . $player->getName());
                }
            }
        } else if ($pf->get("Admin") === true) {

            if ($pf->get("Nickplayer") === true) {
                $player->setDisplayName("§eS§f:§7" . $nickname . "§7");
                $player->setNameTag($settings->get("spieler") . " §7" . $nickname . "§7");
            } elseif ($pf->get("Nick") === true) {
                $player->setDisplayName("§cA§f:§c" . $nickname . "§c");
                if ($hei->get("heistatus") === true) {
                    $player->setNameTag($settings->get("admin") . " [§ao§f] §c" . $nickname . "§c");
                } else if ($hei->get("heistatus") === false or NULL) {
                    $player->setNameTag($settings->get("admin") . " §c" . $nickname . "§c");
                }
            } else {
                $player->setDisplayName("§cA§f:§c" . $player->getName());
                if ($hei->get("heistatus") === true) {
                    $player->setNameTag($settings->get("admin") . " [§ao§f] §c" . $player->getName());
                } else if ($hei->get("heistatus") === false or NULL) {
                    $player->setNameTag($settings->get("admin") . " §c" . $player->getName());
                }
            }
        } else if ($pf->get("Developer") === true) {

            if ($pf->get("Nickplayer") === true) {
                $nickname = $pf->get("Nickname");
                $player->setDisplayName("§eS§f:§7" . $nickname . "§7");
                $player->setNameTag($settings->get("spieler") . " §7" . $nickname . "§7");
            } elseif ($pf->get("Nick") === true) {
                $nickname = $pf->get("Nickname");
                $player->setDisplayName("§5D§f:§d" . $nickname . "§d");
                if ($hei->get("heistatus") === true) {
                    $player->setNameTag($settings->get("developer") . " [§ao§f] §d" . $nickname . "§d");
                } else if ($hei->get("heistatus") === false or NULL) {
                    $player->setNameTag($settings->get("developer") . " §d" . $nickname . "§d");
                }

            } else {

                $player->setDisplayName("§5D§f:§d" . $player->getName());
                if ($hei->get("heistatus") === true) {
                    $player->setNameTag($settings->get("developer") . " [§ao§f] §d" . $player->getName());
                } else if ($hei->get("heistatus") === false or NULL) {
                    $player->setNameTag($settings->get("developer") . "] §d" . $player->getName());
                }
            }
        } else if ($pf->get("Moderator") === true) {

            if ($pf->get("Nickplayer") === true) {
                $nickname = $pf->get("Nickname");
                $player->setDisplayName("§eS§f:§7" . $nickname . "§7");
                $player->setNameTag($settings->get("spieler") . " §7" . $nickname . "§7");
            } elseif ($pf->get("Nick") === true) {
                $nickname = $pf->get("Nickname");
                $player->setDisplayName("§1M§f:§b" . $nickname . "§b");
                if ($hei->get("heistatus") === true) {
                    $player->setNameTag($settings->get("moderator") . " [§ao§f] §b" . $nickname . "§b");
                } else if ($hei->get("heistatus") === false or NULL) {
                    $player->setNameTag($settings->get("moderator") . " §b" . $nickname . "§b");
                }

            } else {

                $player->setDisplayName("§1M§f:§b" . $player->getName());
                if ($hei->get("heistatus") === true) {
                    $player->setNameTag($settings->get("moderator") . " [§ao§f] §b" . $player->getName());
                } else if ($hei->get("heistatus") === false or NULL) {
                    $player->setNameTag($settings->get("moderator") . " §b" . $player->getName());
                }
            }
        } else if ($pf->get("Builder") === true) {

            if ($pf->get("Nickplayer") === true) {
                $nickname = $pf->get("Nickname");
                $player->setDisplayName("§eS§f:§7" . $nickname . "§7");
                $player->setNameTag($settings->get("spieler") . " §7" . $nickname . "§7");
            } elseif ($pf->get("Nick") === true) {
                $nickname = $pf->get("Nickname");
                $player->setDisplayName("§aB§f:§a" . $nickname . "§a");
                if ($hei->get("heistatus") === true) {
                    $player->setNameTag($settings->get("builder") . " [§ao§f] §a" . $nickname . "§a");
                } else if ($hei->get("heistatus") === false or NULL) {
                    $player->setNameTag($settings->get("builder") . " §a" . $nickname . "§a");
                }

            } else {

                $player->setDisplayName("§aB§f:§a" . $player->getName());
                if ($hei->get("heistatus") === true) {
                    $player->setNameTag($settings->get("builder") . " [§ao§f] §a" . $player->getName());
                } else if ($hei->get("heistatus") === false or NULL) {
                    $player->setNameTag($settings->get("builder") . " §a" . $player->getName());
                }
            }

        } else if ($pf->get("Supporter") === true) {

            if ($pf->get("Nickplayer") === true) {
                $nickname = $pf->get("Nickname");
                $player->setDisplayName("§eS§f:§7" . $nickname . "§7");
                $player->setNameTag($settings->get("spieler") . " §7" . $nickname . "§7");
            } elseif ($pf->get("Nick") === true) {
                $nickname = $pf->get("Nickname");
                $player->setDisplayName("§bS§f:§b" . $nickname . "§b");
                if ($hei->get("heistatus") === true) {
                    $player->setNameTag($settings->get("supporter") . " [§ao§f] §b" . $nickname . "§b");
                } else if ($hei->get("heistatus") === false or NULL) {
                    $player->setNameTag($settings->get("supporter") . " §b" . $nickname . "§b");
                }

            } else {

                $player->setDisplayName("§bS§f:§b" . $player->getName());
                if ($hei->get("heistatus") === true) {
                    $player->setNameTag($settings->get("supporter") . " [§ao§f] §b" . $player->getName());
                } else if ($hei->get("heistatus") === false or NULL) {
                    $player->setNameTag($settings->get("supporter") . " §b" . $player->getName());
                }

            }
        } else if ($pf->get("YouTuber") === true) {

            if ($pf->get("Nickplayer") === true) {
                $nickname = $pf->get("Nickname");
                $player->setDisplayName("§eS§f:§7" . $nickname . "§7");
                $player->setNameTag($settings->get("spieler") . " §7" . $nickname . "§7");
            } elseif ($pf->get("Nick") === true) {
                $nickname = $pf->get("Nickname");
                $player->setDisplayName("§cY§fT:§f" . $nickname . "§f");
                if ($hei->get("heistatus") === true) {
                    $player->setNameTag($settings->get("youtuber") . " [§ao§f] " . $nickname . "§f");
                } else if ($hei->get("heistatus") === false or NULL) {
                    $player->setNameTag($settings->get("youtuber") . " " . $nickname . "§f");
                }
            } else {

                $player->setDisplayName("§cY§fT:§f" . $player->getName());
                if ($hei->get("heistatus") === true) {
                    $player->setNameTag($settings->get("youtuber") . " [§ao§f] " . $player->getName());
                } else if ($hei->get("heistatus") === false or NULL) {
                    $player->setNameTag($settings->get("youtuber") . " " . $player->getName());
                }
            }
        } else if ($pf->get("Hero") === true) {

            if ($pf->get("Nickplayer") === true) {
                $nickname = $pf->get("Nickname");
                $player->setDisplayName("§eS§f:§7" . $nickname . "§7");
                $player->setNameTag($settings->get("spieler") . " §7" . $nickname . "§7");
            } elseif ($pf->get("Nick") === true) {
                $nickname = $pf->get("Nickname");
                $player->setDisplayName("§dH§f:§d" . $nickname . "§d");
                if ($hei->get("heistatus") === true) {
                    $player->setNameTag($settings->get("hero") . " [§ao§f] §d" . $nickname . "§d");
                } else if ($hei->get("heistatus") === false or NULL) {
                    $player->setNameTag($settings->get("hero") . " §d" . $nickname . "§d");
                }

            } else {

                $player->setDisplayName("§dH§f:§d" . $player->getName());
                if ($hei->get("heistatus") === true) {
                    $player->setNameTag($settings->get("hero") . " [§ao§f] §d" . $player->getName());
                } else if ($hei->get("heistatus") === false or NULL) {
                    $player->setNameTag($settings->get("hero") . " §d" . $player->getName());
                }
            }
        } else if ($pf->get("Suppremium") === true) {

            if ($pf->get("Nickplayer") === true) {
                $nickname = $pf->get("Nickname");
                $player->setDisplayName("§eS§f:§7" . $nickname . "§7");
                $player->setNameTag($settings->get("spieler") . " §7" . $nickname . "§7");
            } elseif ($pf->get("Nick") === true) {
                $nickname = $pf->get("Nickname");
                $player->setDisplayName("§3S§f:§3" . $nickname . "§3");
                if ($hei->get("heistatus") === true) {
                    $player->setNameTag($settings->get("suppremium") . " [§ao§f] §3" . $nickname . "§3");
                } else if ($hei->get("heistatus") === false or NULL) {
                    $player->setNameTag($settings->get("suppremium") . " §3" . $nickname . "§3");
                }

            } else {

                $player->setDisplayName("§3S§f:§3" . $player->getName());
                if ($hei->get("heistatus") === true) {
                    $player->setNameTag($settings->get("suppremium") . " [§ao§f] §3" . $player->getName());
                } else if ($hei->get("heistatus") === false or NULL) {
                    $player->setNameTag($settings->get("suppremium") . " §3" . $player->getName());
                }
            }
        } else if ($pf->get("Premium") === true) {

            if ($pf->get("Nickplayer") === true) {
                $nickname = $pf->get("Nickname");
                $player->setDisplayName("§eS§f:§7" . $nickname . "§7");
                $player->setNameTag($settings->get("spieler") . " §7" . $nickname . "§7");
            } elseif ($pf->get("Nick") === true) {
                $nickname = $pf->get("Nickname");
                $player->setDisplayName("§6P§f:§6" . $nickname . "§6");
                if ($hei->get("heistatus") === true) {
                    $player->setNameTag($settings->get("premium") . " [§ao§f] §6" . $nickname . "§6");
                } else if ($hei->get("heistatus") === false or NULL) {
                    $player->setNameTag($settings->get("premium") . " §6" . $nickname . "§6");
                }

            } else {

                $player->setDisplayName("§6P§f:§6" . $player->getName());
                if ($hei->get("heistatus") === true) {
                    $player->setNameTag($settings->get("premium") . " [§ao§f] §6" . $player->getName());
                } else if ($hei->get("heistatus") === false or NULL) {
                    $player->setNameTag($settings->get("premium") . " §6" . $player->getName());

                }
            }
        }
    }
}