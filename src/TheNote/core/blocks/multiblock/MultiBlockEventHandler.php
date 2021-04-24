<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//                        2017-2020

namespace TheNote\core\blocks\multiblock;

use pocketmine\event\block\BlockUpdateEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerMoveEvent;

final class MultiBlockEventHandler implements Listener {

    public function onBlockUpdate(BlockUpdateEvent $event): void{
        $block = $event->getBlock();
        $multiBlock = MultiBlockFactory::get($block);
        if($multiBlock !== null && $multiBlock->update($block)){
            $event->setCancelled();
        }
    }

    public function onPlayerInteract(PlayerInteractEvent $event): void{
        if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK){
            $block = $event->getBlock();
            $multiBlock = MultiBlockFactory::get($block);
            if($multiBlock !== null && $multiBlock->interact($block, $event->getPlayer(), $event->getItem(), $event->getFace())){
                $event->setCancelled();
            }
        }
    }

    public function onPlayerMove(PlayerMoveEvent $event): void{
        $from = $event->getFrom();
        $fromFloor = $from->floor();
        $to = $event->getTo();
        $toFloor = $to->floor();
        if($fromFloor->equals($toFloor)) return;
        $player = $event->getPlayer();
        $fromBlock = MultiBlockFactory::get($block = $from->level->getBlockAt($fromFloor->x, $fromFloor->y, $fromFloor->z));
        if($fromBlock !== null){
            $fromBlock->onPlayerMoveOutside($player, $block);
        }
        $toBlock = MultiBlockFactory::get($block = $to->level->getBlockAt($toFloor->x, $toFloor->y, $toFloor->z));
        if($toBlock !== null){
            $toBlock->onPlayerMoveInside($player, $block);
        }
    }
}