<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//                        2017-2020

namespace TheNote\core\events;

use pocketmine\block\Block;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockSpreadEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\level\generator\GeneratorManager;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\protocol\ChangeDimensionPacket;
use pocketmine\Player;
use pocketmine\Server;
use TheNote\core\entity\SkullEntity;
use TheNote\core\Main;

class EventsListener implements Listener
{

    public function onBreak(BlockBreakEvent $event)
    {
        if ($event->isCancelled()) return;
        if ($event->getBlock()->getId() === Block::SKULL_BLOCK) {
            if (($skull = $event->getBlock()->getLevelNonNull()->getNearestEntity($event->getBlock()->floor()->add(0.5, 0, 0.5), 0.5)) instanceof SkullEntity) {

                $name = ($skull->namedtag->hasTag("skull_name", StringTag::class) ? $skull->namedtag->getString("skull_name") : "-");

                $event->setDrops([Main::constructPlayerHeadItem($name, $skull->getSkin())]);

                $skull->flagForDespawn();
            }
        }
    }

    public function onSpread(BlockSpreadEvent $event)
    {
        if ($event->isCancelled()) return;
        if ($event->getBlock()->getId() === Block::SKULL_BLOCK and $event->getBlock()->getDamage() === 1) {
            if (($skull = $event->getBlock()->getLevelNonNull()->getNearestEntity($event->getBlock()->floor()->add(0.5, 0, 0.5), 0.3)) instanceof SkullEntity) {

                $name = ($skull->namedtag->hasTag("skull_name", StringTag::class) ? $skull->namedtag->getString("skull_name") : "-");

                $event->getBlock()->getLevelNonNull()->dropItem($event->getBlock()->add(0, 0.5), Main::constructPlayerHeadItem($name, $skull->getSkin()));

                $skull->flagForDespawn();
            }
        }
    }

    public function onPlace(BlockPlaceEvent $event)
    {
        $player = $event->getPlayer();
        $inv = $player->getInventory()->getItemInHand();
        if ($player->getLevelNonNull()->getProvider()->getGenerator() === "myplot") {
            if ($inv->getId() === Item::COMPARATOR) {
                $event->setCancelled(true);
                $player->sendTip("§cThis item is banned here!");
                return false;
            }
            return true;
        }
        if ($inv->getId() === Item::COMPARATOR) {
            if ($player->hasPermission("core.redstone.comparator")) {
                $event->setCancelled(false);
            } else {
                $event->setCancelled(true);
                $player->sendTip("§cNo Permission to use that item!");
            }
        }
        if ($inv->getId() === Item::REPEATER) {
            if ($player->hasPermission("core.redstone.repeater")) {
                $event->setCancelled(false);
            } else {
                $event->setCancelled(true);
                $player->sendTip("§cNo Permission to use that item!");
            }
        }
        if ($inv->getId() === Item::REDSTONE_LAMP) {
            if ($player->hasPermission("core.redstone.lamp")) {
                $event->setCancelled(false);
            } else {
                $event->setCancelled(true);
                $player->sendTip("§cNo Permission to use that item!");
            }
        }
        if ($inv->getId() === Item::PISTON) {
            if ($player->hasPermission("core.redstone.piston")) {
                $event->setCancelled(false);
            } else {
                $event->setCancelled(true);
                $player->sendTip("§cNo Permission to use that item!");
            }
        }
        if ($inv->getId() === Item::STICKY_PISTON) {
            if ($player->hasPermission("core.redstone.stickypiston")) {
                $event->setCancelled(false);
            } else {
                $event->setCancelled(true);
                $player->sendTip("§cNo Permission to use that item!");
            }
        }
        if ($inv->getId() === Item::OBSERVER) {
            if ($player->hasPermission("core.redstone.observer")) {
                $event->setCancelled(false);
            } else {
                $event->setCancelled(true);
                $player->sendTip("§cNo Permission to use that item!");
            }
        }
        if ($inv->getId() === Item::REDSTONE_TORCH) {
            if ($player->hasPermission("core.redstone.torch")) {
                $event->setCancelled(false);
            } else {
                $event->setCancelled(true);
                $player->sendTip("§cNo Permission to use that item!");
            }
        }
        if ($inv->getId() === Item::REDSTONE_WIRE) {
            if ($player->hasPermission("core.redstone.wire")) {
                $event->setCancelled(false);
            } else {
                $event->setCancelled(true);
                $player->sendTip("§cNo Permission to use that item!");
            }
        }
        if ($inv->getId() === Item::DROPPER) {
            if ($player->hasPermission("core.redstone.dropper")) {
                $event->setCancelled(false);
            } else {
                $event->setCancelled(true);
                $player->sendTip("§cNo Permission to use that item!");
            }
        }
        if ($inv->getId() === Item::DISPENSER) {
            if ($player->hasPermission("core.redstone.dispenser")) {
                $event->setCancelled(false);
            } else {
                $event->setCancelled(true);
                $player->sendTip("§cNo Permission to use that item!");
            }
        }
        if ($inv->getId() === Item::HOPPER) {
            if ($player->hasPermission("core.redstone.hopper")) {
                $event->setCancelled(false);
            } else {
                $event->setCancelled(true);
                $player->sendTip("§cNo Permission to use that item!");
            }
        }
        if ($inv->getId() === Item::COMMAND_BLOCK) {
            if ($player->hasPermission("core.redstone.commandblock")) {
                $event->setCancelled(false);
            } else {
                $event->setCancelled(true);
                $player->sendTip("§cNo Permission to use that item!");
            }
        }
    }

    public function onLevelChange(EntityLevelChangeEvent $event)
    {
        $entity = $event->getEntity();
        if ($entity instanceof Player) {

            $originGenerator = $event->getOrigin()->getProvider()->getGenerator();
            $targetGenerator = $event->getTarget()->getProvider()->getGenerator();

            $getDimension = function ($generator): int {
                switch ($generator) {
                    case "normal":
                    case "skyblock":
                    case "void":
                        return 0;
                    case "nether":
                        return 1;
                    case "ender":
                        return 2;
                    default:
                        return 0;
                }
            };

            if ($getDimension($originGenerator) == $getDimension($targetGenerator)) return;
            $pk = new ChangeDimensionPacket();
            $pk->dimension = $getDimension($targetGenerator);
            $pk->position = $event->getTarget()->getSpawnLocation();
            $entity->dataPacket($pk);
        }
    }
}
