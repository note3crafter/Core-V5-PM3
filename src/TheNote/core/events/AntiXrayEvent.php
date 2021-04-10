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
use pocketmine\event\entity\EntityExplodeEvent;
use pocketmine\event\Listener;
use pocketmine\event\server\DataPacketSendEvent;
use pocketmine\network\mcpe\protocol\BatchPacket;
use pocketmine\network\mcpe\protocol\LevelChunkPacket;
use pocketmine\network\mcpe\protocol\PacketPool;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;
use pocketmine\Server;
use TheNote\core\Main;
use TheNote\core\task\ChunkModificationTask;
use TheNote\core\server\ModifiedChunk;
use TheNote\core\task\BlockCalculationTask;
use function array_map;

class AntiXrayEvent implements Listener {

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }
	public function onDataSend(DataPacketSendEvent $event) {
		if (($batch = $event->getPacket()) instanceof BatchPacket && !($batch instanceof ModifiedChunk)) {
			$batch->decode();
			foreach (Main::getPacketsFromBatch($batch) as $packet) {
				$chunkPacket = PacketPool::getPacket($packet);
				if ($chunkPacket instanceof LevelChunkPacket) {
					$chunkPacket->decode();
                    Server::getInstance()->getAsyncPool()->submitTask(new ChunkModificationTask($event->getPlayer()->getLevel()->getChunk($chunkPacket->getChunkX(), $chunkPacket->getChunkZ()), $event->getPlayer()));
					$event->setCancelled();
				}
			}
		}
	}
	public function onBreak(BlockBreakEvent $event) {
		if ($event->isCancelled()) return;
		$players = $event->getBlock()->getLevel()->getChunkPlayers($event->getBlock()->getFloorX() >> 4, $event->getBlock()->getFloorZ() >> 4);
        $blocks = Main::getInvolvedBlocks([$event->getBlock()->asVector3()]);
		$event->getPlayer()->getLevel()->sendBlocks($players, $blocks, UpdateBlockPacket::FLAG_NEIGHBORS);
	}

    public function onExplode(EntityExplodeEvent $event) {
        if ($event->isCancelled()) return;
        Server::getInstance()->getAsyncPool()->submitTask(new BlockCalculationTask(array_map(function($block) {
            return $block->asVector3();
        }, $event->getBlockList()), $event->getEntity()->getLevelNonNull()->getFolderName()));
    }
}
