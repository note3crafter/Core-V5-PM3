<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//                        2017-2020

namespace TheNote\core\task;

use pocketmine\Player;
use pocketmine\scheduler\Task;
use TheNote\core\Main;
use TheNote\core\server\RocketParticle;

class ElytraRocketBoostTrackingTask extends Task
{
    protected $player;
    protected $count;
    private $internalCount = 1;

    public function __construct(Player $player, int $count)
    {
        $this->player = $player;
        $this->count = $count;
    }

    public function onRun(int $currentTick)
    {
        if ($this->internalCount <= $this->count) {
            $this->player->getLevel()->addParticle(new RocketParticle($this->player->asVector3()->add($this->player->width / 2 + mt_rand(-100, 100) / 500, $this->player->height / 2 + mt_rand(-100, 100) / 500, $this->player->width / 2 + mt_rand(-100, 100) / 500)));
            $this->internalCount++;
        } else {
            Main::getInstance()->getScheduler()->cancelTask($this->getTaskId());
        }
    }
}