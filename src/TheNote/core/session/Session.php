<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\session;

use TheNote\core\inventory\FakeInventory;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\DataPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\Player;

class Session{

    private $player;
    private $gliding = false;
    private $startGlideTime = null;
    private $endGlideTime = null;
    private $inBoat = false;
    private $currentWindow = null;

    public function __construct(Player $player){
        $this->player = $player;
    }

    public function isGliding(): bool{
        return $this->gliding;
    }

    public function setGliding(bool $value = true): void{
        $this->gliding = $value;
        if($value){
            $this->startGlideTime = time();
        }else{
            $this->endGlideTime = time();
        }
    }

    public function getStartGlideTime(): ?int{
        return $this->startGlideTime;
    }

    public function getEndGlideTime(): ?int{
        return $this->endGlideTime;
    }

    public function isInBoat(): bool{
        return $this->inBoat;
    }

    public function setInBoat(bool $inBoat): void{
        $this->inBoat = $inBoat;
    }

    public function getCurrentWindow(): ?FakeInventory{
        return $this->currentWindow;
    }

    public function setCurrentWindow(?FakeInventory $currentWindow): void{
        $this->currentWindow = $currentWindow;
    }

    public static function playSound($player, string $sound, float $pitch = 1, float $volume = 1, bool $packet = false): ?DataPacket{
        $pk = new PlaySoundPacket();
        $pk->soundName = $sound;
        $pk->x = $player->x;
        $pk->y = $player->y;
        $pk->z = $player->z;
        $pk->pitch = $pitch;
        $pk->volume = $volume;
        if($packet){
            return $pk;
        }elseif($player instanceof Player){
            $player->dataPacket($pk);
        }
        return null;
    }
}
