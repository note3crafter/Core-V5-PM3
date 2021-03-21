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

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\sound\AnvilUseSound;
use pocketmine\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\level\sound\PopSound;
use pocketmine\utils\Config;
use TheNote\core\Main;

class RestartServer extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("restart", $config->get("prefix") . "§6Restartet den Server", "/restart");
        $this->setPermission("core.command.restart");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
        if (Main::$restart){
            $sender->sendMessage($config->get("info"). "§r§cDer Server restartet bereits!");
            return false;
        }
        $serverstats = new Config($this->plugin->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
        $serverstats->set("restarts", $serverstats->get("restarts") + 1);
        $serverstats->save();
        Main::getMain()->getScheduler()->scheduleRepeatingTask(new class extends Task
        {
            private $timer = 65;
            private $i = 0;

            public function onRun(int $currentTick)
            {
                $this->timer--;
                if ($this->timer >= 3) {
                    foreach (Main::getMain()->getServer()->getOnlinePlayers() as $player) {
                        switch ($this->i) {
                            case 0:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 60 Sekunden");
                                break;
                            case 1:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 59 Sekunden");
                                break;
                            case 2:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 58 Sekunden");
                                break;
                            case 3:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 57 Sekunden");
                                break;
                            case 4:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 56 Sekunden");
                                break;
                            case 5:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 55 Sekunden");
                                break;
                            case 6:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 54 Sekunden");
                                break;
                            case 7:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 53 Sekunden");
                                break;
                            case 8:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 52 Sekunden");
                                break;
                            case 9:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 51 Sekunden");
                                break;
                            case 10:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 50 Sekunden");
                                break;
                            case 11:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 49 Sekunden");
                                break;
                            case 12:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 48 Sekunden");
                                break;
                            case 13:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 47 Sekunden");
                                break;
                            case 14:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 46 Sekunden");
                                break;
                            case 15:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 45 Sekunden");
                                break;
                            case 16:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 44 Sekunden");
                                break;
                            case 17:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 43 Sekunden");
                                break;
                            case 18:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 42 Sekunden");
                                break;
                            case 19:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 41 Sekunden");
                                break;
                            case 20:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 40 Sekunden");
                                break;
                            case 21:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 39 Sekunden");
                                break;
                            case 22:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 38 Sekunden");
                                break;
                            case 23:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 37 Sekunden");
                                break;
                            case 24:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 36 Sekunden");
                                break;
                            case 25:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 35 Sekunden");
                                break;
                            case 26:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 34 Sekunden");
                                break;
                            case 27:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 33 Sekunden");
                                break;
                            case 28:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 32 Sekunden");
                                break;
                            case 29:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 31 Sekunden");
                                break;
                            case 30:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 30 Sekunden");
                                $player->getLevel()->addSound(new PopSound($player));
                                break;
                            case 31:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 29 Sekunden");
                                $player->getLevel()->addSound(new PopSound($player));
                                break;
                            case 32:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 28 Sekunden");
                                $player->getLevel()->addSound(new PopSound($player));
                                break;
                            case 33:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 27 Sekunden");
                                $player->getLevel()->addSound(new PopSound($player));
                                break;
                            case 34:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 26 Sekunden");
                                $player->getLevel()->addSound(new PopSound($player));
                                break;
                            case 35:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 25 Sekunden");
                                $player->getLevel()->addSound(new PopSound($player));
                                break;
                            case 36:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 24 Sekunden");
                                $player->getLevel()->addSound(new PopSound($player));
                                break;
                            case 37:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 23 Sekunden");
                                $player->getLevel()->addSound(new PopSound($player));
                                break;
                            case 38:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 22 Sekunden");
                                $player->getLevel()->addSound(new PopSound($player));
                                break;
                            case 39:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 21 Sekunden");
                                $player->getLevel()->addSound(new PopSound($player));
                                break;
                            case 40:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 20 Sekunden");
                                $player->getLevel()->addSound(new PopSound($player));
                                break;
                            case 41:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 19 Sekunden");
                                $player->getLevel()->addSound(new PopSound($player));
                                break;
                            case 42:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 18 Sekunden");
                                $player->getLevel()->addSound(new PopSound($player));
                                break;
                            case 43:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 17 Sekunden");
                                $player->getLevel()->addSound(new PopSound($player));
                                break;
                            case 44:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 16 Sekunden");
                                $player->getLevel()->addSound(new PopSound($player));
                                break;
                            case 45:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 15 Sekunden");
                                $player->getLevel()->addSound(new PopSound($player));
                                break;
                            case 46:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 14 Sekunden");
                                $player->getLevel()->addSound(new PopSound($player));
                                break;
                            case 47:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 13 Sekunden");
                                $player->getLevel()->addSound(new PopSound($player));
                                break;
                            case 48:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 12 Sekunden");
                                $player->getLevel()->addSound(new PopSound($player));
                                break;
                            case 49:
                                $player->sendPopup("§f§k|§r§cServer Restartet§f§k|§r §cin 11 Sekunden");
                                $player->getLevel()->addSound(new PopSound($player));
                                break;
                            case 50:
                                $player->addTitle("§f§k|§r§cServer Restartet§f§k|§r");
                                $player->addSubTitle("§710");
                                $player->getLevel()->addSound(new PopSound($player));
                                break;
                            case 51:
                                $player->addTitle("§f§k|§r§cServer Restartet§f§k|§r");
                                $player->addSubTitle("§79");
                                $player->getLevel()->addSound(new PopSound($player));
                                break;
                            case 52:
                                $player->addTitle("§f§k|§r§cServer Restartet§f§k|§r");
                                $player->addSubTitle("§78");
                                $player->getLevel()->addSound(new PopSound($player));
                                break;
                            case 53:
                                $player->addTitle("§f§k|§r§cServer Restartet§f§k|§r");
                                $player->addSubTitle("§77");
                                $player->getLevel()->addSound(new PopSound($player));
                                break;
                            case 54:
                                $player->addTitle("§f§k|§r§cServer Restartet§f§k|§r");
                                $player->addSubTitle("§76");
                                $player->getLevel()->addSound(new PopSound($player));
                                break;
                            case 55:
                                $player->addTitle("§f§k|§r§cServer Restartet§f§k|§r");
                                $player->addSubTitle("§75");
                                $player->getLevel()->addSound(new PopSound($player));
                                break;
                            case 56:
                                $player->addTitle("§f§k|§r§cServer Restartet§f§k|§r");
                                $player->addSubTitle("§74");
                                $player->getLevel()->addSound(new PopSound($player));
                                break;
                            case 57:
                                $player->addTitle("§f§k|§r§cServer Restartet§f§k|§r");
                                $player->addSubTitle("§73");
                                $player->getLevel()->addSound(new PopSound($player));
                                break;
                            case 58:
                                $player->addTitle("§f§k|§r§cServer Restartet§f§k|§r");
                                $player->addSubTitle("§72");
                                $player->getLevel()->addSound(new PopSound($player));
                                break;
                            case 59:
                                $player->addTitle("§f§k|§r§cServer Restartet§f§k|§r");
                                $player->addSubTitle("§71");
                                $player->getLevel()->addSound(new PopSound($player));
                                break;
                            case 60:
                                $player->addTitle("§f§k|§r§cServer Restartet§f§k|§r");
                                $player->addSubTitle("§7Restart");
                                $player->getLevel()->addSound(new AnvilUseSound($player));
                                break;
                            case 61:
                                $player->addTitle("§f§k|§r§cServer Restartet§f§k|§r");
                                $player->addSubTitle("§7Restart");
                                //$player->getLevel()->addSound(new AnvilUseSound($player));
                                break;
                            case 62:
                                $player->addTitle("§f§k|§r§cServer Restartet§f§k|§r");
                                $player->addSubTitle("§7Restart");
                                //$player->getLevel()->addSound(new AnvilUseSound($player));
                                break;
                            case 63:
                                $player->addTitle("§f§k|§r§cServer Restartet§f§k|§r");
                                $player->addSubTitle("§7Restart");
                                break;
                            case 64:
                                $player->addTitle("§f§k|§r§cServer Restartet§f§k|§r");
                                $player->addSubTitle("§7Restart");
                                break;
                            case 65:
                                $player->addTitle("§f§k|§r§cServer Restartet§f§k|§r");
                                $player->addSubTitle("§7Restart");
                                break;
                        }
                        if ($this->i === 65) {
                            $this->i = 0;
                        }
                    }
                    $this->i++;
                }

                if ($this->timer === 0) {
                    Main::getMain()->getServer()->shutdown();
                }
            }
        }, 20);
        return true;
    }
}
