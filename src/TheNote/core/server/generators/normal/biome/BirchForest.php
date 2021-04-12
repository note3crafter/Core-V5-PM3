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

use TheNote\core\server\generators\normal\biome\types\GrassyBiome;
use TheNote\core\server\generators\normal\object\Tree;
use TheNote\core\server\generators\normal\populator\impl\PlantPopulator;
use TheNote\core\server\generators\normal\populator\impl\TallGrassPopulator;
use TheNote\core\server\generators\normal\populator\impl\TreePopulator;
use TheNote\core\server\generators\normal\populator\object\Plant;
use pocketmine\block\BrownMushroom;
use pocketmine\block\Dandelion;
use pocketmine\block\DoublePlant;
use pocketmine\block\Flower;
use pocketmine\block\RedMushroom;

class BirchForest extends GrassyBiome {

    public function __construct() {
        parent::__construct(0.6, 0.6);

        $mushrooms = new PlantPopulator(2, 2, 95);
        $mushrooms->addPlant(new Plant(new BrownMushroom()));
        $mushrooms->addPlant(new Plant(new RedMushroom()));

        $flowers = new PlantPopulator(6, 7, 80);
        $flowers->addPlant(new Plant(new Dandelion()));
        $flowers->addPlant(new Plant(new Flower()));

        $roses = new PlantPopulator(5, 4, 80);
        $roses->addPlant(new Plant(new DoublePlant(4), new DoublePlant(12)));

        $peonys = new PlantPopulator(5, 4, 80);
        $peonys->addPlant(new Plant(new DoublePlant(1), new DoublePlant(9)));

        $birch = new TreePopulator(5, 4, 100, Tree::BIRCH);
        $grass = new TallGrassPopulator(56, 20);

        $this->addPopulators([$birch, $flowers, $peonys, $roses, $mushrooms, $grass]);

        $this->setElevation(66, 79);
    }

    public function getName(): string {
        return "Birch Forest";
    }
}