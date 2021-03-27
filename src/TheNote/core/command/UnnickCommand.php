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

use pocketmine\Player;
use pocketmine\utils\Config;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class UnnickCommand extends Command
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("unnick", $config->get("prefix") . "Setze deinen Spielernamen zurück", "/unick");
        $this->setPermission("core.command.nick");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
             $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
             return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        $pf = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $sender->getName() . ".json", Config::JSON);
        $hei = new Config($this->plugin->getDataFolder() . Main::$heifile . $sender->getLowerCaseName() . ".json", Config::JSON);
        if ($pf->get("NickP") === false or NULL) {
            $sender->sendMessage($config->get("error") . "§cDazu bist du nicht Berechtigt§f!");
            return true;
        }
        if ($pf->get("Nick") === false) {
            $sender->sendMessage($config->get("error") . "Du hast keinen Nickname!");
            return true;
        }
        if ($pf->get("Nick") === true) {
            if ($pf->get("Default") === true) {

                $sender->setDisplayName("§eS§f:§7" . $sender->getName() . "§7");
                if ($hei->get("heistatus") === true) {
                    $sender->setNameTag($config->get("spieler") . " [§ao§f] §7" . $sender->getName() . "§7");
                } else if ($hei->get("heistatus") === false or NULL) {
                    $sender->setNameTag($config->get("spieler") . " §7" . $sender->getName() . "§7");
                }
            } else if ($pf->get("Owner") === true) {

                $sender->setDisplayName("§4O§f:§c" . $sender->getName());
                if ($hei->get("heistatus") === true) {
                    $sender->setNameTag($config->get("owner") . " [§ao§f] §c" . $sender->getName());
                } else if ($hei->get("heistatus") === false or NULL) {
                    $sender->setNameTag($config->get("owner") . " §c" . $sender->getName());
                }
            } else if ($pf->get("Admin") === true) {

                $sender->setDisplayName("§cA§f:§c" . $sender->getName());
                if ($hei->get("heistatus") === true) {
                    $sender->setNameTag($config->get("admin") . " [§ao§f] §c" . $sender->getName());
                } else if ($hei->get("heistatus") === false or NULL) {
                    $sender->setNameTag($config->get("admin") . " §c" . $sender->getName());
                }

            } else if ($pf->get("Developer") === true) {

                $sender->setDisplayName("§5D§f:§d" . $sender->getName());
                if ($hei->get("heistatus") === true) {
                    $sender->setNameTag($config->get("developer") . " [§ao§f] §d" . $sender->getName());
                } else if ($hei->get("heistatus") === false or NULL) {
                    $sender->setNameTag($config->get("developer") . " §d" . $sender->getName());
                }
            } else if ($pf->get("Moderator") === true) {

                $sender->setDisplayName("§1M§f:§b" . $sender->getName());
                if ($hei->get("heistatus") === true) {
                    $sender->setNameTag($config->get("moderator") . " [§ao§f] §b" . $sender->getName());
                } else if ($hei->get("heistatus") === false or NULL) {
                    $sender->setNameTag($config->get("moderator") . " §b" . $sender->getName());
                }
            } else if ($pf->get("Builder") === true) {

                $sender->setDisplayName("§aB§f:§a" . $sender->getName());
                if ($hei->get("heistatus") === true) {
                    $sender->setNameTag($config->get("builder") . " [§ao§f] §a" . $sender->getName());
                } else if ($hei->get("heistatus") === false or NULL) {
                    $sender->setNameTag($config->get("builder") . " §a" . $sender->getName());
                }
            } else if ($pf->get("Supporter") === true) {

                $sender->setDisplayName("§bS§f:§b" . $sender->getName());
                if ($hei->get("heistatus") === true) {
                    $sender->setNameTag($config->get("supporter") . " [§ao§f] §b" . $sender->getName());
                } else if ($hei->get("heistatus") === false or NULL) {
                    $sender->setNameTag($config->get("supporter") . " §b" . $sender->getName());
                }
            } else if ($pf->get("YouTuber") === true) {

                $sender->setDisplayName("§cY§fT:§f" . $sender->getName());
                if ($hei->get("heistatus") === true) {
                    $sender->setNameTag($config->get("youtuber") . " [§ao§f] " . $sender->getName());
                } else if ($hei->get("heistatus") === false or NULL) {
                    $sender->setNameTag($config->get("youtuber") . $sender->getName());
                }
            } else if ($pf->get("Hero") === true) {

                $sender->setDisplayName("§dH§f:§d" . $sender->getName());
                if ($hei->get("heistatus") === true) {
                    $sender->setNameTag($config->get("hero") . " [§ao§f]§d" . $sender->getName());
                } else if ($hei->get("heistatus") === false or NULL) {
                    $sender->setNameTag($config->get("hero") . " §d" . $sender->getName());
                }
            } else if ($pf->get("Suppremium") === true) {

                $sender->setDisplayName("§3S§f:§3" . $sender->getName());
                if ($hei->get("heistatus") === true) {
                    $sender->setNameTag($config->get("suppremium") . " [§ao§f]§3" . $sender->getName());
                } else if ($hei->get("heistatus") === false or NULL) {
                    $sender->setNameTag($config->get("suppremium") . " §3" . $sender->getName());
                }
            } else if ($pf->get("Premium") === true) {

                $sender->setDisplayName("§6P§f:§6" . $sender->getName());
                if ($hei->get("heistatus") === true) {
                    $sender->setNameTag($config->get("premium") . " [§ao§f]§6" . $sender->getName());
                } else if ($hei->get("heistatus") === false or NULL) {
                    $sender->setNameTag($config->get("premium"). " §6" . $sender->getName());

                }
            }
            $sender->sendMessage($config->get("info") . "Du hast dich wieder entnickt!");
            $pf->set("Nick", false);
            $pf->set("Nickplayer", false);
            $pf->set("nicket". false);
            $pf->set("Nickname", $sender->getName());
            $pf->save();
        }
        return true;
    }
}