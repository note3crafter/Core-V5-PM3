<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server\generators\normal\populator\impl;

use pocketmine\block\BlockIds;
use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;
use TheNote\core\server\generators\normal\object\Tree;
use TheNote\core\server\generators\normal\populator\AmountPopulator;

class TreePopulator extends AmountPopulator {

	private $type;
	private $vines = false;

	public function __construct(int $baseAmount, int $randomAmount, int $spawnPercentage = 100, $type = Tree::OAK, bool $vines = false){
		$this->type = $type;
		$this->vines = $vines;

		parent::__construct($baseAmount, $randomAmount, $spawnPercentage);
	}

	public function populateObject(ChunkManager $level, int $chunkX, int $chunkZ, Random $random): void {
	    $this->getRandomSpawnPosition($level, $chunkX, $chunkZ, $random, $x, $y, $z);
        if($y === -1){
            return;
        }

        if(!in_array($level->getBlockIdAt($x, $y-1, $z), [BlockIds::GRASS, BlockIds::MYCELIUM])) {
            return;
        }

        Tree::growTree($level, $x, $y, $z, $random, $this->type, $this->vines);
	}
}
