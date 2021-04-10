<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//                        2017-2020

namespace TheNote\core\blocks\redstone;

class RedstoneTorchUnlit extends RedstoneTorch {
    
    protected $id = self::UNLIT_REDSTONE_TORCH;
    protected $itemId = self::REDSTONE_TORCH;

    public function __construct(int $meta = 0){
        $this->meta = $meta;
    }

    public function getName() : string {
        return "Unlit Redstone Torch";
    }

    public function getLightLevel() : int {
        return 0;
    }

    public function onScheduledUpdate() : void {
        if (!$this->isSidePowered($this, $this->getFace())) {
            $this->getLevel()->setBlock($this, new RedstoneTorch($this->getDamage()));
            $this->updateAroundDiodeRedstone($this);
        }
    }

    public function getStrongPower(int $face) : int {
        return 0;
    }

    public function getWeakPower(int $face) : int {
        return 0;
    }

    public function isPowerSource() : bool {
        return false;
    }

    public function onRedstoneUpdate() : void {
        if (!$this->isSidePowered($this, $this->getFace())) {
            $this->level->scheduleDelayedBlockUpdate($this, 2);
        }
    }
}