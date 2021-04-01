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


use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\Server;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;

class FeedCommand extends Command
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("feed", $config->get("prefix") . "Stillt dein Hunger", "/feed");
        $this->setPermission("core.command.feed");
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
        if (isset($args[0])) {
            if ($sender->hasPermission("core.command.feed.other")) {
                $victim = $this->plugin->getServer()->getPlayer($args[0]);
                $target = Server::getInstance()->getPlayer(strtolower($args[0]));
                if ($target == null) {
                    $sender->sendMessage($config->get("error") . "Der Spieler ist nicht Online!");
                    return false;
                } else {
                    $sender->setAllowFlight(true);
                    $sender->setFood(20);
                    $volume = mt_rand();
                    $sender->getLevel()->broadcastLevelSoundEvent($sender, LevelSoundEventPacket::SOUND_EAT, (int)$volume);
                    $sender->sendMessage($config->get("prefix") . "§6Dein §eHunger §6wurde gestillt von " . $sender->getNameTag());
                    $sender->sendMessage($config->get("prefix") . "§6Du hast den §eHunger von " . $victim->getName() . " gestillt.");
                    return false;
                }
            } else {
                $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um andere Spieler zu Füttern!");
                return false;
            }
        }
        $sender->setAllowFlight(true);
        $sender->setFood(20);
        $volume = mt_rand();
        $sender->getLevel()->broadcastLevelSoundEvent($sender, LevelSoundEventPacket::SOUND_EAT, (int)$volume);
        $sender->sendMessage($config->get("prefix") . "§6Dein §eHunger §6wurde gestillt.");
        return false;
    }
}
