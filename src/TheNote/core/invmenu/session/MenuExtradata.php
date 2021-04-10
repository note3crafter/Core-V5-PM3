<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\invmenu\session;

use pocketmine\math\Vector3;

class MenuExtradata{

	protected $position;
	protected $name;

	public function getPosition() : ?Vector3{
		return $this->position;
	}

	public function getPositionNotNull() : Vector3{
		return $this->position;
	}

	public function getName() : ?string{
		return $this->name;
	}

	public function setPosition(?Vector3 $pos) : void{
		$this->position = $pos;
	}

	public function setName(?string $name) : void{
		$this->name = $name;
	}

	public function reset() : void{
		$this->position = null;
		$this->name = null;
	}
}