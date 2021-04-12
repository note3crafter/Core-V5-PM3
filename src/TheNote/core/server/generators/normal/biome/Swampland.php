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

class Swampland extends GrassyBiome {

    public function __construct() {
        parent::__construct(0.8, 0.5);

        $mushrooms = new PlantPopulator(4, 2, 95);
        $mushrooms->addPlant(new Plant(new BrownMushroom()));
        $mushrooms->addPlant(new Plant(new RedMushroom()));
        $flowers = new PlantPopulator(6, 7, 80);
        $flowers->addPlant(new Plant(new Dandelion()));
        $flowers->addPlant(new Plant(new Flower()));
        $oak = new TreePopulator(2, 2, 100, Sapling::OAK, true);
        $tallGrass = new TallGrassPopulator(56, 12);
        $this->addPopulators([$mushrooms, $flowers, $oak, $tallGrass]);
        $this->setElevation(60, 65);
    }

    public function getName(): string {
        return "Swampland";
    }
}