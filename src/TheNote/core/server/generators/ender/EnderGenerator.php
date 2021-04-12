<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server\generators\ender;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\Generator;
use pocketmine\level\generator\noise\Simplex;
use pocketmine\math\Vector3 as Vector3;
use pocketmine\utils\Random;
use TheNote\core\server\generators\ender\populator\EnderPilar;

class EnderGenerator extends Generator {

    private $populators = [];
    protected $level;
    protected $random;
    private $waterHeight = 0;
    private $emptyHeight = 32;
    private $emptyAmplitude = 1;
    private $density = 0.6;
    private $generationPopulators = [];
    private $noiseBase;
    public function __construct(array $options = []) {}

    public function getName(): string {
        return "ender";
    }

    public function getWaterHeight(): int {
        return $this->waterHeight;
    }

    public function getSettings(): array {
        return [];
    }

    public function init(ChunkManager $level, Random $random): void {
        $this->level = $level;
        $this->random = $random;
        $this->random->setSeed($this->level->getSeed());
        $this->noiseBase = new Simplex($this->random, 4, 1 / 4, 1 / 64);
        $this->random->setSeed($this->level->getSeed());
        $pilar = new EnderPilar;
        $pilar->setBaseAmount(0);
        $pilar->setRandomAmount(0);
        $this->populators[] = $pilar;
    }

    public function generateChunk(int $chunkX, int $chunkZ): void {
        $this->random->setSeed(0xa6fe78dc ^ ($chunkX << 8) ^ $chunkZ ^ $this->level->getSeed());
        $noise = $this->noiseBase->getFastNoise3D(16, 128, 16, 4, 8, 4, $chunkX * 16, 0, $chunkZ * 16);

        $chunk = $this->level->getChunk($chunkX, $chunkZ);
        for ($x = 0; $x < 16; ++$x) {
            for ($z = 0; $z < 16; ++$z) {
                // 9 = biome end
                $chunk->setBiomeId($x, $z, 9);
                for ($y = 0; $y < 128; ++$y) {
                    $noiseValue = (abs($this->emptyHeight - $y) / $this->emptyHeight) * $this->emptyAmplitude - $noise[$x][$z][$y];
                    $noiseValue -= 1 - $this->density;
                    $distance = new Vector3(0, 64, 0);
                    $distance = $distance->distance(new Vector3($chunkX * 16 + $x, ($y / 1.3), $chunkZ * 16 + $z));
                    if ($noiseValue < 0 && $distance < 100 or $noiseValue < -0.2 && $distance > 400) {
                        $chunk->setBlockId($x, $y, $z, Block::END_STONE);
                    }
                }
            }
        }
        foreach ($this->generationPopulators as $populator) {
            $populator->populate($this->level, $chunkX, $chunkZ, $this->random);
        }
    }

    public function populateChunk(int $chunkX, int $chunkZ): void {
        $this->random->setSeed(0xa6fe78dc ^ ($chunkX << 8) ^ $chunkZ ^ $this->level->getSeed());
        foreach ($this->populators as $populator) {
            $populator->populate($this->level, $chunkX, $chunkZ, $this->random);
        }
    }

    public function getSpawn():Vector3 {
        return new Vector3(48, 128, 48);
    }
}