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

class GroupListener implements Listener
{
    public $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onPlayerJoin(PlayerJoinEvent $event)
    {


        $groups = new Config($this->plugin->getDataFolder(). Main::$cloud . "groups.yml", Config::YAML);
        $playerdata = new Config($this->plugin->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        $defaultgroup = $groups->get("DefaultGroup");
        $player = $event->getPlayer();
        $name = $player->getName();

        if (!$playerdata->exists($name)) {
            $playerdata->setNested($name . ".group", $defaultgroup);
            $perms = $playerdata->getNested("{$name}.permissions", []);
            $perms[] = "CoreV5";
            $playerdata->setNested("{$name}.permissions", $perms);
            $playerdata->save();
        }

        $playergroup = $playerdata->getNested($name . ".group");
        $nametag = str_replace("{name}", $player->getName(), $groups->getNested("Groups.{$playergroup}.nametag"));
        $displayname = str_replace("{name}", $player->getName(), $groups->getNested("Groups.{$playerdata->getNested($name.".group")}.displayname"));
        $player->setNameTag($nametag);
        $player->setDisplayName($displayname);

        //Group Perms
        $permissionlist = (array)$groups->getNested("Groups." . $playergroup . ".permissions", []);
        foreach ($permissionlist as $name => $data) {
            $player->addAttachment($this->plugin)->setPermission($data, true);
        }

        //User Perms.
        /*$perms = (array)$playerdata->getNested("{$name}.permissions", []);
        foreach ($perms as $name => $data) {
            var_dump($name);
            $player->addAttachment($this->plugin)->setPermission($data, true);
        }*/
    }

    public function onPlayerChat(PlayerChatEvent $event)
    {

        $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
        $playerdata = new Config($this->plugin->getDataFolder() . Main::$cloud. "players.yml", Config::YAML);

        $player = $event->getPlayer();
        $name = $player->getName();

        $playergroup = $playerdata->getNested($name . ".group");
        $getformat = $groups->getNested("Groups." . $playergroup . ".format");
        $stepone = str_replace("{name}", $name, $getformat);
        $steptwo = str_replace("{msg}", $event->getMessage(), $stepone);
        $format = $steptwo;
        $event->setFormat($format);
    }
    /*public function onChat(PlayerChatEvent $event): bool
    {

        $player = $event->getPlayer();
        $name = $player->getName();
        $msg = $event->getMessage();
        $pf = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $player->getName() . ".json", Config::JSON);
        $clan = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $player->getName() . ".json", Config::JSON);
        $hei = new Config($this->plugin->getDataFolder() . Main::$userfile . $player->getLowerCaseName() . ".json", Config::JSON);
        $settings = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);


        //Groups

        $playerdata = new Config($this->plugin->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);

        $playergroup = $playerdata->getNested($name . ".group");

        $nametagone = $groups->getNested("Groups." . $playergroup . ".nametag1");
        $nametagtwo = $groups->getNested("Groups." . $playergroup . ".nametag2");
        $nametagtree = $groups->getNested("Groups." . $playergroup . ".nametag3");
        $nametagfour = $groups->getNested("Groups." . $playergroup . ".nametag4");
        $groupprefix = $groups->getNested("Groups." . $playergroup . "groupprefix");

        $clan2 = $clan->get("Clan");
        $clan3 = " §f[§d" . $clan2 . "§r§f] ";
        $nick = $pf->get("Nickname");
        $heirat = " §f[§ao§f]";

        //NameTag1
        $a = str_replace("{msg}", $msg, $nametagone);
        $b = str_replace("{player}", $nick, $a);
        $c = str_replace("{group}", $groupprefix, $b);
        //NameTag2
        $aa = str_replace("{msg}", $msg, $nametagtwo);
        $bb = str_replace("{player}", $nick, $aa);
        $cc = str_replace("{clan}", $clan3, $bb);
        $dd = str_replace("{group}", $groupprefix, $cc);
        //NameTag3
        $aaa = str_replace("{msg}", $msg, $nametagtree);
        $bbb = str_replace("{player}", $nick, $aaa);
        $ccc = str_replace("{heirat}", $heirat, $bbb);
        $ddd = str_replace("{group}", $groupprefix, $ccc);
        //NameTag4
        $aaaa = str_replace("{player}", $nick , $nametagfour);
        $bbbb = str_replace("{clan}", $clan3, $aaaa);
        $cccc = str_replace("{heirat}", $heirat, $bbbb);
        $dddd = str_replace("{msg}", $msg, $cccc);
        $eeee = str_replace("{group}", $groupprefix, $dddd);


        if ($pf->get("Nick") === true) {
            if ($clan->get("ClanStatus") === true) {
                if ($hei->get("heistatus") === true) {
                    $event->setFormat($groupprefix . $eeee);
                } elseif ($hei->get("heistatus") === false) {
                    $event->setFormat($groupprefix . $dd);
                }
            } elseif ($clan->get("ClanStatus") === false) {
                if ($hei->get("heistatus") === true) {
                    $event->setFormat($groupprefix . $ddd);
                } elseif ($hei->get("heistatus") === false) {
                    $event->setFormat($groupprefix . $c);
                }
            }
        }
        if ($pf->get("Nick") === false or null) {
            if ($clan->get("ClanStatus") === true) {
                if ($hei->get("heistatus") === true) {
                    $event->setFormat($groupprefix . $eeee);
                } elseif ($hei->get("heistatus") === false) {
                    $event->setFormat($groupprefix . $dd);
                }
            }
            if ($clan->get("ClanStatus") === false) {
                if ($hei->get("heistatus") === true) {
                    $event->setFormat($groupprefix . $ddd);
                } elseif ($hei->get("heistatus") === false) {
                    $event->setFormat($groupprefix . $c);
                }
            }
        }
        return true;
    }
    public function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $name = $player->getName();
        $groups = new Config($this->plugin->getDataFolder()."groups.yml", Config::YAML);
        $playerdata = new Config($this->plugin->getDataFolder()."players.yml", Config::YAML);
        $hei = new Config($this->plugin->getDataFolder() . Main::$userfile . $player->getLowerCaseName() . ".json", Config::JSON);
        $pf = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $player->getName() . ".json", Config::JSON);

        if(!$playerdata->exists($name)){
            $this->plugin->getLogger()->info("§7Player §6" . $name . "'s §7Data does not exist. Creating new Data...");
            $playerdata->setNested($name.".group", $groups->get("DefaultGroup"));
            $perms = $playerdata->getNested("{$name}.permissions",[]);
            $perms[] = "CoreV5";
            $playerdata->setNested("{$name}.permissions", $perms);
            $playerdata->save();
        }
        $playergroup = $playerdata->getNested($name.".group");
        $player->setNameTag(str_replace("{name}", $player->getName(), $groups->getNested("Groups.{$playergroup}.nametag1")));
        $player->setDisplayName(str_replace("{name}", $player->getName(), $groups->getNested("Groups.{$playergroup}.displayname")));

        //Group Perms
        $permissionlist = (array)$groups->getNested("Groups.".$playergroup.".permissions", []);
        foreach($permissionlist as $name => $data) {
            $player->addAttachment($this->plugin)->setPermission($data, true);
        }

        $nick = $pf->get("Nickname");
        $heirat = " §f[§ao§f]";

        //Displayname1
        $b = str_replace("{player}", $nick, $);
        $c = str_replace("{group}", $groupprefix, $b);
        //Displayname2
        $dp1 = str_replace("{player}", $nick , $nametagfour);
        $dp2 = str_replace("{heirat}", $heirat, $dp1);
        $dp3 = str_replace("{group}", $groupprefix, $dp2);
        if ($pf->get("Nickplayer") === true) {
            $player->setDisplayName("");
            $player->setNameTag("");
        } elseif ($pf->get("Nick") === true) {
            $player->setDisplayName("");
            if ($hei->get("heistatus") === true) {
                $player->setNameTag("");
            } elseif ($pf->get("heistatus") === false or NULL) {
                $player->setNameTag("");
            }
        } else {
            $player->setDisplayName("§4O§f:§c" . $player->getName());
            if ($hei->get("heistatus") === true) {
                $player->setNameTag("");
            } else if ($hei->get("heistatus") === false or NULL) {
                $player->setNameTag("");
            }
        }
        //User Perms.
        $perms = (array)$playerdata->getNested("{$name}.permissions",[]);
        foreach($perms as $name => $data) {
            var_dump($name);
            $player->addAttachment($this->plugin)->setPermission($data, true);
        }
    }*/
}