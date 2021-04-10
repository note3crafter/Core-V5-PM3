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

use TheNote\core\blocks\Placeholder;
use TheNote\core\item\newitems\ItemFactory;
use pocketmine\event\entity\EntityInventoryChangeEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\BatchPacket;
use pocketmine\network\mcpe\protocol\LevelChunkPacket;
use pocketmine\network\mcpe\protocol\PacketPool;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;
use pocketmine\scheduler\ClosureTask;

class NewItemsEvents implements Listener
{
    public function onEntityInventoryChange(EntityInventoryChangeEvent $event): void{
        $item = $event->getNewItem();
        if($item->getVanillaName() == 'Unknown' and ItemFactory::isRegistered($item->getId())){
            $event->setNewItem(ItemFactory::get($item->getId(), $item->getDamage(), $item->getCount(), $item->getNamedTag()));
        }
    }
    public function onInventoryTransaction(InventoryTransactionEvent $event){
        foreach($event->getTransaction()->getActions() as $action){
            $item = $action->getTargetItem();
            if($item->getVanillaName() == 'Unknown' and ItemFactory::isRegistered($item->getId())){
                $targetItem = new \ReflectionProperty($action, 'targetItem');
                $targetItem->setAccessible(true);
                $targetItem->setValue($action, ItemFactory::get($item->getId(), $item->getDamage(), $item->getCount(), $item->getNamedTag()));
            }
        }
    }
    public function onPlayerLogin(PlayerLoginEvent $event): void{
        $player = $event->getPlayer();
        $player->getInventory()->setContents(array_map(static function(Item $item): Item{
            if($item->getId() > 0){
                return ItemFactory::get($item->getId(), $item->getDamage(), $item->getCount(), $item->getNamedTag());
            }
            return $item;
        }, $player->getInventory()->getContents()));
    }
    public function onDataPacketSend(DataPacketSendEvent $event): void{
        $packet = $event->getPacket();
        $player = $event->getPlayer();
        $level = $player->getLevel();
        if($packet instanceof BatchPacket){
            $blocks = [];
            $packet->decode();
            foreach($packet->getPackets() as $buf){
                $pk = PacketPool::getPacket($buf);
                if($pk instanceof LevelChunkPacket){
                    $pk->decode();
                    foreach($level->getChunkTiles($pk->getChunkX(), $pk->getChunkZ()) as $tile){
                        if($tile->getBlock() instanceof Placeholder){
                            $blocks[] = $tile->getBlock(true);
                        }
                    }
                }
            }
            if(count($blocks) > 0){
                $this->plugin->getScheduler()->scheduleDelayedTask(new ClosureTask(static function() use($blocks, $player, $level): void{
                    $level->sendBlocks([$player], $blocks, UpdateBlockPacket::FLAG_ALL_PRIORITY);
                }), intdiv($player->getPing(), 50) + 1);
            }
        }
    }
}
