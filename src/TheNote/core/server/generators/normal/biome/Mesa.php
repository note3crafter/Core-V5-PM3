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
use pocketmine\block\GoldOre;
use pocketmine\block\HardenedClay;
use pocketmine\block\Sand;
use pocketmine\block\StainedClay;
use pocketmine\level\generator\object\OreType;
use TheNote\core\server\generators\nether\populator\Ore;
use TheNote\core\server\generators\normal\biome\types\CoveredBiome;
use TheNote\core\server\generators\normal\populator\impl\CactusPopulator;
use TheNote\core\server\generators\normal\populator\impl\PlantPopulator;

class Mesa extends CoveredBiome {

    public function __construct() {
        parent::__construct(2, 0);

        $this->setGroundCover([
            new Sand(1),
            new HardenedClay(),
            new StainedClay(7),
            new StainedClay(0),
            new StainedClay(14),
            new HardenedClay(),
            new StainedClay(4),
            new StainedClay(4),
            new HardenedClay(),
            new HardenedClay(),
            new StainedClay(1),
            new StainedClay(1),
            new HardenedClay(),
            new StainedClay(7),
            new StainedClay(8),
            new StainedClay(4),
            new HardenedClay()
        ]);

        $cactus = new CactusPopulator(3, 2);
        $deadBush = new PlantPopulator(3, 2);
        $deadBush->allowBlockToStayAt(BlockIds::SAND);

        $ore = new Ore();
        $ore->setOreTypes([new OreType(new GoldOre(), 20, 12, 0, 128)]);

        $this->addPopulators([$cactus, $deadBush, $ore]);

        $this->setElevation(63, 67);
    }

    public function getName(): string {
        return "Mesa";
    }
}