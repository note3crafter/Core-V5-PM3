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

use pocketmine\command\CommandSender;
use pocketmine\entity\Animal;
use pocketmine\entity\Monster;
use pocketmine\entity\object\ItemEntity;
use pocketmine\utils\Config;
use TheNote\core\Main;
use pocketmine\command\Command;

class ClearlaggCommand extends Command
{

    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("clearlagg", $config->get("prefix") . "Löscht alle Items die auf dem Boden Liegen", "/clearlagg", ["cl", "clagg"]);
        $this->setPermission("core.command.clearlagg");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args):bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);

        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        $this->plugin->clearItems = (bool)(true);
        foreach ($this->plugin->getServer()->getLevels() as $level) {
            foreach ($level->getEntities() as $entity) {
                if ($this->plugin->clearItems && $entity instanceof ItemEntity) {
                    $entity->flagForDespawn();
                }
                //ClearLagg will now clear Monsters an Animals too
                if ($this->plugin->clearItems && ($entity instanceof Monster || $entity instanceof Animal)) {
                    $entity->flagForDespawn();
                }
            }
        }
        $sender->sendMessage($config->get("prefix") . "Du hast soeben alle Items die auf dem Boden gelegen haben Gelöscht!");
        return true;
    }
}
