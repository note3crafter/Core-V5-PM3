<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//                        2017-2020

namespace TheNote\core\tile;

use pocketmine\tile\Tile as Tile;

abstract class Tiles extends Tile
{
    public const
        JUKEBOX = "Jukebox", CAULDRON = "Cauldron";
    public const SHULKER_BOX = "ShulkerBox";
    public const NOTE_BLOCK = "Noteblock";
    public const HOPPER = "Hopper";
    public const DISPENSER = "Dispenser";
    public const DROPPER = "Dropper";
    public const BEACON = "Beacon";
    public const COMMAND_BLOCK = "CommandBlock";


    public static function init()
    {
        self::registerTile(BrewingStand::class);
        self::registerTile(Cauldron::class);
        self::registerTile(ShulkerBox::class, [self::SHULKER_BOX, "minecraft:shulker_box"]);
        JBTile::registerTile(JBTile::class, ["Jukebox"]);
        Tile::registerTile(Beacon::class, [Beacon::BEACON, "minecraft:beacon"]);
        self::registerTile(NoteBlockTile::class, [self::NOTE_BLOCK, "minecraft:noteblock"]);


        //Todo self::registerTile(Chest::class, ["Chest", "minecraft:chest"]);
        self::registerTile(CommandBlock::class, ["CommandBlock", "minecraft:command_block"]);
        self::registerTile(DaylightDetector::class, ["DaylightDetector", "minecraft:daylight_detector"]);
        self::registerTile(Dropper::class, ["Dropper", "minecraft:dropper"]);
        self::registerTile(Dispenser::class, ["Dispenser", "minecraft:dispenser"]);
        self::registerTile(Hopper::class, ["Hopper", "minecraft:hopper"]);
        self::registerTile(NoteBlock::class, ["NoteBlock", "minecraft:note_block"]);
        self::registerTile(Observer::class, ["Observer", "minecraft:observer"]);
        self::registerTile(MovingBlock::class, ["Movingblock", "minecraft:movingblock"]);
        self::registerTile(PistonArm::class, ["PistonArm", "minecraft:piston_arm"]);
        self::registerTile(RedstoneComparator::class, ["Comparator", "minecraft:comparator"]);
    }
}

