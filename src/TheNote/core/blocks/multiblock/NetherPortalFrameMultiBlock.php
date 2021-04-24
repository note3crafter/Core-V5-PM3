<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//                        2017-2020

namespace TheNote\core\blocks\multiblock;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\level\Level;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\Player;
use SplQueue;
use TheNote\core\blocks\Obsidian;
use TheNote\core\blocks\Portal;
use TheNote\core\utils\ArrayUtils;

class NetherPortalFrameMultiBlock implements MultiBlock {
    
    private $frameBlock;
    private $lengthSquared;

    public function __construct(){
        $this->frameBlock = (new Obsidian())->getId();
        $this->lengthSquared = (new Vector2(21, 21))->lengthSquared();
    }
    
    public function interact(Block $wrapping, Player $player, Item $item, int $face): bool{
        if($item->getId() === ItemIds::FLINT_AND_STEEL){
            $affectedBlock = $wrapping->getSide($face);
            if($affectedBlock->getId() === Block::AIR){
                $level = $player->getLevel();
                $pos = $affectedBlock->asVector3();
                $blocks = $this->fill($level, $pos, 10, Vector3::SIDE_WEST);
                if(count($blocks) === 0){
                    $blocks = $this->fill($level, $pos, 10, Vector3::SIDE_NORTH);
                }
                if(count($blocks) > 0){
                    foreach($blocks as $hash => $block){
                        if($block->getId() === Block::PORTAL){
                            Level::getBlockXYZ($hash, $x, $y, $z);
                            $level->setBlock(new Vector3($x, $y, $z), new Portal(), false);
                        }
                    }
                    return true;
                }
            }
        }
        return false;
    }

    public function fill(Level $Level, Vector3 $origin, int $radius, int $direction): array{
        $blocks = [];
        $visits = new SplQueue();
        $visits->enqueue($origin);
        while(!$visits->isEmpty()){
            $coordinates = $visits->pop();
            if($origin->distanceSquared($coordinates) >= $this->lengthSquared) return [];
            $coordinates_hash = Level::blockHash($coordinates->x, $coordinates->y, $coordinates->z);
            $block = $Level->getBlockAt($coordinates->x, $coordinates->y, $coordinates->z);
            if($block->getId() === Block::AIR && ArrayUtils::firstOrDefault($blocks, static function(int $hash, Block $block) use ($coordinates_hash) : bool{
                  return $hash === $coordinates_hash;
              }) === null){
                $this->visit($coordinates, $blocks, $direction);
                if($direction === Vector3::SIDE_WEST){
                    $visits->push($coordinates->getSide(Vector3::SIDE_NORTH));
                    $visits->push($coordinates->getSide(Vector3::SIDE_SOUTH));
                }elseif($direction === Vector3::SIDE_NORTH){
                    $visits->push($coordinates->getSide(Vector3::SIDE_EAST));
                    $visits->push($coordinates->getSide(Vector3::SIDE_WEST));
                }
                $visits->push($coordinates->getSide(Vector3::SIDE_UP));
                $visits->push($coordinates->getSide(Vector3::SIDE_DOWN));
            }elseif(!$this->isValid($block, $coordinates_hash, $blocks)) return [];
        }
        return $blocks;
    }

    public function visit(Vector3 $coords, array &$blocks, int $direction): void{
        $blocks[Level::blockHash($coords->x, $coords->y, $coords->z)] = BlockFactory::get(Block::PORTAL, $direction - 2);
    }

    private function isValid(Block $block, int $bHash, array $portals): bool{
        return $block->getId() === $this->frameBlock || ArrayUtils::firstOrDefault($portals, static function(int $hash, Block $b) use ($bHash) : bool{
              return $b->getId() === Block::PORTAL;
          }) !== null;
    }
    
    public function update(Block $wrapping): bool{
        return false;
    }
    
    public function onPlayerMoveInside(Player $player, Block $block): void{
    }
    
    public function onPlayerMoveOutside(Player $player, Block $block): void{
    }
}