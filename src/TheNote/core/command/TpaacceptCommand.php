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

use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;

class TpaacceptCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("tpaccept", $config->get("prefix") . "Nehme eine Teleportanfrage an", "/tpaccept");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $configs = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($configs->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }
        if (!empty($args[0])) {
            $sender->sendMessage($configs->get("info") . "Nutze : /tpaccept {player}");
        } else {
            $this->tpak($sender->getName());
        }
        return true;
    }
    public function tpak(string $name): void
    {
        $configs = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $player = $this->plugin->getServer()->getPlayer($name);
        if ($this->plugin->getInviteControl($name)) {
            $sender = $this->plugin->getServer()->getPlayer($this->plugin->getInvite($name));
            $sender->teleport($player->asPosition());
            unset($this->plugin->invite[$name]);
            $sender->sendMessage($configs->get("tpa") . "§6Der Spieler§e $name §6hat deine TPA-Anfrage angenommen.");
        } else {
            $player->sendMessage($configs->get("tpa") . "Du hast derzeit keine TPA-Anfrage");
        }
    }
}