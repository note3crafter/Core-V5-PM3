<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\invmenu\transaction;

use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\inventory\transaction\InventoryTransaction;
use pocketmine\item\Item;
use pocketmine\Player;

class InvMenuTransaction{

	private $player;
	private $out;
	private $in;
	private $action;
	private $transaction;

	public function __construct(Player $player, Item $out, Item $in, SlotChangeAction $action, InventoryTransaction $transaction){
		$this->player = $player;
		$this->out = $out;
		$this->in = $in;
		$this->action = $action;
		$this->transaction = $transaction;
	}

	public function getPlayer() : Player{
		return $this->player;
	}

	public function getOut() : Item{
		return $this->out;
	}

	public function getIn() : Item{
		return $this->in;
	}

	public function getItemClicked() : Item{
		return $this->getOut();
	}

	public function getItemClickedWith() : Item{
		return $this->getIn();
	}

	public function getAction() : SlotChangeAction{
		return $this->action;
	}

	public function getTransaction() : InventoryTransaction{
		return $this->transaction;
	}

	public function continue() : InvMenuTransactionResult{
		return new InvMenuTransactionResult(false);
	}

	public function discard() : InvMenuTransactionResult{
		return new InvMenuTransactionResult(true);
	}
}