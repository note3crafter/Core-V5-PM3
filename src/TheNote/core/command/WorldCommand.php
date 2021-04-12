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


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\level\generator\Flat;
use pocketmine\level\generator\normal\Normal;
use pocketmine\level\Level;
use pocketmine\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use TheNote\core\Main;
use TheNote\core\server\generators\ender\EnderGenerator;
use TheNote\core\server\generators\nether\NetherGenerator;
use TheNote\core\server\generators\normal\NormalGenerator;
use TheNote\core\server\generators\void\VoidGenerator;

class WorldCommand extends Command
{
    private $plugin;

    public const GENERATOR_NORMAL = 0;
    public const GENERATOR_NORMAL_CUSTOM = 1;
    public const GENERATOR_HELL = 2;
    public const GENERATOR_ENDER = 3;
    public const GENERATOR_FLAT = 4;
    public const GENERATOR_VOID = 5;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("world", $config->get("prefix") . "§aManage die Welten", "/world");
        $this->setPermission("core.command.world");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);

        /*if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }*/
        if (empty($args[0])) {
            $sender->sendMessage($config->get("info") . "§cBenutze : /world (help) für Hilfe");
            return false;
        }
        $levels = [];
        if ($args[0] == "help") {
            $sender->sendMessage($config->get("world") . "§6Hilfe");
            $sender->sendMessage("§e/world teleport (worldname)");
            $sender->sendMessage("§e/world create (name) (generator) (seed)");
            $sender->sendMessage("§e-> normal|nether|ender|void|flat|vanilla");
            $sender->sendMessage("§e/world delete (worldname)");
            $sender->sendMessage("§e/world list");
        }
        if ($args[0] == "teleport" and "tp") {
            if (!isset($args[1])) {
                $sender->sendMessage($config->get("info") . "Nutze : /world teleport [worldname]");
                return false;
            }

            if (!$this->plugin->getServer()->isLevelGenerated($args[1])) {
                $sender->sendMessage($config->get("error") . "§cDie Welt mit dem Namen §f:§e " . $args[1] . " §cexistiert nicht!");
                return false;
            }

            if (!$this->plugin->getServer()->isLevelLoaded($args[1])) {
                $this->plugin->getServer()->loadLevel($args[1]);
            }

            $level = $this->plugin->getServer()->getLevelByName($args[1]);
            $sender->teleport($level->getSafeSpawn());
            $sender->sendMessage($config->get("world") . "§6Du wurdest erfolgreich in die Welt §f: §e" . $level->getName() . " §6teleportiert!");
        }
        if ($args[0] == "delete") {
            if (empty($args[1])) {
                $sender->sendMessage($config->get("info") . "Nutze : /world delete [worldname]");
                return false;
            }
            if (!$this->isLevelLoaded($args[1])) $this->plugin->getServer()->loadLevel($args[1]);

            if (!$this->plugin->getServer()->isLevelGenerated($args[0]) and !file_exists($this->plugin->getServer()->getDataPath() . "worlds/" . $args[1])) {
                if ($this->plugin->getServer()->getDefaultLevel() === $this->plugin->getServer()->getLevelByName($args[1])) {
                    $sender->sendMessage($config->get("error") . "§cDu kannst die Standartwelt nicht Löschen!");
                } else {
                    $sender->sendMessage($config->get("error") . "§cDie Welt mit dem Namen " . $args[1] . " existiert nicht!");
                    return false;
                }
            } else {
                $files = $this->removeLevel($args[1]);
                $sender->sendMessage($config->get("world") . "§6Du hast die Welt§f:§e " . $args[1] . " §6erfolgreich gelöscht!");
                $sender->sendMessage("§6$files Dataien Gelöscht!");
            }
        }

        if ($args[0] == "list") {
            foreach (scandir($this->plugin->getServer()->getDataPath() . "worlds") as $file) {
                if ($this->isLevelGenerated($file)) {
                    $isLoaded = $this->isLevelLoaded($file);
                    $players = 0;

                    if ($isLoaded) {
                        $players = count($this->plugin->getServer()->getLevelByName($file)->getPlayers());
                    }

                    $levels[$file] = [$isLoaded, $players];
                }
            }
            $sender->sendMessage($config->get("world") . "§eGeladene Welten :" . (string)count($levels));

            foreach ($levels as $level => [$loaded, $players]) {
                $loaded = $loaded ? "§aGeladen§7" : "§cUngeladen§7";
                $sender->sendMessage("§7{$level} > {$loaded} §7Spieler§f:§e {$players}");
            }
        }
        if ($args[0] == "create") {
            if (empty($args[1])) {
                $sender->sendMessage($config->get("info") . "§eBenutze : /world make (worldname) (generator) (seed)");
                return false;

            }
            if ($this->isLevelGenerated($args[1])) {
                $sender->sendMessage($config->get("error") . "§cDie Welt mit dem Namen §f:§e" . $args[1] . " §cexistiert bereits!");
                return false;
            }
            $seed = 0;
            if (isset($args[3]) && is_numeric($args[3])) {
                $seed = (int)$args[3];
            }
            $generatorName = "normal";
            $generator = null;

            if (isset($args[2])) {
                $generatorName = $args[2];
            }
            switch (strtolower($generatorName)) {
                case "normal":
                    $generator = WorldCommand::GENERATOR_NORMAL;
                    $generatorName = "Normal";
                    break;
                case "vanilla":
                    $generator = WorldCommand::GENERATOR_NORMAL_CUSTOM;
                    $generatorName = "Custom";
                    break;
                case "flat":
                    $generator = WorldCommand::GENERATOR_FLAT;
                    $generatorName = "Flat";
                    break;
                case "nether":
                    $generator = WorldCommand::GENERATOR_HELL;
                    $generatorName = "Nether";
                    break;
                case "ender":
                    $generator = WorldCommand::GENERATOR_ENDER;
                    $generatorName = "End";
                    break;
                default:
                    $generator = WorldCommand::GENERATOR_NORMAL;
                    $generatorName = "Normal";
                    break;
            }
            $this->generateLevel($args[1], $seed, $generator);
            $sender->sendMessage($config->get("world") . "§6Die Welt wurde erfolgreich erstellt! §eName§f: " . $args[1] . " §eSeed§f: " . $seed . " §eGenerator§f: " . $generatorName);
        }
        return true;

    }

    public static function isLevelLoaded(string $levelName): bool
    {
        return Server::getInstance()->isLevelLoaded($levelName);
    }

    public static function isLevelGenerated(string $levelName): bool
    {
        return Server::getInstance()->isLevelGenerated($levelName) && !in_array($levelName, [".", ".."]);
    }

    public static function getLevel(string $name): ?Level
    {
        return Server::getInstance()->getLevelByName($name);
    }

    public static function loadLevel(string $name): bool
    {
        return self::isLevelLoaded($name) ? false : Server::getInstance()->loadLevel($name);
    }

    public static function unloadLevel(Level $level): bool
    {
        return $level->getServer()->unloadLevel($level);
    }

    public static function generateLevel(string $levelName, int $seed = 0, int $generator = WorldCommand::GENERATOR_NORMAL): bool
    {
        if (self::isLevelGenerated($levelName)) {
            return false;
        }

        $generatorClass = Normal::class;
        switch ($generator) {
            case self::GENERATOR_HELL:
                $generatorClass = NetherGenerator::class;
                break;
            case self::GENERATOR_ENDER:
                $generatorClass = EnderGenerator::class;
                break;
            case self::GENERATOR_NORMAL_CUSTOM:
                $generatorClass = NormalGenerator::class;
                break;
            case self::GENERATOR_VOID:
                $generatorClass = VoidGenerator::class;
                break;
            case self::GENERATOR_FLAT:
                $generatorClass = Flat::class;
                break;
        }
        return Server::getInstance()->generateLevel($levelName, $seed, $generatorClass);
    }

    public static function removeLevel(string $name): int
    {
        if (self::isLevelLoaded($name)) {
            $level = self::getLevel($name);

            if (count($level->getPlayers()) > 0) {
                foreach ($level->getPlayers() as $player) {
                    $player->teleport(Server::getInstance()->getDefaultLevel()->getSpawnLocation());
                }
            }
            $level->getServer()->unloadLevel($level);
        }
        return self::removeDir(Server::getInstance()->getDataPath() . "/worlds/" . $name);
    }


    public static function getAllLevels(): array
    {
        $levels = [];
        foreach (glob(Server::getInstance()->getDataPath() . "/worlds/*") as $world) {
            if (count(scandir($world)) >= 4) { // don't forget to .. & .
                $levels[] = basename($world);
            }
        }
        return $levels;
    }

    private static function removeFile(string $path): int
    {
        unlink($path);
        return 1;
    }

    private static function removeDir(string $dirPath): int
    {
        $files = 1;
        if (basename($dirPath) == "." || basename($dirPath) == ".." || !is_dir($dirPath)) {
            return 0;
        }
        foreach (scandir($dirPath) as $item) {
            if ($item != "." || $item != "..") {
                if (is_dir($dirPath . DIRECTORY_SEPARATOR . $item)) {
                    $files += self::removeDir($dirPath . DIRECTORY_SEPARATOR . $item);
                }
                if (is_file($dirPath . DIRECTORY_SEPARATOR . $item)) {
                    $files += self::removeFile($dirPath . DIRECTORY_SEPARATOR . $item);
                }
            }
        }
        rmdir($dirPath);
        return $files;
    }
}
