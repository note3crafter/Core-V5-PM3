<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server\generators\normal\biome\types;

use pocketmine\level\biome\Biome as Bio;

abstract class Biome extends Bio {

    private $isFrozen = false;

    public function __construct(float $temperature, float $rainfall) {
        $this->temperature = $temperature;
        $this->rainfall = $rainfall;

        $this->isFrozen = ($temperature <= 0);
    }

    public function isFrozen(): bool {
        return $this->isFrozen;
    }

    public function setFrozen(bool $isFrozen = true): void {
        $this->isFrozen = $isFrozen;
    }

    public function addPopulators(array $populators = []) {
        foreach ($populators as $populator) {
            $this->addPopulator($populator);
        }
    }
}