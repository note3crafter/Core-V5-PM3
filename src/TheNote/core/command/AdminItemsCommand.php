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
use pocketmine\event\Listener;
use pocketmine\item\ItemFactory;
use pocketmine\Player;
use pocketmine\utils\Config;
use TheNote\core\Main;

class AdminItemsCommand extends Command implements Listener
{

    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("adminitems", $config->get("prefix") . $lang->get("adminitemsprefix"), "/adminitems" , ["ai", "aitmes"]);
        $this->setPermission("core.command.adminitems");
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args):bool
    {
        $configs = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        $l = $langsettings->get("Lang");
        $lang = new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $l . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($configs->get("error") . $lang->get("commandingame"));
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($configs->get("error") . $lang->get("nopermission"));
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($configs->get("info") . "§eUsage : /adminitems <superbow|explosivbow|explosivegg>");
            return true;
        }
        if (isset($args[0])) {
            if ($args[0] == "superbow") {
                if ($sender->hasPermission("core.command.adminitems.superbow")) {
                    $this->superbow($sender);
                    $sender->sendMessage($configs->get("prefix") . $lang->get("adminitems1"));
                } else {
                    $sender->sendMessage($configs->get("error") . $lang->get("adminitems2"));
                }
            }
            if ($args[0] == "explosivbow") {
                if ($sender->hasPermission("core.command.adminitems.explosivbow")) {
                    $this->explosivbow($sender);
                    $sender->sendMessage($configs->get("prefix") . $lang->get("adminitems1"));
                } else {
                    $sender->sendMessage($configs->get("error") . $lang->get("adminitems2"));
                }
            }
            if ($args[0] == "explosivegg") {
                if ($sender->hasPermission("core.command.adminitems.explosivbow")) {
                    $this->explodeegg($sender);
                    $sender->sendMessage($configs->get("prefix") . $lang->get("adminitems1"));
                } else {
                    $sender->sendMessage($configs->get("error") . $lang->get("adminitems2"));
                }
            }
        }
        return true;
    }
    public function superbow(Player $player)
    {
        $sbogen = ItemFactory::get(261, 0, 1);
        $sbogen->setCustomName("§f[§cSuperBow§f]");
        $sbogen->getNamedTag()->setString("custom_data", "super_bow");
        $player->getInventory()->addItem($sbogen);

    }
    public function explosivbow(Player $player)
    {
        $ebow = ItemFactory::get(261, 0, 1);
        $ebow->setCustomName("§f[§cExplosivBow§f]");
        $ebow->getNamedTag()->setString("custom_data", "explode_bow");
        $player->getInventory()->addItem($ebow);

    }
    public function explodeegg(Player $player)
    {
        $egg = ItemFactory::get(344, 0, 16);
        $egg->setCustomName("§f[§cExplosivEgg§f]");
        $egg->getNamedTag()->setString("custom_data", "explode_egg");
        $player->getInventory()->addItem($egg);
    }
}