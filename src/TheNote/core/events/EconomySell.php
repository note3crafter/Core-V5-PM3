<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\events;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\Listener;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\item\ItemFactory;
use pocketmine\utils\Config;
use pocketmine\item\Item;
use TheNote\core\Main;

class EconomySell implements Listener
{

    private $sell;
    private $placeQueue;
    private $sellSign;
    private $plugin;
    private $tap;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $this->placeQueue = [];
        $this->sell = (new Config($this->plugin->getDataFolder() . Main::$cloud . "Sell.yml", Config::YAML))->getAll();
    }

    public function getMessage($key, $val = array("{price}", "{item}", "{amount}"))
    {
        return str_replace(array("{price}", "{item}", "{amount}"), array($val[0], $val[1], $val[2]), $this->plugin->sellSign->get($key));
    }

    public function onSignChange(SignChangeEvent $event)
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $tag = $event->getLine(0);
        if (($val = $this->checkTag($tag)) !== false) {
            $player = $event->getPlayer();
            if (!$player->hasPermission("core.economy.sell.create")) {
                $player->sendMessage($config->get("error") . "§cDu hast keine Berechtigung um einen Verkaufsshop zu erstellen!");
                return;
            }
            if (!is_numeric($event->getLine(1)) or !is_numeric($event->getLine(3))) {
                return;
            }
            $item = Item::fromString($event->getLine(2));
            if ($item === false) {
                $player->sendMessage($this->getMessage($config->get("error") . "§cDas Item wird nicht Unterstützt! §e", array($event->getLine(2), "", "")));
                return;
            }

            $block = $event->getBlock();
            $this->sell[$block->getX() . ":" . $block->getY() . ":" . $block->getZ() . ":" . $player->getLevel()->getName()] = array(
                "x" => $block->getX(),
                "y" => $block->getY(),
                "z" => $block->getZ(),
                "level" => $player->getLevel()->getName(),
                "cost" => (int)$event->getLine(1),
                "item" => (int)$item->getID(),
                "itemName" => $item->getName(),
                "meta" => (int)$item->getDamage(),
                "amount" => (int)$event->getLine(3)
            );
            $cfg = new Config($this->plugin->getDataFolder() . Main::$cloud . "Sell.yml", Config::YAML);
            $cfg->setAll($this->sell);
            $cfg->save();
            //$sellcreate = $item->getName() . (int)$event->getLine(3);
            $player->sendMessage($config->get("money") . "§6Du hast den Verkaufsshop erfolgreich erstellt!"/* . $sellcreate*/);

            $event->setLine(0, $val[0]);
            $event->setLine(1, str_replace("{price}", $event->getLine(1), $val[1])); // PRICE
            $event->setLine(2, str_replace("{item}", $item->getName(), $val[2])); // ITEM NAME
            $event->setLine(3, str_replace("{amount}", $event->getLine(3), $val[3])); // AMOUNT
        }
    }

    public function onTouch(PlayerInteractEvent $event)
    {
        $money = new Config($this->plugin->getDataFolder() . Main::$cloud . "Money.yml", Config::YAML);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);

        if ($event->getAction() !== PlayerInteractEvent::RIGHT_CLICK_BLOCK) {
            return;
        }

        $block = $event->getBlock();
        $loc = $block->getX() . ":" . $block->getY() . ":" . $block->getZ() . ":" . $block->getLevel()->getName();
        if (isset($this->sell[$loc])) {
            $sell = $this->sell[$loc];
            $player = $event->getPlayer();

            if ($player->getGamemode() % 2 === 1) {
                $player->sendMessage($config->get("error") . "Du kannst nur im Gamemode 0 was verkaufen!");
                $event->setCancelled();
                return;
            }
            if (!$player->hasPermission("core.economy.sell.sell")) {
                $player->sendMessage($config->get("error") . "§cDu hast keine Berechtigung um was zu verkaufen!");
                $event->setCancelled();
                return;
            }
            $cnt = 0;
            foreach ($player->getInventory()->getContents() as $item) {
                if ($item->getID() == $sell["item"] and $item->getDamage() == $sell["meta"]) {
                    $cnt = $item->getCount();
                }
            }
            if (!isset($sell["itemName"])) {
                $item = $this->getItem($sell["item"], $sell["meta"], $sell["amount"]);
                if ($item === false) {
                    $item = $sell["item"] . ":" . $sell["meta"];
                } else {
                    $item = $item[0];
                }
                $this->sell[$loc]["itemName"] = $item;
                $sell["itemName"] = $item;
            }
            $now = microtime(true);
            if (!isset($this->tap[$player->getName()]) or $now - $this->tap[$player->getName()][1] >= 1.5 or $this->tap[$player->getName()][0] !== $loc) {
                $this->tap[$player->getName()] = [$loc, $now];
                $player->sendTip($config->get("money") . "§cDrücke erneut um was zu verkaufen!");
                return;
            } else {
                unset($this->tap[$player->getName()]);
            }

            if ($cnt >= $sell["amount"]) {
                $signsell = ItemFactory::get((int)$sell["item"], (int)$sell["meta"], (int)$sell["amount"]);
                $player->getInventory()->removeItem($signsell);
                if ($this->plugin->economyapi == null) {
                    $money->setNested("money." . $player->getName(), $money->getNested("money." . $player->getName()) + $sell ["cost"]);
                    $money->save();
                } else {
                    EconomyAPI::getInstance()->addMoney($player, $sell["price"]);
                }
                $player->sendTip($config->get("money") . "§6Du hast erfolgreich was verkauft!"/*,array($sell ["amount"], $sell ["item"].":".$sell ["meta"], $sell ["cost"])*/);
            } else {
                $player->sendTip($config->get("error") . "§cDu hast bereits alles verkauft!");
            }
            $event->setCancelled(true);
            if ($event->getItem()->canBePlaced()) {
                $this->placeQueue [$player->getName()] = true;
            }
        }
    }

    public function onPlace(BlockPlaceEvent $event)
    {
        $username = $event->getPlayer()->getName();
        if (isset($this->placeQueue [$username])) {
            $event->setCancelled(true);
            unset($this->placeQueue [$username]);
        }
    }

    public function onBreak(BlockBreakEvent $event)
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $block = $event->getBlock();
        if (isset($this->sell[$block->getX() . ":" . $block->getY() . ":" . $block->getZ() . ":" . $block->getLevel()->getName()])) {
            $player = $event->getPlayer();
            if (!$player->hasPermission("core.economy.remove.sell")) {
                $player->sendMessage($config->get("error") . "§cDu hast keine Berechtigung um diesen Verkaufsshop zu zerstören!");
                $event->setCancelled(true);
                return;
            }
            $this->sell[$block->getX() . ":" . $block->getY() . ":" . $block->getZ() . ":" . $block->getLevel()->getName()] = null;
            unset($this->sell[$block->getX() . ":" . $block->getY() . ":" . $block->getZ() . ":" . $block->getLevel()->getName()]);
            $player->sendMessage($config->get("money") . "§6Der Verkaufsshop wurde erfolgreich entfernt.");
        }
    }

    public function checkTag($line1)
    {
        foreach ($this->plugin->sellSign->getAll() as $tag => $val) {
            if ($tag == $line1) {
                return $val;
            }
        }
        return false;
    }

    public function removeItem($sender, $getitem)
    {
        $getcount = $getitem->getCount();
        if ($getcount <= 0)
            return;
        for ($index = 0; $index < $sender->getInventory()->getSize(); $index++) {
            $setitem = $sender->getInventory()->getItem($index);
            if ($getitem->getID() == $setitem->getID() and $getitem->getDamage() == $setitem->getDamage()) {
                if ($getcount >= $setitem->getCount()) {
                    $getcount -= $setitem->getCount();
                    $sender->getInventory()->setItem($index, Item::get(Item::AIR, 0, 1));
                } else if ($getcount < $setitem->getCount()) {
                    $sender->getInventory()->setItem($index, Item::get($getitem->getID(), 0, $setitem->getCount() - $getcount));
                    break;
                }
            }
        }
    }
}
