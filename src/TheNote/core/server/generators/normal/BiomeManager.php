<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server\generators\normal;

use TheNote\core\server\generators\normal\biome\Beach;
use TheNote\core\server\generators\normal\biome\BirchForest;
use TheNote\core\server\generators\normal\biome\ExtremeHillsEdge;
use TheNote\core\server\generators\normal\biome\FrozenOcean;
use TheNote\core\server\generators\normal\biome\FrozenRiver;
use TheNote\core\server\generators\normal\biome\IcePlains;
use TheNote\core\server\generators\normal\biome\Jungle;
use TheNote\core\server\generators\normal\biome\MushroomIsland;
use TheNote\core\server\generators\normal\biome\MushroomIslandShore;
use TheNote\core\server\generators\normal\biome\RoofedForest;
use TheNote\core\server\generators\normal\biome\RoffedForestHills;
use TheNote\core\server\generators\normal\biome\DeepOcean;
use TheNote\core\server\generators\normal\biome\Desert;
use TheNote\core\server\generators\normal\biome\DesertHills;
use TheNote\core\server\generators\normal\biome\Forest;
use TheNote\core\server\generators\normal\biome\ForestHills;
use TheNote\core\server\generators\normal\biome\ExtremeHillsMutated;
use TheNote\core\server\generators\normal\biome\Mesa;
use TheNote\core\server\generators\normal\biome\MesaPlateau;
use TheNote\core\server\generators\normal\biome\ExtremeHills;
use TheNote\core\server\generators\normal\biome\Ocean;
use TheNote\core\server\generators\normal\biome\Plains;
use TheNote\core\server\generators\normal\biome\River;
use TheNote\core\server\generators\normal\biome\Savanna;
use TheNote\core\server\generators\normal\biome\SavannaPlateau;
use TheNote\core\server\generators\normal\biome\SunflowerPlains;
use TheNote\core\server\generators\normal\biome\Swampland;
use TheNote\core\server\generators\normal\biome\Taiga;
use TheNote\core\server\generators\normal\biome\TaigaHills;
use TheNote\core\server\generators\normal\biome\TallBirchForest;
use TheNote\core\server\generators\normal\biome\types\Biome;

class BiomeManager {

    protected static $map = [];

    const OCEAN = 0;
    const PLAINS = 1;
    const DESERT = 2;
    const EXTREME_HILLS = 3;
    const FOREST = 4;
    const TAIGA = 5;
    const SWAMP = 6;
    const RIVER = 7;
    const NETHER = 8;
    const THE_END = 9;
    const FROZEN_OCEAN = 10; // new
    const FROZEN_RIVER = 11; // new
    const ICE_PLAINS = 12; // new
    const ICE_MOUNTAINS = 13;
    const MUSHROOM_ISLAND = 14; // new
    const MUSHROOM_ISLAND_SHORE = 15; // new
    const BEACH = 16;
    const DESERT_HILLS = 17;
    const FOREST_HILLS = 18;
    const TAIGA_HILLS = 19; // new
    const EXTREME_HILLS_EDGE = 20; // new
    const JUNGLE = 21; // new
    const JUNGLE_HILLS = 22;
    const JUNGLE_EDGE = 23;
    const DEEP_OCEAN = 24;
    const STONE_BEACH = 25;
    const COLD_BEACH = 26;
    const BIRCH_FOREST = 27;
    const BIRCH_FOREST_HILLS = 28;
    const ROOFED_FOREST = 29;
    const COLD_TAIGA = 30;
    const COLD_TAIGA_HILLS = 31;
    const MEGA_TAIGA = 32;
    const MEGA_TAIGA_HILLS = 33;
    const EXTREME_HILLS_PLUS = 34;
    const SAVANNA = 35;
    const SAVANNA_PLATEAU = 36; // new
    const MESA = 37;
    const MESA_PLATEAU = 39;
    const SUNFLOWER_PLAINS = 129;
    const EXTREME_HILLS_MUTATED = 131;
    const TALL_BIRCH_FOREST = 155;
    const ROOFED_FOREST_HILLS = 157;

    public static function registerBiomes() {
        $biomeClass = new \ReflectionClass(\pocketmine\level\biome\Biome::class);

        $biomes = $biomeClass->getProperty("biomes");
        $biomes->setAccessible(true);
        $biomes->setValue(new \SplFixedArray(Biome::MAX_BIOMES));

        $register = $biomeClass->getMethod("register");
        $register->setAccessible(true);
        foreach (static::getBiomes() as $id => $biome) {
            $register->invokeArgs(null, [$id, $biome]);
            self::$map[$biome->getTemperature()][$biome->getRainfall()][] = $biome;
        }
    }

    public static function getBiome(int $id) {
        return Biome::getBiome($id);
    }

    private static function getBiomes(): array {
        return [
            0 => new Ocean(),
            1 => new Plains(),
            2 => new Desert(),
            3 => new ExtremeHills(),
            4 => new Forest(),
            5 => new Taiga(),
            6 => new Swampland(),
            7 => new River(),
            10 => new FrozenOcean(),
            11 => new FrozenRiver(),
            12 => new IcePlains(),
            14 => new MushroomIsland(),
            15 => new MushroomIslandShore(),
            16 => new Beach(),
            17 => new DesertHills(),
            18 => new ForestHills(),
            19 => new TaigaHills(),
            20 => new ExtremeHillsEdge(),
            21 => new Jungle(),
            24 => new DeepOcean(),
            27 => new BirchForest(),
            29 => new RoofedForest(),
            35 => new Savanna(),
            36 => new SavannaPlateau(),
            37 => new Mesa(),
            39 => new MesaPlateau(),
            129 => new SunflowerPlains(),
            131 => new ExtremeHillsMutated(),
            155 => new TallBirchForest(),
            157 => new RoffedForestHills()
        ];
    }
}