<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server\generators\void;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\Generator;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;

class VoidGenerator extends Generator {

    protected $level;
    protected $random;
    private $options;

    public function getSettings() : array {
        return [];
    }

    public function getName() : string {
        return "void";
    }

    public function __construct(array $settings = []){
        $this->options = $settings;
    }

    public function init(ChunkManager $level, Random $random): void {
        $this->level = $level;
        $this->random = $random;
    }

    public function generateChunk(int $chunkX, int $chunkZ): void {
        $chunk = $this->level->getChunk($chunkX, $chunkZ);
        for($x = 0; $x < 16; ++$x) {
            for ($z = 0; $z < 16; ++$z) {
                for($y = 0; $y < 168; ++$y) {
                    $spawn = $this->getSpawn();
                    if($spawn->getX() >> 4 === $chunkX && $spawn->getZ() >> 4 === $chunkZ){
                        $chunk->setBlockId(0, 64, 0, Block::GRASS);
                    }
                    else {
                        $chunk->setBlockId($x, $y, $z, Block::AIR);
                    }
                }
            }
        }
        $chunk->setGenerated(true);
    }

    public function populateChunk(int $chunkX, int $chunkZ): void {}

    public function getSpawn(): Vector3 {
        return new Vector3(256, 65, 256);
    }
}