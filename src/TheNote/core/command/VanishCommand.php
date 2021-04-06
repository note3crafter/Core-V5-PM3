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

use pocketmine\entity\Entity;
use pocketmine\utils\Config;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class VanishCommand extends Command
{

    private $vanish = [];

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("vanish", $config->get("prefix") . "§aAktiviert§f/§cDeaktiviert§6 Vanish", "/vanish", ["v"]);
        $this->setPermission("core.command.vanish");
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
        if ($sender->hasPermission("core.command.vanish") || $sender->isOp()) {
            if(!isset($this->vanish[$sender->getName()])){
                $this->vanish[$sender->getName()] = true;
                $sender->setNameTagVisible(false);
                $sender->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INVISIBLE, true);
                $sender->sendMessage($config->get("prefix") . "Dein §eVanish §6wurde §aAktiviert§6.");
            }else{
                unset($this->vanish[$sender->getName()]);
                $sender->setNameTagVisible(true);
                $sender->setDataFlag(Entity::DATA_FLAGS, Entity::DATA_FLAG_INVISIBLE, false);
                $sender->sendMessage($config->get("prefix") . "Dein §eVanish §6wurde §cDeaktiviert§6.");
            }
        }
        return false;
    }
}
