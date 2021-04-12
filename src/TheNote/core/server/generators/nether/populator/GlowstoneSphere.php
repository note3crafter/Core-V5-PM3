<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server\generators\nether\populator;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\populator\Populator;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class GlowstoneSphere extends Populator {

    public const SPHERE_RADIUS = 3;

    public function populate(ChunkManager $level, int $chunkX, int $chunkZ, Random $random) {
        $chunk = $level->getChunk($chunkX, $chunkZ);
        if($random->nextRange(0, 10) !== 0) return;

        $x = $random->nextRange($chunkX << 4 , ($chunkX << 4) + 15);
        $z = $random->nextRange($chunkZ << 4, ($chunkZ << 4) + 15);

        $sphereY = 0;

        for($y = 0; $y < 127; $y++) {
            if($level->getBlockIdAt($x, $y, $z) == 0) {
                $sphereY = $y;
            }
        }

        if($sphereY < 80) {
            return;
        }

        $this->placeGlowstoneSphere($level, $random, new Vector3($x, $sphereY - $random->nextRange(2, 4), $z));
    }

    public function placeGlowstoneSphere(ChunkManager $level, Random $random, Vector3 $position) {
        for($x = $position->getX() - $this->getRandomRadius($random); $x < $position->getX() + $this->getRandomRadius($random); $x++) {
            $xsqr = ($position->getX()-$x) * ($position->getX()-$x);
            for($y = $position->getY() - $this->getRandomRadius($random); $y < $position->getY() + $this->getRandomRadius($random); $y++) {
                $ysqr = ($position->getY()-$y) * ($position->getY()-$y);
                for($z = $position->getZ() - $this->getRandomRadius($random); $z < $position->getZ() + $this->getRandomRadius($random); $z++) {
                    $zsqr = ($position->getZ()-$z) * ($position->getZ()-$z);
                    if(($xsqr + $ysqr + $zsqr) < (pow(2, $this->getRandomRadius($random)))) {
                        if($random->nextRange(0, 4) !== 0) {
                            $level->setBlockIdAt($x, $y, $z, Block::GLOWSTONE);
                        }
                    }
                }
            }
        }
    }

    public function getRandomRadius(Random $random): int {
        return $random->nextRange(self::SPHERE_RADIUS, self::SPHERE_RADIUS+2);
    }

}