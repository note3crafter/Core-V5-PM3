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

use pocketmine\block\BrownMushroom;
use pocketmine\block\Dandelion;
use pocketmine\block\Flower;
use pocketmine\block\RedMushroom;
use pocketmine\block\Sapling;
use TheNote\core\server\generators\normal\biome\types\GrassyBiome;
use TheNote\core\server\generators\normal\populator\impl\PlantPopulator;
use TheNote\core\server\generators\normal\populator\impl\TallGrassPopulator;
use TheNote\core\server\generators\normal\populator\impl\TreePopulator;
use TheNote\core\server\generators\normal\populator\object\Plant;

class Taiga extends GrassyBiome {

    public function __construct() {
        parent::__construct(0.25, 0.8);

        $mushrooms = new PlantPopulator(4, 5, 95);
        $mushrooms->addPlant(new Plant(new BrownMushroom()));
        $mushrooms->addPlant(new Plant(new RedMushroom()));
        $flowers = new PlantPopulator(6, 7, 80);
        $flowers->addPlant(new Plant(new Dandelion()));
        $flowers->addPlant(new Plant(new Flower()));
        $spruce = new TreePopulator(3, 2, 100, Sapling::SPRUCE);
        $tallGrass = new TallGrassPopulator(56, 12);
        $this->addPopulators([$mushrooms, $flowers, $spruce, $tallGrass]);
        $this->addPopulator($spruce);
        $this->addPopulator($flowers);
        $this->addPopulator($mushrooms);
        $this->setElevation(70, 79);
    }

    public function getName(): string {
        return "Taiga";
    }
}