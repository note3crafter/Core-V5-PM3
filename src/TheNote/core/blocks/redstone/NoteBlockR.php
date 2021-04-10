<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//                        2017-2020

namespace TheNote\core\blocks\redstone;

use pocketmine\block\BlockToolType;
use pocketmine\network\mcpe\protocol\BlockEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;

use pocketmine\block\Block;
use pocketmine\block\NoteBlock as NoteB;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\tile\Tile;

use TheNote\core\tile\NoteBlock as NoteBlockT;

class NoteBlockR extends NoteB implements IRedstone {
    use RedstoneTrait;
    protected $id = self::NOTE_BLOCK;

    public function __construct(int $meta = 0){
        $this->meta = $meta;
    }

    public function getHardness() : float{
        return 0.8;
    }

    public function getToolType() : int{
        return BlockToolType::TYPE_AXE;
    }
    public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool {
        if (parent::place($item, $blockReplace, $blockClicked, $face, $clickVector, $player)) {

            Tile::createTile("NoteBlock", $this->getLevel(), NoteBlockT::createNBT($this->asVector3()));

            return true;
        }
        return false;
    }

    public function onActivate(Item $item, Player $player = null) : bool {
        $tile = $this->level->getTile($this);
        if($tile instanceof NoteBlockT){
            $tile->addPitch();

            return $tile->getSound();

        }

        return false;
    }
    public function playSound() : bool {
        $tile = $this->getLevel()->getTile($this);
        if($tile instanceof NoteBlockT){

            return $tile->getSound() . $tile->getPitch();

        }
    }
    
    public function getStrongPower(int $face) : int {
        return 0;
    }

    public function getWeakPower(int $face) : int {
        return 0;
    }

    public function isPowerSource() : bool {
        return false;
    }
    public function getName() : string{
        return "Noteblock";
    }

    public function getFuelTime() : int{
        return 300;
    }

    public function onRedstoneUpdate() : void {
        $tile = $this->getLevel()->getTile($this);
        $note = null;
        if($tile instanceof NoteBlockT){
            $note = $tile;
        }else{
            $note = Tile::createTile("NoteBlock", $this->getLevel(), NoteBlockT::createNBT($this->asVector3()));
        }
        if ($this->isBlockPowered($this->asVector3()) && !$note->isPowered()) {
            $note->setPowered(true);
            $this->playSound();
        } elseif (!$this->isBlockPowered($this->asVector3()) && $note->isPowered()) {
            $note->setPowered(false);
        }
    }
}