<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

declare(strict_types=1);

namespace TheNote\core\command;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\Config;
use pocketmine\utils\TextFormat as TF;
use TheNote\core\Main;

class SignCommand extends Command {

    private $plugin;

	public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
		parent::__construct("sign", $config->get("prefix") . "Signiere ein Item", "/sign <text>");
		$this->setPermission("core.command.sign");
	}
	
	public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }
        if (!$this->testPermission($sender)) {
            $sender->sendMessage($config->get("error") . "Du hast keine Berechtigung um diesen Command auszuführen!");
            return false;
        }
		if(empty($args)) {
			$sender->sendMessage($config->get("info") . "Nutze: /sign {text}");
			return false;
		}
		$item = $sender->getInventory()->getItemInHand();
        $date = date("d.m.Y");
        $time = date("H:i:s");
        $name = $sender->getName();
        $fullargs = implode(" ", $args);
        $item->clearCustomName();
        $item->setLore([$this->convert("{date} um {time}", $date, $time, $name)."\n".$this->convert("Signiert von {name}", $date, $time, $name)]);
		$item->setCustomName(str_replace("&", TF::ESCAPE, $fullargs));
        $sender->getInventory()->setItemInHand($item);
        $sender->sendMessage($config->get("prefix") . "Du hast dein Item erfolgreich Signiert");
        return true;
    }

    public function convert(string $string, $date, $time, $name): string{
        $string = str_replace("{date}", $date, $string);
        $string = str_replace("{time}", $time, $string);
        $string = str_replace("{name}", $name, $string);
        return $string;
	}
}