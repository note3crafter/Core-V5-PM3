<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace pocketmine\entity\vehicle;

use pocketmine\entity\Vehicle;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\math\Vector3;

class Minecart extends Vehicle{
    public const NETWORK_ID = self::MINECART;

    public $height = 0.7;
    public $width = 0.98;

    protected $gravity = 0.5;
    protected $drag = 0.1;

    protected function initEntity() : void{
        $this->setHealth(6);

        parent::initEntity();
    }

    public function getRiderSeatPosition(int $seatNumber = 0) : Vector3{
        return new Vector3($seatNumber * 0.8, 0, 0);
    }

    public function getDrops() : array{
        return [
            ItemFactory::get(Item::MINECART)
        ];
    }
}
