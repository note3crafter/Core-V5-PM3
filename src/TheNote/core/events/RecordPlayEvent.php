<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//                        2017-2020

namespace TheNote\core\events;

use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\block\Block;

use TheNote\core\Main;

class RecordPlayEvent extends JukeboxEvent
{
    private $plugin;
    private $player;
    private $block;
    private $record;

    public function __construct(Main $plugin, Block $block, Item $record, Player $player = null)
    {
        $this->block = $block;
        $this->player = $player;
        $this->record = $record;
        parent::__construct($plugin);
    }

    public function getPlayer()
    {
        return $this->player;
    }

    public function getBlock()
    {
        return $this->block;
    }

    public function getRecord()
    {
        return $this->record;
    }
}
