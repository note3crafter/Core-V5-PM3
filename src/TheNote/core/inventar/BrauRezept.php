<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//                        2017-2020

declare(strict_types = 1);

namespace TheNote\core\inventar;

use TheNote\core\Main;
use pocketmine\inventory\CraftingManager;
use pocketmine\inventory\Recipe;
use pocketmine\item\Item;
use pocketmine\utils\UUID;

class BrauRezept implements Recipe {

	private $id = null;

	/** @var Item */
	private $output;

	/** @var Item */
	private $ingredient;

	/** @var Item */
	private $potion;

	/**
	 * BrewingRecipe constructor.
	 * @param Item $result
	 * @param Item $ingredient
	 * @param Item $potion
	 */
	public function __construct(Item $result, Item $ingredient, Item $potion){
		$this->output = clone $result;
		$this->ingredient = clone $ingredient;
		$this->potion = clone $potion;
	}

	public function getPotion(){
		return clone $this->potion;
	}

	public function getId(){
		return $this->id;
	}

	public function setId(UUID $id){
		if($this->id !== null){
			throw new \InvalidStateException("ID is already set");
		}

		$this->id = $id;
	}

	/**
	 * @param Item $item
	 */
	public function setInput(Item $item){
		$this->ingredient = clone $item;
	}

	/**
	 * @return Item
	 */
	public function getInput(){
		return clone $this->ingredient;
	}

	/**
	 * @return Item
	 */
	public function getResult(){
		return clone $this->output;
	}

	public function registerToCraftingManager(CraftingManager $manager): void{
		Main::getInstance()->getBrewingManager()->registerBrewingRecipe($this);
	}
}