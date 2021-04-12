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

use pocketmine\block\BlockIds;
use pocketmine\block\BrownMushroom;
use pocketmine\block\Dirt;
use pocketmine\block\Mycelium;
use pocketmine\block\RedMushroom;
use TheNote\core\server\generators\normal\biome\types\Biome;
use TheNote\core\server\generators\normal\object\Tree;
use TheNote\core\server\generators\normal\populator\impl\PlantPopulator;
use TheNote\core\server\generators\normal\populator\impl\TreePopulator;
use TheNote\core\server\generators\normal\populator\object\Plant;

class MushroomIsland extends Biome {

    public function __construct() {
        parent::__construct(0.9, 1);
        $this->setGroundCover([
            new Mycelium(),
            new Dirt(),
            new Dirt(),
            new Dirt()

        ]);
        $mushrooms = new PlantPopulator(5, 4, 95);
        $mushrooms->addPlant(new Plant(new RedMushroom()));
        $mushrooms->addPlant(new Plant(new BrownMushroom()));
        $mushrooms->allowBlockToStayAt(BlockIds::MYCELIUM);
        $this->addPopulators([$mushrooms, new TreePopulator(1, 1, 100, Tree::MUSHROOM)]);
        $this->setElevation(64, 74);
    }


    public function getName(): string {
        return "Mushroom Island";
    }
}