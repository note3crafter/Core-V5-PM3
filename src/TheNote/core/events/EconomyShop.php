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
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\item\ItemFactory;
use pocketmine\utils\Config;
use pocketmine\item\Item;
use pocketmine\event\block\BlockPlaceEvent;
use TheNote\core\Main;

use onebone\economyapi\EconomyAPI;

class EconomyShop implements Listener{

    private $shop;
    private $placeQueue;
    private $shopSign;
    private $plugin;
    private $tap;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $this->placeQueue = [];
        $this->shop = (new Config($this->plugin->getDataFolder(). Main::$cloud . "Shop.yml", Config::YAML))->getAll();
    }

    public function onSignChange(SignChangeEvent $event){
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $result = $this->tagExists($event->getLine(0));
        if($result !== false){
            $player = $event->getPlayer();
            if(!$player->hasPermission("core.command.shop.create")){
                $player->sendMessage($config->get("error") . "§cDu hast keine Berechtigung um einen Shop zu erstellen!");
                return;
            }
            if(!is_numeric($event->getLine(1)) or !is_numeric($event->getLine(3))){
                return;
            }
            $item = Item::fromString($event->getLine(2));
            if($item === false){
                $player->sendMessage($config->get("error") . "§cDas Item wird nicht Unterstützt! §e" . array($event->getLine(2), "", ""));
                return;
            }

            $block = $event->getBlock();
            $this->shop[$block->getX().":".$block->getY().":".$block->getZ().":".$block->getLevel()->getFolderName()] = array(
                "x" => $block->getX(),
                "y" => $block->getY(),
                "z" => $block->getZ(),
                "level" => $block->getLevel()->getFolderName(),
                "price" => (int) $event->getLine(1),
                "item" => (int) $item->getID(),
                "itemName" => $item->getName(),
                "meta" => (int) $item->getDamage(),
                "amount" => (int) $event->getLine(3)
            );
            $cfg = new Config($this->plugin->getDataFolder(). Main::$cloud . "Shop.yml", Config::YAML);
            $cfg->setAll($this->shop);
            $cfg->save();
            //$a = array($item->getID(), $item->getDamage(), $event->getLine(1));
            $player->sendMessage($config->get("money") . "§6Du hast den Shop erfolgreich erstellt!"/* . $a*/);
            $event->setLine(0, $result[0]); // TAG
            $event->setLine(1, str_replace("{price}", $event->getLine(1), $result[1])); // PRICE
            $event->setLine(2, str_replace("{item}", $item->getName(), $result[2])); // ITEM NAME
            $event->setLine(3, str_replace("{amount}", $event->getLine(3), $result[3])); // AMOUNT
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
        $loc = $block->getX() . ":" . $block->getY() . ":" . $block->getZ() . ":" . $block->getLevel()->getFolderName();
        if (isset($this->shop[$loc])) {
            $shop = $this->shop[$loc];
            $player = $event->getPlayer();
            if ($player->getGamemode() % 2 == 1) {
                $player->sendMessage($config->get("error") . "Du kannst nur im Gamemode 0 was kaufen!");
                $event->setCancelled();
                return;
            }
            if (!$player->hasPermission("core.command.shop.buy")) {
                $player->sendMessage($config->get("error") . "§cDu hast keine Berechtigung um was zu kaufen!");
                $event->setCancelled();
                return;
            }
            if (!$player->getInventory()->canAddItem(Item::get($shop["item"], $shop["meta"]))) {
                $player->sendMessage($config->get("error") . "§cDein Inventar ist voll! Leere es bevor du was Kaufst");
                return;
            }
            if ($this->plugin->economyapi == null) {
                $geld = $money->getNested("money." . $player->getName());
            } else {
                $geld = EconomyAPI::getInstance()->myMoney($player);
            }
            if ($shop["price"] > $geld) {
                $player->sendMessage($config->get("error") . "§cDu hast zu wenig geld um dir was zu kaufen!" /*. [$shop["item"] . ":" . $shop["meta"], $shop["price"]]*/);
                $event->setCancelled(true);
                if ($event->getItem()->canBePlaced()) {
                    $this->placeQueue[$player->getName()] = true;
                }
                return;
            } else {
                if (!isset($shop["itemName"])) {
                    $item = $this->getItem($shop["item"], $shop["meta"], $shop["amount"]);
                    if ($item === false) {
                        $item = $shop["{item}"] . ":" . $shop["meta"];
                    } else {
                        $item = $item[0];
                    }
                    $this->shop[$loc]["itemName"] = $item;
                    $shop["itemName"] = $item;
                }
                $now = microtime(true);

                if(!isset($this->tap[$player->getName()]) or $now - $this->tap[$player->getName()][1] >= 1.5  or $this->tap[$player->getName()][0] !== $loc){
                    $this->tap[$player->getName()] = [$loc, $now];
                    $player->sendTip($config->get("money") . "§cDrücke erneut um was zu kaufen!");
                    return;
                }else{
                    unset($this->tap[$player->getName()]);
                }
                $signshop = ItemFactory::get((int)$shop ["item"], (int)$shop["meta"], (int)$shop["amount"]);
                $player->getInventory()->addItem($signshop);
                if ($this->plugin->economyapi == null) {
                    $money->setNested("money." . $player->getName(), $money->getNested("money." . $player->getName()) - $shop ["price"]);
                    $money->save();
                } else {
                    EconomyAPI::getInstance()->reduceMoney($player, $shop ["cost"]);
                }
                $player->sendTip($config->get("money") . "§6Du hast erfolgreich was gekauft!" /*. [$shop["amount"], $shop["itemName"], $shop["price"]]*/);
                $event->setCancelled(true);
                if ($event->getItem()->canBePlaced()) {
                    $this->placeQueue[$player->getName()] = true;
                }
            }
        }
    }

    public function onPlaceEvent(BlockPlaceEvent $event){
        $username = $event->getPlayer()->getName();
        if(isset($this->placeQueue[$username])){
            $event->setCancelled(true);
            unset($this->placeQueue[$username]);
        }
    }

    public function onBreakEvent(BlockBreakEvent $event){
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $block = $event->getBlock();
        if(isset($this->shop[$block->getX().":".$block->getY().":".$block->getZ().":".$block->getLevel()->getFolderName()])){
            $player = $event->getPlayer();
            if(!$player->hasPermission("core.economy.shop.remove")){
                $player->sendMessage($config->get("error") . "§cDu hast keine Berechtigung um diesen Shop zu zerstören!");
                $event->setCancelled(true);
                return;
            }
            $this->shop[$block->getX().":".$block->getY().":".$block->getZ().":".$block->getLevel()->getFolderName()] = null;
            unset($this->shop[$block->getX().":".$block->getY().":".$block->getZ().":".$block->getLevel()->getFolderName()]);
            $player->sendMessage($config->get("money") . "§6Der Shop wurde erfolgreich entfernt.");
        }
    }
    public function tagExists($tag){
        foreach($this->plugin->shopSign->getAll() as $key => $val){
            if($tag == $key){
                return $val;
            }
        }
        return false;
    }
}