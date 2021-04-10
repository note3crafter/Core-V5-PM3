<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\inventory;

use pocketmine\inventory\ContainerInventory;
use pocketmine\level\Position;
use pocketmine\network\mcpe\protocol\types\WindowTypes;
use pocketmine\Player;

class BeaconInventory extends ContainerInventory {

    protected $holder;

    public function __construct(Position $pos) {
        parent::__construct($pos->asPosition());
    }
    public function getNetworkType() : int {
        return WindowTypes::BEACON;
    }
    public function getName() : string {
        return "Beacon";
    }
    public function getDefaultSize() : int {
        return 1;
    }
    public function getHolder() {
        return $this->holder;
    }
    public function onClose(Player $who) : void {
        parent::onClose($who);
        $this->dropContents($this->holder->getLevel(), $this->holder->add(0.5, 0.5, 0.5));
    }
}