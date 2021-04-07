<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\invmenu\inventory;

use TheNote\core\invmenu\metadata\MenuMetadata;
use TheNote\core\invmenu\session\PlayerManager;
use pocketmine\inventory\ContainerInventory;
use pocketmine\level\Position;
use pocketmine\Player;

class InvMenuInventory extends ContainerInventory{

	private $menu_metadata;

	public function __construct(MenuMetadata $menu_metadata){
		$this->menu_metadata = $menu_metadata;
		parent::__construct(new Position(), [], $menu_metadata->getSize());
	}

	public function moveTo(int $x, int $y, int $z) : void{
		$this->holder->setComponents($x, $y, $z);
	}

	final public function getMenuMetadata() : MenuMetadata{
		return $this->menu_metadata;
	}

	final public function getName() : string{
		return $this->menu_metadata->getIdentifier();
	}

	public function getDefaultSize() : int{
		return $this->menu_metadata->getSize();
	}

	public function getNetworkType() : int{
		return $this->menu_metadata->getWindowType();
	}

	public function onClose(Player $who) : void{
		if(isset($this->viewers[spl_object_hash($who)])){
			parent::onClose($who);
			$menu = PlayerManager::getNonNullable($who)->getCurrentMenu();
			if($menu !== null && $menu->getInventory() === $this){
				$menu->onClose($who);
			}
		}
	}
}