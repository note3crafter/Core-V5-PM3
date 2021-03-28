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

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\Config;

class AFKCommand extends Command implements Listener
{
    private $plugin;
    private $playerSession = [];
    private $doPublicMessage = true;
    private $publicMessage = "Publicmessage";
    private $doAfkNametag = true;
    private $nametagFormat = "AFK";
    private $doRemoveOnMove = true;
    private $doRemoveOnChat = true;
    private $doRemoveOnAttack = true;


    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("afk", $config->get("prefix") . "Setze dich afk", "/afk");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }

            if (isset($args[0])) {
                switch (strtolower($args[0])) {
                    case "on":
                        if ($this->isInSession($sender)) {
                            $sender->sendMessage("You are already AFK");
                        } else {
                            $this->addToSession($sender);
                            $sender->sendMessage("You are now AFK");
                            if ($this->doAfkNametag()) {
                                $sender->setNameTag(TextFormat::colorize($this->getAfkNametagFormat()) . $sender->getName());
                            }
                            if ($this->doPublicMessage()) {
                                foreach ($this->plugin->getServer()->getOnlinePlayers() as $sender) {
                                    $sender->sendMessage("der spieler $sender ist afk");
                                }
                            }
                        }
                        break;
                    case "off":
                        if (!$this->isInSession($sender)) {
                            $sender->sendMessage("You are already not AFK");
                        } else {
                            $this->removeFromSession($sender);
                            $sender->sendMessage("You are no longer AFK.");
                            if ($this->doAfkNametag()) {
                                $sender->setNameTag(str_replace($this->getAfkNametagFormat(), "", $sender->getName()));
                            }
                        }
                        break;
                    default:
                        $sender->sendMessage("afk usage");
                }


        } else {
            $sender->sendMessage("afk usage");
        }
        return false;
    }
    public function onChat(PlayerChatEvent $event): void{
        if($this->isInSession($event->getPlayer())){
            if($this->doRemoveOnChat()){
                $this->removeFromSession($event->getPlayer());
                $event->getPlayer()->sendMessage("Kein AFK Chatevent");
            }
        }
    }

    public function onMove(PlayerMoveEvent $event): void{
        if($this->isInSession($event->getPlayer())){
            if($this->doRemoveOnMove()){
                $this->removeFromSession($event->getPlayer());
                $event->getPlayer()->sendMessage("Kein AFk Moveevent");
            }
        }
    }

    public function onAttack(EntityDamageByEntityEvent $event): void{
        if($event->getDamager() instanceof Player) {
            if ($this->isInSession($event->getDamager())) {
                if ($this->doRemoveOnAttack()) {
                    $this->removeFromSession($event->getDamager());
                    $event->getDamager()->sendMessage("Kein AFK Entity Attack");
                }
            }
        }
    }
    public function isInSession($sender): bool{
        if($sender instanceof Player){
            $sender = $sender->getName();
        }
        $sender = strtolower($sender);
        return in_array($sender, $this->playerSession);
    }

    public function removeFromSession($sender): void
    {
        if ($sender->isInSession($sender)) {
            if ($sender instanceof Player) {
                $sender = $sender->getName();
            }
            $sender = strtolower($sender);
            unset($this->playerSession[array_search($sender, $this->playerSession)]);
        }
    }
    public function addToSession($sender): void{
        if(!$this->isInSession($sender)) {
            if ($sender instanceof Player) {
                $sender = $sender->getName();
            }
            $sender = strtolower($sender);
            $this->playerSession[] = $sender;
        }
    }

    public function doAfkNametag(): bool
    {
        return $this->doAfkNametag;
    }
    public function getAfkNametagFormat(): string
    {
        return $this->nametagFormat;
    }
    public function doRemoveOnMove(): bool
    {
        return $this->doRemoveOnMove;
    }

    public function doRemoveOnChat(): bool
    {
        return $this->doRemoveOnChat;
    }

    public function doRemoveOnAttack(): bool
    {
        return $this->doRemoveOnAttack;
    }
    public function getPublicMessage(): string
    {
        return $this->publicMessage;

    }
    public function doPublicMessage(): bool
    {
        return $this->doPublicMessage;

    }

}