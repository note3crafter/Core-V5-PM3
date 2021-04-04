<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server;

use pocketmine\event\Listener;
use pocketmine\inventory\ShapedRecipe;
use pocketmine\item\Item;
use pocketmine\plugin\PluginBase;
use TheNote\core\Main;
class Rezept  implements Listener {

    public function __construct(Main $plugin) {
        $this->plugin = $plugin;
    }

    public function onEnable()
    {
        $grass = Item::get(2);
        $air = Item::get(0);
        $dirt = Item::get(3);
        $tailgrass = Item::get(32,1);

        $recipe = new ShapedRecipe(
            ["XYX", "XZX", "XXX"],
            ["X" => $air, "Y" =>  $tailgrass, "Z" => $dirt],
            [$grass]
        );
        $this->plugin->getServer()->getCraftingManager()->registerShapedRecipe($recipe);
        $recipe2 = new ShapedRecipe(
            ["XXY", "XXZ", "XXX"],
            ["X" => $air, "Y" =>  $tailgrass, "Z" => $dirt],
            [$grass]
        );
        $this->plugin->getServer()->getCraftingManager()->registerShapedRecipe($recipe2);
        $recipe3 = new ShapedRecipe(
            ["YXX", "ZXX", "XXX"],
            ["X" => $air, "Y" =>  $tailgrass, "Z" => $dirt],
            [$grass]
        );
        $this->plugin->getServer()->getCraftingManager()->registerShapedRecipe($recipe3);
        $recipe4 = new ShapedRecipe(
               ["XXX",
                "XYX",
                "XZX"],
            ["X" => $air, "Y" =>  $tailgrass, "Z" => $dirt],
            [$grass]
        );
        $this->plugin->getServer()->getCraftingManager()->registerShapedRecipe($recipe4);
        $recipe5 = new ShapedRecipe(
            ["XXX", "YXX", "ZXX"],
            ["X" => $air, "Y" =>  $tailgrass, "Z" => $dirt],
            [$grass]
        );
        $this->plugin->getServer()->getCraftingManager()->registerShapedRecipe($recipe5);
        $recipe6 = new ShapedRecipe(
            ["XXX", "XXY", "XXZ"],
            ["X" => $air, "Y" =>  $tailgrass, "Z" => $dirt],
            [$grass]
        );
        $this->plugin->getServer()->getCraftingManager()->registerShapedRecipe($recipe6);
    }
}