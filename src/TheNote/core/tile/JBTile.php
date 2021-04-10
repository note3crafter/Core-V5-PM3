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

namespace TheNote\core\tile;


use pocketmine\Server;
use pocketmine\Player;
use pocketmine\item\Item;
use pocketmine\tile\Spawnable;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\level\particle\GenericParticle;
use pocketmine\network\mcpe\protocol\TextPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

use TheNote\core\Main;
use TheNote\core\events\RecordPlayEvent;
use TheNote\core\events\RecordStopEvent;
use TheNote\core\item\Record;

use mt_rand;
use mt_getrandmax;

class JBTile extends Spawnable{

    public $has_record = false;
    public $record = null;

    public function getDefaultName() : string{
        return "Jukebox";
    }

    public function handleInteract(Item $item, Player $player = null){
        if($this->has_record){
            $this->updateRecord();
        } else {
            if($item instanceof Record){
                $this->updateRecord($item, $player);
            }
        }
        $this->scheduleUpdate();
    }

    public function handleBreak(Item $item, Player $player){
        if($this->has_record){
            $this->updateRecord();
        }
    }

    public function updateRecord(Item $record = null, Player $player = null){
        if($record == null){

            //RecordStopEvent


            $ev = new RecordStopEvent(Main::getInstance(), $this->getBlock(), $this->record, $player);
            Server::getInstance()->getPluginManager()->callEvent($ev);
            if($ev->isCancelled()){
                return;
            }

            $this->dropRecord();
        } else {

            //RecordPlayingEvent

            $ev = new RecordPlayEvent(Main::getInstance(), $this->getBlock(), $record, $player);
            Server::getInstance()->getPluginManager()->callEvent($ev);
            if($ev->isCancelled()){
                return;
            }
            $player->getInventory()->removeItem($record);
            $this->record = $record;
            $this->has_record = true;
            $this->getLevel()->broadcastLevelSoundEvent($this, $record->getSoundId());
            $plug = Main::getInstance();
        }
        $this->onChanged();
    }
    public function dropRecord(){
        if($this->has_record){
            $this->getLevel()->dropItem($this->asVector3(), $this->record);
            $this->has_record = false;
            $this->record = null;
            $this->stopSound();
        }
    }
    public function stopSound() : void{
        $this->getLevel()->broadcastLevelSoundEvent($this, LevelSoundEventPacket::SOUND_STOP_RECORD);
    }
    /*public function onUpdate() : bool{
        $plug = Main::getInstance();
                $this->level->addParticle(new GenericParticle($this->add($this->randomFloat(0.3,0.7), $this->randomFloat(1.2,1.6), $this->randomFloat(0.3,0.7)), 36));
        return true;
    }*/
    public function readSaveData(CompoundTag $nbt) : void{
        if($nbt->hasTag("Record")){
            $this->record = Item::nbtDeserialize($nbt->getCompoundTag("Record"));
        }
    }
    protected function writeSaveData(CompoundTag $nbt) : void{
        if($this->record !== null){
            $nbt->setTag($this->record->nbtSerialize(-1, "Record"));
        }
    }
    protected function addAdditionalSpawnData(CompoundTag $nbt) : void{} //must stay

    private function randomFloat($min = 0, $max = 1) {
        return $min + mt_rand() / mt_getrandmax() * ($max - $min);
    }
}