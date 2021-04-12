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
use TheNote\core\server\generators\normal\populator\object\Plant;

class PlantPopulator extends AmountPopulator {

    private $plants = [];
    private $allowedBlocks = [];

    public function addPlant(Plant $plant) {
        $this->plants[] = $plant;
    }

    public function allowBlockToStayAt(int $blockId) {
        $this->allowedBlocks[] = $blockId;
    }

    public function populateObject(ChunkManager $level, int $chunkX, int $chunkZ, Random $random): void {
        if(count($this->plants) === 0) {
            return;
        }

        $this->getRandomSpawnPosition($level, $chunkX, $chunkZ, $random, $x, $y, $z);

        if($y !== -1 and $this->canPlantStay($level, $x, $y, $z)){
            $plant = $random->nextRange(0, (int)(count($this->plants)-1));
            $pY = $y;
            foreach ($this->plants[$plant]->blocks as $block) {
                $level->setBlockIdAt($x, $pY, $z, $block->getId());
                $level->setBlockDataAt($x, $pY, $z, $block->getDamage());
                $pY++;
            }
        }
    }

    private function canPlantStay(ChunkManager $level, int $x, int $y, int $z): bool {
        $b = $level->getBlockIdAt($x, $y, $z);
        return ($b === Block::AIR or $b === Block::SNOW_LAYER or $b === Block::WATER) and in_array($level->getBlockIdAt($x, $y - 1, $z), array_merge([Block::GRASS], $this->allowedBlocks)) ;
    }
}