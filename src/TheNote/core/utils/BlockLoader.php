<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\utils;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;

class BlockLoader {

    public const SIDE_HELPER = [
        "bottom" => ["east" => 0, "west" => 1, "south" => 2, "north" => 3],
        "top" => ["east" => 4, "west" => 5, "south" => 6, "north" => 7]
    ];

    public const BLOCK_MAP = [
        "minecraft:air" => [0, 0],
        "minecraft:cobblestone" => [4, 0],
        "minecraft:torch" => [50, 5],
        "minecraft:pumpkin" => [86, 1],
        "minecraft:carved_pumpkin" => [86, 1], // wrong id
        "minecraft:dark_oak_slab" => [158, "type" => ["bottom" => 5, "top" => 13]],
        "minecraft:dark_oak_log" => [162, "axis" => ["y" => 1, "x" => 5, "z" => 9]],
        "minecraft:dark_oak_fence" => [85, 5],
        "minecraft:illager_captain_wall_banner" => [177, 4], // 2=>z-;3=>z+;4=>x-;5=>x+
        "minecraft:birch_planks" => [5, 2],
        "minecraft:dark_oak_planks" => [5, 5],
        "minecraft:white_wool" => [35, 0],
        "minecraft:hay_block" => [170, 0],
        "minecraft:cobblestone_stairs" => [67, "sides" => self::SIDE_HELPER],
        "minecraft:mossy_cobblestone_stairs" => [67, "sides" => self::SIDE_HELPER], // wrong id (isn't implemented)
        "minecraft:dark_oak_stairs" => [164, "sides" => self::SIDE_HELPER],
        "minecraft:vine" => [106, "facing" => ["north" => 4, "east" => 8 , "south" => 1, "west" => 2]],
        "minecraft:cobblestone_wall" => [139, 0],
        "minecraft:mossy_cobblestone_wall" => [139, 1],
        "minecraft:cobblestone_slab" => [44, "type" => ["bottom" => 3, "top" => 11]],
        "minecraft:mossy_cobblestone_slab" => [182, "type" => ["bottom" => 5, "top" => 13]],
        "minecraft:mossy_cobblestone" => [48, 0],
        "minecraft:crafting_table" => [58, 0],
        "minecraft:structure_block" => [0, 0] // 252
    ];

    public static function getBlockByState(CompoundTag $state): SimpleBlockData {
        $data = self::BLOCK_MAP[$state->getString("Name")] ?? 0;

        if($data === 0) {
            return new SimpleBlockData(0, 0);
        }

        $id = $data[0];
        if(isset($data[1])) {
            return new SimpleBlockData($id, $data[1]);
        }

        if(isset($data["axis"])) {
            return new SimpleBlockData($id, $data["axis"][$state->getCompoundTag("Properties")->getString("axis")]);
        }

        if(isset($data["type"])) {
            return new SimpleBlockData($id, $data["type"][$state->getCompoundTag("Properties")->getString("type")]);
        }

        if(isset($data["sides"])) {
            return new SimpleBlockData($id, $data["sides"][$state->getCompoundTag("Properties")->getString("half")][$state->getCompoundTag("Properties")->getString("facing")]);
        }


        if(isset($data["facing"])) {
            $facing = null;
            $properties = $state->getCompoundTag("Properties");

            if($properties->offsetExists("facing"))
                $facing = $state->getCompoundTag("Properties")->getString("facing");

            else {

                foreach ($properties->getValue() as $side => $value) {
                    if($value->getValue() == "true") {
                        $facing = $side;
                        break;
                    }
                }
            }

            return new SimpleBlockData($id, $data["facing"][$facing]);
        }

        return new SimpleBlockData(0, 0);
    }
}

class SimpleBlockData {

    public $id;
    public $meta;

    public function __construct(int $id, int $meta) {
        $this->id = $id;
        $this->meta = $meta;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getMeta(): int {
        return $this->meta;
    }
}