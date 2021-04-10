<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//                        2017-2020

namespace TheNote\core\blocks;

use TheNote\core\entity\SkullEntity;
use TheNote\core\Main;
use TheNote\core\utils\SkullSideManager;
use pocketmine\block\Block;
use pocketmine\block\Skull;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\ByteArrayTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use ReflectionClass;
use ReflectionException;
use function base64_decode;

class SkullBlock extends Skull {

	public function place(Item $item, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector, Player $player = null): bool {
		if ($face === Vector3::SIDE_DOWN) return false;
		
		if (!parent::place($item, $blockReplace, $blockClicked, $face, $clickVector, $player)) return false;
		
		if (!$item->hasCustomBlockData() or !($tag = $item->getCustomBlockData())->hasTag("skull_data", StringTag::class)) return true;
		
		if (!($tile = $this->getLevelNonNull()->getTile($this->asVector3())) instanceof \pocketmine\tile\Skull) return true;
		$ref = new ReflectionClass($tile);
		$property = $ref->getProperty("skullRotation");
		$property->setAccessible(true);
		$yaw = $property->getValue($tile);
		
		$skinData = $tag->getString("skull_data");
		
		for ($i = 1; $i < 32; $i++) {
			if ($tag->hasTag("skull_data_" . $i, StringTag::class)) $skinData .= $tag->getString("skull_data_" . $i);
		}
		
		$skinData = base64_decode($skinData);
		
		$data = SkullSideManager::addAdditions($face, $yaw);
		
		$position = $this->add($data[1]);
		
		$nbt = Entity::createBaseNBT($position, null, $data[0]);
		
		$nbt->setTag(new CompoundTag("Skin", [
			new StringTag("Name", "Custom_Head_Layer"),
			new ByteArrayTag("Data", $skinData),
			new ByteArrayTag("CapeData", ""),
			new StringTag("GeometryName", "geometry.skull"),
			new ByteArrayTag("GeometryData", Main::GEOMETRY)
		]));
		
		$nbt->setString("skull_name", $tag->getString("skull_name"));
		
		$skull = new SkullEntity($this->getLevelNonNull(), $nbt);
		$skull->setImmobile();
		$skull->spawnToAll();
		return true;
	}

	public function getDrops(Item $item): array {
		return [];
	}
}