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
use pocketmine\utils\Random;
use TheNote\core\server\generators\normal\populator\AmountPopulator;

class TallGrassPopulator extends AmountPopulator {

    private $allowDoubleGrass = true;

    public function setDoubleGrassAllowed(bool $allowed = true) {
        $this->allowDoubleGrass = $allowed;
    }

	public function populateObject(ChunkManager $level, int $chunkX, int $chunkZ, Random $random): void{
	    $this->getRandomSpawnPosition($level, $chunkX, $chunkZ, $random, $x, $y, $z);

        if($y !== -1 and $this->canTallGrassStay($level, $x, $y, $z)){
            $id = ($this->allowDoubleGrass && $random->nextBoundedInt(5) == 4) ? Block::DOUBLE_PLANT : Block::TALL_GRASS;
            $level->setBlockIdAt($x, $y, $z, $id);

            if($id == Block::DOUBLE_PLANT) {
                $level->setBlockDataAt($x, $y, $z, 2);
                $level->setBlockIdAt($x, $y+1, $z, $id);
                $level->setBlockDataAt($x, $y+1, $z, 10);
            }
        }
	}

	private function canTallGrassStay(ChunkManager $level, int $x, int $y, int $z) : bool{
		$b = $level->getBlockIdAt($x, $y, $z);
		return ($b === Block::AIR or $b === Block::SNOW_LAYER) and $level->getBlockIdAt($x, $y - 1, $z) === Block::GRASS;
	}
}
