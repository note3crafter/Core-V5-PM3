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

use pocketmine\block\Dandelion;
use pocketmine\block\Flower;
use TheNote\core\server\generators\normal\biome\types\GrassyBiome;
use TheNote\core\server\generators\normal\populator\impl\LakePopulator;
use TheNote\core\server\generators\normal\populator\impl\PlantPopulator;
use TheNote\core\server\generators\normal\populator\impl\StructurePopulator;
use TheNote\core\server\generators\normal\populator\impl\TallGrassPopulator;
use TheNote\core\server\generators\normal\populator\impl\TreePopulator;
use TheNote\core\server\generators\normal\populator\object\Plant;

class Plains extends GrassyBiome {

    public function __construct() {
        parent::__construct(0.8, 0.4);

        $flowers = new PlantPopulator(9, 7, 85);
        $flowers->addPlant(new Plant(new Dandelion()));
        $flowers->addPlant(new Plant(new Flower()));
        $daisy = new PlantPopulator(9, 7, 85);
        $daisy->addPlant(new Plant(new Flower(8)));
        $bluet = new PlantPopulator(9,7, 85);
        $bluet->addPlant(new Plant(new Flower(3)));
        $tulips = new PlantPopulator(9, 7, 85);
        $tulips->addPlant(new Plant(new Flower(4)));
        $tulips->addPlant(new Plant(new Flower(5)));
        $tree = new TreePopulator(2, 1, 85);
        $lake = new LakePopulator();
        $tallGrass = new TallGrassPopulator(89, 26);
        $this->addPopulators([new StructurePopulator(), $lake, $flowers, $daisy, $bluet, $tulips, $tree, $tallGrass]);
        $this->setElevation(64, 68);
    }

    public function getName(): string {
        return "Plains";
    }
}