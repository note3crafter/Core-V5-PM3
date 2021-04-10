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

use pocketmine\command\ConsoleCommandSender;
use pocketmine\permission\DefaultPermissions;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class SeePermsCommand extends Command
{
    private $plugin;
    private $pmDefaultPerms = [];
    private $messages;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("seeperms", $config->get("prefix") . "§6Siehe die Pluginberechtigung eines Plugins", "/seeperms [pluginname]", ["fperms"]);
        $this->setPermission("core.command.seeperms");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        if (!isset($args[0]) || count($args) > 2) {
            $sender->sendMessage($config->get("info") . "Nutze : /seeperms [pluginname]");
            return true;
        }
        $plugin = (strtolower($args[0]) === 'pocketmine' || strtolower($args[0]) === 'pmmp') ? 'pocketmine' : $this->plugin->getServer()->getPluginManager()->getPlugin($args[0]);

        if ($plugin === null) {
            $sender->sendMessage($config->get("error") . "§cDas Plugin §e" . $args[0] . " §cExistiert nicht!");

            return true;
        }
        $permissions = ($plugin instanceof PluginBase) ? $plugin->getDescription()->getPermissions() : $this->getPocketMinePerms();
        if (empty($permissions)) {
            $sender->sendMessage($config->get("error") . "§cDas Plugin §e" . $args[0] . " §chat keine Berechtigung!");

            return true;
        }
        $pageHeight = $sender instanceof ConsoleCommandSender ? 48 : 6;
        $chunkedPermissions = array_chunk($permissions, $pageHeight);
        $maxPageNumber = count($chunkedPermissions);
        if (!isset($args[1]) || !is_numeric($args[1]) || $args[1] <= 0) {
            $pageNumber = 1;
        } else if ($args[1] > $maxPageNumber) {
            $pageNumber = $maxPageNumber;
        } else {
            $pageNumber = $args[1];
        }
        if (($plugin instanceof PluginBase) ? $plugin->getName() : 'PocketMine-MP') {
            $sender->sendMessage($config->get("group") . "§6Seite §f: §e" . $pageNumber . "§f/§e" . $maxPageNumber);
        }
        foreach ($chunkedPermissions[$pageNumber - 1] as $permission) {
            $sender->sendMessage("§e" . $permission->getName());
        }
        return true;
    }
    public function getPlugin(): Plugin
    {
        return $this->plugin;
    }
    public function getPocketMinePerms()
    {
        if ($this->pmDefaultPerms === []) {
            foreach ($this->plugin->getServer()->getPluginManager()->getPermissions() as $permission) {
                if (strpos($permission->getName(), DefaultPermissions::ROOT) !== false)
                    $this->pmDefaultPerms[] = $permission;
            }
        }
        return $this->pmDefaultPerms;
    }
}
