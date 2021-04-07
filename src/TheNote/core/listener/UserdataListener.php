<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\listener;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\utils\Config;
use TheNote\core\Main;

class UserdataListener implements Listener {

    private $plugin;

    public function __construct(Main $plugin){
        $this->plugin = $plugin;
    }

    public function onJoin(PlayerJoinEvent $event){
        $player = $event->getPlayer();
        $name = $player->getLowerCaseName();
        $os = ["unbekannt", "Android", "iOS", "macOS", "FireOS", "GearVR", "HoloLens", "Windows 10", "Windows", "Dedicated", "Orbis", "NX", "playstation_4"];
        $UI = ["Classic UI", "Pocket UI"];
        $Controls = ["unbekannt", "Mouse", "Touch", "Controller"];
        $GUI = [-2 => "Minimum", -1 => "Medium", 0 => "Maximum"];

        /*$this->getScheduler()->scheduleTask(new SaveTask(
            $this,
            $player->getName(),
            $this->getModel($cdata["DeviceModel"]),
            $os[$cdata["DeviceOS"]],
            $player->getAddress(),
            $UI[$cdata["UIProfile"]],
            $GUI[$cdata["GuiScale"]],
            $Controls[$cdata["CurrentInputMode"]]
        ));*/
        if (!file_exists($this->plugin->getDataFolder() . Main::$logdatafile . strtolower($name) ."$.json")){
            $pf= new Config($this->plugin->getDataFolder() . Main::$logdatafile . strtolower($name) .".json");
            $config = new Config($this->plugin->getDataFolder() . Main::$setup . "Config" . ".yml", Config::YAML);

            $pf->set("Name", $player->getName());
            $pf->set("IP", $player->getAddress());
            $pf->set("Xbox-ID", $player->getPlayer()->getXuid());
            //$pf->set("OS", $player->getPlayer()->getDeviceOS($os));
            $pf->set("Last_Join", date('d.m.Y H:I') . date_default_timezone_set("Europe/Berlin"));
            $pf->save();
        }
    }
}