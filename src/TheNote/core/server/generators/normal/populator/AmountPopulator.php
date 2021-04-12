<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server\generators\normal\populator;

use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;

abstract class AmountPopulator extends Populator {

    private $baseAmount = 1;
    private $randomAmount = 0;
    private $spawnPercentage = 100;

    public function __construct(int $baseAmount, int $randomAmount, ?int $spawnPercentage = null) {
        $this->baseAmount = $baseAmount;
        $this->randomAmount = $randomAmount;

        if(!is_null($spawnPercentage)) {
            $this->spawnPercentage = $spawnPercentage;
        }
    }

    public function setBaseAmount(int $baseAmount): void {
        $this->baseAmount = $baseAmount;
    }

    public function setRandomAmount(int $randomAmount): void {
        $this->randomAmount = $randomAmount;
    }

    public function setSpawnPercentage(int $percentage): void {
        $this->spawnPercentage = $percentage;
    }

    public final function populate(ChunkManager $level, int $chunkX, int $chunkZ, Random $random): void {
        if($random->nextRange($this->spawnPercentage, 100) != 100) {
            return;
        }

        $amount = $random->nextBoundedInt($this->randomAmount + 1) + $this->baseAmount;
        for($i = 0; $i < $amount; $i++) {
            $this->populateObject($level, $chunkX, $chunkZ, $random);
        }
    }

    abstract public function populateObject(ChunkManager $level, int $chunkX, int $chunkZ, Random $random): void;
}