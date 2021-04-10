<?php

/*
 *               _ _
 *         /\   | | |
 *        /  \  | | |_ __ _ _   _
 *       / /\ \ | | __/ _` | | | |
 *      / ____ \| | || (_| | |_| |
 *     /_/    \_|_|\__\__,_|\__, |
 *                           __/ |
 *                          |___/
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author TuranicTeam
 * @link https://github.com/TuranicTeam/Altay
 *
 */



namespace TheNote\core\blocks;

use pocketmine\block\Block;
use pocketmine\block\BlockToolType;
use pocketmine\block\Solid;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\Player;
use TheNote\core\tile\NoteBlockTile as TileNoteBlock;
use TheNote\core\tile\Tiles;

class NoteBlock extends Solid{

    // TODO: Redstone power
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


    /*public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null) : bool{
        $this->getLevel()->setBlock($blockReplace, $this, true, true);

        Tile::createTile(Tiles::NOTE_BLOCK, $this->getLevel(), TileNoteBlock::createNBT($this, $face, $item, $player));

        return true;
    }*/
    public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null): bool
    {
        if (parent::place($item, $blockReplace, $blockClicked, $face, $clickVector, $player)) {

            Tiles::createTile(Tiles::NOTE_BLOCK, $this->getLevel(), TileNoteBlock::createNBT($this, $face, $item, $player));

            return true;
        }
        return false;
    }

    public function onActivate(Item $item, Player $player = null) : bool{
        $tile = $this->level->getTile($this);
        if($tile instanceof TileNoteBlock){
            $tile->changePitch();

            return $tile->triggerNote();
        }

        return false;
    }

    public function getName() : string{
        return "Noteblock";
    }

    public function getFuelTime() : int{
        return 300;
    }
}