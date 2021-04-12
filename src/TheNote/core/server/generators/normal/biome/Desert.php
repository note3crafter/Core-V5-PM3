<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server\generators\normal\biome;

use pocketmine\block\Block;
use pocketmine\block\DeadBush;
use TheNote\core\server\generators\normal\biome\types\SandyBiome;
use TheNote\core\server\generators\normal\populator\impl\CactusPopulator;
use TheNote\core\server\generators\normal\populator\impl\PlantPopulator;
use TheNote\core\server\generators\normal\populator\object\Plant;

class Desert extends SandyBiome {

    public function __construct() {
        parent::__construct(2.0, 0.0);

        $cactus = new CactusPopulator(4, 3);

        $deadBush = new PlantPopulator(4, 2);
        $deadBush->addPlant(new Plant(new DeadBush()));
        $deadBush->allowBlockToStayAt(Block::SAND);

        $this->addPopulators([$cactus, $deadBush]);

        $this->setElevation(63, 69);
    }

    public function getName(): string {
        return "Desert";
    }
}