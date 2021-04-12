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

use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;
use TheNote\core\server\generators\normal\populator\Populator;
use TheNote\core\server\structure\StructureManager;

class StructurePopulator extends Populator {

    public function populate(ChunkManager $level, int $chunkX, int $chunkZ, Random $random) {
        if($random->nextBoundedInt(200) !== 0) {
            return;
        }

        $this->getRandomSpawnPosition($level, $chunkX, $chunkZ, $random, $x, $y, $z);

        $pillagerOutpost = StructureManager::getStructure(PillagerOutpost::class);
        $pillagerOutpost->placeAt($level, $x, $y, $z, $random);
    }
}