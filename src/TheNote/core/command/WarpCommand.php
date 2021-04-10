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


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\utils\Config;
use TheNote\core\Main;

class WarpCommand extends Command
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("warp", $config->get("prefix") . "§aTeleportiere dich zu einem Warp", "/warp", ["world"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);

        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }
        if (empty($args[0])) {
            $sender->sendMessage($config->get("info") . "§cBenutze : /warp [warpname]");
        }
        if (isset($args[0])) {
            $name = $args[0];
            $warp = new Config($this->plugin->getDataFolder() . Main::$cloud . "warps.json", Config::JSON);
            $x = $warp->getNested($args[0] . ".X");
            $y = $warp->getNested($args[0] . ".Y");
            $z = $warp->getNested($args[0] . ".Z");
            $world = $warp->getNested($args[0] . ".world");
            if ($name === null) {
                $sender->sendMessage($config->get("info") . "§cBenutze : /listwarp um die verfügbaren Warps zu sehen");
                return false;
            } else {
                if ($world == null) {
                    $sender->sendMessage($config->get("error") . "§6Der angegebene Warp §c$args[0] §6existiert nicht.");
                    return false;
                } else {
                    $this->plugin->getServer()->loadLevel($world);
                    $sender->teleport(new Position($x, $y, $z, $this->plugin->getServer()->getLevelByName($world)));
                }
                return false;
            }
        }
        return true;
    }
}