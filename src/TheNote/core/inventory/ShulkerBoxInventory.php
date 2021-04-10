<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

declare(strict_types=1);

namespace TheNote\core\inventory;

use pocketmine\inventory\ContainerInventory;
use pocketmine\level\Level;
use pocketmine\network\mcpe\protocol\BlockEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\types\WindowTypes;
use pocketmine\Player;
use TheNote\core\tile\ShulkerBox;

class ShulkerBoxInventory extends ContainerInventory{

    protected $holder;

    public function __construct(ShulkerBox $tile){
        parent::__construct($tile);
    }

    public function getName() : string{
        return "Shulker Box";
    }

    public function getDefaultSize() : int{
        return 27;
    }

    public function getNetworkType() : int{
        return WindowTypes::CONTAINER;
    }

    public function onClose(Player $who) : void{
        if(count($this->getViewers()) === 1 && ($level = $this->getHolder()->getLevel()) instanceof Level){
            $this->broadcastBlockEventPacket(false);
            $level->broadcastLevelSoundEvent($this->getHolder()->add(0.5, 0.5, 0.5), LevelSoundEventPacket::SOUND_SHULKER_CLOSE);
        }
        parent::onClose($who);
    }

    public function onOpen(Player $who) : void{
        parent::onOpen($who);

        if(count($this->getViewers()) === 1 && ($level = $this->getHolder()->getLevel()) instanceof Level){
            $this->broadcastBlockEventPacket(true);
            $level->broadcastLevelSoundEvent($this->getHolder()->add(0.5, 0.5, 0.5), LevelSoundEventPacket::SOUND_SHULKER_OPEN);
        }
    }

    protected function broadcastBlockEventPacket(bool $isOpen){
        $holder = $this->getHolder();

        $pk = new BlockEventPacket();
        $pk->x = (int) $holder->x;
        $pk->y = (int) $holder->y;
        $pk->z = (int) $holder->z;
        $pk->eventType = 1;
        $pk->eventData = +$isOpen;
        $holder->getLevel()->addChunkPacket($holder->getX() >> 4, $holder->getZ() >> 4, $pk);
    }

    public function getHolder(){
        return $this->holder;
    }
}