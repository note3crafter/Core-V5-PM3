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

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\level\particle\FloatingTextParticle;
use pocketmine\math\Vector3;
use pocketmine\scheduler\Task;
use pocketmine\Player;
use pocketmine\level\Position;

use pocketmine\utils\Config;
use TheNote\core\Main;

class StatstextTask extends Task
{

    private $plugin;
    private $floattext;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onRun($tick)
    {

        $all = $this->plugin->getServer()->getOnlinePlayers();

        foreach ($all as $player) {

            $config = new Config($this->plugin->getDataFolder() . Main::$setup . "Config" . ".yml", Config::YAML);
            $level = $this->plugin->getServer()->getLevelByName($config->get("level"));
            $text = $this->getText($player);
            $x = $config->get("X");
            $y = $config->get("Y");
            $z = $config->get("Z");

            if ($this->plugin->anni === 1) {
                $this->plugin->anni = 2;
            } elseif ($this->plugin->anni === 2) {
                $this->plugin->anni = 1;
            }
            if ($config->get("statstext") == true) {
                if (!isset($this->floattext[$player->getName()])) {
                    # existiert noch nicht
                    $this->floattext[$player->getName()] = new FloatingTextParticle(new Vector3($x, $y, $z), $text);
                    $particle = $this->floattext[$player->getName()];
                    #$packet = $particle->encode()
                    $particle->setInvisible(false);
                    $level->addParticle($particle, [$player]);
                } else {
                    # is schon da
                    $particle = $this->floattext[$player->getName()];
                    $particle->setInvisible(true);
                    $level->addParticle($particle, [$player]);
                    $this->floattext[$player->getName()] = new FloatingTextParticle(new Vector3($x, $y, $z), $text);
                    $newparticle = $this->floattext[$player->getName()];
                    $newparticle->setInvisible(false);
                    $level->addParticle($newparticle, [$player]);
                }
            }
        }
    }

    public function getText(Player $player)
    {
        $stats = new Config($this->plugin->getDataFolder() . Main::$statsfile . $player->getLowerCaseName() . ".json", Config::JSON);
        if ($this->plugin->anni === 1) {
            $text = "§6====§f[§eStatistiken§f]§6====\n" .
                "§eDeine Joins §f: §e" . $stats->get("joins") . "\n" .
                "§eDeine Sprünge §f: §e" . $stats->get("jumps") . "\n" .
                "§eDeine Kicks §f: §e" . $stats->get("kicks") . "\n" .
                "§eDeine Interacts §f: §e" . $stats->get("interact") . "\n" .
                "§eGelaufene Meter §f: §e" . $stats->get("movewalk") . "m\n" .
                "§eGeflogene Meter §f: §e" . $stats->get("movefly") . "m\n" .
                "§eBlöcke abgebaut §f: §e" . $stats->get("break") . "\n" .
                "§eBlöcke gesetzt §f: §e" . $stats->get("place") . "\n" .
                "§eGedroppte Items §f: §e" . $stats->get("drop") . "\n" .
                "§eGesammelte Items §f: §e" . $stats->get("pick") . "\n" .
                "§eKonsumierte Items §f: §e" . $stats->get("consume") . "\n" .
                "§eDeine Nachrrichten §f: §e" . $stats->get("messages") . "\n".
                "§eDeine Votes §f: §e" . $stats->get("votes");
        } else {
            $text = "§6====§f[§eStatistiken§f]§6====\n" .
                "§eDeine Joins §f: §e" . $stats->get("joins") . "\n" .
                "§eDeine Sprünge §f: §e" . $stats->get("jumps") . "\n" .
                "§eDeine Kicks §f: §e" . $stats->get("kicks") . "\n" .
                "§eDeine Interacts §f: §e" . $stats->get("interact") . "\n" .
                "§eGelaufene Meter §f: §e" . $stats->get("movewalk") . "m\n" .
                "§eGeflogene Meter §f: §e" . $stats->get("movefly") . "m\n" .
                "§eBlöcke abgebaut §f: §e" . $stats->get("break") . "\n" .
                "§eBlöcke gesetzt §f: §e" . $stats->get("place") . "\n" .
                "§eGedroppte Items §f: §e" . $stats->get("drop") . "\n" .
                "§eGesammelte Items §f: §e" . $stats->get("pick") . "\n" .
                "§eKonsumierte Items §f: §e" . $stats->get("consume") . "\n" .
                "§eDeine Nachrrichten §f: §e" . $stats->get("messages") . "\n".
                "§eDeine Votes §f: §e" . $stats->get("votes");
        }
        return $text;
    }
}