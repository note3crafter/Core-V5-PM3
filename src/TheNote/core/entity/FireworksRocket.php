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

namespace TheNote\core\entity;

use pocketmine\entity\Entity;
use TheNote\core\item\Firework;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\ActorEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

class FireworksRocket extends Entity{
    public const NETWORK_ID = self::FIREWORKS_ROCKET;

    public $width = 0.25;
    public $height = 0.25;

    /** @var int */
    protected $lifeTime = 0;

    public function __construct(Level $level, CompoundTag $nbt, ?Firework $fireworks = null){
        parent::__construct($level, $nbt);

        if($fireworks !== null && $fireworks->getNamedTagEntry("Fireworks") instanceof CompoundTag){
            $this->propertyManager->setCompoundTag(self::DATA_MINECART_DISPLAY_BLOCK, $fireworks->getNamedTag());
            $this->setLifeTime($fireworks->getRandomizedFlightDuration());
        }

        $level->broadcastLevelSoundEvent($this, LevelSoundEventPacket::SOUND_LAUNCH);
    }

    protected function tryChangeMovement() : void{
        $this->motion->x *= 1.15;
        $this->motion->y += 0.04;
        $this->motion->z *= 1.15;
    }

    public function entityBaseTick(int $tickDiff = 1) : bool{
        if($this->closed){
            return false;
        }

        $hasUpdate = parent::entityBaseTick($tickDiff);
        if($this->doLifeTimeTick()){
            $hasUpdate = true;
        }

        return $hasUpdate;
    }

    public function setLifeTime(int $life) : void{
        $this->lifeTime = $life;
    }

    protected function doLifeTimeTick() : bool{
        if(!$this->isFlaggedForDespawn() and --$this->lifeTime < 0){
            $this->doExplosionAnimation();
            $this->flagForDespawn();
            return true;
        }

        return false;
    }

    protected function doExplosionAnimation() : void{
        $fireworks_nbt = $this->propertyManager->getCompoundTag(self::DATA_MINECART_DISPLAY_BLOCK);
        if($fireworks_nbt === null){
            return;
        }

        $fireworks_nbt = $fireworks_nbt->getCompoundTag("Fireworks");
        if($fireworks_nbt === null){
            return;
        }

        $explosions = $fireworks_nbt->getListTag("Explosions");
        if($explosions === null){
            return;
        }

        /** @var CompoundTag $explosion */
        foreach($explosions->getAllValues() as $explosion){
            switch($explosion->getByte("FireworkType")){
                case Firework::TYPE_SMALL_SPHERE:
                    $this->level->broadcastLevelSoundEvent($this, LevelSoundEventPacket::SOUND_BLAST);
                    break;
                case Firework::TYPE_HUGE_SPHERE:
                    $this->level->broadcastLevelSoundEvent($this, LevelSoundEventPacket::SOUND_LARGE_BLAST);
                    break;
                case Firework::TYPE_STAR:
                case Firework::TYPE_BURST:
                case Firework::TYPE_CREEPER_HEAD:
                    $this->level->broadcastLevelSoundEvent($this, LevelSoundEventPacket::SOUND_TWINKLE);
                    break;
            }
        }
        $this->broadcastEntityEvent(ActorEventPacket::FIREWORK_PARTICLES);
    }
}