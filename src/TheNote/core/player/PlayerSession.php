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

use pocketmine\level\Location;
use pocketmine\Player;
use pocketmine\Server;
use TheNote\core\blocks\multiblock\PortalMultiBlock;
use TheNote\core\events\PlayerEnterPortalEvent;
use TheNote\core\utils\Utils;
use TheNote\core\events\PlayerPortalTeleportEvent;
use TheNote\core\item\Elytra;
use TheNote\core\Main;

class PlayerSession {

    public $lastEnderPearlUse = 0, $lastChorusFruitEat = 0, $lastHeldSlot = 0;
    public $usingElytra = false, $allowCheats = false, $fishing = false;
    public $fishingHook = null;
    public $clientData = [];
    public $vehicle = null;
    private $player;
    private $inPortal;
    private $changingDimension = false;

    public function __construct(Player $player){
        $this->player = $player;
    }

    public function getPlayer(): Player{
        return $this->player;
    }
    
    public function getServer(): Server{
        return $this->player->getServer();
    }

    public function damageElytra(int $damage = 1){
        if(!$this->player->isAlive() || !$this->player->isSurvival()) return;
        $inv = $this->player->getArmorInventory();
        $elytra = $inv->getChestplate();
        if(!$elytra instanceof Elytra) return;
        $elytra->applyDamage($damage);
    }
    
    public function isUsingElytra(): bool{
        return ($this->player->getArmorInventory()->getChestplate() instanceof Elytra);
    }
    
    public function onEnterPortal(PortalMultiBlock $block): void{
        $ev = new PlayerEnterPortalEvent($this->player, $block, $block->getTeleportationDuration($this->player));
        $ev->call();
        if(!$ev->isCancelled()){
            $this->inPortal = new PlayerPortalInfo($block, $ev->getTeleportDuration());
            PlayerSessionManager::scheduleTicking($this->player);
        }
    }
    
    public function startDimensionChange(): void{
        $this->changingDimension = true;
    }
    
    public function endDimensionChange(): void{
        $this->changingDimension = false;
    }
    
    public function isChangingDimension(): bool{
        return $this->changingDimension;
    }
    
    public function tick(): void{
        if($this->inPortal->tick()){
            $this->teleport();
            $this->onLeavePortal();
        }
    }
    
    private function teleport(): void{
        $to = $this->inPortal->getBlock()->getTargetWorldInstance();
        $target = Location::fromObject(($this->player->getLevel() === $to ? Main::$overworldLevel : $to)->getSpawnLocation());
        ($ev = new PlayerPortalTeleportEvent($this->player, $this->inPortal->getBlock(), $target))->call();
        if(!$ev->isCancelled()){
            $pos = $ev->getTarget();
            if($target->getLevel() === Main::$netherLevel){
                $pos = Utils::genNetherSpawn($this->player->asPosition(), $target->getLevel());
            }
            $this->player->teleport($pos);
        }
    }
    
    public function onLeavePortal(): void{
        PlayerSessionManager::stopTicking($this->player);
        $this->inPortal = null;
    }
}
