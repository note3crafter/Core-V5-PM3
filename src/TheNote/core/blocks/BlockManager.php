<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗ 
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝ 
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
// 

declare(strict_types = 1);

namespace TheNote\core\blocks;

use TheNote\core\Main;
use pocketmine\block\BlockFactory;

class BlockManager {
	public static function init(): void{
		BlockFactory::registerBlock(new SlimeBlock(), true);
		BlockFactory::registerBlock(new EndPortalFrame(), true);
		BlockFactory::registerBlock(new FrostedIce(), true);
		BlockFactory::registerBlock(new Cauldron(), true);
        BlockFactory::registerBlock(new Sponge(), true);
        BlockFactory::registerBlock(new BrewingStand, true);
        BlockFactory::registerBlock(new EndPortal(), true);
        BlockFactory::registerBlock(new Portal(), true);
        BlockFactory::registerBlock(new Obsidian(), true);
        //BlockFactory::registerBlock(new Jukebox(), true); Nicht Funktionsfähig
    }
}
