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
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\tile\Sign;
use TheNote\core\Main;

class PlotBewertung implements Listener
{
    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onInteract(PlayerInteractEvent $event)
    {

        $player = $event->getPlayer();
        if ($player->isOp()) {
            $block = $event->getBlock();
            $tile = $player->getLevel()->getTile($block);
            if ($tile instanceof Sign) {
                $signtext = $tile->getText();
                if ($signtext[0] === "1") {
                    $tile->setText("§eBewertet mit", "§41§f/§a5 Sternen", "§fvon", "§c" . $player->getName());
                }
                if ($signtext[0] === "2") {
                    $tile->setText("§eBewertet mit", "§c2§f/§a5 Sternen", "§fvon", "§c" . $player->getName());
                }
                if ($signtext[0] === "3") {
                    $tile->setText("§eBewertet mit", "§63§f/§a5 Sternen", "§fvon", "§c" . $player->getName());
                }
                if ($signtext[0] === "4") {
                    $tile->setText("§eBewertet mit", "§e4§f/§a5 Sternen", "§fvon", "§c" . $player->getName());
                }
                if ($signtext[0] === "5") {
                    $tile->setText("§eBewertet mit", "§a5§f/§a5 Sternen", "§fvon", "§c" . $player->getName());
                }
            }
        }
    }
}