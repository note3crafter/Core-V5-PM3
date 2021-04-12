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
use pocketmine\block\DeadBush;
use pocketmine\block\GoldOre;
use pocketmine\block\HardenedClay;
use pocketmine\block\StainedClay;
use pocketmine\level\generator\object\OreType;
use TheNote\core\server\generators\normal\populator\impl\PlantPopulator;
use TheNote\core\server\generators\normal\populator\object\Plant;
use TheNote\core\server\generators\nether\populator\Ore;

class MesaPlateau extends Mesa {

    public function __construct() {
        parent::__construct();

        $this->setGroundCover([
            new HardenedClay(),
            new StainedClay(0),
            new HardenedClay(),
            new HardenedClay(),
            new StainedClay(4),
            new StainedClay(4),
            new HardenedClay(),
            new HardenedClay(),
            new HardenedClay(),
            new StainedClay(1),
            new StainedClay(1),
            new HardenedClay()
        ]);

        $deadBush = new PlantPopulator(4, 3);
        $deadBush->addPlant(new Plant(new DeadBush()));
        $deadBush->allowBlockToStayAt(BlockIds::HARDENED_CLAY);

        $ore = new Ore();
        $ore->setOreTypes([
            new OreType(new GoldOre(), 20, 12, 0, 128)
        ]);

        $this->clearPopulators();
        $this->addPopulators([$deadBush, $ore]);

        $this->setElevation(84, 87);
    }

    public function getName(): string {
        return "Mesa Plateau";
    }
}