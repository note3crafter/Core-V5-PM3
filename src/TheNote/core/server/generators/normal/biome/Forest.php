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
use pocketmine\block\DoublePlant;
use pocketmine\block\Flower;
use pocketmine\block\RedMushroom;
use TheNote\core\server\generators\normal\biome\types\GrassyBiome;
use TheNote\core\server\generators\normal\object\Tree;
use TheNote\core\server\generators\normal\populator\impl\PlantPopulator;
use TheNote\core\server\generators\normal\populator\impl\TallGrassPopulator;
use TheNote\core\server\generators\normal\populator\impl\TreePopulator;
use TheNote\core\server\generators\normal\populator\object\Plant;

class Forest extends GrassyBiome {

    public function __construct() {
        parent::__construct(0.7, 0.8);

        $mushrooms = new PlantPopulator(4, 3, 95);
        $mushrooms->addPlant(new Plant(new BrownMushroom()));
        $mushrooms->addPlant(new Plant(new RedMushroom()));

        $flowers = new PlantPopulator(6, 7, 80);
        $flowers->addPlant(new Plant(new Dandelion()));
        $flowers->addPlant(new Plant(new Flower()));

        $roses = new PlantPopulator(5, 4, 75);
        $roses->addPlant(new Plant(new DoublePlant(4), new DoublePlant(12)));

        $peonys = new PlantPopulator(5, 4, 75);
        $peonys->addPlant(new Plant(new DoublePlant(1), new DoublePlant(9)));


        $oak = new TreePopulator(3, 3);
        $birch = new TreePopulator(3, 3, 100, Tree::BIRCH);

        $grass = new TallGrassPopulator(56, 30);

        $this->addPopulators([$oak, $birch, $flowers, $peonys, $roses, $mushrooms, $grass]);

        $this->setElevation(64, 74);
    }

    public function getName(): string {
        return "Forest";
    }
}