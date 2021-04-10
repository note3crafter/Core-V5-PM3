<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗ 
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝ 
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
// 

namespace TheNote\core\blocks;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\item\Item;
use TheNote\core\blocks\redstone\doors\IronDoor;
use TheNote\core\blocks\redstone\buttons\ButtonStone;
use TheNote\core\blocks\redstone\buttons\ButtonWooden;
use TheNote\core\blocks\redstone\commandblock\CommandBlock;
use TheNote\core\blocks\redstone\commandblock\CommandBlockChain;
use TheNote\core\blocks\redstone\commandblock\CommandBlockRepeating;
use TheNote\core\blocks\redstone\DaylightDetector;
use TheNote\core\blocks\redstone\DaylightDetectorInverted;
use TheNote\core\blocks\redstone\Dispenser;
use TheNote\core\blocks\redstone\doors\IronTrapdoor;
use TheNote\core\blocks\redstone\doors\Trapdoor;
use TheNote\core\blocks\redstone\doors\WoodenDoor;
use TheNote\core\blocks\redstone\Dropper;
use TheNote\core\blocks\redstone\FenceGate;
use TheNote\core\blocks\redstone\Hopper;
use TheNote\core\blocks\redstone\Lever;
use TheNote\core\blocks\redstone\Moving;
use TheNote\core\blocks\redstone\NoteBlockR;
use TheNote\core\blocks\redstone\Observer;
use TheNote\core\blocks\redstone\piston\Piston;
use TheNote\core\blocks\redstone\piston\Pistonarmcollision;
use TheNote\core\blocks\redstone\piston\PistonSticky;
use TheNote\core\blocks\redstone\plates\PressurePlateStone;
use TheNote\core\blocks\redstone\plates\PressurePlateWooden;
use TheNote\core\blocks\redstone\Redstone;
use TheNote\core\blocks\redstone\RedstoneComparatorPowered;
use TheNote\core\blocks\redstone\RedstoneComparatorUnpowered;
use TheNote\core\blocks\redstone\RedstoneLamp;
use TheNote\core\blocks\redstone\RedstoneLampLit;
use TheNote\core\blocks\redstone\RedstoneRepeaterPowered;
use TheNote\core\blocks\redstone\RedstoneRepeaterUnpowered;
use TheNote\core\blocks\redstone\RedstoneTorch;
use TheNote\core\blocks\redstone\RedstoneTorchUnlit;
use TheNote\core\blocks\redstone\RedstoneWire;
use TheNote\core\blocks\redstone\Slime;
use TheNote\core\blocks\redstone\TNT;
use TheNote\core\blocks\redstone\TrappedChest;
use TheNote\core\blocks\redstone\Tripwire;
use TheNote\core\blocks\redstone\TripwireHook;
use TheNote\core\blocks\redstone\WeightedPressurePlateHeavy;
use TheNote\core\blocks\redstone\WeightedPressurePlateLight;

class BlockManager {

    public static function init(): void
    {
        //BlockFactory::registerBlock(new SlimeBlock(), true);
        BlockFactory::registerBlock(new EndPortalFrame(), true);
        BlockFactory::registerBlock(new FrostedIce(), true);
        BlockFactory::registerBlock(new Cauldron(), true);
        BlockFactory::registerBlock(new Sponge(), true);
        BlockFactory::registerBlock(new BrewingStand, true);
        BlockFactory::registerBlock(new EndPortal(), true);
        BlockFactory::registerBlock(new Portal(), true);
        BlockFactory::registerBlock(new Obsidian(), true);
        BlockFactory::registerBlock(new ShulkerBox(), true);
        BlockFactory::registerBlock(new UndyedShulkerBox(), true);
        BlockFactory::registerBlock(new Jukebox(), true);
        BlockFactory::registerBlock(new Beacon(), true);
        BlockFactory::registerBlock(new Anvil(), true);
        BlockFactory::registerBlock(new SkullBlock(), true);
        //BlockFactory::registerBlock(new NoteBlock(), true);
        BlockFactory::registerBlock(new RedstoneWire(), true);
        BlockFactory::registerBlock(new RedstoneRepeaterPowered(), true);
        BlockFactory::registerBlock(new RedstoneRepeaterUnpowered(), true);
        BlockFactory::registerBlock(new RedstoneComparatorPowered(), true);
        BlockFactory::registerBlock(new RedstoneComparatorUnpowered(), true);
        BlockFactory::registerBlock(new RedstoneTorch(), true);
        BlockFactory::registerBlock(new RedstoneTorchUnlit(), true);
        BlockFactory::registerBlock(new Redstone(), true);
        BlockFactory::registerBlock(new Lever(), true);
        BlockFactory::registerBlock(new ButtonStone(), true);
        BlockFactory::registerBlock(new ButtonWooden(), true);
        BlockFactory::registerBlock(new PressurePlateStone(), true);
        BlockFactory::registerBlock(new PressurePlateWooden(), true);
        BlockFactory::registerBlock(new WeightedPressurePlateLight(), true);
        BlockFactory::registerBlock(new WeightedPressurePlateHeavy(), true);
        BlockFactory::registerBlock(new DaylightDetector(), true);
        BlockFactory::registerBlock(new DaylightDetectorInverted(), true);
        BlockFactory::registerBlock(new Observer(), true);
        BlockFactory::registerBlock(new TrappedChest(), true);
        BlockFactory::registerBlock(new TripwireHook(), true);
        BlockFactory::registerBlock(new Tripwire(), true);
        BlockFactory::registerBlock(new RedstoneLamp(), true);
        BlockFactory::registerBlock(new RedstoneLampLit(), true);
        BlockFactory::registerBlock(new NoteBlockR(), true);
        BlockFactory::registerBlock(new Dropper(), true);
        BlockFactory::registerBlock(new Dispenser(), true);
        BlockFactory::registerBlock(new Hopper(), true);
        BlockFactory::registerBlock(new Piston(), true);
        BlockFactory::registerBlock(new Pistonarmcollision(), true);
        BlockFactory::registerBlock(new PistonSticky(), true);
        BlockFactory::registerBlock(new Moving(), true);
        BlockFactory::registerBlock(new CommandBlock(), true);
        BlockFactory::registerBlock(new CommandBlockRepeating(), true);
        BlockFactory::registerBlock(new CommandBlockChain(), true);
        BlockFactory::registerBlock(new TNT(), true);
        BlockFactory::registerBlock(new WoodenDoor(Block::OAK_DOOR_BLOCK, 0, "Oak Door", Item::OAK_DOOR), true);
        BlockFactory::registerBlock(new WoodenDoor(Block::SPRUCE_DOOR_BLOCK, 0, "Spruce Door", Item::SPRUCE_DOOR), true);
        BlockFactory::registerBlock(new WoodenDoor(Block::BIRCH_DOOR_BLOCK, 0, "Birch Door", Item::BIRCH_DOOR), true);
        BlockFactory::registerBlock(new WoodenDoor(Block::JUNGLE_DOOR_BLOCK, 0, "Jungle Door", Item::JUNGLE_DOOR), true);
        BlockFactory::registerBlock(new WoodenDoor(Block::ACACIA_DOOR_BLOCK, 0, "Acacia Door", Item::ACACIA_DOOR), true);
        BlockFactory::registerBlock(new WoodenDoor(Block::DARK_OAK_DOOR_BLOCK, 0, "Dark Oak Door", Item::DARK_OAK_DOOR), true);
        BlockFactory::registerBlock(new IronDoor(), true);
        BlockFactory::registerBlock(new Trapdoor(), true);
        BlockFactory::registerBlock(new IronTrapdoor(), true);
        BlockFactory::registerBlock(new FenceGate(Block::OAK_FENCE_GATE, 0, "Oak Fence Gate"), true);
        BlockFactory::registerBlock(new FenceGate(Block::SPRUCE_FENCE_GATE, 0, "Spruce Fence Gate"), true);
        BlockFactory::registerBlock(new FenceGate(Block::BIRCH_FENCE_GATE, 0, "Birch Fence Gate"), true);
        BlockFactory::registerBlock(new FenceGate(Block::JUNGLE_FENCE_GATE, 0, "Jungle Fence Gate"), true);
        BlockFactory::registerBlock(new FenceGate(Block::DARK_OAK_FENCE_GATE, 0, "Dark Oak Fence Gate"), true);
        BlockFactory::registerBlock(new FenceGate(Block::ACACIA_FENCE_GATE, 0, "Acacia Fence Gate"), true);
        BlockFactory::registerBlock(new Slime(), true);
    }
}
