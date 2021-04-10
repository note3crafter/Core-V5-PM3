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

class RedstoneLampLit extends RedstoneLamp {
    
    protected $id = self::LIT_REDSTONE_LAMP;

    public function getName() : string {
        return "Lit Redstone Lamp";
    }

    public function getLightLevel() : int {
        return 15;
    }

    public function onRedstoneUpdate() : void {
        if (!$this->isBlockPowered($this)) {
            $this->level->scheduleDelayedBlockUpdate($this, 8);
        }
    }

    public function onScheduledUpdate() : void {
        if (!$this->isBlockPowered($this)) {
            $this->getLevel()->setBlock($this, new RedstoneLamp());
        }
    }
}