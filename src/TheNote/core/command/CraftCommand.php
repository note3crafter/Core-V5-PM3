<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\command;

use pocketmine\block\Block;
use pocketmine\inventory\CraftingGrid;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;
use pocketmine\network\mcpe\protocol\types\WindowTypes;
use pocketmine\utils\Config;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class CraftCommand extends Command
{

    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("craft", $config->get("prefix") . "Benutze die CraftingTable Unterwegs", "/craft", ["crafting"]);
        $this->setPermission("core.command.craft");

    }
    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        $this->sendCraftingTable($sender);
        $sender->setCraftingGrid(new CraftingGrid($sender, CraftingGrid::SIZE_BIG));
        if(!array_key_exists($windowId = Player::HARDCODED_CRAFTING_GRID_WINDOW_ID, $sender->openHardcodedWindows))
        {
            $pk = new ContainerOpenPacket();
            $pk->windowId = $windowId;
            $pk->type = WindowTypes::WORKBENCH;
            $pk->x = $sender->getFloorX();
            $pk->y = $sender->getFloorY() - 2;
            $pk->z = $sender->getFloorZ();
            $sender->sendDataPacket($pk);
            $sender->openHardcodedWindows[$windowId] = true;
        }
        return true;
    }
    public function sendCraftingTable(Player $player)
    {
        $block1 = Block::get(Block::CRAFTING_TABLE);
        $block1->x = (int)floor($player->x);
        $block1->y = (int)floor($player->y) - 2;
        $block1->z = (int)floor($player->z);
        $block1->level = $player->getLevel();
        $block1->level->sendBlocks([$player], [$block1]);
    }
}
