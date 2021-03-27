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

use pocketmine\block\Sapling;
use pocketmine\event\Cancellable;
use pocketmine\event\player\PlayerEvent;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\object\BirchTree;
use pocketmine\level\generator\object\JungleTree;
use pocketmine\level\generator\object\OakTree;
use pocketmine\level\generator\object\SpruceTree;
use pocketmine\level\Position;
use pocketmine\utils\Config;
use pocketmine\utils\Random;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class TreeCommand extends Command
{

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("tree", $config->get("prefix") . "Lasse ein Baum Spawnen", "/tree", ["baum"]);
        $this->setPermission("core.command.tree");
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
        if (empty($args[0])) {
            $sender->sendMessage($config->get("info") . "Nutze : /tree <oak|spruce|birch|jungle>");
            return true;
        }

        if (isset($args[0])) {

            if ($args[0] == "oak") {
                $type = Sapling::OAK;

                $ev = new BaumEvent($sender, $sender->getTargetBlock(100));
                $ev->call();
                if ($ev->isCancelled()) {
                    return true;
                }
                $this->plugin->Baum($ev->getPosition()->getLevel(), $ev->getPosition()->getFloorX(), $ev->getPosition()->getFloorY() + 1, $ev->getPosition()->getFloorZ(), new Random(), $type);
                $sender->sendMessage($config->get("info") . "Der Eichenbaum wurde gesetzt!");
            }
            if ($args[0] == "spruce") {
                $type = Sapling::SPRUCE;
                $ev = new BaumEvent($sender, $sender->getTargetBlock(100));
                $ev->call();
                if ($ev->isCancelled()) {
                    return true;
                }
                $this->plugin->Baum($ev->getPosition()->getLevel(), $ev->getPosition()->getFloorX(), $ev->getPosition()->getFloorY() + 1, $ev->getPosition()->getFloorZ(), new Random(), $type);
                $sender->sendMessage($config->get("info") . "Der Tannenbaum wurde gesetzt!");
            }
            if ($args[0] == "birch") {
                $type = Sapling::BIRCH;
                $ev = new BaumEvent($sender, $sender->getTargetBlock(100));
                $ev->call();
                if ($ev->isCancelled()) {
                    return true;
                }
                $this->plugin->Baum($ev->getPosition()->getLevel(), $ev->getPosition()->getFloorX(), $ev->getPosition()->getFloorY() + 1, $ev->getPosition()->getFloorZ(), new Random(), $type);
                $sender->sendMessage($config->get("info") . "Der Birkenbaum wurde gesetzt!");
            }
            if ($args[0] == "jungle") {
                $type = Sapling::JUNGLE;
                $ev = new BaumEvent($sender, $sender->getTargetBlock(100));
                $ev->call();
                if ($ev->isCancelled()) {
                    return true;
                }
                $this->plugin->Baum($ev->getPosition()->getLevel(), $ev->getPosition()->getFloorX(), $ev->getPosition()->getFloorY() + 1, $ev->getPosition()->getFloorZ(), new Random(), $type);
                $sender->sendMessage($config->get("info") . "Der Tropenbaum wurde gesetzt!");
            }
        }
        return true;
    }

    public function Baum(ChunkManager $level, int $x, int $y, int $z, Random $random, int $type = 0)
    {
        switch ($type) {
            case Sapling::SPRUCE:
                $tree = new SpruceTree();
                break;
            case Sapling::BIRCH:
                if ($random->nextBoundedInt(39) === 0) {
                    $tree = new BirchTree(true);
                } else {
                    $tree = new BirchTree();
                }
                break;
            case Sapling::JUNGLE:
                $tree = new JungleTree();
                break;
            default:
                $tree = new OakTree();
                break;
        }
        $tree->placeObject($level, $x, $y, $z, $random);
    }
}
class BaumEvent extends PlayerEvent implements Cancellable{

    protected $position;
    protected $canceled = false;
    protected $bigtree;

    public function __construct(Player $player, Position $position, bool $bigtree = false){
        $this->player = $player;
        $this->position = $position;
        $this->bigtree = $bigtree;
    }

    public function isCancelled(): bool
    {
        return $this->canceled;
    }

    public function setCancelled(bool $value = true) : void
    {
        $this->canceled = $value;
    }

    public function getPosition() : Position{
        return $this->position;
    }

    public function setPosition(Position $position) : void{
        $this->position = $position;
    }

    public function isBigTree() : bool {
        return $this->bigtree;
    }

    public function setBigTree(bool $bigtree = true) : void{
        $this->bigtree = $bigtree;
    }
}
