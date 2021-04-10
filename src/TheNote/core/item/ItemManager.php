<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\item;

use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;

class ItemManager
{
    public static function init()
    {

        ItemFactory::registerItem(new Trident(), true);
        ItemFactory::registerItem(new EyeOfEnder(), true);
        ItemFactory::registerItem(new AcaciaSign(), true);
        ItemFactory::registerItem(new BirchSign(), true);
        ItemFactory::registerItem(new DarkoakSign(), true);
        ItemFactory::registerItem(new JungleSign(), true);
        ItemFactory::registerItem(new SpruceSign(), true);
        ItemFactory::registerItem(new Crossbow(), true);
        ItemFactory::registerItem(new Elytra(), true);
        ItemFactory::registerItem(new Lead(), true);
        ItemFactory::registerItem(new Schild(), true);
        ItemFactory::registerItem(new FireCharge(), true);
        ItemFactory::registerItem(new ShulkerBox(), true);
        ItemFactory::registerItem(new NetheriteHelmet(), true);
        ItemFactory::registerItem(new NetheriteBoots(), true);
        ItemFactory::registerItem(new NetheriteChestplate(), true);
        ItemFactory::registerItem(new NetheriteLeggings(), true);
        ItemFactory::registerItem(new NetheriteIngot(), true);
        ItemFactory::registerItem(new NetheriteScrap(), true);
        ItemFactory::registerItem(new NetheriteShovel(), true);
        ItemFactory::registerItem(new NetheriteSword(), true);
        ItemFactory::registerItem(new NetheriteAxe(), true);
        ItemFactory::registerItem(new NetheritePickaxe(), true);
        ItemFactory::registerItem(new NetheriteHoe(), true);
        ItemFactory::registerItem(new Beacon(), true);
        ItemFactory::registerItem(new JukeboxItem(), true);
        ItemFactory::registerItem(new Saddle(), true);
        //ItemFactory::addCreativItem(new Item(525, 0, "Netherite Block"), true);

        //ItemFactory::registerItem(new Fireworks(), true); Defekt... Wer bock hat es zu repaieren nur zu!
        ItemFactory::registerItem(new Record(500, "13", LevelSoundEventPacket::SOUND_RECORD_13), true);
        ItemFactory::registerItem(new Record(501, "Cat", LevelSoundEventPacket::SOUND_RECORD_CAT), true);
        ItemFactory::registerItem(new Record(502, "Blocks", LevelSoundEventPacket::SOUND_RECORD_BLOCKS), true);
        ItemFactory::registerItem(new Record(503, "Chirp", LevelSoundEventPacket::SOUND_RECORD_CHIRP), true);
        ItemFactory::registerItem(new Record(504, "Far", LevelSoundEventPacket::SOUND_RECORD_FAR), true);
        ItemFactory::registerItem(new Record(505, "Mall", LevelSoundEventPacket::SOUND_RECORD_MALL), true);
        ItemFactory::registerItem(new Record(506, "Mellohi", LevelSoundEventPacket::SOUND_RECORD_MELLOHI), true);
        ItemFactory::registerItem(new Record(507, "Stal", LevelSoundEventPacket::SOUND_RECORD_STAL), true);
        ItemFactory::registerItem(new Record(508, "Strad", LevelSoundEventPacket::SOUND_RECORD_STRAD), true);
        ItemFactory::registerItem(new Record(509, "Ward", LevelSoundEventPacket::SOUND_RECORD_WARD), true);
        ItemFactory::registerItem(new Record(510, "11", LevelSoundEventPacket::SOUND_RECORD_11), true);
        ItemFactory::registerItem(new Record(511, "Wait", LevelSoundEventPacket::SOUND_RECORD_WAIT), true);
        //ItemFactory::registerItem(new Record(759, "Pigstep Disc"));

        Item::initCreativeItems();

    }
}
