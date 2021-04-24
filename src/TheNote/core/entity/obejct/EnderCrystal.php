<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\entity\obejct;

use pocketmine\block\Block;
use pocketmine\block\Fire;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\level\Explosion;
use TheNote\core\server\GameRules;
use TheNote\core\server\generators\ender\EnderGenerator as End;

class EnderCrystal extends Entity{
    public const NETWORK_ID = self::ENDER_CRYSTAL;

    public $height = 0.98;
    public $width = 0.98;

    public $gravity = 0;
    public $drag = 0;

    public function onMovementUpdate() : void{
        // NOOP
    }

    public function onUpdate(int $currentTick) : bool{
        if($this->level->getProvider()->getPath() === End::class){
            if($this->level->getBlock($this)->getId() !== Block::FIRE){
                $this->level->setBlock($this, new Fire());
            }
        }

        return parent::onUpdate($currentTick);
    }

    public function attack(EntityDamageEvent $source) : void{
        parent::attack($source);

        if(!$this->isFlaggedForDespawn() and !$source->isCancelled() and $source->getCause() !== EntityDamageEvent::CAUSE_FIRE and $source->getCause() !== EntityDamageEvent::CAUSE_FIRE_TICK){
            $this->flagForDespawn();

            if($this->level->getGameRules()->getBool(GameRules::RULE_TNT_EXPLODES)){
                $exp = new Explosion($this, 6, $this);

                $exp->explodeA();
                $exp->explodeB();
            }
        }
    }

    public function canBeCollidedWith() : bool{
        return false;
    }

    public function setShowBase(bool $value) : void{
        $this->setGenericFlag(self::DATA_FLAG_SHOWBASE, $value);
    }

    public function showBase() : bool{
        return $this->getGenericFlag(self::DATA_FLAG_SHOWBASE);
    }
}

