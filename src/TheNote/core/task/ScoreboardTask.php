<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\Task;

use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;
use TheNote\core\Main;
use onebone\economyapi\EconomyAPI;

class ScoreboardTask extends Task
{

    private $plugin;
    private $player;

    function __construct(Main $plugin, Player $player)
    {
        $this->plugin = $plugin;
        $this->player = $player;
    }

    function numberPacket(Player $player, $score = 1, $msg = ""): void
    {
        $entrie = new ScorePacketEntry();
        $entrie->objectiveName = "test";
        $entrie->type = 3;
        $entrie->customName = str_repeat("", 5) . $msg . str_repeat(" ", 1);
        $entrie->score = $score;
        $entrie->scoreboardId = $score;
        $pk = new SetScorePacket();
        $pk->type = 1;
        $pk->entries[] = $entrie;
        $player->sendDataPacket($pk);
        $pk2 = new SetScorePacket();
        $pk2->entries[] = $entrie;
        $pk2->type = 0;
        $player->sendDataPacket($pk2);
    }

    function onRun($currentTick)
    {
        $user = new Config($this->plugin->getDataFolder() . Main::$userfile . $this->player->getLowerCaseName() . ".json", Config::JSON);
        $gruppe = new Config($this->plugin->getDataFolder() . Main::$gruppefile . $this->player->getName() . ".json", Config::JSON);
        $online = new Config($this->plugin->getDataFolder() . Main::$cloud . "Count.json", Config::JSON);
        $stats = new Config($this->plugin->getDataFolder() . Main::$statsfile . $this->player->getLowerCaseName() . ".json", Config::JSON);
        $hei = new Config($this->plugin->getDataFolder() . Main::$heifile . $this->player->getLowerCaseName() . ".json", Config::JSON);
        $settings = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $playerdata = new Config($this->plugin->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        $money = new Config($this->plugin->getDataFolder() . Main::$cloud . "Money.yml", Config::YAML);

        $mymoney = $money->getNested("money." .$this->player->getName());
        $votes = $stats->get("votes");
        $joins = $stats->get("joins");
        $break = $stats->get("break");
        $this->player->setScoreTag("§eVotes §f: §6$votes\n §eJoins §f: §6$joins\n§eAbgebaut §f: §6$break");
        $this->plugin->getScheduler()->scheduleDelayedTask(new ScoreboardTask($this->plugin, $this->player->getPlayer()), 20);

        $pk = new SetDisplayObjectivePacket();
        $pk->displaySlot = "sidebar";
        $pk->objectiveName = "test";
        $pk->displayName = $settings->get("ueberschrift");
        $pk->criteriaName = "dummy";
        $pk->sortOrder = 0;
        $this->player->sendDataPacket($pk);
        $this->numberPacket($this->player, 1, "§eDein Rang");
        $this->numberPacket($this->player, 2, "§f➥ " .   $playerdata->getNested($this->player->getName() . ".groupprefix"));
        $this->numberPacket($this->player, 3, "§eDein Geldstand");
        if ($this->plugin->economyapi === null) {
            $this->numberPacket($this->player, 4, "§f➥ §e" . $mymoney . "§e$");
        } else {
            $this->numberPacket($this->player, 4, "§f➥ §e" . $this->plugin->economyAPI->myMoney($this->player) . "§e$");
        }
        $this->numberPacket($this->player, 5, "§eDeine Coins");
        $this->numberPacket($this->player, 6, "§f➥ §e" . $user->get("coins"));
        $this->numberPacket($this->player, 7, "§aDein Partner§f/§ain");
        if ($user->get("heistatus") === false) {
            $this->numberPacket($this->player, 8, "§f➥ §aKein Partner");
        } else {
            $this->numberPacket($this->player, 8, "§f➥ §a" . $hei->get("heiraten"));
        }
        $this->numberPacket($this->player, 9, "§dDein Clan");
        if ($gruppe->get("ClanStatus") === false) {
            $this->numberPacket($this->player, 10, "§f➥ §dKein Clan");
        } else {
            $this->numberPacket($this->player, 10, "§f➥ §d" . $gruppe->get("Clan"));
        }
        $this->numberPacket($this->player, 11, "§eOnline");
        $this->numberPacket($this->player, 12, "§f➥ §e" . $online->get("players") . "§f/§e" . $settings->get("slots") . "§f");
    }
}