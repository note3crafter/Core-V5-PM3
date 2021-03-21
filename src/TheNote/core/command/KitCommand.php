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
use pocketmine\item\Item;
use pocketmine\Player;
use TheNote\core\Main;
use TheNote\core\formapi\SimpleForm;
use onebone\economyapi\EconomyAPI;
use pocketmine\utils\Config;
use DateTime;


class KitCommand extends Command
{
    public $cooldownlist;
    public $cooldowndaily;
    public $cooldownweelky;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("kit", $config->get("prefix") . "Wähle dein Kit", "/kit");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            return $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
        }
        if ($sender instanceof Player) {
            if ($sender instanceof Player) {
                $form = new SimpleForm(function (Player $sender, $data) {
                    $name = $sender->getLowerCaseName();
                    $item = $sender->getPlayer();
                    $mymoney = $this->plugin->getServer()->getPluginManager()->getPlugin("EconomyAPI");
                    $user = new Config($this->plugin->getDataFolder() . Main::$userfile . $name . ".json", Config::JSON);
                    $result = $data;
                    if ($result === null) {
                        return true;
                    }
                    switch ($result) {
                        case 0: #+7 day
                            $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
                            $name = $sender->getName();
                            $bannedtime = $user->get("weekcrate");
                            $time = new DateTime("$bannedtime", new \DateTimeZone("Europe/Berlin"));
                            $now = new DateTime("now", new \DateTimeZone("Europe/Berlin"));
                            $inv = $sender->getInventory();
                            $emptySlots = $inv->getSize() - count($inv->getContents());

                            if ($time->format("d.m.Y H:i") > $now->format("d.m.Y H:i")) {
                                $sender->sendMessage($config->get("kit") . "§r§6Du kannst deine Wöchentliche Belohnung erst am§c $bannedtime h §6wieder abholen.");

                            } elseif (count($inv->getContents()) >= $inv->getSize()) {
                                $sender->sendMessage($config->get("kit") . "§cDu benötigst mindestens 9 Freie Slots in deinem Inventarum dieses Kit zu Claimen!");
                                return true;

                            } elseif ($emptySlots === 9) {
                                $newtime = new \DateTime("now", new \DateTimeZone("Europe/Berlin"));
                                $newtime->modify("+7 day");
                                $user->set("weekcrate", $newtime->format("d.m.Y H:i"));
                                $user->save();
                                $mymoney->addMoney($sender, 5000);
                                $user->set("coins", $user->get("coins") + 200);
                                $user->save();
                                $item->getInventory()->addItem(Item::get(17, 0, 64));
                                $item->getInventory()->addItem(Item::get(272, 0, 1));
                                $item->getInventory()->addItem(Item::get(275, 0, 1));
                                $item->getInventory()->addItem(Item::get(274, 0, 1));
                                $item->getInventory()->addItem(Item::get(291, 0, 1));
                                $item->getInventory()->addItem(Item::get(273, 0, 1));
                                $item->getInventory()->addItem(Item::get(264, 0, 16));
                                $item->getInventory()->addItem(Item::get(297, 0, 32));
                                $item->getInventory()->addItem(Item::get(263, 0, 32));
                                $sender->sendMessage($config->get("kit") . "Du hast dein Wöchentliches Kit erhalten sowie 5000$ sowie 200 Coins Bekommen!");
                            } else {
                                $sender->sendMessage($config->get("kit") . "§cDu benötigst mindestens 9 Freie Slots in deinem Inventar um dieses Kit zu Claimen!");
                            }
                            break;
                        case 1: #+1 day
                            $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
                            $name = $sender->getName();
                            $inv = $sender->getInventory();
                            $emptySlots = $inv->getSize() - count($inv->getContents());
                            $bannedtime = $user->get("dailycrate");
                            $time = new DateTime("$bannedtime", new \DateTimeZone("Europe/Berlin"));
                            $now = new DateTime("now", new \DateTimeZone("Europe/Berlin"));

                            if ($time->format("d.m.Y H:i") > $now->format("d.m.Y H:i")) {
                                $sender->sendMessage($config->get("kit") . "§r§6Du kannst deine Tägliche Belohnung erst morgen den§c $bannedtime h §6wieder abholen.");
                            } elseif (count($inv->getContents()) >= $inv->getSize()) {
                                $sender->sendMessage($config->get("kit") . "§cDu benötigst mindestens 2 Freie Slots in deinem Inventar um dieses Kit zu Claimen!");
                                return true;
                            } elseif ($emptySlots === 2) {
                                $newtime = new \DateTime("now", new \DateTimeZone("Europe/Berlin"));
                                $newtime->modify("+1 day");
                                $user->set("dailycrate", $newtime->format("d.m.Y H:i"));
                                $user->save();
                                $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), 'key Daily ' . $name . ' 1');
                                $mymoney->addMoney($sender, 700);
                                $user->set("coins", $user->get("coins") + 50);
                                $user->save();
                                $item->getInventory()->addItem(Item::get(297, 0, 16));
                                $item->getInventory()->addItem(Item::get(265, 0, 16));
                                $sender->sendMessage($config->get("kit") . "Du hast dein Tägliches Kit erhalten sowie 700$, 50 Coins und 1 Dailykey!");
                            } else {
                                $sender->sendMessage($config->get("kit") . "§cDu benötigst mindestens 2 Freie Slots in deinem Inventar um dieses Kit zu Claimen!");
                            }
                            break;
                        case 2: #+ 1 hour
                            $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
                            $name = $sender->getName();
                            $inv = $sender->getInventory();
                            $emptySlots = $inv->getSize() - count($inv->getContents());
                            $bannedtime = $user->get("hourcrate");
                            $time = new DateTime("$bannedtime", new \DateTimeZone("Europe/Berlin"));
                            $now = new DateTime("now", new \DateTimeZone("Europe/Berlin"));

                            if ($time->format("d.m.Y H:i") > $now->format("d.m.Y H:i")) {
                                $sender->sendMessage($config->get("kit") . "§r§6Du kannst deine Stündliche Belohnung erst um§c $bannedtime h §6wieder abholen.");
                            } else if (count($inv->getContents()) >= $inv->getSize()) {
                                $sender->sendMessage($config->get("kit") . "§cDu benötigst mindestens 2 Freie Slots in deinem Inventar um dieses Kit zu Claimen!");
                                return true;
                            } elseif ($emptySlots === 2) {
                                $newtime = new \DateTime("now", new \DateTimeZone("Europe/Berlin"));
                                $newtime->modify("+ 1 hour");
                                $user->set("hourcrate", $newtime->format("d.m.Y H:i"));
                                $user->save();
                                $this->plugin->getServer()->dispatchCommand(new ConsoleCommandSender(), 'key Stundlicher ' . $name . ' 1');
                                $mymoney->addMoney($sender, 100);
                                $item->getInventory()->addItem(Item::get(357, 0, 16));
                                $item->getInventory()->addItem(Item::get(354, 0, 1));
                                $sender->sendMessage($config->get("kit") . "Du hast dein Stündliches Kit erhalten sowie 100$ und 1 Stündlicher Key!");
                            } else {
                                $sender->sendMessage($config->get("kit") . "§cDu benötigst mindestens 2 Freie Slots in deinem Inventar um dieses Kit zu Claimen!");
                            }
                            break;
                    }
                });
                $name = $sender->getLowerCaseName();
                $user = new Config($this->plugin->getDataFolder() . Main::$userfile . $name . ".json", Config::JSON);
                $now = new DateTime("now", new \DateTimeZone("Europe/Berlin"));

                $form->setTitle($config->get("uiname"));
                $form->setContent("§eWähle dein Kit");
                $week = $user->get("weekcrate");
                $timew = new DateTime("$week", new \DateTimeZone("Europe/Berlin"));
                if ($timew->format("d.m.Y H:i") > $now->format("d.m.Y H:i")) {
                    $form->addButton("§0Wöchentliches Kit\n§r§cAbholbar am : $week");
                } else {
                    $form->addButton("§0Wöchentliches Kit");
                }
                $day = $user->get("dailycrate");
                $timed = new DateTime("$day", new \DateTimeZone("Europe/Berlin"));
                if ($timed->format("d.m.Y H:i") > $now->format("d.m.Y H:i")) {
                    $form->addButton("§0Tägliches Kit\n§r§cAbholbar am : $day");
                } else {
                    $form->addButton("§0Tägliches Kit");
                }
                $hour = $user->get("hourcrate");
                $timeh = new DateTime("$hour", new \DateTimeZone("Europe/Berlin"));
                if ($timeh->format("d.m.Y H:i") > $now->format("d.m.Y H:i")) {
                    $form->addButton("§0Stündliches Kit\n§r§cAbholbar um: $hour");
                } else {
                    $form->addButton("§0Stündliches Kit");
                }
                $form->sendToPlayer($sender);
            }
        }
        return true;
    }
}