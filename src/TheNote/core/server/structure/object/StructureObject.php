<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server\structure\object;

use pocketmine\math\Vector3;
use pocketmine\nbt\BigEndianNBTStream;
use pocketmine\utils\Random;
use TheNote\core\utils\BlockLoader;
use TheNote\core\utils\BlockPalette;

class StructureObject {

    protected $blockMap = [];
    public $data;
    public $axisVector;

    public function load(string $path) {
        $data = (new BigEndianNBTStream())->readCompressed(file_get_contents($path));

        $compound = $data->getValue();
        $palette = new BlockPalette();

        foreach ($compound["palette"] as $state) {
            $palette->registerBlock(BlockLoader::getBlockByState($state));
        }

        foreach ($compound["blocks"] as $blockData) {
            $pos = $blockData->getListTag("pos");
            $state = $blockData->getInt("state");

            $x = (int) $pos->offsetGet(0);
            $y = (int) $pos->offsetGet(1);
            $z = (int) $pos->offsetGet(2);

            $this->getBlockAt($x, $y, $z)->addBlock($palette->getBlock($state));
        }

        if(isset($compound["size"])) {
            $list = $compound["size"];
            $axis = new Vector3($list->offsetGet(0), $list->offsetGet(1), $list->offsetGet(2));

            if(is_null($this->axisVector) || ($axis->getX() + $axis->getY() + $axis->getZ()) > ($this->axisVector->getX() + $this->axisVector->getY() + $this->axisVector->getZ())) {
                $this->axisVector = $axis;
            }
        }
    }

    public function registerBlock(int $x, int $y, int $z) {
        if(!isset($this->blockMap[$x][$y][$z]))
            $this->blockMap[$x][$y][$z] = new StructureBlock();
    }

    public function getBlockAt(int $x, int $y, int $z): StructureBlock {
        self::registerBlock($x, $y, $z);

        return $this->blockMap[$x][$y][$z];
    }

    public function getBlocks(Random $random): \Generator {
        foreach ($this->blockMap as $x => $yz) {
            foreach ($yz as $y => $zBlock) {
                foreach ($zBlock as $z => $block) {
                    yield [$x, $y, $z, $block->getBlock($random)];
                }
            }
        }
    }

    public function getAxisVector(): Vector3 {
        return $this->axisVector;
    }
}