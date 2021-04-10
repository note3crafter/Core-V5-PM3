<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\events;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\utils\TextFormat;
use TheNote\core\Main;

class ColorChat implements Listener
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onChat(PlayerChatEvent $ev)
    {
        $player = $ev->getPlayer();
        if ($player->hasPermission("core.event.colorchat") or $player->isOp()) {
            $message = $ev->getMessage();
            $ev->setMessage($this->plugin->getServer()->getLanguage()->translateString($this->translateColors('&', $message)));
        }
    }

    public function translateColors($symbol, $message)
    {
        $message = str_replace($symbol . "0", TextFormat::BLACK, $message);
        $message = str_replace($symbol . "1", TextFormat::DARK_BLUE, $message);
        $message = str_replace($symbol . "2", TextFormat::DARK_GREEN, $message);
        $message = str_replace($symbol . "3", TextFormat::DARK_AQUA, $message);
        $message = str_replace($symbol . "4", TextFormat::DARK_RED, $message);
        $message = str_replace($symbol . "5", TextFormat::DARK_PURPLE, $message);
        $message = str_replace($symbol . "6", TextFormat::GOLD, $message);
        $message = str_replace($symbol . "7", TextFormat::GRAY, $message);
        $message = str_replace($symbol . "8", TextFormat::DARK_GRAY, $message);
        $message = str_replace($symbol . "9", TextFormat::BLUE, $message);
        $message = str_replace($symbol . "a", TextFormat::GREEN, $message);
        $message = str_replace($symbol . "b", TextFormat::AQUA, $message);
        $message = str_replace($symbol . "c", TextFormat::RED, $message);
        $message = str_replace($symbol . "d", TextFormat::LIGHT_PURPLE, $message);
        $message = str_replace($symbol . "e", TextFormat::YELLOW, $message);
        $message = str_replace($symbol . "f", TextFormat::WHITE, $message);
        $message = str_replace($symbol . "k", TextFormat::OBFUSCATED, $message);
        $message = str_replace($symbol . "l", TextFormat::BOLD, $message);
        $message = str_replace($symbol . "m", TextFormat::STRIKETHROUGH, $message);
        $message = str_replace($symbol . "n", TextFormat::UNDERLINE, $message);
        $message = str_replace($symbol . "o", TextFormat::ITALIC, $message);
        $message = str_replace($symbol . "r", TextFormat::RESET, $message);
        return $message;
    }
}