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

use pocketmine\entity\Entity;
use pocketmine\entity\Vehicle;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\network\mcpe\protocol\ActorEventPacket;
use pocketmine\Server;

class BoatEntity extends Vehicle
{

    public const TAG_WOOD_ID = "WoodID";
    public const NETWORK_ID = self::BOAT;

    public $height = 0.7;
    public $width = 1.6;
    public $gravity = 0;
    public $drag = 0.1;

    public $linkedEntity = null;
    protected $age = 0;

    public function initEntity(): void
    {
        if (!$this->namedtag->hasTag(self::TAG_WOOD_ID, ByteTag::class)) {
            $this->namedtag->setByte(self::TAG_WOOD_ID, 0);
        }
        $this->setMaxHealth(4);
        parent::initEntity();
    }

    public function getDrops(): array
    {
        return [Item::get(Item::BOAT, $this->getWoodID(), 1)];
    }

    public function getWoodID()
    {
        return $this->namedtag->getByte(self::TAG_WOOD_ID);
    }

    public function attack(EntityDamageEvent $source): void
    {
        parent::attack($source);
        if (!$source->isCancelled()) {
            $pk = new ActorEventPacket();
            $pk->entityRuntimeId = $this->id;
            Server::getInstance()->broadcastPacket($this->getViewers(), $pk);
        }
    }

    public function entityBaseTick(int $tickDiff = 1): bool
    {
        return false;
        if ($this->closed) {
            return false;
        }
        if ($tickDiff <= 0 and !$this->justCreated) {
            return true;
        }
        $this->lastUpdate = Server::getInstance()->getTick();
        $this->timings->startTiming();
        $hasUpdate = parent::entityBaseTick($tickDiff);
        if (!$this->level->getBlock(new Vector3($this->x, $this->y, $this->z))->getBoundingBox() == null or $this->isInsideOfWater()) {
            $this->motionY = 0.1;
        } else {
            $this->motionY = -0.08;
        }
        $this->move($this->motionX, $this->motionY, $this->motionZ);
        $this->updateMovement();
        if (!($this->linkedEntity instanceof Entity)) {
            if ($this->age > 1500) {
                $this->flagForDespawn();
                $hasUpdate = true;
                //$this->scheduleUpdate();
                $this->age = 0;
            }
            $this->age++;
        } else $this->age = 0;
        $this->timings->stopTiming();
        return $hasUpdate or !$this->onGround or abs($this->motionX) > 0.00001 or abs($this->motionY) > 0.00001 or abs($this->motionZ) > 0.00001;

    }
}