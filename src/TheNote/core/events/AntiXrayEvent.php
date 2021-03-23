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
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\BatchPacket;
use pocketmine\network\mcpe\protocol\LevelChunkPacket;
use pocketmine\network\mcpe\protocol\PacketPool;
use pocketmine\network\mcpe\protocol\UpdateBlockPacket;
use pocketmine\Server;
use TheNote\core\Main;
use TheNote\core\task\ChunkModificationTask;
use TheNote\core\server\ModifiedChunk;
use function array_chunk;

class AntiXrayEvent implements Listener {

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

	public function onDataSend(DataPacketSendEvent $event) {
		/** @var $batch BatchPacket */
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
		
		$blocks = $this->getInvolvedBlocks([$event->getBlock()->asVector3()]);
		
		$event->getPlayer()->getLevel()->sendBlocks($players, $blocks, UpdateBlockPacket::FLAG_NEIGHBORS);
	}

	public function onExplode(EntityExplodeEvent $event) {
		if ($event->isCancelled()) return;
		$players = $event->getPosition()->getLevel()->getChunkPlayers($event->getPosition()->getFloorX() >> 4, $event->getPosition()->getFloorZ() >> 4);
		foreach (array_chunk($this->getInvolvedBlocks($event->getBlockList()), 450) as $blocks) {
			$event->getPosition()->getLevel()->sendBlocks($players, $blocks, UpdateBlockPacket::FLAG_NEIGHBORS);
		}
	}

	public function getInvolvedBlocks(array $blocks): array {
		$finalBlocks = $blocks;
		
		foreach ($blocks as $key => $block) {
			foreach (ChunkModificationTask::BLOCK_SIDES as $side) {
				$side = $blocks[$key]->getSide($side);
				
				foreach (ChunkModificationTask::BLOCK_SIDES as $side_2)
					$finalBlocks[] = $side->getSide($side_2);
				
				$finalBlocks[] = $side;
			}
		}
		return $finalBlocks;
	}
}
