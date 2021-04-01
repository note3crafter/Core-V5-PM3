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
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\Main;

class GruppeCommand extends Command
{
    public $plugin;

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
        $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
        $playerdata = new Config($this->plugin->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);

        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage("§f=========== " . $config->get("gruppe") . "§f===========");
            $sender->sendMessage("§6/group add {groupname}");
            $sender->sendMessage("§6/group list");
            $sender->sendMessage("§6/group remove {groupname}");
            $sender->sendMessage("§6/group addperm {groupname} {permission}");
            $sender->sendMessage("§6/group removeperm {groupname} {permission}");
            $sender->sendMessage("§6/group default {groupname}");
            $sender->sendMessage("§6/group set {player} {groupname}");
            $sender->sendMessage("§6/group adduserperm {player} {permission}");
            $sender->sendMessage("§6/group removeuserperm {player} {permission}");
            $sender->sendMessage("§6/group listgroupperm {groupname}");
            $sender->sendMessage("§6/group listuserperm {groupname}");
            return false;
        }
        if ($sender->hasPermission("core.command.group")) {
            if ($args[0] == "add") {
                if (empty($args[0])) {
                    $sender->sendMessage($config->get("info") . "Nutze : /group add {groupname}");
                    return false;
                }
                if (empty($args[1])) {
                    $sender->sendMessage($config->get("info") . "Nutze : /group add {groupname}");
                    return false;
                }
                $groupName = $args[1];
                if ($groups->getNested("Groups." . $groupName) !== null) {
                    $sender->sendMessage($config->get("error") . "Die Gruppe gibt es Bereits!");
                    return false;
                }
                $groups->setNested("Groups." . $groupName . ".format", "$groupName : {heirat} {clan} {nickname} : {msg}");
                $groups->setNested("Groups." . $groupName . ".nametag", "$groupName §7: §8{name}");
                $groups->setNested("Groups." . $groupName . ".displayname", "$groupName §7: §8{name}");
                $groups->setNested("Groups." . $groupName . ".permissions", ["CoreV5"]);
                $groups->save();
                $sender->sendMessage("gruppe $groupName hinzugefügt");
            }
            if ($args[0] == "list") {
                if (empty($args[0])) {
                    $sender->sendMessage($config->get("info") ."Nutze : /group list");
                    return false;
                }
                $list = [];
                $grouplist = $groups->get("Groups");
                foreach ($grouplist as $name => $data) $list[] = $name;
                $sender->sendMessage($config->get("gruppe") . "\n§8- §7" . implode("\n§8-§7 ", $list));
            }
            if ($args[0] == "remove") {
                if (empty($args[0])) {
                    $sender->sendMessage($config->get("info") ."Nutze : /group remove {groupname}");
                    return false;
                }
                if (empty($args[1])) {
                    $sender->sendMessage($config->get("info") ."Nutze : /group remove {groupname}");
                    return false;
                }
                $groupName = $args[1];
                if ($groups->getNested("Groups." . $groupName) == null) {
                    $sender->sendMessage($config->get("error") . "Die Gruppe gibts nicht... überprüfe deine Eingabe!");
                    return true;
                }
                $groups->removeNested("Groups." . $groupName);
                $groups->save();
                $sender->sendMessage($config->get("group") . "Die Gruppe $groupName wurde erfolgreich entfernt.");
            }
            if ($args[0] == "addperm") {
                if (empty($args[0])) {
                    $sender->sendMessage($config->get("info") . "Nutze : /group addperm {groupname}");
                    return false;
                }
                if (empty($args[1])) {
                    $sender->sendMessage($config->get("info") ."Nutze : /group addperm {groupname}");
                    return false;
                }
                $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
                $groupName = $args[1];
                if ($groups->getNested("Groups." . $groupName) == null) {
                    $sender->sendMessage($config->get("error") . "Die Gruppe gibts nicht... überprüfe deine Eingabe!");
                    return true;
                }
                $perms = $groups->getNested("Groups.{$groupName}.permissions", []);
                $permission = $args[2];
                $perms[] = $permission;
                $groups->setNested("Groups.{$groupName}.permissions", $perms);
                $groups->save();
                $sender->sendMessage($config->get("gruppe") . "die permissions" . $args[2] . "von gruppe" . $args[1] . "wurde hinzugefügt");
            }
            if ($args[0] == "removeperm") {
                if (empty($args[0])) {
                    $sender->sendMessage($config->get("info") ."Nutze : /group removeperm {groupname}");
                    return false;
                }
                if (empty($args[1])) {
                    $sender->sendMessage($config->get("info") ."Nutze : /group removeperm {groupname}");
                    return false;
                }
                $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
                $groupName = $args[1];
                if ($groups->getNested("Groups." . $groupName) == null) {
                    $sender->sendMessage($config->get("error") . "Die Gruppe gibts nicht... überprüfe deine Eingabe!");
                    return true;
                }
                $perms = $groups->getNested("Groups.{$groupName}.permissions", []);
                $permission = $args[2];
                if (!in_array($permission, $perms)) {
                    $sender->sendMessage($config->get("error") . "Die Permission gibts nicht! Überprüfe deine Eingabe.");
                    return true;
                }
                unset($perms[array_search($permission, $perms)]);
                $groups->setNested("Groups.{$groupName}.permissions", $perms);
                $groups->save();
                $sender->sendMessage($config->get("group") . "Die Permission " . $args[2] . " wurde von der Gruppe " . $args[1] . " entfernt.");
            }
            if ($args[0] == "default") {
                if (empty($args[0])) {
                    $sender->sendMessage($config->get("info") ."Nutze : /group default {groupname}");
                    return true;
                }
                if (empty($args[1])) {
                    $sender->sendMessage($config->get("info") ."Nutze : /group default {groupname}");
                    return true;
                }
                if ($groups->getNested("Groups." . $args[1]) == null) {
                    $sender->sendMessage($config->get("error") . "Die Gruppe gibts nicht... überprüfe deine Eingabe!");
                    return true;
                }
                $groups->set("DefaultGroup", $args[1]);
                $groups->save();
                $sender->sendMessage($config->get("gruppe") . "Die Gruppe : §f[$args[1]] §6wurde erfolgreich als Standartgruppe ausgewählt.");
            }
            if ($args[0] == "set") {
                if (empty($args[0])) {
                    $sender->sendMessage($config->get("info") . "Nutze : /group set {player} {groupname}");
                    return false;
                }
                if (empty($args[1])) {
                    $sender->sendMessage($config->get("info") . "Nutze : /group set {player} {groupname}");
                    return false;
                }
                if (empty($args[2])) {
                    $sender->sendMessage($config->get("info") . "Nutze : /group set {player} {groupname}");
                    return false;
                }
                $victim = $this->plugin->getServer()->getPlayer($args[0]);
                $target = Server::getInstance()->getPlayer(strtolower($args[1]));
                if ($target == null) {
                    $sender->sendMessage($config->get("error") . "Der Spieler ist nicht Online!");
                    return false;
                }
                $name = $target->getName();
                $group = $args[2];
                if ($groups->getNested("Groups." . $group) == null) {
                    $sender->sendMessage($config->get("error") . "Die Gruppe gibts nicht... überprüfe deine Eingabe!");
                    return true;
                }
                $playerdata->setNested($name . ".group", $group);
                $playergroup = $playerdata->getNested($name.".group");
                $nametag = str_replace("{name}", $target->getName(), $groups->getNested("Groups.{$playergroup}.nametag1"));
                $displayname = str_replace("{name}", $target->getName(), $groups->getNested("Groups.{$playergroup}.displayname"));
                $target->setNameTag($nametag);
                $target->setDisplayName($displayname);
                $playerdata->setNested($name . ".group", $group);
                $playerdata->save();
                /*$permissionlist = (array)$groups->getNested("Groups.".$playergroup.".permissions", []);
                foreach($permissionlist as $name => $data) {
                    $target->addAttachment($this->plugin)->setPermission($data, true);
                }*/
                $target->kick($config->get("gruppe") . "§6Deine Gruppe wurde zu : $group §6geändert!\n§6Rejoine einfach den Server!", false);
                $sender->sendMessage("gruppe von $victim wurde zu $group geändert");
            }
            if ($args[0] == "adduserperm") {
                $sender->sendMessage("Comming Soon...");
            }
            if ($args[0] == "removeuserperm") {
                $sender->sendMessage("Comming Soon...");
            }
            if ($args[0] == "listperms") {
                $sender->sendMessage("Comming Soon...");
            }
        }
        return true;
    }
}