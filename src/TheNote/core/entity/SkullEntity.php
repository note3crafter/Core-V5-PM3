<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\entity;

use pocketmine\entity\Human;
use pocketmine\event\entity\EntityDamageEvent;

class SkullEntity extends Human {

	public $width = 0.025;
	public $height = 0.025;
	public $canCollide = false;
	
	protected function initEntity(): void {
		$this->setMaxHealth(1);
		$this->setImmobile();
		$this->setScale(1.1275);
		parent::initEntity();
	}

	public function attack(EntityDamageEvent $source): void {
		$source->setCancelled();
	}

	public function onUpdate(int $currentTick): bool {
		return true;
	}

	public function canBeCollidedWith(): bool {
		return false;
	}
}