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
use pocketmine\utils\Config;
use TheNote\core\Main;
use onebone\economyapi\EconomyAPI;
use TheNote\core\formapi\SimpleForm;

class PerkShopCommand extends Command
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("perkshop", $config->get("prefix") . "Kaufe deine Perks", "/perkshop", ["ps"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) :bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }
        $name = $sender->getLowerCaseName();
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("info") . "Du kannst dir diesen Perk im Perkshop kaufen");
            return true;
        }

        $form = new SimpleForm(function (Player $sender, $data) {
            $result = $data;
            $mymoney = $this->plugin->getServer()->getPluginManager()->getPlugin("EconomyAPI");
            $player = $sender->getName();
            $daten = new Config($this->plugin->getDataFolder() . Main::$userfile . $player . ".json", Config::JSON);
            $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
            $preise = new Config($this->plugin->getDataFolder() . Main::$setup . "PerkSettings.yml", Config::YAML);
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    if ($mymoney->myMoney($sender) < $preise->get("explode")) {
                        $sender->sendMessage($config->get("error") . "§cDu hast zu wenig Geld um den Perk zu kaufen!");
                        return true;
                    }
                    if ($sender->hasPermission("core.command.perk.explode") or $sender->isOp()) {
                        $sender->sendMessage($config->get("error") . "§cDu hast diesen Perk bereits gekauft");
                    } else if ($mymoney->myMoney($sender) >= $preise->get("explode")) {
                        $mymoney->reduceMoney($sender, $preise->get("explode"));
                        $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm $player core.command.perk.explode");
                        $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "ppreload");
                        $daten->set("explodeperk", true);
                        $daten->save();
                        $sender->sendMessage($config->get("perks") . "§aDu hast dir deinen Perk erfolgreich gekauft!");
                    }
                    break;
                case 1:
                    if ($mymoney->myMoney($sender) < $preise->get("angry")) {
                        $sender->sendMessage($config->get("error") . "§cDu hast zu wenig Geld um den Perk zu kaufen!");
                        return true;
                    }
                    if ($sender->hasPermission("core.command.perk.angry") or $sender->isOp()) {
                        $sender->sendMessage($config->get("error") . "§cDu hast diesen Perk bereits gekauft");
                    } else if ($mymoney->myMoney($sender) >= $preise->get("angry")) {
                        $mymoney->reduceMoney($sender, $preise->get("angry"));
                        $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm $player core.command.perk.angry");
                        $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "ppreload");
                        $daten->set("angryperk", true);
                        $daten->save();
                        $sender->sendMessage($config->get("perks") . "§aDu hast dir deinen Perk erfolgreich gekauft!");
                    }
                    break;
                case 2:
                    if ($mymoney->myMoney($sender) < $preise->get("redstone")) {
                        $sender->sendMessage($config->get("error") . "§cDu hast zu wenig Geld um den Perk zu kaufen!");
                        return true;
                    }
                    if ($sender->hasPermission("core.command.perk.redstone") or $sender->isOp()) {
                        $sender->sendMessage($config->get("error") . "§cDu hast diesen Perk bereits gekauft");
                    } else if ($mymoney->myMoney($sender) >= $preise->get("redstone")) {
                        $mymoney->reduceMoney($sender, $preise->get("redstone"));
                        $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm $player core.command.perk.redstone");
                        $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "ppreload");
                        $daten->set("redstoneperk", true);
                        $daten->save();
                        $sender->sendMessage($config->get("perks") . "§aDu hast dir deinen Perk erfolgreich gekauft!");
                    }
                    break;
                case 3:
                    if ($mymoney->myMoney($sender) < $preise->get("smoke")) {
                        $sender->sendMessage($config->get("error") . "§cDu hast zu wenig Geld um den Perk zu kaufen!");
                        return true;
                    }
                    if ($sender->hasPermission("core.command.perk.smoke") or $sender->isOp()) {
                        $sender->sendMessage($config->get("error") . "§cDu hast diesen Perk bereits gekauft");
                    } else if ($mymoney->myMoney($sender) >= $preise->get("smoke")) {
                        $mymoney->reduceMoney($sender, $preise->get("smoke"));
                        $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm $player core.command.perk.smoke");
                        $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "ppreload");
                        $daten->set("smokeperk", true);
                        $daten->save();
                        $sender->sendMessage($config->get("perks") . "§aDu hast dir deinen Perk erfolgreich gekauft!");
                    }
                    break;
                case 4:
                    if ($mymoney->myMoney($sender) < $preise->get("lava")) {
                        $sender->sendMessage($config->get("error") . "§cDu hast zu wenig Geld um den Perk zu kaufen!");
                        return true;
                    }
                    if ($sender->hasPermission("core.command.perk.lava") or $sender->isOp()) {
                        $sender->sendMessage($config->get("error") . "§cDu hast diesen Perk bereits gekauft");
                    } else if ($mymoney->myMoney($sender) >= $preise->get("lava")) {
                        $mymoney->reduceMoney($sender, $preise->get("lava"));
                        $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm $player core.command.perk.lava");
                        $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "ppreload");
                        $daten->set("lavaperk", true);
                        $daten->save();
                        $sender->sendMessage($config->get("perks") . "§aDu hast dir deinen Perk erfolgreich gekauft!");
                    }
                    break;
                case 5:
                    if ($mymoney->myMoney($sender) < $preise->get("heart")) {
                        $sender->sendMessage($config->get("error") . "§cDu hast zu wenig Geld um den Perk zu kaufen!");
                        return true;
                    }
                    if ($sender->hasPermission("core.command.perk.heart") or $sender->isOp()) {
                        $sender->sendMessage($config->get("error") . "§cDu hast diesen Perk bereits gekauft");
                    } else if ($mymoney->myMoney($sender) >= $preise->get("heart")) {
                        $mymoney->reduceMoney($sender, $preise->get("heart"));
                        $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm $player core.command.perk.heart");
                        $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "ppreload");
                        $daten->set("heartperk", true);
                        $daten->save();
                        $sender->sendMessage($config->get("perks") . "§aDu hast dir deinen Perk erfolgreich gekauft!");
                    }
                    break;
                case 6:
                    if ($mymoney->myMoney($sender) < $preise->get("flame")) {
                        $sender->sendMessage($config->get("error") . "§cDu hast zu wenig Geld um den Perk zu kaufen!");
                        return true;
                    }
                    if ($sender->hasPermission("core.command.perk.flame") or $sender->isOp()) {
                        $sender->sendMessage($config->get("error") . "§cDu hast diesen Perk bereits gekauft");
                    } else if ($mymoney->myMoney($sender) >= $preise->get("flame")) {
                        $mymoney->reduceMoney($sender, $preise->get("flame"));
                        $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm $player core.command.perk.flame");
                        $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "ppreload");
                        $daten->set("flameperk", true);
                        $daten->save();
                        $sender->sendMessage($config->get("perks") . "§aDu hast dir deinen Perk erfolgreich gekauft!");
                    }
                    break;
                case 7:
                    if ($mymoney->myMoney($sender) < $preise->get("portal")) {
                        $sender->sendMessage($config->get("error") . "§cDu hast zu wenig Geld um den Perk zu kaufen!");
                        return true;
                    }
                    if ($sender->hasPermission("core.command.perk.portal") or $sender->isOp()) {
                        $sender->sendMessage($config->get("error") . "§cDu hast diesen Perk bereits gekauft");
                    } else if ($mymoney->myMoney($sender) >= $preise->get("portal")) {
                        $mymoney->reduceMoney($sender, $preise->get("portal"));
                        $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm $player core.command.perk.portal");
                        $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "ppreload");
                        $daten->set("portalperk", true);
                        $daten->save();
                        $sender->sendMessage($config->get("perks") . "§aDu hast dir deinen Perk erfolgreich gekauft!");
                    }
                    break;
                case 8:
                    if ($mymoney->myMoney($sender) < $preise->get("spore")) {
                        $sender->sendMessage($config->get("error") . "§cDu hast zu wenig Geld um den Perk zu kaufen!");
                        return true;
                    }
                    if ($sender->hasPermission("core.command.perk.spore") or $sender->isOp()) {
                        $sender->sendMessage($config->get("error") . "§cDu hast diesen Perk bereits gekauft");
                    } else if ($mymoney->myMoney($sender) >= $preise->get("spore")) {
                        $mymoney->reduceMoney($sender, $preise->get("spore"));
                        $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm $player core.command.perk.spore");
                        $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "ppreload");
                        $daten->set("sporeperk", true);
                        $daten->save();
                        $sender->sendMessage($config->get("perks") . "§aDu hast dir deinen Perk erfolgreich gekauft!");
                    }
                    break;
                case 9:
                    if ($mymoney->myMoney($sender) < $preise->get("splash")) {
                        $sender->sendMessage($config->get("error") . "§cDu hast zu wenig Geld um den Perk zu kaufen!");
                        return true;
                    }
                    if ($sender->hasPermission("core.command.perk.splash") or $sender->isOp()) {
                        $sender->sendMessage($config->get("error") . "§cDu hast diesen Perk bereits gekauft");
                    } else if ($mymoney->myMoney($sender) >= $preise->get("splash")) {
                        $mymoney->reduceMoney($sender, $preise->get("splash"));
                        $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "setuperm $player core.command.perk.splash");
                        $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), "ppreload");
                        $daten->set("splashperk", true);
                        $daten->save();
                        $sender->sendMessage($config->get("perks") . "§aDu hast dir deinen Perk erfolgreich gekauft!");
                    }
                    break;
            }
        });
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $form->setTitle($config->get("uiname"));
        $form->setContent("§6===========§f[§dPerkShop§f]§6==========\n\n" .
            "§dWillkommen im Perkshop. Hier kannst du verschiedene Perks Kaufen mit IngameGeld die dann dauerhaft aktiviert sind!\n" .
            "§fKostenpunkt pro Perk : §c50000$\n");
        $form->addButton("§0ExplodePerk", 0);
        $form->addButton("§0VillagerAngryPerk", 0);
        $form->addButton("§0RedstonePerk", 0);
        $form->addButton("§0RauchPerk", 0);
        $form->addButton("§0LavaPerk", 0);
        $form->addButton("§0HerzPerk", 0);
        $form->addButton("§0FlammenPerk", 0);
        $form->addButton("§0PortalPerk", 0);
        $form->addButton("§0SporenPerk", 0);
        $form->addButton("§0WasserPerk", 0);
        $form->sendToPlayer($sender);
        return true;
    }
}
