<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server\generators\normal\populator\impl;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;
use TheNote\core\server\generators\normal\populator\AmountPopulator;

class CactusPopulator extends AmountPopulator {

    public function populateObject(ChunkManager $level, int $chunkX, int $chunkZ, Random $random): void {
        $this->getRandomSpawnPosition($level, $chunkX, $chunkZ, $random, $x, $y, $z);

        if($y !== -1 && $this->canCactusStay($level, new Vector3($x, $y, $z))){
            for($aY = 0; $aY < $random->nextRange(0, 3); $aY++) {
                $level->setBlockIdAt($x, $y + $aY, $z, Block::CACTUS);
                $level->setBlockDataAt($x, $y, $z, 1);
            }
        }
    }

    private function canCactusStay(ChunkManager $level, Vector3 $pos) : bool{
        $b = $level->getBlockIdAt($pos->getX(), $pos->getY(), $pos->getZ());
        if($level->getBlockIdAt($pos->getX() + 1, $pos->getY(), $pos->getZ()) != 0 ||
            $level->getBlockIdAt($pos->getX() - 1, $pos->getY(), $pos->getZ()) != 0 ||
            $level->getBlockIdAt($pos->getX(), $pos->getY(), $pos->getZ() + 1) != 0 ||
            $level->getBlockIdAt($pos->getX(), $pos->getY(), $pos->getZ() - 1) != 0) {
            return false;
        }

        return ($b === Block::AIR) && $level->getBlockIdAt($pos->getX(), $pos->getY() - 1, $pos->getZ()) === Block::SAND;
    }
}