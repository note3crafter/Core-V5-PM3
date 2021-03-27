<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

declare(strict_types = 1);

namespace TheNote\core\item;

use pocketmine\inventory\ShapedRecipe;
use pocketmine\inventory\ShapelessRecipe;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;

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

        //ItemFactory::registerItem(new Fireworks(), true); Defekt... Wer bock hat es zu repaieren nur zu!
        ItemFactory::registerItem(new Record(Item::RECORD_13, 0, "Music Disc 13"), true);
        ItemFactory::registerItem(new Record(Item::RECORD_CAT, 0, "Music Disc cat"), true);
        ItemFactory::registerItem(new Record(Item::RECORD_BLOCKS, 0, "Music Disc blocks"), true);
        ItemFactory::registerItem(new Record(Item::RECORD_CHIRP, 0, "Music Disc chirp"), true);
        ItemFactory::registerItem(new Record(Item::RECORD_FAR, 0, "Music Disc far"), true);
        ItemFactory::registerItem(new Record(Item::RECORD_MALL, 0, "Music Disc mall"), true);
        ItemFactory::registerItem(new Record(Item::RECORD_MELLOHI, 0, "Music Disc mellohi"), true);
        ItemFactory::registerItem(new Record(Item::RECORD_STAL, 0, "Music Disc stal"), true);
        ItemFactory::registerItem(new Record(Item::RECORD_STRAD, 0, "Music Disc strad"), true);
        ItemFactory::registerItem(new Record(Item::RECORD_WARD, 0, "Music Disc ward"), true);
        ItemFactory::registerItem(new Record(Item::RECORD_11, 0, "Music Disc 11"), true);
        ItemFactory::registerItem(new Record(Item::RECORD_WAIT, 0, "Music Disc wait"), true);

        Item::initCreativeItems();

    }
}
