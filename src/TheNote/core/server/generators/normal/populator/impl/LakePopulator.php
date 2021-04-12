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
use TheNote\core\server\generators\normal\populator\Populator;

class LakePopulator extends Populator {

    public function populate(ChunkManager $level, int $chunkX, int $chunkZ, Random $random) {
        if($random->nextBoundedInt(7) != 0) {
            return;
        }

        $this->getRandomSpawnPosition($level, $chunkX, $chunkZ, $random, $x, $y, $z);
        $pos = new Vector3($x, $y, $z);

        $blocks = [];

        foreach ($this->getRandomShape($random) as $vec) {
            $finalPos = $pos->add($vec);

            $id = $vec->add($pos)->getY() < $pos->getY() ? Block::WATER : Block::AIR;

            $blocks[] = [$finalPos, $id];
            if($id == Block::WATER && in_array(Block::AIR, [$level->getBlockIdAt($finalPos->getX() + 1, $finalPos->getY(), $finalPos->getZ()), $level->getBlockIdAt($finalPos->getX() - 1, $finalPos->getY(), $finalPos->getZ()), $level->getBlockIdAt($finalPos->getX(), $finalPos->getY(), $finalPos->getZ() + 1), $level->getBlockIdAt($finalPos->getX(), $finalPos->getY(), $finalPos->getZ() - 1)])) {
                return;
            }

        }

        foreach ($blocks as [$vec, $id]) {
            $level->setBlockIdAt($vec->getX(), $vec->getY(), $vec->getZ(), $id);
            $level->setBlockDataAt($vec->getX(), $vec->getY(), $vec->getZ(), 0);
        }
    }

    private function getRandomShape(Random $random): \Generator {
        for($x = -($random->nextRange(12, 20)); $x < $random->nextRange(12, 20); $x++) {
            $xsqr = $x*$x;
            for($z = -($random->nextRange(12, 20)); $z < $random->nextRange(12, 20); $z++) {
                $zsqr = $z*$z;
                for($y = $random->nextRange(0, 1); $y < $random->nextRange(6, 7); $y++) {
                    if(($xsqr*1.5)+($zsqr*1.5) <= $random->nextRange(34, 40)) {
                        yield new Vector3($x, $y-4, $z);
                    }
                }
            }
        }
    }
}