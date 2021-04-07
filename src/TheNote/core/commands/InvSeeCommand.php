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

use pocketmine\item\Item;
use pocketmine\Server;
use TheNote\core\invmenu\InvMenu;
use TheNote\core\invmenu\transaction\InvMenuTransaction;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;

class InvSeeCommand extends Command
{
    private $plugin;
    private $tName;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("invsee", $config->get("prefix") . "§6Siehe das Inventar eines anderen Spielers", "/invsee {player}");
        $this->setPermission("core.command.invsee");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . "§cDu hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($config->get("info") . "§eNutze : /invsee {player}");
            return false;
        }
        $target = Server::getInstance()->getPlayer(strtolower($args[0]));
        if ($target == null) {
            $sender->sendMessage($config->get("error") . "§cDer Spieler ist nicht Online");
            return false;
        }
        if (isset($args[0])) {
            if ($target instanceof Player) {
                $this->tName = "";
                $tName = $target->getName();
                $this->tName = "$tName";
                $sender->sendMessage($config->get("prefix") . "§6Das Inventar wird geöffnet");
                $this->send($sender);
            }
        }
        return true;
    }

    public function send($sender)
    {
        $menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
        $inv = $menu->getInventory();
        $menu->setName($this->tName . "'s Inventar");
        $target = $this->plugin->getServer()->getPlayer($this->tName);
        $content = $target->getInventory()->getContents();
        $inv->setContents($content);
        $i1 = Item::get(95, 0, 1);
        $r1 = $target->getArmorInventory()->getHelmet();
        $r2 = $target->getArmorInventory()->getChestplate();
        $r3 = $target->getArmorInventory()->getLeggings();
        $r4 = $target->getArmorInventory()->getBoots();
        $i1->setCustomName("§r   ");
        $inv->setItem(36, $i1);
        $inv->setItem(37, $i1);
        $inv->setItem(38, $i1);
        $inv->setItem(39, $i1);
        $inv->setItem(40, $i1);
        $inv->setItem(41, $i1);
        $inv->setItem(42, $i1);
        $inv->setItem(43, $i1);
        $inv->setItem(44, $i1);
        $inv->setItem(45, $i1);
        $inv->setItem(46, $r1);
        $inv->setItem(47, $r2);
        $inv->setItem(48, $r3);
        $inv->setItem(49, $r4);
        $inv->setItem(50, $i1);
        $inv->setItem(52, $i1);
        $inv->setItem(53, $i1);
        $menu->setListener(function (InvMenuTransaction $transaction) use ($sender): \TheNote\core\invmenu\transaction\InvMenuTransactionResult {
            if (!$sender->hasPermission("core.command.invsee.use")) {
                return $transaction->discard();
            }
            if ($transaction->getItemClicked()->getId() == 95) {
                return $transaction->discard();
            }
            return $transaction->continue();
        });
        $menu->send($sender);
    }
}
