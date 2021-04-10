<?php
declare(strict_types=1);

namespace TheNote\core\blocks\multiblock;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\EndPortalFrame;
use pocketmine\block\Obsidian;
use TheNote\core\blocks\EndPortal;
use TheNote\core\blocks\Portal;
use TheNote\core\Main;

final class MultiBlockFactory {
    
    /** @var MultiBlock[] */
    private static $blocks = [];
    
    public static function init(): void {
        Main::getInstance()->getServer()->getPluginManager()->registerEvents(new MultiBlockEventHandler(), Main::getInstance());
        self::initNether();
        self::initEnd();
    }
    private static function initNether(): void{
        self::register(new NetherPortalFrameMultiBlock(), new Obsidian());
        self::register(new NetherPortalMultiBlock(), new Portal());
    }
    
    public static function register(MultiBlock $multiBlock, Block $block): void{
        self::$blocks[$block->getId() . ":" . $block->getDamage()] = $multiBlock;
        foreach(BlockFactory::getBlockStatesArray() as $state){
            if($state->getId() === $block->getId()){
                self::$blocks[$state->getId() . ":" . $state->getDamage()] = $multiBlock;
            }
        }
    }
    
    private static function initEnd(): void{
        self::register(new EndPortalFrameMultiBlock(), new EndPortalFrame());
        self::register(new EndPortalMultiBlock(), new EndPortal());
    }
    
    public static function get(Block $block): ?MultiBlock{
        return self::$blocks[$block->getId() . ":" . $block->getDamage()] ?? null;
    }
}