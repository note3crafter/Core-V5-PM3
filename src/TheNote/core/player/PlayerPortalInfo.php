<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//                        2017-2020

namespace TheNote\core\player;

use TheNote\core\blocks\multiblock\PortalMultiBlock;

final class PlayerPortalInfo {
    
    private $block;
    private $duration = 0;
    private $max_duration;
    public function __construct(PortalMultiBlock $block, int $max_duration){
        $this->block = $block;
        $this->max_duration = $max_duration;
    }
    
    public function getBlock(): PortalMultiBlock{
        return $this->block;
    }
    
    public function tick(): bool{
        if($this->duration === $this->max_duration){
            $this->duration = 0;
            return true;
        }
        
        ++$this->duration;
        return false;
    }
}