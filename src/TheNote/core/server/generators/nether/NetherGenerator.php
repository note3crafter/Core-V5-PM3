<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server\generators\nether;

use pocketmine\block\Block;
use pocketmine\block\NetherQuartzOre;
use pocketmine\level\biome\Biome;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\Generator;
use pocketmine\level\generator\noise\Simplex;
use pocketmine\level\generator\object\OreType;
use pocketmine\level\generator\populator\Populator;
use pocketmine\math\Vector3;
use pocketmine\utils\Random;
use TheNote\core\server\generators\nether\populator\GlowstoneSphere;
use TheNote\core\server\generators\nether\populator\Ore;
use TheNote\core\server\generators\nether\populator\SoulSand;

class NetherGenerator extends Generator {

    private $populators = [];
    private $waterHeight = 32;
    private $emptyHeight = 64;
    private $emptyAmplitude = 1;
    private $density = 0.5;
    private $generationPopulators = [];
    private $noiseBase;

    public function __construct(array $options = []) {}

    public function getName(): string {
        return "nether";
    }

    public function getSettings(): array {
        return [];
    }

    public function init(ChunkManager $level, Random $random) : void{
        parent::init($level, $random);
        $this->random->setSeed($this->level->getSeed());
        $this->noiseBase = new Simplex($this->random, 4, 1 / 4, 1 / 64);
        $this->random->setSeed($this->level->getSeed());

        $ores = new Ore();
        $ores->setOreTypes([
            new OreType(new NetherQuartzOre(), 50, 14, 0, 128)
        ]);
        $this->populators[] = $ores;
        $this->populators[] = new GlowstoneSphere();
        $this->populators[] = new SoulSand();
    }

    public function generateChunk(int $chunkX, int $chunkZ) : void{
        $this->random->setSeed(0xdeadbeef ^ ($chunkX << 8) ^ $chunkZ ^ $this->level->getSeed());

        $noise = $this->noiseBase->getFastNoise3D(16, 128, 16, 4, 8, 4, $chunkX * 16, 0, $chunkZ * 16);
        $chunk = $this->level->getChunk($chunkX, $chunkZ);

        for($x = 0; $x < 16; ++$x){
            for($z = 0; $z < 16; ++$z){

                $biome = Biome::getBiome(Biome::HELL);
                $chunk->setBiomeId($x, $z, $biome->getId());

                for($y = 0; $y < 128; ++$y){
                    if($y === 0 or $y === 127){
                        $chunk->setBlockId($x, $y, $z, Block::BEDROCK);
                        continue;
                    }
                    if($y === 126) {
                        $chunk->setBlockId($x, $y, $z, Block::NETHERRACK);
                        continue;
                    }
                    $noiseValue = (\abs($this->emptyHeight - $y) / $this->emptyHeight) * $this->emptyAmplitude - $noise[$x][$z][$y];
                    $noiseValue -= 1 - $this->density;

                    if($noiseValue > 0){
                        $chunk->setBlockId($x, $y, $z, Block::NETHERRACK);
                    }elseif($y <= $this->waterHeight){
                        $chunk->setBlockId($x, $y, $z, Block::STILL_LAVA);
                    }
                }
            }
        }

        foreach($this->generationPopulators as $populator){
            $populator->populate($this->level, $chunkX, $chunkZ, $this->random);
        }
    }

    public function populateChunk(int $chunkX, int $chunkZ) : void{
        $this->random->setSeed(0xdeadbeef ^ ($chunkX << 8) ^ $chunkZ ^ $this->level->getSeed());
        foreach($this->populators as $populator){
            $populator->populate($this->level, $chunkX, $chunkZ, $this->random);
        }

        $chunk = $this->level->getChunk($chunkX, $chunkZ);
        $biome = Biome::getBiome($chunk->getBiomeId(7, 7));
        $biome->populateChunk($this->level, $chunkX, $chunkZ, $this->random);
    }

    public function getSpawn() : Vector3 {
        return new Vector3(127.5, 128, 127.5);
    }
}