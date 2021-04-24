<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server\maps;

use pocketmine\nbt\BigEndianNBTStream;
use pocketmine\nbt\LittleEndianNBTStream;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Server;

class MapManager{

	protected static $maps = [];
	protected static $mapIdCounter = 0;

	private function __construct(){
		// NOOP
	}

	public static function setMapData(MapData $map) : void{
		self::$maps[$map->getId()] = $map;
	}

	public static function unsetMapData(int $mapId) : void{
		unset(self::$maps[$mapId]);
	}

	public static function getMapDataById(int $id) : ?MapData{
		if(!isset(self::$maps[$id])){
			self::loadMapData($id);
		}
		return self::$maps[$id] ?? null;
	}

	public static function getNextId() : int{
		return self::$mapIdCounter++;
	}

	public static function loadIdCounts() : void{
		@mkdir($path = Server::getInstance()->getDataPath() . "maps/", 0777);
		$stream = new LittleEndianNBTStream();

		if(is_file($path . "idcounts.dat")){
			$data = $stream->read(file_get_contents($path . "idcounts.dat"));
			self::$mapIdCounter = $data->getInt("map", 0);
		}
	}

	public static function loadMapData(int $id) : void{
		@mkdir($path = Server::getInstance()->getDataPath() . "maps/");
		$stream = new BigEndianNBTStream();

		if(is_file($fp = $path . "map_" . strval($id) . ".dat")){
			$data = $stream->readCompressed(file_get_contents($fp));
			$mp = new MapData($id);
			$mp->readSaveData($data);

			self::setMapData($mp);
		}
	}

	public static function saveMaps() : void{
		@mkdir($path = Server::getInstance()->getDataPath() . "maps/", 0777);
		$stream = new LittleEndianNBTStream();

		$idcounts = new CompoundTag();
		$idcounts->setInt("map", self::$mapIdCounter);

		file_put_contents($path . "idcounts.dat", $stream->write($idcounts));
		$stream = new BigEndianNBTStream();

		foreach(self::$maps as $data){
			if(!$data->isVirtual() and $data->isDirty()){
				$tag = new CompoundTag("data");
				$data->writeSaveData($tag);

				file_put_contents($path . "map_" . strval($data->getId()) . ".dat", $stream->writeCompressed($tag));
			}
		}
	}

	public static function resetMaps() : void{
		self::$maps = [];
	}
}