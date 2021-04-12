<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server\structure;

use pocketmine\level\ChunkManager;
use pocketmine\utils\Random;
use TheNote\core\server\structure\object\StructureObject;

abstract class Structure {

    private $dir;
    private $objects = [];

    public function __construct(string $dir) {
        $this->dir = $dir;
    }

    abstract public function placeAt(ChunkManager $level, int $x, int $y, int $z, Random $random): void;

    public function getTargetFiles(): \Generator {
        foreach (glob($this->dir . "/*.nbt") as $file) {
            yield $file;
        }
    }

    public function getDirectory(): string {
        return $this->dir;
    }

    public function getObjects(): array {
        return $this->objects;
    }

    public function addObject(StructureObject $object, string $name = null) {
        if(is_string($name))
            $this->objects[$name] = $object;
        else
            $this->objects[] = $object;
    }

    public function getObject(string $name): ?StructureObject {
        return $this->objects[$name] ?? null;
    }
}