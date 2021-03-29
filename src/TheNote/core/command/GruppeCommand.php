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
            $sender->sendMessage($config->get("info") . "/group <list|remove|addperm|removeperm|adduserperm|removeuserperm>");
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
                                $sender->sendMessage($config->get("info") . "§eDie Gruppe vom Spieler $args[0] wurde nun zu Spieler gewechselt!");
                                $victim->sendMessage($config->get("info") . "§eDeine Gruppe wurde zu " . $config->get("spieler") . " §eGewechselt!");
                            } elseif ($cfg->get("extern") == false) {
                                $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
                                $playerdata = new Config($this->plugin->getDataFolder(). Main::$cloud . "players.yml", Config::YAML);
                                if($groups->getNested("Groups."."Default") == null){
                                    $groups->setNested("Groups." . "Default" . ".permissions", ["CoreV5"]);
                                    $groups->save();
                                    return true;
                                }
                                $name = $victim->getName();
                                $playerdata->setNested($name.".group", "Default");
                                $playerdata->save();
                                if($cfg->get("rejoinallowed") == true) {
                                    $victim->transfer($cfg->get("rejoinserverip"), $cfg->get("rejoinserverport"));
                                } else {
                                    $victim->kick($config->get("info") . "\n§eDu wurdest gekick!\n §cGrund§f: §§eDeine Gruppe wurde zu " . $config->get("spieler") . " §eGewechselt!", false);
                                }
                                $sender->sendMessage($config->get("info") . "§eDie Gruppe vom Spieler $args[0] wurde nun zu Spieler gewechselt!");
                            }

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
                                $sender->sendMessage($config->get("info") . "§eDie Gruppe vom Spieler $args[0] wurde nun zu Owner gewechselt!");
                                $victim->sendMessage($config->get("info") . "§eDeine Gruppe wurde zu " . $config->get("owner") . " §6Gewechselt!");
                            } elseif ($cfg->get("extern") == false) {
                                $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
                                $playerdata = new Config($this->plugin->getDataFolder(). Main::$cloud . "players.yml", Config::YAML);
                                if($groups->getNested("Groups."."Owner") == null){
                                    $groups->setNested("Groups." . "Owner" . ".permissions", ["CoreV5"]);
                                    $groups->save();
                                    return true;
                                }
                                $name = $victim->getName();
                                $playerdata->setNested($name.".group", "Owner");
                                $playerdata->save();
                                if($cfg->get("rejoinallowed") == true) {
                                    $victim->transfer($cfg->get("rejoinserverip"), $cfg->get("rejoinserverport"));
                                } else {
                                    $victim->kick($config->get("info") . "\n§eDu wurdest gekick!\n §cGrund§f: §eDeine Gruppe wurde zu " . $config->get("owner") . " §6Gewechselt!", false);
                                }
                                $sender->sendMessage($config->get("info") . "§eDie Gruppe vom Spieler $args[0] wurde nun zu Owner gewechselt!");
                            }

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
                                $sender->sendMessage($config->get("info") . "§eDie Gruppe vom Spieler $args[0] wurde nun zu Admin gewechselt!");
                                $victim->sendMessage($config->get("info") . "§eDeine Gruppe wurde zu " . $config->get("admin") . " §6Gewechselt!");
                            } elseif ($cfg->get("extern") == false) {
                                $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
                                $playerdata = new Config($this->plugin->getDataFolder(). Main::$cloud . "players.yml", Config::YAML);
                                if($groups->getNested("Groups."."Admin") == null){
                                    $groups->setNested("Groups." . "Admin" . ".permissions", ["CoreV5"]);
                                    $groups->save();
                                    return true;
                                }
                                $name = $victim->getName();
                                $playerdata->setNested($name.".group", "Admin");
                                $playerdata->save();
                                if($cfg->get("rejoinallowed") == true) {
                                    $victim->transfer($cfg->get("rejoinserverip"), $cfg->get("rejoinserverport"));
                                } else {
                                    $victim->kick($config->get("info") . "\n§eDu wurdest gekick!\n §cGrund§f: §§eDeine Gruppe wurde zu " . $config->get("admin") . " §eGewechselt!", false);
                                }
                                $sender->sendMessage($config->get("info") . "§eDie Gruppe vom Spieler $args[0] wurde nun zu Admin gewechselt!");
                            }

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
                                $sender->sendMessage($config->get("info") . "§eDie Gruppe vom Spieler $args[0] wurde nun zu Developer gewechselt!");
                                $victim->sendMessage($config->get("info") . "§eDeine Gruppe wurde zu " . $config->get("developer") . " §6Gewechselt!");
                            } elseif ($cfg->get("extern") == false) {
                                $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
                                $playerdata = new Config($this->plugin->getDataFolder(). Main::$cloud . "players.yml", Config::YAML);
                                if($groups->getNested("Groups."."Developer") == null){
                                    $groups->setNested("Groups." . "Developer" . ".permissions", ["CoreV5"]);
                                    $groups->save();
                                    return true;
                                }
                                $name = $victim->getName();
                                $playerdata->setNested($name.".group", "Developer");
                                $playerdata->save();
                                if($cfg->get("rejoinallowed") == true) {
                                    $victim->transfer($cfg->get("rejoinserverip"), $cfg->get("rejoinserverport"));
                                } else {
                                    $victim->kick($config->get("info") . "\n§eDu wurdest gekick!\n §cGrund§f: §§eDeine Gruppe wurde zu " . $config->get("developer") . " §eGewechselt!", false);
                                }
                                $sender->sendMessage($config->get("info") . "§eDie Gruppe vom Spieler $args[0] wurde nun zu Developer gewechselt!");
                            }

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
                                $sender->sendMessage($config->get("info") . "§eDie Gruppe vom Spieler $args[0] wurde nun zu Moderator gewechselt!");
                                $victim->sendMessage($config->get("info") . "§eDeine Gruppe wurde zu " . $config->get("moderator") . " §6Gewechselt!");
                            } elseif ($cfg->get("extern") == false) {
                                $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
                                $playerdata = new Config($this->plugin->getDataFolder(). Main::$cloud . "players.yml", Config::YAML);
                                if($groups->getNested("Groups."."Moderator") == null){
                                    $groups->setNested("Groups." . "Moderator" . ".permissions", ["CoreV5"]);
                                    $groups->save();
                                    return true;
                                }
                                $name = $victim->getName();
                                $playerdata->setNested($name.".group", "Moderator");
                                $playerdata->save();
                                if($cfg->get("rejoinallowed") == true) {
                                    $victim->transfer($cfg->get("rejoinserverip"), $cfg->get("rejoinserverport"));
                                } else {
                                    $victim->kick($config->get("info") . "\n§eDu wurdest gekick!\n §cGrund§f: §§eDeine Gruppe wurde zu " . $config->get("moderator") . " §eGewechselt!", false);
                                }
                                $sender->sendMessage($config->get("info") . "§eDie Gruppe vom Spieler $args[0] wurde nun zu Moderator gewechselt!");
                            }

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
                                $sender->sendMessage($config->get("info") . "§eDie Gruppe vom Spieler $args[0] wurde nun zu Builder gewechselt!");
                                $victim->sendMessage($config->get("info") . "§eDeine Gruppe wurde zu " . $config->get("builder") . " §6Gewechselt!");
                            } elseif ($cfg->get("extern") == false) {
                                $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
                                $playerdata = new Config($this->plugin->getDataFolder(). Main::$cloud . "players.yml", Config::YAML);
                                if($groups->getNested("Groups."."Builder") == null){
                                    $groups->setNested("Groups." . "Builder" . ".permissions", ["CoreV5"]);
                                    $groups->save();
                                    return true;
                                }
                                $name = $victim->getName();
                                $playerdata->setNested($name.".group", "Builder");
                                $playerdata->save();
                                if($cfg->get("rejoinallowed") == true) {
                                    $victim->transfer($cfg->get("rejoinserverip"), $cfg->get("rejoinserverport"));
                                } else {
                                    $victim->kick($config->get("info") . "\n§eDu wurdest gekick!\n §cGrund§f: §§eDeine Gruppe wurde zu " . $config->get("builder") . " §eGewechselt!", false);
                                }
                                $sender->sendMessage($config->get("info") . "§eDie Gruppe vom Spieler $args[0] wurde nun zu Builder gewechselt!");
                            }

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
                                $sender->sendMessage($config->get("info") . "§eDie Gruppe vom Spieler $args[0] wurde nun zu Supporter gewechselt!");
                                $victim->sendMessage($config->get("info") . "§eDeine Gruppe wurde zu " . $config->get("supporter") . " §6Gewechselt!");
                            } elseif ($cfg->get("extern") == false) {
                                $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
                                $playerdata = new Config($this->plugin->getDataFolder(). Main::$cloud . "players.yml", Config::YAML);
                                if($groups->getNested("Groups."."Supporter") == null){
                                    $groups->setNested("Groups." . "Supporter" . ".permissions", ["CoreV5"]);
                                    $groups->save();
                                    return true;
                                }
                                $name = $victim->getName();
                                $playerdata->setNested($name.".group", "Supporter");
                                $playerdata->save();
                                if($cfg->get("rejoinallowed") == true) {
                                    $victim->transfer($cfg->get("rejoinserverip"), $cfg->get("rejoinserverport"));
                                } else {
                                    $victim->kick($config->get("info") . "\n§eDu wurdest gekick!\n §cGrund§f: §eDeine Gruppe wurde zu " . $config->get("supporter") . " §eGewechselt!", false);
                                }
                                $sender->sendMessage($config->get("info") . "§eDie Gruppe vom Spieler $args[0] wurde nun zu Supporter gewechselt!");
                            }

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
                                $sender->sendMessage($config->get("info") . "§eDie Gruppe vom Spieler $args[0] wurde nun zu YouTuber gewechselt!");
                                $victim->sendMessage($config->get("info") . "§eDeine Gruppe wurde zu " . $config->get("youtuber") . " §6Gewechselt!");
                            } elseif ($cfg->get("extern") == false) {
                                $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
                                $playerdata = new Config($this->plugin->getDataFolder(). Main::$cloud . "players.yml", Config::YAML);
                                if($groups->getNested("Groups."."YouTuber") == null){
                                    $groups->setNested("Groups." . "YouTuber" . ".permissions", ["CoreV5"]);
                                    $groups->save();
                                    return true;
                                }
                                $name = $victim->getName();
                                $playerdata->setNested($name.".group", "YouTuber");
                                $playerdata->save();
                                if($cfg->get("rejoinallowed") == true) {
                                    $victim->transfer($cfg->get("rejoinserverip"), $cfg->get("rejoinserverport"));
                                } else {
                                    $victim->kick($config->get("info") . "\n§eDu wurdest gekick!\n §cGrund§f: §eDeine Gruppe wurde zu " . $config->get("youtuber") . " §6Gewechselt!", false);
                                }
                                $sender->sendMessage($config->get("info") . "Die Gruppe vom Spieler $args[0] wurde nun zu YouTuber gewechselt!");
                            }

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
                                $sender->sendMessage($config->get("info") . "§eDie Gruppe vom Spieler $args[0] wurde nun zu Hero gewechselt!");
                                $victim->sendMessage($config->get("info") . "§eDeine Gruppe wurde zu " . $config->get("hero") . " §6Gewechselt!");
                            } elseif ($cfg->get("extern") == false) {
                                $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
                                $playerdata = new Config($this->plugin->getDataFolder(). Main::$cloud . "players.yml", Config::YAML);
                                if($groups->getNested("Groups."."Hero") == null){
                                    $groups->setNested("Groups." . "Hero" . ".permissions", ["CoreV5"]);
                                    $groups->save();
                                    return true;
                                }
                                $name = $victim->getName();
                                $playerdata->setNested($name.".group", "Hero");
                                $playerdata->save();
                                if($cfg->get("rejoinallowed") == true) {
                                    $victim->transfer($cfg->get("rejoinserverip"), $cfg->get("rejoinserverport"));
                                } else {
                                    $victim->kick($config->get("info") . "\n§eDu wurdest gekick!\n §cGrund§f: §eDeine Gruppe wurde zu " . $config->get("hero") . " §6Gewechselt!", false);
                                }
                                $sender->sendMessage($config->get("info") . "§eDie Gruppe vom Spieler $args[0] wurde nun zu Hero gewechselt!");
                            }

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
                                $sender->sendMessage($config->get("info") . "§eDie Gruppe vom Spieler $args[0] wurde nun zu Suppremium gewechselt!");
                                $victim->sendMessage($config->get("info") . "§eDeine Gruppe wurde zu " . $config->get("suppremium") . " §6Gewechselt!");
                            } elseif ($cfg->get("extern") == false) {
                                $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
                                $playerdata = new Config($this->plugin->getDataFolder(). Main::$cloud . "players.yml", Config::YAML);
                                if($groups->getNested("Groups."."Suppremium") == null){
                                    $groups->setNested("Groups." . "Suppremium" . ".permissions", ["CoreV5"]);
                                    $groups->save();
                                    return true;
                                }
                                $name = $victim->getName();
                                $playerdata->setNested($name.".group", "Suppremium");
                                $playerdata->save();
                                if($cfg->get("rejoinallowed") == true) {
                                    $victim->transfer($cfg->get("rejoinserverip"), $cfg->get("rejoinserverport"));
                                } else {
                                    $victim->kick($config->get("info") . "\n§eDu wurdest gekick!\n §cGrund§f: §eDeine Gruppe wurde zu " . $config->get("suppremium") . " §6Gewechselt!", false);
                                }
                                $sender->sendMessage($config->get("info") . "§eDie Gruppe vom Spieler $args[0] wurde nun zu Suppremium gewechselt!");
                            }

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
                                $sender->sendMessage($config->get("info") . "§eDie Gruppe vom Spieler $args[0] wurde nun zu Premium gewechselt!");
                                $victim->sendMessage($config->get("info") . "§eDeine Gruppe wurde zu " . $config->get("premium") . " §6Gewechselt!");
                            } elseif ($cfg->get("extern") == false) {
                                $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
                                $playerdata = new Config($this->plugin->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
                                if ($groups->getNested("Groups." . "Premium") == null) {
                                    $groups->setNested("Groups." . "Premium" . ".permissions", ["CoreV5"]);
                                    $groups->save();
                                    return true;
                                }
                                $name = $victim->getName();
                                $playerdata->setNested($name . ".group", "Premium");
                                $playerdata->save();
                                if ($cfg->get("rejoinallowed") == true) {
                                    $victim->transfer($cfg->get("rejoinserverip"), $cfg->get("rejoinserverport"));
                                } else {
                                    $victim->kick($config->get("info") . "\n§eDu wurdest gekick!\n §cGrund§f: §eDeine Gruppe wurde zu " . $config->get("premium") . " §6Gewechselt!!", false);
                                }
                                $sender->sendMessage($config->get("info") . "§eDie Gruppe vom Spieler $args[0] wurde nun zu Premium gewechselt!");
                            }
                        }
                    }
                }
            }
            if($args[0] == "list"){
                $groups = new Config($this->plugin->getDataFolder(). Main::$cloud . "groups.yml", Config::YAML);
                $list = [];
                $grouplist = $groups->get("Groups");
                foreach($grouplist as $name => $data) $list[] = $name;
                $sender->sendMessage($config->get("info") . "\n§8- §7" . implode("\n§8-§7 ", $list));
            }
            if($args[0] == "remove"){
                $sender->sendMessage("Comming Soon...");
            }
            if($args[0] == "addperm"){
                $groups = new Config($this->plugin->getDataFolder(). Main::$cloud . "groups.yml", Config::YAML);
                $groupName = $args[1];
                if($groups->getNested("Groups.".$groupName) == null){
                    $sender->sendMessage("Gruppe gibts nicht");
                    return true;
                }
                $perms = $groups->getNested("Groups.{$groupName}.permissions",[]);
                $permission = $args[2];
                $perms[] = $permission;
                $groups->setNested("Groups.{$groupName}.permissions", $perms);
                $groups->save();
                $sender->sendMessage("die permissions" . $args[2] . "von gruppe" . $args[1] . "wurde hinzugefügt");

            }
            if($args[0] == "removeperm"){
                $groups = new Config($this->plugin->getDataFolder(). Main::$cloud . "groups.yml", Config::YAML);
                $groupName = $args[1];
                if($groups->getNested("Groups.".$groupName) == null){
                    $sender->sendMessage("Gruppe gibts nicht");
                    return true;
                }
                $perms = $groups->getNested("Groups.{$groupName}.permissions",[]);
                $permission = $args[2];
                if(!in_array($permission, $perms)){
                    $sender->sendMessage("permissions gibts nicht");
                    return true;
                }
                unset($perms[array_search($permission, $perms)]);
                $groups->setNested("Groups.{$groupName}.permissions", $perms);
                $groups->save();
                $sender->sendMessage("die permissions" . $args[2] . "von gruppe" . $args[1] . "wurde entfernt");
            }
            if($args[0] == "adduserperm"){
                $sender->sendMessage("Comming Soon...");
            }
            if($args[0] == "removeuserperm"){
                $sender->sendMessage("Comming Soon...");
            }
        }
        return true;
    }
}