<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\item;

use pocketmine\item\TieredTool;

class TTool extends TieredTool
{

    const TIER_NETHERITE = 6;

    public function getMaxDurability(): int
    {
        return static::getDurabilityFromTier($this->tier);
    }

    public static function getDurabilityFromTier(int $tier): int
    {
        static $levels = [

            self::TIER_GOLD => 33,
            self::TIER_WOODEN => 60,
            self::TIER_STONE => 132,
            self::TIER_IRON => 251,
            self::TIER_DIAMOND => 1562,
            self::TIER_NETHERITE => 2032
        ];

        if (!isset($levels[$tier])) {
            throw new \InvalidArgumentException("Unknown tier '$tier'");
        }

        return $levels[$tier];
    }

    protected static function getBaseDamageFromTier(int $tier): int
    {
        static $levels = [
            self::TIER_WOODEN => 5,
            self::TIER_GOLD => 5,
            self::TIER_STONE => 6,
            self::TIER_IRON => 7,
            self::TIER_DIAMOND => 8,
            self::TIER_NETHERITE => 9
        ];

        if (!isset($levels[$tier])) {
            throw new \InvalidArgumentException("Unknown tier '$tier'");
        }

        return $levels[$tier];
    }

    public static function getBaseMiningEfficiencyFromTier(int $tier): float
    {
        static $levels = [
            self::TIER_WOODEN => 2,
            self::TIER_STONE => 4,
            self::TIER_IRON => 6,
            self::TIER_DIAMOND => 8,
            self::TIER_NETHERITE => 9,
            self::TIER_GOLD => 12
        ];

        if (!isset($levels[$tier])) {
            throw new \InvalidArgumentException("Unknown tier '$tier'");
        }

        return $levels[$tier];
    }

    protected function getBaseMiningEfficiency(): float
    {
        return static::getBaseMiningEfficiencyFromTier($this->tier);
    }

}
