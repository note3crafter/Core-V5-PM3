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

use pocketmine\block\BlockFactory;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntityShootBowEvent;
use pocketmine\event\entity\ProjectileHitBlockEvent;
use pocketmine\event\Listener;
use pocketmine\item\Egg;
use pocketmine\item\ItemFactory;
use pocketmine\Player;
use pocketmine\utils\Config;
use TheNote\core\Main;

class AdminItemsCommand extends Command implements Listener
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("admintiem", $config->get("prefix") . "Hole dir SuperItems", "/adminitems" , ["ai", "aitmes"]);
        $this->setPermission("core.command.adminitems");
    }
    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            return $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        $gruppe = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $sender->getName() . ".json", Config::JSON);
        $owner = $gruppe->get("Owner");
        if (empty($args[0])) {
            $sender->sendMessage($config->get("prefix") . "/adminitems <superbow|explosivbow|explosivegg>");
            return true;
        }
        if (isset($args[0])) {
            if ($owner === false) {
                $sender->sendMessage($config->get("error") . "Tut mir leid du gehörst nicht zu, Stammbaum : Owner");
                return true;
            }
            if ($owner === true) {
                if ($args[0] == "superbow") {
                    if ($sender instanceof Player) {
                        if ($sender->hasPermission("core.command.adminitems.superbow.superbow")) {
                            $this->superbow($sender);
                            $sender->sendMessage($config->get("prefix") . "§aDu hast das SuperItem Erhalten!");
                        } else {
                            $sender->sendMessage($config->get("error") . "Nene nix für kleine Kinder!");
                        }
                    }
                }
                if ($args[0] == "explosivbow") {
                    if ($sender instanceof Player) {
                        if ($sender->hasPermission("core.command.adminitems.explosivbow")) {
                            $this->explosivbow($sender);
                            $sender->sendMessage($config->get("prefix") . "§aDu hast das SuperItem Erhalten!");
                        } else {
                            $sender->sendMessage($config->get("error") . "Nene nix für kleine Kinder!");
                        }
                    }
                }
                if ($args[0] == "explosivegg") {
                    if ($sender instanceof Player) {
                        if ($sender->hasPermission("core.command.adminitems.explosivbow")) {
                            $this->explosivegg($sender);
                            $sender->sendMessage($config->get("prefix") . "§aDu hast das SuperItem Erhalten!");
                        } else {
                            $sender->sendMessage($config->get("error") . "Nene nix für kleine Kinder!");
                        }
                    }
                }
            }
        }
        return true;
    }
    public function superbow(Player $player)
    {
        $bogen = ItemFactory::get(261, 0, 1);
        $bogen->setCustomName("§f[§cSuperBow§f]");
        $bogen->getNamedTag()->setString("custom_data", "super_bow");
        $player->getInventory()->addItem($bogen);

    }
    public function explosivbow(Player $player)
    {
        $bogen = ItemFactory::get(261, 0, 1);
        $bogen->setCustomName("§f[§cExplosivBow§f]");
        $bogen->getNamedTag()->setString("custom_data", "explode_bow");
        $player->getInventory()->addItem($bogen);

    }
    public function explosivegg(Player $player)
    {
        $egg = ItemFactory::get(344, 0, 16);
        $egg->setCustomName("§f[§cExplosivEgg§f]");
        $egg->getNamedTag()->setString("custom_data", "explode_egg");
        $player->getInventory()->addItem($egg);
    }
}
//Last edit by Rudolf2000 15.03.2021 17:26