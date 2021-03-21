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
use pocketmine\command\ConsoleCommandSender;
use pocketmine\utils\Config;
use pocketmine\Player;
use TheNote\core\Main;


class GruppeCommand extends Command
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("group", $config->get("prefix") . "Setze die Gruppe eines Spielers", "/group", ["gruppe"]);
        $this->setPermission("core.command.group");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($config->get("info") . "/group <Spielername> <default|owner|admin|developer|builder|moderator|supporter|hero|youtuber|suppremium|premium>");
            return true;
        }
        if (isset($args[0])) {
            if (file_exists($this->plugin->getDataFolder() . Main::$gruppefile . $args[0] . ".json")) {
                if (empty($args[1])) {
                    $sender->sendMessage($config->get("info") . "/group <Spielername> <default|owner|admin|developer|builder|moderator|supporter|hero|youtuber|suppremium|premium>");
                    return true;
                }
                $playerfile = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $args[0] . ".json", Config::JSON);
                if ($sender->isOp()) {

                    if (isset($args[1])) {
                        if ($args[1] === null) {
                            $sender->sendMessage($config->get("info") . "Bitte gebe einen Spielernamen ein!");
                        }
                        if (strtolower($args[1]) === "default") {

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
                            $playerfile->set("NickP", true);
                            $playerfile->set("Nickname", $args[0]);
                            $playerfile->save();
                            $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "setgroup $args[0] Spieler");
                            $sender->sendMessage($config->get("info") . "Die Gruppe vom Spieler $args[0] wurde nun zu Spieler gewechselt!");
                            //$args[0]->sendMessage(Main::$prefix ."Deine Gruppe wurde zu §f[§eSpieler§f] §6Gewechselt!");

                        } else if (strtolower($args[1]) === "owner") {

                            $playerfile->set("Default", false);
                            $playerfile->set("Owner", true);
                            $playerfile->set("Admin", false);
                            $playerfile->set("Developer", false);
                            $playerfile->set("Moderator", false);
                            $playerfile->set("Builder", false);
                            $playerfile->set("Supporter", false);
                            $playerfile->set("YouTuber", false);
                            $playerfile->set("Hero", false);
                            $playerfile->set("Suppremium", false);
                            $playerfile->set("Premium", false);
                            $playerfile->set("NickP", true);
                            $playerfile->set("Nickname", $args[0]);
                            $playerfile->save();
                            $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "setgroup $args[0] Owner");
                            $sender->sendMessage($config->get("info") . "Die Gruppe vom Spieler $args[0] wurde nun zu Owner gewechselt!");
                            //$args[0]->sendMessage(Main::$prefix ."Deine Gruppe wurde zu §f[§4Owner§f] §6Gewechselt!");

                        } else if (strtolower($args[1]) === "admin") {

                            $playerfile->set("Default", false);
                            $playerfile->set("Owner", false);
                            $playerfile->set("Admin", true);
                            $playerfile->set("Developer", false);
                            $playerfile->set("Moderator", false);
                            $playerfile->set("Builder", false);
                            $playerfile->set("Supporter", false);
                            $playerfile->set("YouTuber", false);
                            $playerfile->set("Hero", false);
                            $playerfile->set("Suppremium", false);
                            $playerfile->set("Premium", false);
                            $playerfile->set("NickP", true);
                            $playerfile->set("Nickname", $args[0]);
                            $playerfile->save();
                            $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "setgroup $args[0] Administrator");
                            $sender->sendMessage($config->get("info") . "Die Gruppe vom Spieler $args[0] wurde nun zu Admin gewechselt!");
                            //$args[0]->sendMessage(Main::$prefix ."Deine Gruppe wurde zum §f[§cAdmin§f] §6Gewechselt!");

                        } else if (strtolower($args[1]) === "developer") {

                            $playerfile->set("Default", false);
                            $playerfile->set("Owner", false);
                            $playerfile->set("Admin", false);
                            $playerfile->set("Developer", true);
                            $playerfile->set("Moderator", false);
                            $playerfile->set("Builder", false);
                            $playerfile->set("Supporter", false);
                            $playerfile->set("YouTuber", false);
                            $playerfile->set("Hero", false);
                            $playerfile->set("Suppremium", false);
                            $playerfile->set("Premium", false);
                            $playerfile->set("NickP", true);
                            $playerfile->set("Nickname", $args[0]);
                            $playerfile->save();
                            $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "setgroup $args[0] Developer");
                            $sender->sendMessage($config->get("info") . "Die Gruppe vom Spieler $args[0] wurde nun zu Developer gewechselt!");
                            //$args[0]->sendMessage(Main::$prefix ."Deine Gruppe wurde zu §f[§5Developer§f] §6Gewechselt!");

                        } else if (strtolower($args[1]) === "moderator") {

                            $playerfile->set("Default", false);
                            $playerfile->set("Owner", false);
                            $playerfile->set("Admin", false);
                            $playerfile->set("Developer", false);
                            $playerfile->set("Moderator", true);
                            $playerfile->set("Builder", false);
                            $playerfile->set("Supporter", false);
                            $playerfile->set("YouTuber", false);
                            $playerfile->set("Hero", false);
                            $playerfile->set("Suppremium", false);
                            $playerfile->set("Premium", false);
                            $playerfile->set("NickP", true);
                            $playerfile->set("Nickname", $args[0]);
                            $playerfile->save();
                            $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "setgroup $args[0] Moderator");
                            $sender->sendMessage($config->get("info") . "Die Gruppe vom Spieler $args[0] wurde nun zu Moderator gewechselt!");
                            //$args[0]->sendMessage(Main::$prefix ."Deine Gruppe wurde zu §f[§1Moderator§f] §6Gewechselt!");

                        } else if (strtolower($args[1]) === "builder") {

                            $playerfile->set("Default", false);
                            $playerfile->set("Owner", false);
                            $playerfile->set("Admin", false);
                            $playerfile->set("Developer", false);
                            $playerfile->set("Moderator", false);
                            $playerfile->set("Builder", true);
                            $playerfile->set("Supporter", false);
                            $playerfile->set("YouTuber", false);
                            $playerfile->set("Hero", false);
                            $playerfile->set("Suppremium", false);
                            $playerfile->set("Premium", false);
                            $playerfile->set("NickP", true);
                            $playerfile->set("Nickname", $args[0]);
                            $playerfile->save();
                            $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "setgroup $args[0] Builder");
                            $sender->sendMessage($config->get("info") . "Die Gruppe vom Spieler $args[0] wurde nun zu Builder gewechselt!");
                            //$args[0]->sendMessage(Main::$prefix ."Deine Gruppe wurde zu §f[§aBuilder§f] §6Gewechselt!");

                        } else if (strtolower($args[1]) === "supporter") {

                            $playerfile->set("Default", false);
                            $playerfile->set("Owner", false);
                            $playerfile->set("Admin", false);
                            $playerfile->set("Developer", false);
                            $playerfile->set("Moderator", false);
                            $playerfile->set("Builder", false);
                            $playerfile->set("Supporter", true);
                            $playerfile->set("YouTuber", false);
                            $playerfile->set("Hero", false);
                            $playerfile->set("Suppremium", false);
                            $playerfile->set("Premium", false);
                            $playerfile->set("NickP", true);
                            $playerfile->set("Nickname", $args[0]);
                            $playerfile->save();
                            $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "setgroup $args[0] Supporter");
                            $sender->sendMessage($config->get("info") . "Die Gruppe vom Spieler $args[0] wurde nun zu Supporter gewechselt!");
                            //$args[0]->sendMessage(Main::$prefix ."Deine Gruppe wurde zu §f[§bSupporter§f] §6Gewechselt!");

                        } else if (strtolower($args[1]) === "youtuber") {

                            $playerfile->set("Default", false);
                            $playerfile->set("Owner", false);
                            $playerfile->set("Admin", false);
                            $playerfile->set("Developer", false);
                            $playerfile->set("Moderator", false);
                            $playerfile->set("Builder", false);
                            $playerfile->set("Supporter", false);
                            $playerfile->set("YouTuber", true);
                            $playerfile->set("Hero", false);
                            $playerfile->set("Suppremium", false);
                            $playerfile->set("Premium", false);
                            $playerfile->set("NickP", true);
                            $playerfile->set("Nickname", $args[0]);
                            $playerfile->save();
                            $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "setgroup $args[0] YouTuber");
                            $sender->sendMessage($config->get("info") . "Die Gruppe vom Spieler $args[0] wurde nun zu YouTuber gewechselt!");
                            //$args[0]->sendMessage(Main::$prefix ."Deine Gruppe wurde zu §f[§cYou§fTuber] §6Gewechselt!");

                        } else if (strtolower($args[1]) === "hero") {

                            $playerfile->set("Default", false);
                            $playerfile->set("Owner", false);
                            $playerfile->set("Admin", false);
                            $playerfile->set("Developer", false);
                            $playerfile->set("Moderator", false);
                            $playerfile->set("Builder", false);
                            $playerfile->set("Supporter", false);
                            $playerfile->set("YouTuber", false);
                            $playerfile->set("Hero", true);
                            $playerfile->set("Suppremium", false);
                            $playerfile->set("Premium", false);
                            $playerfile->set("NickP", true);
                            $playerfile->set("Nickname", $args[0]);
                            $playerfile->save();
                            $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "setgroup $args[0] Hero");
                            $sender->sendMessage($config->get("info") . "Die Gruppe vom Spieler $args[0] wurde nun zu Hero gewechselt!");
                            //$args[0]->sendMessage(Main::$prefix ."Deine Gruppe wurde zu §f[§dHero§f] §6Gewechselt!");

                        } else if (strtolower($args[1]) === "suppremium") {

                            $playerfile->set("Default", false);
                            $playerfile->set("Owner", false);
                            $playerfile->set("Admin", false);
                            $playerfile->set("Developer", false);
                            $playerfile->set("Moderator", false);
                            $playerfile->set("Builder", false);
                            $playerfile->set("Supporter", false);
                            $playerfile->set("YouTuber", false);
                            $playerfile->set("Hero", false);
                            $playerfile->set("Suppremium", true);
                            $playerfile->set("Premium", false);
                            $playerfile->set("NickP", true);
                            $playerfile->set("Nickname", $args[0]);
                            $playerfile->save();
                            $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "setgroup $args[0] Suppremium");
                            $sender->sendMessage($config->get("info") . "Die Gruppe vom Spieler $args[0] wurde nun zu Suppremium gewechselt!");
                            //$args[0]->sendMessage(Main::$prefix ."Deine Gruppe wurde zu §f[§3Suppremium§f] §6Gewechselt!");

                        } else if (strtolower($args[1]) === "premium") {

                            $playerfile->set("Default", false);
                            $playerfile->set("Owner", false);
                            $playerfile->set("Admin", false);
                            $playerfile->set("Developer", false);
                            $playerfile->set("Moderator", false);
                            $playerfile->set("Builder", false);
                            $playerfile->set("Supporter", false);
                            $playerfile->set("YouTuber", false);
                            $playerfile->set("Hero", false);
                            $playerfile->set("Suppremium", false);
                            $playerfile->set("Premium", true);
                            $playerfile->set("NickP", true);
                            $playerfile->set("Nickname", $args[0]);
                            $playerfile->save();
                            $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "setgroup $args[0] Premium");
                            $sender->sendMessage($config->get("info") . "Die Gruppe vom Spieler $args[0] wurde nun zu Premium gewechselt!");
                            //$args[0]->sendMessage(Main::$prefix ."Deine Gruppe wurde zu §f[§6Premium§f] §6Gewechselt!");
                        }
                    }
                }
            }
        }
        return true;
    }
}