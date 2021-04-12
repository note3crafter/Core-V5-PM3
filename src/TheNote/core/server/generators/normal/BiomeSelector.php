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

use pocketmine\level\biome\Biome;
use pocketmine\level\generator\noise\Simplex;
use pocketmine\utils\Random;

class BiomeSelector
{

    public $temperature;
    public $rainfall;
    public $ocean;
    public $hills;
    public $smallHills;
    public $river;

    public function __construct(Random $random)
    {
        $this->temperature = new Simplex($random, 3, 1 / 4, 1 / 2048); //2 oct
        $this->rainfall = new Simplex($random, 3, 1 / 4, 1 / 2048); // 2 oct
        $this->ocean = new Simplex($random, 6, 1 / 2, 1 / 2048);
        $this->hills = new Simplex($random, 6, 1 / 2, 1 / 2048);
        $this->smallHills = new Simplex($random, 2, 1 / 32, 1 / 256);
        $this->river = new Simplex($random, 6, 1 / 2, 1 / 1024);

    }

    public function getTemperature($x, $z)
    {
        return abs(round($this->temperature->noise2D($x, $z, true) * M_PI / 3 * 2, 1));
    }

    public function getRainfall($x, $z)
    {
        return abs(round($this->rainfall->noise2D($x, $z, true) * M_PI / 3 * 2, 1));
    }

    public function getSmallHills($x, $z)
    {
        return $this->smallHills->noise2D($x, $z, true);
    }

    public function getRiver($x, $z)
    {
        return $this->river->noise2D($x, $z, true);
    }

    public function getOcean($x, $z)
    {
        return $this->ocean->noise2D($x, $z, true);
    }

    public function pickBiome($x, $z): Biome
    {
        if ($this->getOcean($x, $z) < -0.2) {
            if ($this->getTemperature($x, $z) < 0) {
                return BiomeManager::getBiome(BiomeManager::FROZEN_OCEAN);
            }

            if ($this->getOcean($x, $z) > -0.4) {
                if ($this->getTemperature($x, $z) > 0.8) {
                    return BiomeManager::getBiome(BiomeManager::SWAMP);
                }
            }

            if ($this->getOcean($x, $z) > -0.23) {
                if ($this->getSmallHills($x, $z) > 0) {
                    return BiomeManager::getBiome(BiomeManager::BEACH);
                }
            }
            return BiomeManager::getBiome(BiomeManager::OCEAN);
        }

        if (abs($this->getRiver($x, $z)) < 0.06) {
            if ($this->getTemperature($x, $z) < 0) {
                return BiomeManager::getBiome(BiomeManager::FROZEN_RIVER);
            }
            return BiomeManager::getBiome(BiomeManager::RIVER);
        }

        $temperature = $this->getTemperature($x, $z);
        $rainfall = $this->getRainfall($x, $z);
        $hills = $this->getSmallHills($x, $z);

        if ($rainfall < 0.4) {
            if ($temperature > 0.5) {
                if ($hills < 0) {
                    return BiomeManager::getBiome(BiomeManager::DESERT);
                }

                return BiomeManager::getBiome(BiomeManager::DESERT_HILLS);
            }

            if ($hills < 0) {
                return BiomeManager::getBiome(BiomeManager::SAVANNA);
            }

            return BiomeManager::getBiome(BiomeManager::SAVANNA_PLATEAU);
        }

        if ($rainfall < 0.8) {
            if ($temperature < 0.3) {
                if ($hills > 0) {
                    return BiomeManager::getBiome(BiomeManager::FOREST_HILLS);
                }
                return BiomeManager::getBiome(BiomeManager::FOREST);
            }

            if ($temperature < 0.6) {
                if ($hills > 0) {
                    return BiomeManager::getBiome(BiomeManager::TALL_BIRCH_FOREST);
                }
                return BiomeManager::getBiome(BiomeManager::BIRCH_FOREST);
            }

            if ($hills > 0) {
                return BiomeManager::getBiome(BiomeManager::ROOFED_FOREST_HILLS);
            }
            return BiomeManager::getBiome(BiomeManager::ROOFED_FOREST);
        }

        if ($rainfall < 1.2) {
            if ($temperature < 0) {
                return BiomeManager::getBiome(BiomeManager::ICE_MOUNTAINS);
            }
            if ($temperature < 0.4) {
                if ($hills > 0.5) {
                    return BiomeManager::getBiome(BiomeManager::EXTREME_HILLS_MUTATED);
                }
                return BiomeManager::getBiome(BiomeManager::EXTREME_HILLS);
            }
            if ($temperature < 0.8) {
                return BiomeManager::getBiome(BiomeManager::EXTREME_HILLS_EDGE);
            }
        }

        return BiomeManager::getBiome(BiomeManager::PLAINS);
    }
}