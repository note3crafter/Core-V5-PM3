<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server\structure\type;

use pocketmine\block\BlockIds;
use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;
use TheNote\core\server\structure\object\StructureFeature;
use TheNote\core\server\structure\object\StructureObject;
use TheNote\core\server\structure\Structure;
use TheNote\core\utils\PositionCalc;

class PillagerOutpost extends Structure {
    use PositionCalc;

    public const STRUCTURE_NAME = "pillager_outpost";
    private const WATCHTOWER_FILENAME = "watchtower";
    private const WATCHTOWER_OVERGROWN_FILENAME = "watchtower_overgrown";
    public $features = [];

    public function __construct(string $dir) {
        parent::__construct($dir);

        $object = new StructureObject;

        foreach ($this->getTargetFiles() as $file) {
            if(in_array(basename($file, ".nbt"), [self::WATCHTOWER_FILENAME, self::WATCHTOWER_OVERGROWN_FILENAME])) {
                $object->load($file);
            }
            else {
                $this->features[] = new StructureFeature($file);
            }
        }

        $this->addObject($object, self::STRUCTURE_NAME);
    }

    public function placeAt(ChunkManager $level, int $x, int $y, int $z, Random $random): void {
        $this->placeMainBuilding($level, $x, $y, $z, $random);
        $this->placeFeatures($level, $x, $y, $z, $random);
    }

    private function placeMainBuilding(ChunkManager $level, int $x, int $y, int $z, Random $random) {
        $air = 0;

        for($xx = 0; $xx < 13; $xx++) {
            for($zz = 0; $zz < 13; $zz++) {
                if($level->getBlockIdAt($x+$xx, $y-1, $z + $zz) == BlockIds::AIR) {
                    $air++;
                }
            }
        }

        if($air > 1) {
            $this->placeAt($level, $x, $y-1, $z, $random);
            return;
        }

        for($xx = 0; $xx < 13; $xx++) {
            for($zz = 0; $zz < 13; $zz++) {
                $level->setBlockIdAt($x+$xx, $y-1, $z + $zz, BlockIds::GRASS);
            }
        }

        foreach ($this->getObject(self::STRUCTURE_NAME)->getBlocks($random) as [$xx, $yy, $zz, $block]) {
            $level->setBlockIdAt($x + $xx, $y + $yy, $z + $zz, $block->getId());
            $level->setBlockDataAt($x + $xx, $y + $yy, $z + $zz, $block->getMeta());
        }
    }

    private function placeFeatures(ChunkManager $level, int $x, int $y, int $z, Random $random) {
        $x += 7;
        $z += 7;

        $featuresCount = $random->nextBoundedInt(3) + 1;
        $usedFeatures = [];

        for($i = 0; $i < $featuresCount; $i++) {

            choosingTheFeature:
            $featureType = $this->features[$random->nextBoundedInt(count($this->features))];
            if(in_array($featureType->path, $usedFeatures)) {
                goto choosingTheFeature;
            }

            $usedFeatures[] = $featureType->path;

            $xx = 13 + $random->nextBoundedInt(8);
            $zz = 13 + $random->nextBoundedInt(8);

            if($random->nextBoolean()) {
                $xx = -$xx;
            }
            if($random->nextBoolean()) {
                $zz = -$zz;
            }

            $x += $xx;
            $z += $zz;
            $y = 50;
            for(; $y < 100; $y++) {
                if($level->getBlockIdAt($x, $y, $z) === BlockIds::AIR) {
                    break;
                }
            }

            $break = false;
            for($xx = 0; $xx < 9 && !$break; $xx++) {
                for($zz = 0; $zz < 9 && !$break; $zz++) {
                    for($yy = 0; $yy < 8 && !$break; $yy++) {
                        if(!in_array($level->getBlockIdAt($xx + $x, $yy + $y, $zz + $z), [BlockIds::AIR, BlockIds::GRASS, BlockIds::DIRT, BlockIds::DOUBLE_PLANT, BlockIds::TALL_GRASS])) {
                            $break = true;
                            break;
                        }
                    }
                }
            }

            if($break) {
                break;
            }

            foreach ($featureType->getBlocks($random) as [$xx, $yy, $zz, $block]) {
                $level->setBlockIdAt($x + $xx, $y + $yy, $z + $zz, $block->getId());
                $level->setBlockDataAt($x + $xx, $y + $yy, $z + $zz, $block->getMeta());
            }
        }
    }
}