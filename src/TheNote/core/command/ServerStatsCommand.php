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

    use pocketmine\Player;
    use pocketmine\utils\Config;
    use TheNote\core\Main;
    use TheNote\core\formapi\SimpleForm;
    use pocketmine\command\Command;
    use pocketmine\command\CommandSender;

class ServerStatsCommand extends Command
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("serverstats", $config->get("prefix") . "§6Schaue die Serverstatistiken an", "/serverstats", ["sstats"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }
        $stats = new Config($this->plugin->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
        $form = new SimpleForm(function (Player $sender, $data) {
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    break;
            }
        });
        $form->setTitle($config->get("uiname"));
        $form->setContent("§6======§f[§eStatistiken§f]§6======\n" .
            "§6Gesammte Joins : \n" .
            "§e" . $stats->get("joins") . "\n" .
            "§6Gesammte Jumps : \n" .
            "§e" . $stats->get("jumps") . "\n" .
            "§6Gesammte Kicks : \n" .
            "§e" . $stats->get("kicks") . "\n" .
            "§6Gesammte Deaths : \n" .
            "§e" . $stats->get("deaths") . "\n" .
            "§6Gesammte Blöcke abgebaut : \n" .
            "§e" . $stats->get("break") . "\n" .
            "§6Blöcke insgesammt gesetzt : \n" .
            "§e" . $stats->get("place") . "\n" .
            "§6Gesammt gelaufene Meter : \n" .
            "§e" . $stats->get("movewalk") . "m\n" .
            "§6Gesammt geflogene Meter : " .
            "§e" . $stats->get("movefly") . "m\n" .
            "§6Gedroppte Items : \n" .
            "§e" . $stats->get("drop") . "\n" .
            "§6Gesammelte Items : \n" .
            "§e" . $stats->get("pick") . "\n" .
            "§6Consumierte Items : \n" .
            "§e" . $stats->get("consume") . "\n" .
            "§6Insgesammt gesendete Nachrrichten : \n" .
            "§e" . $stats->get("messages") . "\n" .
            "§6Insgesammte Neustarts : \n" .
            "§e" . $stats->get("restarts") . "\n" .
            "§6Regestrierte Spieler : \n" .
            "§e" . $stats->get("Users") . "\n" .
            "§6Insgesammte Votes : \n" .
            "§e" . $stats->get("votes"));

        $form->addButton("§0OK", 0);
        $form->sendToPlayer($sender);
        return true;
    }
}