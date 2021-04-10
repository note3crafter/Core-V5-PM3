<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\invmenu;

use Closure;
use InvalidStateException;
use TheNote\core\invmenu\inventory\InvMenuInventory;
use TheNote\core\invmenu\metadata\MenuMetadata;
use TheNote\core\invmenu\session\PlayerManager;
use TheNote\core\invmenu\transaction\DeterministicInvMenuTransaction;
use TheNote\core\invmenu\transaction\InvMenuTransaction;
use TheNote\core\invmenu\transaction\InvMenuTransactionResult;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\inventory\transaction\InventoryTransaction;
use pocketmine\item\Item;
use pocketmine\Player;

class InvMenu implements MenuIds{

	public static function create(string $identifier) : InvMenu{
		return new InvMenu(InvMenuHandler::getMenuType($identifier));
	}

	public static function readonly(?Closure $listener = null) : Closure{
		return static function(InvMenuTransaction $transaction) use($listener) : InvMenuTransactionResult{
			$result = $transaction->discard();
			if($listener !== null){
				$listener(new DeterministicInvMenuTransaction($transaction, $result));
			}
			return $result;
		};
	}

	protected $type;
	protected $name;
	protected $listener;
	protected $inventory_close_listener;
	protected $inventory;

	public function __construct(MenuMetadata $type){
		if(!InvMenuHandler::isRegistered()){
			throw new InvalidStateException("Tried creating menu before calling " . InvMenuHandler::class . "::register()");
		}
		$this->type = $type;
		$this->inventory = $this->type->createInventory();
	}

	public function getType() : MenuMetadata{
		return $this->type;
	}

	public function getName() : ?string{
		return $this->name;
	}

	public function setName(?string $name) : self{
		$this->name = $name;
		return $this;
	}

	public function setListener(?Closure $listener) : self{
		$this->listener = $listener;
		return $this;
	}

	public function setInventoryCloseListener(?Closure $listener) : self{
		$this->inventory_close_listener = $listener;
		return $this;
	}

	final public function send(Player $player, ?string $name = null, ?Closure $callback = null) : void{
		$session = PlayerManager::getNonNullable($player);
		$network = $session->getNetwork();
		$network->dropPending();

		$session->removeWindow();

		$network->waitUntil($network->getGraphicWaitDuration(), function(bool $success) use($player, $session, $name, $callback) : void{
			if($success){
				$extra_data = $session->getMenuExtradata();
				$extra_data->setName($name ?? $this->getName());
				$extra_data->setPosition($this->type->calculateGraphicPosition($player));
				if($this->type->sendGraphic($player, $extra_data)){
					$session->setCurrentMenu($this, $callback);
				}else{
					$extra_data->reset();
					if($callback !== null){
						$callback(false);
					}
				}
			}elseif($callback !== null){
				$callback(false);
			}
		});
	}

	public function getInventory() : InvMenuInventory{
		return $this->inventory;
	}

	public function sendInventory(Player $player) : bool{
		return $player->addWindow($this->getInventory()) !== -1;
	}

	public function handleInventoryTransaction(Player $player, Item $out, Item $in, SlotChangeAction $action, InventoryTransaction $transaction) : InvMenuTransactionResult{
		$inv_menu_txn = new InvMenuTransaction($player, $out, $in, $action, $transaction);
		return $this->listener !== null ? ($this->listener)($inv_menu_txn) : $inv_menu_txn->continue();
	}

	public function onClose(Player $player) : void{
		if($this->inventory_close_listener !== null){
			($this->inventory_close_listener)($player, $this->getInventory());
		}

		PlayerManager::getNonNullable($player)->removeCurrentMenu();
	}
}
