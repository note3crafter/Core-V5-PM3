<?php

declare(strict_types=1);

namespace TheNote\core\blocks;

use pocketmine\block\Air;
use pocketmine\block\Block;
use pocketmine\block\Obsidian as PMObsidian;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;

/**
 * Class Obsidian
 * @package Xenophilicy\TableSpoon\block
 */
class Obsidian extends PMObsidian {
    
    public function onBreak(Item $item, Player $player = null): bool{
        parent::onBreak($item);
        foreach($this->getAllSides() as $i => $block){
            if($block instanceof Portal){
                if($block->getSide(Vector3::SIDE_WEST) instanceof Portal or $block->getSide(Vector3::SIDE_EAST) instanceof Portal){//x方向
                    for($x = $block->x; $this->getLevel()->getBlockIdAt($x, $block->y, $block->z) == Block::PORTAL; $x++){
                        for($y = $block->y; $this->getLevel()->getBlockIdAt($x, $y, $block->z) == Block::PORTAL; $y++){
                            $this->getLevel()->setBlock(new Vector3($x, $y, $block->z), new Air());
                        }
                        for($y = $block->y - 1; $this->getLevel()->getBlockIdAt($x, $y, $block->z) == Block::PORTAL; $y--){
                            $this->getLevel()->setBlock(new Vector3($x, $y, $block->z), new Air());
                        }
                    }
                    for($x = $block->x - 1; $this->getLevel()->getBlockIdAt($x, $block->y, $block->z) == Block::PORTAL; $x--){
                        for($y = $block->y; $this->getLevel()->getBlockIdAt($x, $y, $block->z) == Block::PORTAL; $y++){
                            $this->getLevel()->setBlock(new Vector3($x, $y, $block->z), new Air());
                        }
                        for($y = $block->y - 1; $this->getLevel()->getBlockIdAt($x, $y, $block->z) == Block::PORTAL; $y--){
                            $this->getLevel()->setBlock(new Vector3($x, $y, $block->z), new Air());
                        }
                    }
                }else{
                    for($z = $block->z; $this->getLevel()->getBlockIdAt($block->x, $block->y, $z) == Block::PORTAL; $z++){
                        for($y = $block->y; $this->getLevel()->getBlockIdAt($block->x, $y, $z) == Block::PORTAL; $y++){
                            $this->getLevel()->setBlock(new Vector3($block->x, $y, $z), new Air());
                        }
                        for($y = $block->y - 1; $this->getLevel()->getBlockIdAt($block->x, $y, $z) == Block::PORTAL; $y--){
                            $this->getLevel()->setBlock(new Vector3($block->x, $y, $z), new Air());
                        }
                    }
                    for($z = $block->z - 1; $this->getLevel()->getBlockIdAt($block->x, $block->y, $z) == Block::PORTAL; $z--){
                        for($y = $block->y; $this->getLevel()->getBlockIdAt($block->x, $y, $z) == Block::PORTAL; $y++){
                            $this->getLevel()->setBlock(new Vector3($block->x, $y, $z), new Air());
                        }
                        for($y = $block->y - 1; $this->getLevel()->getBlockIdAt($block->x, $y, $z) == Block::PORTAL; $y--){
                            $this->getLevel()->setBlock(new Vector3($block->x, $y, $z), new Air());
                        }
                    }
                }
                return true;
            }
        }
        return true;
    }
}