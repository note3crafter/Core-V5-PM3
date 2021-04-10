<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\task;

use pocketmine\scheduler\Task;
use pocketmine\utils\Config;
use TheNote\core\Main;

class PingTask extends Task
{

    public function __construct(Main $main)
    {
        $this->plugin = $main;
    }
    public function onRun(int $currentTick)
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "Config" . ".yml", Config::YAML);
        $settings = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        foreach ($this->plugin->getServer()->getOnlinePlayers() as $player) {
            if ($player->getPing() >= $config->get("PingLimit")) {
                $player->kick($settings->get("prefix") . "Du wurdest wegen einem zu Hohen Ping gekickt! Das Limit beträgt" . $config->get("PingLimit" ));
            }
        }
    }
}