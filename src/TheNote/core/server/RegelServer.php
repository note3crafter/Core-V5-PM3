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
use pocketmine\network\mcpe\protocol\LoginPacket;
use pocketmine\Player;
use pocketmine\utils\Config;
use TheNote\core\formapi\SimpleForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use TheNote\core\Main;

class RegelServer extends Command implements Listener
{


    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("regeln", $config->get("prefix") . "Zeigt die Regeln", "/regeln", ["rules"]);

    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $configs = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($configs->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }
        $player = $sender->getPlayer();
        $name = $player->getName();
        $ip = $player->getAddress();
        $uuid = $player->getUniqueId();
        $cid = $player->getPlayer()->getClientId();
        $loaderID = $player->getPlayer()->getLoaderId();
        $xuid = $player->getPlayer()->getXuid();
        $deviceOS = $player . $this->plugin->getDeviceOS();
        $deviceModel = $player . $this->plugin->getDeviceModel();
        $deviceID = $player . $this->plugin->getDeviceId();
        $rules = Main::$dateversion;

        $form = new SimpleForm(function (Player $sender, $data) {
            $cfg = new Config($this->plugin->getDataFolder() . Main::$userfile . $sender->getLowerCaseName() . ".json", Config::JSON);
            $configs = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);

            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    $cfg->set("rulesaccpet", true);
                    $cfg->save();
                    $sender->sendMessage($configs->get("info") . "Wir wünschen dir viel Spaß auf dem Server");
                    break;
            }
        });
        $form->setTitle($configs->get("uiname"));
        $form->setContent("§6======§f[§eRegelwerk§f]§6======\n" .
            "§c1§f.\n" .
            "Hacking sowie das Ausnutzen von Bugs ist Verboten!\n" .
            "§c2§f.\nWerbung ist auf dem Netzwerk strengstens untersagt!\n" .
            "§c3§f.\nAls Teammitglied ist es nicht erlaubt items aus dem Gamemode zu verteilen dies führt zum sofortigen ausschluss des Teams\n" .
            "§c4§f.\nBeleidigungen sowie Spamming ist Verboten.\n" .
            "§c5§f.\nEchtgeldgeschäfte werden zur Anzeige gebracht!!!\n" .
            "§c6§f.\nUm ins Team zu gelangen muss man Mindestens 2 Wochen auf " . $configs->get("servername"). " sein sowie Discord besitzen!\n" .
            "§c7§f.\nDas erbetteln von Rängen ist unnerwünscht!\n" .
            "§c8§f.\nItems die verloren gegangen sind werden NICHT wiedererstattet, es sei man hat ein Beweis!\n" .
            "§c9§f.\nDas ausnutzen von Bugs führt zu einem Permamenten Ban des Servers sowie Discord.\n" .
            "§c10§f.\nUnwissenheit schützt vor Strafe nicht!\n" .
            "§c11§f.\nDas Team behält sich vor die Regeln jederzeit zu ändern!\n" .
            "§c12§f.\nJeder Ban ist Permament!\n" .
            "§c13§f.\nPlugins erfragen ist nicht Gestattet! Denn es gibt nur 1\n" .
            "\n" .
            "§6------§f[§eDSGVO§f]§6------\n" .
            "§eWir müssen euch drauf hinweisen, dass wir bestimmte daten von euch Abspeichern und ggf nutzen wie z.b. IP-Ban\n\n" .
            "§eDein Spielername §f:§a $name\n" .
            "§eDeine IP §f:§a $ip\n" .
            "§eDeine UUID §f:§a $uuid\n" .
            "§eDeine Xbox-ID §f:§a $xuid\n" .
            "§eStand §f:§c $rules");
        $form->addButton("§4Ich habe die Regeln Gelesen\n§cMuss bestätigt werden!", 0);
        $form->sendToPlayer($sender);
        return true;
    }
}
