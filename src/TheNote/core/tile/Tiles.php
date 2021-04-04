<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//                        2017-2020

declare(strict_types = 1);

namespace TheNote\core\tile;

use pocketmine\tile\Tile as Tile;
use TheNote\core\tile\JBTile;
use pocketmine\nbt\tag\CompoundTag;

abstract class Tiles extends Tile
{
    public const
        JUKEBOX = "Jukebox", CAULDRON = "Cauldron";
    public const SHULKER_BOX = "ShulkerBox";
    
    public static function init() {
        self::registerTile(BrewingStand::class);
        self::registerTile(Cauldron::class);
        self::registerTile(ShulkerBox::class, [self::SHULKER_BOX, "minecraft:shulker_box"]);
        JBTile::registerTile(JBTile::class, ["Jukebox"]);
        Tile::registerTile(Beacon::class, [Beacon::BEACON, "minecraft:beacon"]);
    }
}

