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

class NickCommand extends Command
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("nick", $config->get("prefix") . "Ändere dein §eNickname", "/nick <Name>");
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
        $playerfile = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $sender->getName() . ".json", Config::JSON);
        if ($playerfile->get("NickP") === false or NULL) {
            $sender->sendMessage($config->get("error") . "§cDazu bist du nicht Berechtigt§f!");
            return true;
        }
        if ($playerfile->get("Nick") === true) {
            $sender->sendMessage($config->get("error") . "Du hast bereits ein Nickname");
            return true;
        }
        If (empty($args[0])) {
            $sender->sendMessage($config->get("info") . "§eBitte benutze /nick <name>");
            return true;
        }
        If (isset($args[0])) {
            if ($playerfile->get("NickP") === true) {
                $nickname = $args[0];
                if ($playerfile->get("Default") === true) {
                    $sender->setDisplayName("§eS§f:§f" . $nickname . "§7");
                    $sender->setNameTag($config->get("spieler") . " §f" . $nickname . "§7");
                } else if ($playerfile->get("Owner") === true) {
                    $sender->setDisplayName("§4O§f:§c" . $nickname . "§c");
                    $sender->setNameTag($config->get("owner") . " §c" . $nickname . "§c");
                } else if ($playerfile->get("Admin") === true) {
                    $sender->setDisplayName("§cA§f:§c" . $nickname . "§c");
                    $sender->setNameTag($config->get("admin") . " §c" . $nickname . "§c");
                } else if ($playerfile->get("Developer") === true) {
                    $sender->setDisplayName("§dD§f:§d" . $nickname . "§d");
                    $sender->setNameTag($config->get("developer") . " §d" . $nickname . "§d");
                } else if ($playerfile->get("Moderator") === true) {
                    $sender->setDisplayName("§1M§f:§b" . $nickname . "§b");
                    $sender->setNameTag($config->get("moderator") . " §b" . $nickname . "§b");
                } else if ($playerfile->get("Builder") === true) {
                    $sender->setDisplayName("§aB§f:§a" . $nickname . "§a");
                    $sender->setNameTag($config->get("builder") . " §a" . $nickname . "§a");
                } else if ($playerfile->get("Supporter") === true) {
                    $sender->setDisplayName("§bS§f:§b" . $nickname . "§b");
                    $sender->setNameTag($config->get("supporter") . " §b" . $nickname . "§b");
                } else if ($playerfile->get("YouTuber") === true) {
                    $sender->setDisplayName("§cY§fT:§f" . $nickname . "§f");
                    $sender->setNameTag($config->get("youtuber") . " §f" . $nickname . "§f");
                } else if ($playerfile->get("Hero") === true) {
                    $sender->setDisplayName("§dH§f:§d" . $nickname . "§d");
                    $sender->setNameTag($config->get("hero") . " §d" . $nickname . "§d");
                } else if ($playerfile->get("Suppremium") === true) {
                    $sender->setDisplayName("§3S§f:§3" . $nickname . "§7");
                    $sender->setNameTag($config->get("suppremium") . " §3" . $nickname . "§3");
                } else if ($playerfile->get("Premium") === true) {
                    $sender->setDisplayName("§6P§f:§6" . $nickname . "§6");
                    $sender->setNameTag($config->get("premium") . " §6" . $nickname . "§6");
                }
                $sender->sendMessage($config->get("info") . "Du hast deinen Nicknamen zu §e$nickname §6geändert!");
                $playerfile->set("Nick", true);
                $playerfile->set("Nickname", $args[0]);
                $playerfile->save();
            }
        }
        return true;
    }
}