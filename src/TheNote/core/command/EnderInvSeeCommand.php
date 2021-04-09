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

use pocketmine\inventory\Inventory;
use pocketmine\Server;
use TheNote\core\invmenu\InvMenu;
use TheNote\core\invmenu\transaction\InvMenuTransaction;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;

class EnderInvSeeCommand extends Command
{
    private $plugin;
    private $tName;
    private $inv;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("enderinvsee", $config->get("prefix") . "§6Siehe das Inventar eines anderen Spielers", "/enderinvsee {player}", ["ecinvsee"]);
        $this->setPermission("core.command.enderinvsee");
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
            $sender->sendMessage($config->get("info") . "§eNutze : /enderinvsee {player}");
            return false;
        }
        $target = Server::getInstance()->getPlayer(strtolower($args[0]));
        if ($target == null) {
            $sender->sendMessage($config->get("error") . "§cDer Spieler ist nicht Online");
            return false;
        }
        if(isset($args[0])){
            $target = $this->plugin->getServer()->getPlayer($args[0]);
            if($target instanceof Player){
                $this->tName = "";
                $tName = $target->getName();
                $this->tName = "$tName";
                $sender->sendMessage($config->get("prefix") . "Enderchest wird geöffnet!");
                $this->send($sender);
            }
        }
        return true;
    }

    public function send($sender){
        $menu = InvMenu::create(InvMenu::TYPE_CHEST);
        $inv = $menu->getInventory();
        $menu->setName( $this->tName . "'s Enderchest");
        $target = $this->plugin->getServer()->getPlayer($this->tName);
        $content = $target->getEnderChestInventory()->getContents();
        $this->inv = $menu;
        $inv->setContents($content);
        $menu->setListener(function (InvMenuTransaction $transaction) use ($sender) : \TheNote\core\invmenu\transaction\InvMenuTransactionResult {
            $inv = $this->inv->getInventory();
            $target = $this->plugin->getServer()->getPlayer($this->tName);
            if($target->getName() !== $sender->getName()) {
                return $transaction->discard();
            } else {
                $nContents = $inv->getContents();
                $sender->getEnderChestInventory()->setContents($nContents);
                return $transaction->continue();
            }
        });
        $menu->setInventoryCloseListener(function(Player $sender, Inventory $inventory) : void {
            if($this->tName == $sender->getName()) {
                $nContents = $inventory->getContents();
                $sender->getEnderChestInventory()->setContents($nContents);
            }
        });
        $menu->send($sender);
    }
}
