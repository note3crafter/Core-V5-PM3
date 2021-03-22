<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

declare(strict_types = 1);

namespace TheNote\core\item;

use pocketmine\item\Item;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

class Record extends Item {

    public function __construct(int $id, int $meta, string $name){
        parent::__construct($id, $meta, $name);
    }
    
    public function getMaxStackSize(): int{
        return 1;
    }
    
    public function isValid(): bool{
        return ($this->getId() >= 500 && $this->getId() <= 511);
    }

    public function getSoundId(){
        $cal = LevelSoundEventPacket::SOUND_RECORD_13 + ($this->getRecordId() - 2255);
        $cal -= 1;
        return $cal;
    }
    
    public function getRecordId(): int{
        return 1756 + $this->getId(); // so that it matches the wiki...
    }
    
    public function getRecordName(): string{
        $names = [Item::RECORD_13 => "13", Item::RECORD_CAT => "cat", Item::RECORD_BLOCKS => "blocks", Item::RECORD_CHIRP => "chirp", Item::RECORD_FAR => "far", Item::RECORD_MALL => "mall", Item::RECORD_MELLOHI => "mellohi", Item::RECORD_STAL => "stal", Item::RECORD_STRAD => "strad", Item::RECORD_WARD => "ward", Item::RECORD_11 => "11", Item::RECORD_WAIT => "wait"];
        return $names[$this->getId()];
    }
}