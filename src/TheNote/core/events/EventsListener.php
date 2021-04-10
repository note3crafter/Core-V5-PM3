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
use pocketmine\event\block\BlockSpreadEvent;
use pocketmine\event\Listener;
use pocketmine\nbt\tag\StringTag;
use TheNote\core\entity\SkullEntity;
use TheNote\core\Main;

class EventsListener implements Listener {

    public function onBreak(BlockBreakEvent $event) {
        if ($event->isCancelled()) return;
        if ($event->getBlock()->getId() === Block::SKULL_BLOCK) {
            if (($skull = $event->getBlock()->getLevelNonNull()->getNearestEntity($event->getBlock()->floor()->add(0.5, 0, 0.5), 0.5)) instanceof SkullEntity) {

                $name = ($skull->namedtag->hasTag("skull_name", StringTag::class) ? $skull->namedtag->getString("skull_name") : "-");

                $event->setDrops([Main::constructPlayerHeadItem($name, $skull->getSkin())]);

                $skull->flagForDespawn();
            }
        }
    }

    public function onSpread(BlockSpreadEvent $event) {
        if ($event->isCancelled()) return;
        if ($event->getBlock()->getId() === Block::SKULL_BLOCK and $event->getBlock()->getDamage() === 1) {
            if (($skull = $event->getBlock()->getLevelNonNull()->getNearestEntity($event->getBlock()->floor()->add(0.5, 0, 0.5), 0.3)) instanceof SkullEntity) {

                $name = ($skull->namedtag->hasTag("skull_name", StringTag::class) ? $skull->namedtag->getString("skull_name") : "-");

                $event->getBlock()->getLevelNonNull()->dropItem($event->getBlock()->add(0, 0.5), Main::constructPlayerHeadItem($name, $skull->getSkin()));

                $skull->flagForDespawn();
            }
        }
    }
}
