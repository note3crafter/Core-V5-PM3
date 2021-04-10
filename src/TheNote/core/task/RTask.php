<?php

namespace TheNote\core\task;

use pocketmine\scheduler\Task;
use pocketmine\utils\Config;
use TheNote\core\Main;

class RTask extends Task
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
    }

    public function onRun($ticks)
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);

        $w = ["granit", "erde", "stein", "diorit", "drachen", "spielzeit", "spielspass", "minecraft", "downloaden", "nukkit",
            "wortschatz", "wartungen", "pocketmine", "diamant", "copyright", "redstone", "bruchstein", "verlassen", "kinderspiele",
            "abzocken", "gartenarbeit", "motorrad", "note3crafter", "adminshop", "schwein", "kochen", "votefornote",
            "kindergarten", "assozial", "mensch", "computer", "festplatte", "monster", "logitech",
            "javascript", "humor", "whatsapp", "tiktok", "snapchat", "musicaly", "playstore", "lastkraftwagen",
            "tracktor", "hupe", "pupsen", "fairplay", "piratenbucht", "koenigsmine", "hyperdraft", "noteland", "motorola",
            "kacken", "samsung", "kuscheltier", "facebock", "wikipedia", "kennzeichen", "einsendungen", "einzelkabiene",
            "suppremium", "administrator", "moderator", "supporter", "atomkraftwerk", "cavegame", "kreuzfahrt", "bremerhaven",
            "polizei", "flugzeugbau", "container", "spielplatz", "lachanfall", "aufnahme", "videoaufnahme", "bildschirm",
            "dokument", "werbung", "teamspeak", "blockieren", "programmieren", "ladekabel", "akkumulator", "festplatte",
            "arbeiter", "grafikkarte", "computer", "smartphone", "playstation", "nintendo", "mondschein", "sonnenschein",
            "fahrradfahrer", "autofahrer", "linux", "windows", "microsoft", "sekunden", "magdeburg", "hamburg",
            "europapark", "phantasialand", "russland", "coronavirus", "griechenland", "albanien", "deutschland",
            "papagai", "giraffe", "spielzeuge"];
        $key = array_rand($w);
        $word = $w[$key];
        $this->plugin->win = $word;
        $price = mt_rand(50, 300);
        $this->plugin->price = $price;
        $this->plugin->getServer()->broadcastMessage($config->get("prefix") . "§7Entschlüssle das Wort unten und schreibe es in den Chat!\n\n§6-> §e" . str_shuffle($word) . "\n\n" . $config->get("prefix") . "§7Der erste Spieler erhält §a" . $price . "€§7!");
        $this->plugin->getScheduler()->scheduleDelayedTask(new RTask($this->plugin), (20 * 60 * 10));
    }
}