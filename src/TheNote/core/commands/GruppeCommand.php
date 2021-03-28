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
use pocketmine\item\Book;
use pocketmine\Server;
use pocketmine\utils\Config;
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

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $cfg = new Config($this->plugin->getDataFolder() . Main::$setup . "groupcommands.yml");

        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($config->get("info") . "/group {Spielername} <default|owner|admin|developer|builder|moderator|supporter|hero|youtuber|suppremium|premium>");
            return false;
        }
        if (isset($args[0])) {
            if (file_exists($this->plugin->getDataFolder() . Main::$gruppefile . $args[0] . ".json")) {
                if (empty($args[1])) {
                    $sender->sendMessage($config->get("info") . "/group {Spielername} <default|owner|admin|developer|builder|moderator|supporter|hero|youtuber|suppremium|premium>");
                    return false;
                }
                $playerfile = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $args[0] . ".json", Config::JSON);

                if ($sender->isOp()) {
                    if (isset($args[1])) {
                        $victim = $this->plugin->getServer()->getPlayer($args[0]);
                        $target = Server::getInstance()->getPlayer(strtolower($args[0]));
                        if ($args[1] === null) {
                            $sender->sendMessage($config->get("info") . "Bitte gebe einen Spielernamen ein!");
                            return false;
                        }
                        if ($target == null) {
                            $sender->sendMessage($config->get("error") . "Der Spieler ist nicht Online!");
                            return false;
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

                            if ($cfg->get("extern") == true) {
                                $sender2 = $this->plugin->getServer()->getPlayer(strtolower($args[0]));
                                $command = $cfg->get("default");
                                $command = str_replace("{player}", strtolower($sender2->getName()), $command);
                                $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), $command);
                            }

                            $sender->sendMessage($config->get("info") . "§eDie Gruppe vom Spieler $args[0] wurde nun zu Spieler gewechselt!");
                            $victim->sendMessage($config->get("info") . "§eDeine Gruppe wurde zu §f[§eSpieler§f] §eGewechselt!");

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

                            if ($cfg->get("extern") == true) {
                                $sender2 = $this->plugin->getServer()->getPlayer(strtolower($args[0]));
                                $command = $cfg->get("owner");
                                $command = str_replace("{player}", strtolower($sender2->getName()), $command);
                                $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), $command);
                            }

                            $sender->sendMessage($config->get("info") . "§eDie Gruppe vom Spieler $args[0] wurde nun zu Owner gewechselt!");
                            $victim->sendMessage($config->get("info") . "§eDeine Gruppe wurde zu §f[§4Owner§f] §6Gewechselt!");

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

                            if ($cfg->get("extern") == true) {

                                $sender2 = $this->plugin->getServer()->getPlayer(strtolower($args[0]));
                                $command = $cfg->get("admin");
                                $command = str_replace("{player}", strtolower($sender2->getName()), $command);
                                $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), $command);
                            }

                            $sender->sendMessage($config->get("info") . "Die Gruppe vom Spieler $args[0] wurde nun zu Admin gewechselt!");
                            $victim->sendMessage($config->get("info") . "Deine Gruppe wurde zum §f[§cAdmin§f] §6Gewechselt!");

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

                            if ($cfg->get("extern") == true) {

                                $sender2 = $this->plugin->getServer()->getPlayer(strtolower($args[0]));
                                $command = $cfg->get("developer");
                                $command = str_replace("{player}", strtolower($sender2->getName()), $command);
                                $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), $command);
                            }

                            $sender->sendMessage($config->get("info") . "Die Gruppe vom Spieler $args[0] wurde nun zu Developer gewechselt!");
                            $victim->sendMessage($config->get("info") . "Deine Gruppe wurde zu §f[§5Developer§f] §6Gewechselt!");

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

                            if ($cfg->get("extern") == true) {

                                $sender2 = $this->plugin->getServer()->getPlayer(strtolower($args[0]));
                                $command = $cfg->get("moderator");
                                $command = str_replace("{player}", strtolower($sender2->getName()), $command);
                                $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), $command);
                            }

                            $sender->sendMessage($config->get("info") . "Die Gruppe vom Spieler $args[0] wurde nun zu Moderator gewechselt!");
                            $victim->sendMessage($config->get("info") . "Deine Gruppe wurde zu §f[§1Moderator§f] §6Gewechselt!");

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

                            if ($cfg->get("extern") == true) {

                                $sender2 = $this->plugin->getServer()->getPlayer(strtolower($args[0]));
                                $command = $cfg->get("builder");
                                $command = str_replace("{player}", strtolower($sender2->getName()), $command);
                                $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), $command);
                            }

                            $sender->sendMessage($config->get("info") . "Die Gruppe vom Spieler $args[0] wurde nun zu Builder gewechselt!");
                            $victim->sendMessage($config->get("info") . "Deine Gruppe wurde zu §f[§aBuilder§f] §6Gewechselt!");

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

                            if ($cfg->get("extern") == true) {

                                $sender2 = $this->plugin->getServer()->getPlayer(strtolower($args[0]));
                                $command = $cfg->get("supporter");
                                $command = str_replace("{player}", strtolower($sender2->getName()), $command);
                                $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), $command);
                            }

                            $sender->sendMessage($config->get("info") . "Die Gruppe vom Spieler $args[0] wurde nun zu Supporter gewechselt!");
                            $victim->sendMessage($config->get("info") . "Deine Gruppe wurde zu §f[§bSupporter§f] §6Gewechselt!");

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

                            if ($cfg->get("extern") == true) {

                                $sender2 = $this->plugin->getServer()->getPlayer(strtolower($args[0]));
                                $command = $cfg->get("youtuber");
                                $command = str_replace("{player}", strtolower($sender2->getName()), $command);
                                $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), $command);
                            }

                            $sender->sendMessage($config->get("info") . "Die Gruppe vom Spieler $args[0] wurde nun zu YouTuber gewechselt!");
                            $victim->sendMessage($config->get("info") . "Deine Gruppe wurde zu §f[§cYou§fTuber] §6Gewechselt!");

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

                            if ($cfg->get("extern") == true) {

                                $sender2 = $this->plugin->getServer()->getPlayer(strtolower($args[0]));
                                $command = $cfg->get("hero");
                                $command = str_replace("{player}", strtolower($sender2->getName()), $command);
                                $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), $command);
                            }

                            $sender->sendMessage($config->get("info") . "Die Gruppe vom Spieler $args[0] wurde nun zu Hero gewechselt!");
                            $victim->sendMessage($config->get("info") . "Deine Gruppe wurde zu §f[§dHero§f] §6Gewechselt!");

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

                            if ($cfg->get("extern") == true) {

                                $sender2 = $this->plugin->getServer()->getPlayer(strtolower($args[0]));
                                $command = $cfg->get("suppremium");
                                $command = str_replace("{player}", strtolower($sender2->getName()), $command);
                                $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), $command);
                            }

                            $sender->sendMessage($config->get("info") . "Die Gruppe vom Spieler $args[0] wurde nun zu Suppremium gewechselt!");
                            $victim->sendMessage($config->get("info") . "Deine Gruppe wurde zu §f[§3Suppremium§f] §6Gewechselt!");

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

                            if ($cfg->get("extern") == true) {

                                $sender2 = $this->plugin->getServer()->getPlayer(strtolower($args[0]));
                                $command = $cfg->get("premium");
                                $command = str_replace("{player}", strtolower($sender2->getName()), $command);
                                $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), $command);
                            }

                            $sender->sendMessage($config->get("info") . "Die Gruppe vom Spieler $args[0] wurde nun zu Premium gewechselt!");
                            $victim->sendMessage($config->get("info") . "Deine Gruppe wurde zu §f[§6Premium§f] §6Gewechselt!");
                        }
                    }
                }
            }
        }
        return true;
    }
}