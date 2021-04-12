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
use pocketmine\block\DoublePlant;
use pocketmine\block\RedMushroom;
use pocketmine\block\Sapling;
use TheNote\core\server\generators\normal\biome\types\GrassyBiome;
use TheNote\core\server\generators\normal\object\Tree;
use TheNote\core\server\generators\normal\populator\impl\PlantPopulator;
use TheNote\core\server\generators\normal\populator\impl\TallGrassPopulator;
use TheNote\core\server\generators\normal\populator\impl\TreePopulator;
use TheNote\core\server\generators\normal\populator\object\Plant;

class RoofedForest extends GrassyBiome {

    public function __construct() {
        parent::__construct(0.7, 0.8);

        $mushrooms = new PlantPopulator(4, 2, 95);
        $mushrooms->addPlant(new Plant(new BrownMushroom()));
        $mushrooms->addPlant(new Plant(new RedMushroom()));

        $roses = new PlantPopulator(5, 4, 80);
        $roses->addPlant(new Plant(new DoublePlant(4), new DoublePlant(12)));

        $peonys = new PlantPopulator(5, 4, 80);
        $peonys->addPlant(new Plant(new DoublePlant(1), new DoublePlant(9)));

        $tree = new TreePopulator(4,2, 100, Tree::DARK_OAK);
        $mushroom = new TreePopulator(1,  1, 95, Tree::MUSHROOM);
        $birch = new TreePopulator(1, 2, 100, Sapling::BIRCH);
        $oak = new TreePopulator(1, 2, 100,Sapling::OAK);

        $grass = new TallGrassPopulator(56, 20);

        $this->addPopulators([$tree, $peonys, $roses, $mushrooms, $mushroom, $birch, $oak, $grass]);
        $this->setElevation(64, 74);
    }

    public function getName(): string {
        return "Roofed Forest";
    }
}