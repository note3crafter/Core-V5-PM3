<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//                        2017-2021

namespace TheNote\core;


use pocketmine\block\Block;
use pocketmine\block\Sapling;
use pocketmine\command\CommandSender;
use pocketmine\entity\Entity;
use pocketmine\event\entity\ItemSpawnEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerKickEvent;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\event\server\QueryRegenerateEvent;
use pocketmine\item\Armor;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\Tool;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\object\BirchTree;
use pocketmine\level\generator\object\JungleTree;
use pocketmine\level\generator\object\OakTree;
use pocketmine\level\generator\object\SpruceTree;
use pocketmine\level\particle\DustParticle;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\NetworkBinaryStream;
use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\BatchPacket;
use pocketmine\network\mcpe\protocol\InventoryTransactionPacket;
use pocketmine\network\mcpe\protocol\OnScreenTextureAnimationPacket;
use pocketmine\network\mcpe\protocol\ScriptCustomEventPacket;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Binary;
use pocketmine\utils\Config;
use pocketmine\command\ConsoleCommandSender;

//informationen

use pocketmine\utils\Random;

//Command
use TheNote\core\command\DayCommand;
use TheNote\core\command\NickCommand;
use TheNote\core\command\NightCommand;
use TheNote\core\command\FeedCommand;
use TheNote\core\command\HealCommand;
use TheNote\core\command\SurvivalCommand;
use TheNote\core\command\KreativCommand;
use TheNote\core\command\AbenteuerCommand;
use TheNote\core\command\ChatClearCommand;
use TheNote\core\command\ZuschauerCommand;
use TheNote\core\command\FlyCommand;
use TheNote\core\command\VanishCommand;
use TheNote\core\command\BoosterCommand;
use TheNote\core\command\NightVisionCommand;
use TheNote\core\command\PayallCommand;
use TheNote\core\command\EnderChestCommand;
use TheNote\core\command\RepairCommand;
use TheNote\core\command\RenameCommand;
use TheNote\core\command\ClanCommand;
use TheNote\core\command\ClearCommand;
use TheNote\core\command\FriendCommand;
use TheNote\core\command\ClearlaggCommand;
use TheNote\core\command\UnnickCommand;
use TheNote\core\command\SignCommand;
use TheNote\core\command\TellCommand;
use TheNote\core\command\ReplyCommand;
use TheNote\core\command\PerkCommand;
use TheNote\core\command\PerkShopCommand;
use TheNote\core\command\PosCommand;
use TheNote\core\command\StatsCommand;
use TheNote\core\command\ServerStatsCommand;
use TheNote\core\command\KitCommand;
use TheNote\core\command\SetHomeCommand;
use TheNote\core\command\DelHomeCommand;
use TheNote\core\command\ListHomeCommand;
use TheNote\core\command\HomeCommand;
use TheNote\core\command\MyCoinsCommand;
use TheNote\core\command\PayCoinsCommand;
use TheNote\core\command\UserdataCommand;
use TheNote\core\command\HeiratenCommand;
use TheNote\core\command\TpallCommand;
use TheNote\core\command\FakeCommand;
use TheNote\core\command\GruppeCommand;
use TheNote\core\command\NoDMCommand;
use TheNote\core\command\AnimationCommand;
use TheNote\core\command\CraftCommand;
use TheNote\core\command\ErfolgCommand;
use TheNote\core\command\KickallCommand;
use TheNote\core\command\VoteCommand;
use TheNote\core\command\GiveCoinsCommand;
use TheNote\core\command\SuperVanishCommand;
use TheNote\core\command\TreeCommand;
use TheNote\core\command\ServermuteCommand;
use TheNote\core\command\BanCommand;
use TheNote\core\command\BanIDListCommand;
use TheNote\core\command\BanListCommand;
use TheNote\core\command\UnbanCommand;
use TheNote\core\command\AdminItemsCommand;

//Server
use TheNote\core\item\Fireworks;
use TheNote\core\server\Version;

//Events
use TheNote\core\events\ColorChat;
use TheNote\core\events\Particle;
use TheNote\core\events\DeathMessages;
use TheNote\core\events\BanEventListener;
use TheNote\core\events\AdminItemsEvents;
use TheNote\core\events\AntiXrayEvent;

//listener
use TheNote\core\listener\UserdataListener;
use TheNote\core\listener\HeiratsListener;
use TheNote\core\listener\BackListener;
use TheNote\core\listener\CollisionsListener;
use TheNote\core\listener\GroupListener;

//Emotes
use TheNote\core\emotes\burb;
use TheNote\core\emotes\geil;
use TheNote\core\emotes\happy;
use TheNote\core\emotes\sauer;
use TheNote\core\emotes\traurig;

//Server
use TheNote\core\server\RestartServer;
use TheNote\core\server\Stats;
use TheNote\core\server\PlotBewertung;
use TheNote\core\server\Rezept;

//Anderes
use TheNote\core\formapi\SimpleForm;
use TheNote\core\item\ItemManager;
use TheNote\core\entity\EntityManager;
use TheNote\core\blocks\BlockManager;
use TheNote\core\blocks\PowerBlock;
use TheNote\core\server\LiftSystem\BlockBreakListener;
use TheNote\core\server\LiftSystem\BlockPlaceListener;
use TheNote\core\server\LiftSystem\PlayerInteractListener;
use TheNote\core\server\LiftSystem\PlayerJumpListener;
use TheNote\core\server\LiftSystem\PlayerToggleSneakListener;
use TheNote\core\inventar\BrauManager;
use TheNote\core\tile\Tiles;

//Task
use TheNote\core\item\Trident;
use TheNote\core\task\ScoreboardTask;
use TheNote\core\task\SendAsyncTask;
use TheNote\core\task\OnlineTask;
use TheNote\core\task\StatstextTask;
use TheNote\core\task\CallbackTask;
use TheNote\core\task\RTask;


class Main extends PluginBase implements Listener
{
    private $clicks;
    private $message = "";
    private $items = [];
    private $debug = false;
    private $default;
    private $padding;
    private $universalMute = false;
    private $min, $max;
    private $multibyte;
    public $queue = [];
    public $anni = 1;
    public $isAdd = [];
    public $myplot;
    public $config;
    public $economyapi;
    public $pureperms;
    public $ores = [14, 15, 21, 22, 41, 42, 56, 57, 73, 129, 133, 152];
    /** @var array $cooldown */
    public $cooldown = [];
    /** @var array $interactCooldown */
    public $interactCooldown = [];


    //PluginVersion
    public static $version = "5.0.4ALPHA";
    public static $protokoll = "428";
    public static $mcpeversion = "1.16.210";
    public static $dateversion = "24.03.2021";
    public static $plname = "CoreV5";

    //Configs
    public static $clanfile = "Cloud/players/Clans/";
    public static $custommsbfile = "Cloud/players/CustomScoreboard/";
    public static $freundefile = "Cloud/players/";
    public static $gruppefile = "Cloud/players/Gruppe/";
    public static $heifile = "Cloud/players/Heiraten/";
    public static $homefile = "Cloud/players/Homes/";
    public static $inventarfile = "Cloud/players/Inventare/";
    public static $logdatafile = "Cloud/players/Logdata/";
    public static $statsfile = "Cloud/players/Stats/";
    public static $userfile = "Cloud/players/User/";
    public static $backfile = "Cloud/players/";
    public static $cloud = "Cloud/";
    public static $setup = "Setup/";

    //Anderes
    public static $delay;
    public static $instance;
    public static $restart;
    public $players = [];
    public $bank;
    public $win = null;
    public $price = null;
    public $economy;
    private $lastSent;
    private $sessions = [];

    final public static function getPacketsFromBatch(BatchPacket $packet)
    {
        $stream = new NetworkBinaryStream($packet->payload);
        while (!$stream->feof()) {
            yield $stream->getString();
        }
    }

    function onJoin(PlayerJoinEvent $event)
    {
        $player = $event->getPlayer();
        $this->getScheduler()->scheduleDelayedTask(new ScoreboardTask($this, $player->getPlayer()), 20);
        $this->economyAPI = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
    }

    public static function getInstance(): Main
    {
        return self::$instance;
    }

    public static function getMain(): self
    {
        return self::$instance;
    }


    public function onLoad()
    {
        ItemManager::init();
        EntityManager::init();
        BlockManager::init();
        Tiles::init();
        $this->brewingManager = new BrauManager();
        $this->brewingManager->init();
        self::$instance = $this;

        if (isset($c["API-Key"])) {
            if (trim($c["API-Key"]) != "") {
                if (!is_dir($this->getDataFolder() . "Setup/")) {
                    mkdir($this->getDataFolder() . "Setup/");
                }
                file_put_contents($this->getDataFolder() . "Setup/minecraftpocket-servers.com.vrc", "{\"website\":\"http://minecraftpocket-servers.com/\",\"check\":\"http://minecraftpocket-servers.com/api-vrc/?object=votes&element=claim&key=" . $c["API-Key"] . "&username={USERNAME}\",\"claim\":\"http://minecraftpocket-servers.com/api-vrc/?action=post&object=votes&element=claim&key=" . $c["API-Key"] . "&username={USERNAME}\"}");

            }
        }
    }

    public function onEnable()
    {


        @mkdir($this->getDataFolder() . "Setup");
        @mkdir($this->getDataFolder() . "Cloud");
        @mkdir($this->getDataFolder() . "Cloud/players/");
        @mkdir($this->getDataFolder() . "Cloud/players/User/");
        @mkdir($this->getDataFolder() . "Cloud/players/Logdata/");
        @mkdir($this->getDataFolder() . "Cloud/players/Gruppe/");
        @mkdir($this->getDataFolder() . "Cloud/players/Heiraten/");
        @mkdir($this->getDataFolder() . "Cloud/players/Freunde/");
        @mkdir($this->getDataFolder() . "Cloud/players/Clans");
        @mkdir($this->getDataFolder() . "Cloud/players/Homes");
        @mkdir($this->getDataFolder() . "Cloud/players/Inventare");
        @mkdir($this->getDataFolder() . "Cloud/players/Stats");
        @mkdir($this->getDataFolder() . "Cloud/players/CustomScoreboard");
        $this->saveResource("liesmich.txt", true);
        $this->saveResource("Setup/settings.json", false);
        $this->saveResource("Setup/powerblock.yml", false);
        $this->saveResource("Setup/vote.yml", false);
        $this->saveResource("Setup/discordsettings.yml", false);
        $this->saveResource("Setup/Config.yml", false);
        $this->saveResource("Setup/PerkSettings.yml", false);
        $this->saveResource("Setup/starterkit.yml", false);
        $this->saveResource("Setup/kitsettings.yml", false);
        $this->saveResource("permissions.md", true);

        $this->default = "";
        if (strlen($this->default) > 1) {
            $this->getLogger()->warning("The \"default\" property in config.yml has an error - the value is too long! Assuming as \"_\".");
            $this->default = "_";
        }
        $this->padding = "";
        $this->min = 3;
        $this->max = 16;
        if ($this->max === -1 or $this->max === "-1") {
            $this->max = PHP_INT_MAX;
        }
        $this->multibyte = function_exists("mb_substr") and function_exists("mb_strlen");

        self::$instance = $this;


        $configs = new Config($this->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        $config = new Config($this->getDataFolder() . Main::$setup . "settings.json", Config::JSON);
        $kit = new Config($this->getDataFolder() . Main::$setup . "kitsettings.yml", Config::YAML);

        $serverstats = new Config($this->getDataFolder() . "Cloud/stats.json", Config::JSON);
        $serverstats->set("aktiviert", $serverstats->get("aktivieret") + 1);
        $serverstats->save();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->getServer()->getNetwork()->setName($configs->get("networkname"));
        $this->economy = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
        $this->getLogger()->info($config->get("prefix") . "§6Wird Geladen...");

        //Server::getInstance()->getCommandMap()->unregister(Server::getInstance()->getCommandMap()->getCommand("clear"));
        Server::getInstance()->getCommandMap()->unregister(Server::getInstance()->getCommandMap()->getCommand("version"));
        Server::getInstance()->getCommandMap()->unregister(Server::getInstance()->getCommandMap()->getCommand("tell"));
        Server::getInstance()->getCommandMap()->unregister(Server::getInstance()->getCommandMap()->getCommand("ban"));
        Server::getInstance()->getCommandMap()->unregister(Server::getInstance()->getCommandMap()->getCommand("unban"));
        Server::getInstance()->getCommandMap()->unregister(Server::getInstance()->getCommandMap()->getCommand("banlist"));

        $this->myplot = $this->getServer()->getPluginManager()->getPlugin("MyPlot");
        $this->economyapi = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
        $this->pureperms = $this->getServer()->getPluginManager()->getPlugin("PurePerms");
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML);

        if ($this->myplot === null) {
            $this->getLogger()->error("§cMyPlot fehlt bitte installiere dies bevor du die Core benutzt!");
            $this->setEnabled(false);
            return;
        }
        if ($this->economyapi === null) {
            $this->getLogger()->error("§cEconomyAPI fehlt bitte installiere dies bevor du die Core benutzt!");
            $this->setEnabled(false);
            return;
        }
        if ($this->pureperms === null) {
            $this->getLogger()->error("§cPurePerms fehlt bitte installiere dies bevor du die Core benutzt!");
            $this->setEnabled(false);
            return;
        }
        $this->getLogger()->info($config->get("prefix") . "§6Plugins wurden Erfolgreich geladen!");
        $this->bank = new Config($this->getDataFolder() . "bank.json", Config::JSON);
        new Config($this->getDataFolder() . Main::$cloud . "Count.json", Config::JSON);
        $votes = new Config($this->getDataFolder() . Main::$setup . "vote.yml", Config::YAML);
        //Blocks
        $this->getServer()->getPluginManager()->registerEvents(new PowerBlock($this), $this);

        //Commands
        $this->getServer()->getCommandMap()->register("gma", new AbenteuerCommand($this));
        $this->getServer()->getCommandMap()->register("adminitem", new AdminItemsCommand($this));
        $this->getServer()->getCommandMap()->register("animation", new AnimationCommand($this));
        $this->getServer()->getCommandMap()->register("ban", new BanCommand($this));
        $this->getServer()->getCommandMap()->register("banids", new BanIDListCommand($this));
        $this->getServer()->getCommandMap()->register("banlist", new BanListCommand($this));
        if ($votes->get("BoosterCommand") == true) {
            $this->getServer()->getCommandMap()->register("booster", new BoosterCommand($this));
        }
        $this->getServer()->getCommandMap()->register("chatclear", new ChatClearCommand($this));
        $this->getServer()->getCommandMap()->register("clan", new ClanCommand($this));
        $this->getServer()->getCommandMap()->register("clear", new ClearCommand($this));
        $this->getServer()->getCommandMap()->register("clearlagg", new ClearlaggCommand($this));
        $this->getServer()->getCommandMap()->register("craft", new CraftCommand($this));
        $this->getServer()->getCommandMap()->register("day", new DayCommand($this));
        $this->getServer()->getCommandMap()->register("delhome", new DelHomeCommand($this));
        $this->getServer()->getCommandMap()->register("ec", new EnderChestCommand($this));
        $this->getServer()->getCommandMap()->register("erfolg", new ErfolgCommand($this));
        $this->getServer()->getCommandMap()->register("fake", new FakeCommand($this));
        $this->getServer()->getCommandMap()->register("feed", new FeedCommand($this));
        $this->getServer()->getCommandMap()->register("fly", new FlyCommand($this));
        $this->getServer()->getCommandMap()->register("friend", new FriendCommand($this));
        $this->getServer()->getCommandMap()->register("givecoins", new GiveCoinsCommand($this));
        $this->getServer()->getCommandMap()->register("group", new GruppeCommand($this));
        $this->getServer()->getCommandMap()->register("heal", new HealCommand($this));
        $this->getServer()->getCommandMap()->register("heiraten", new HeiratenCommand($this));
        $this->getServer()->getCommandMap()->register("home", new HomeCommand($this));
        $this->getServer()->getCommandMap()->register("kickall", new KickallCommand($this));
        if ($kit->get("KitCommand") == true) {
            $this->getServer()->getCommandMap()->register("kit", new KitCommand($this));
        }
        $this->getServer()->getCommandMap()->register("gmc", new KreativCommand($this));
        $this->getServer()->getCommandMap()->register("listhome", new ListHomeCommand($this));
        $this->getServer()->getCommandMap()->register("mycoins", new MyCoinsCommand($this));
        $this->getServer()->getCommandMap()->register("nick", new NickCommand($this));
        $this->getServer()->getCommandMap()->register("night", new NightCommand($this));
        $this->getServer()->getCommandMap()->register("nightvision", new NightVisionCommand($this));
        $this->getServer()->getCommandMap()->register("notell", new NoDMCommand($this));
        $this->getServer()->getCommandMap()->register("payall", new PayallCommand($this));
        $this->getServer()->getCommandMap()->register("paycoins", new PayCoinsCommand($this));
        $this->getServer()->getCommandMap()->register("perk", new PerkCommand($this));
        $this->getServer()->getCommandMap()->register("perkshop", new PerkShopCommand($this));
        $this->getServer()->getCommandMap()->register("position", new PosCommand($this));
        $this->getServer()->getCommandMap()->register("rename", new RenameCommand($this));
        $this->getServer()->getCommandMap()->register("repair", new RepairCommand($this));
        $this->getServer()->getCommandMap()->register("reply", new ReplyCommand($this));
        $this->getServer()->getCommandMap()->register("serverstats", new ServerStatsCommand($this));
        $this->getServer()->getCommandMap()->register("sethome", new SetHomeCommand($this));
        $this->getServer()->getCommandMap()->register("servermute", new ServermuteCommand($this));
        $this->getServer()->getCommandMap()->register("sign", new SignCommand($this));
        $this->getServer()->getCommandMap()->register("stats", new StatsCommand($this));
        $this->getServer()->getCommandMap()->register("supervanish", new SuperVanishCommand($this));
        $this->getServer()->getCommandMap()->register("gms", new SurvivalCommand($this));
        $this->getServer()->getCommandMap()->register("tell", new TellCommand($this));
        $this->getServer()->getCommandMap()->register("tpall", new TpallCommand($this));
        $this->getServer()->getCommandMap()->register("tree", new TreeCommand($this));
        $this->getServer()->getCommandMap()->register("unban", new UnbanCommand($this));
        $this->getServer()->getCommandMap()->register("unnick", new UnnickCommand($this));
        $this->getServer()->getCommandMap()->register("userdata", new UserdataCommand($this));
        $this->getServer()->getCommandMap()->register("vanish", new VanishCommand($this));
        if ($votes->get("votes") == true) {
            $this->getServer()->getCommandMap()->register("vote", new VoteCommand($this));
        } elseif ($votes->get("votes") == false) {
            $this->getLogger()->alert("Voten ist Deaktiviert! Wenn du es Nutzen möchtest Aktiviere es in den Einstelungen..");
        }
        $this->getServer()->getCommandMap()->register("gmspc", new ZuschauerCommand($this));

        //Emotes
        $this->getServer()->getCommandMap()->register("burb", new burb($this));
        $this->getServer()->getCommandMap()->register("geil", new geil($this));
        $this->getServer()->getCommandMap()->register("happy", new happy($this));
        $this->getServer()->getCommandMap()->register("sauer", new sauer($this));
        $this->getServer()->getCommandMap()->register("traurig", new traurig($this));

        //Events
        $this->getServer()->getPluginManager()->registerEvents(new BanEventListener($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new ColorChat($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new DeathMessages($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new Particle($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new AdminItemsEvents($this), $this);
        if ($configs->get("AntiXray") == true) {
            $this->getServer()->getPluginManager()->registerEvents(new AntiXrayEvent($this), $this);
        } elseif ($configs->get("AntiXray") == false) {
            $this->getLogger()->alert("AntiXray ist Deaktiviert! Wenn du es Nutzen möchtest Aktiviere es in den Einstelungen.");
        }

        //listener
        $this->getServer()->getPluginManager()->registerEvents(new BackListener($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new CollisionsListener($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new GroupListener($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new HeiratsListener($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new UserdataListener($this), $this);

        //LiftSystem
        $this->getServer()->getPluginManager()->registerEvents(new BlockBreakListener($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new BlockPlaceListener($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerInteractListener($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerJumpListener($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerToggleSneakListener($this), $this);

        //Server
        $this->getServer()->getPluginManager()->registerEvents(new PlotBewertung($this), $this);
        $this->getServer()->getCommandMap()->register("restart", new RestartServer($this));
        $this->getServer()->getPluginManager()->registerEvents(new Rezept($this), $this);
        $this->getServer()->getPluginManager()->registerEvents(new Stats($this), $this);
        $this->getServer()->getCommandMap()->register("version", new Version($this));

        //Task
        $this->getScheduler()->scheduleRepeatingTask(new CallbackTask([$this, "particle"]), 10);
        $this->getScheduler()->scheduleRepeatingTask(new OnlineTask($this), 20);
        $this->getScheduler()->scheduleDelayedTask(new RTask($this), (20 * 60 * 10));
        $this->getScheduler()->scheduleRepeatingTask(new StatstextTask($this), 60);

        $this->getLogger()->info($config->get("prefix") . "§6Die Commands wurden Erfolgreich Regestriert");
        $this->getLogger()->info($config->get("prefix") . "§6Die Core ist nun Einsatzbereit!");
        $this->Banner();
    }

    private function Banner()
    {
        $banner = strval(
            "\n" .
            "╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗\n" .
            "╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝\n" .
            "  ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗ \n" .
            "  ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝ \n" .
            "  ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗\n" .
            "  ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝\n" .
            "Copyright by TheNote! Als eigenes ausgeben Verboten!\n" .
            "                      2017-2021                       "
        );
        $this->getLogger()->info($banner);
    }

    public function particle()
    {

        $level = $this->getServer()->getDefaultLevel();
        $pos = $level->getSafeSpawn();
        $count = 100;
        $particle = new DustParticle($pos, mt_rand(), mt_rand(), mt_rand(), mt_rand());
        for ($yaw = 0, $y = $pos->y; $y < $pos->y + 4; $yaw += (M_PI * 2) / 20, $y += 1 / 20) {
            $x = -sin($yaw) + $pos->x;
            $z = cos($yaw) + $pos->z;
            $particle->setComponents($x, $y, $z);
            $level->addParticle($particle);
        }
    }

    public function reload()
    {

        $this->saveDefaultConfig();
        if (!is_dir($this->getDataFolder() . "Setup/")) {
            mkdir($this->getDataFolder() . "Setup/");
        }
        $this->lists = [];
        foreach (scandir($this->getDataFolder() . "Setup/") as $file) {
            $ext = explode(".", $file);
            $ext = (count($ext) > 1 && isset($ext[count($ext) - 1]) ? strtolower($ext[count($ext) - 1]) : "");
            if ($ext == "vrc") {
                $this->lists[] = json_decode(file_get_contents($this->getDataFolder() . "Setup/$file"), true);
            }
        }
        $config = new Config($this->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $prefix = $config->get("voten");

        $this->reloadConfig();
        $config = $this->getConfig()->getAll();
        $this->message = $prefix . "§6Danke das du für uns abgestimmt hast :D";
        $this->items = [];
        $this->debug = isset($config["Debug"]) && $config["Debug"] === true ? true : false;
    }

    public function rewardPlayer($player, $multiplier)
    {
        $config = new Config($this->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $prefix = $config->get("voten");
        if (!$player instanceof Player) {
            return;
        }
        if ($multiplier < 1) {
            $player->sendMessage($prefix . "§6Vote hier -> " . $config->get("votelink"));
            return;
        }
        $clones = [];
        $player->sendMessage($prefix . "§6Danke das du für uns abgestimmt hast :D " . ($multiplier == 1 ? "" : "s") . "!");
        $this->getServer()->broadcastMessage($config->get("voten") . $player->getNameTag() . "hat für uns abgestimmt! Danke :D");
        $config = new Config($this->getDataFolder() . Main::$statsfile . $player->getLowerCaseName() . ".json", Config::JSON);
        $config->set("votes", $config->get("votes") + 1);
        $config->save();
    }

    public function onQuery(QueryRegenerateEvent $event)
    {
        $all = $this->getServer()->getOnlinePlayers();
        $count = count($all);
        $countdata = new Config($this->getDataFolder() . Main::$cloud . "Count.json", Config::JSON);
        $countdata->set("players", $count);
        $countdata->save();
        $online = new Config($this->getDataFolder() . Main::$cloud . "Count.json", Config::JSON);
        $event->setPlayerCount($online->get("Online"));
    }

    public function onPlayerLogin(PlayerLoginEvent $event)
    {
        $config = new Config($this->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        if ($config->get("defaultspawn") == true) {
            $event->getPlayer()->teleport($this->getServer()->getDefaultLevel()->getSafeSpawn());
        }
    }

    public function correctName($name)
    {
        if ($this->multibyte and mb_strlen($name) !== strlen($name)) {
            $length = mb_strlen($name, "UTF-8");
            $new = "";
            for ($i = 0; $i < $length; $i++) {
                $char = mb_substr($name, $i, 1, "UTF-8");
                if (strlen($char) > 1) {
                    $char = $this->default;
                }
                $new .= $char;
            }
            $name = $new;
        }
        $name = preg_replace('/[^A-Za-z0-9_]/', $this->default, $name);
        $name = substr($name, 0, min($this->max, strlen($name)));
        if ($this->padding !== "") {
            while (strlen($name) < $this->min) {
                $name .= $this->padding;
            }
        }
        return $name;
    }

    public static function num_addOrdinal($num)
    {
        return $num . self::num_getOrdinal($num);
    }

    public static function num_getOrdinal($num)
    {
        $rounded = $num % 100;
        if (3 < $rounded and $rounded < 21) {
            return "th";
        }
        $unit = $rounded % 10;
        if ($unit === 1) {
            return "st";
        }
        if ($unit === 2) {
            return "nd";
        }
        return $unit === 3 ? "rd" : "th";
    }

    public function onPlayerJoin(PlayerJoinEvent $event)
    {
        //Discord
        $dcsettings = new Config($this->getDataFolder() . Main::$setup . "discordsettings" . ".yml", Config::YAML);
        $dcname = $dcsettings->get("chatname");
        if ($dcsettings->get("DC") == true) {
            $playername = $event->getPlayer()->getName();
            $ar = getdate();
            $time = $ar['hours'] . ":" . $ar['minutes'];
            $format = "**" . $dcname . " : {time} : {player} : hat den Server Betreten!**";
            $msg = str_replace("{time}", $time, str_replace("{player}", $playername, $format));
            $this->sendMessage($playername, $msg);
        }

        //Weiteres
        $player = $event->getPlayer();
        $this->isAdd[$player->getName()] = false;
        $ainv = $player->getArmorInventory();
        $all = $this->getServer()->getOnlinePlayers();
        $event->setJoinMessage("");
        $nicks = new Config($this->getDataFolder() . Main::$gruppefile . $player->getName() . ".json", Config::JSON);
        $config = new Config($this->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $names = $nicks->get("Nickname");
        if ($nicks->get("Default") === true) {
            $this->getServer()->broadcastMessage("§f[§a+§f] " . $config->get("spieler") . " §f" . $names . " §ahat den Server betreten! §f[§a" . count($all) . "§f/§a" . $config->get("slots") . "§f]");
        } else if ($nicks->get("Owner") === true) {
            $this->getServer()->broadcastMessage("§f[§a+§f] " . $config->get("owner") . " §c" . $names . " §ahat den Server betreten! §f[§a" . count($all) . "§f/§a" . $config->get("slots") . "§f]");
        } else if ($nicks->get("Admin") === true) {
            $this->getServer()->broadcastMessage("§f[§a+§f] " . $config->get("admin") . " §c" . $names . " §ahat den Server betreten! §f[§a" . count($all) . "§f/§a" . $config->get("slots") . "§f]");
        } else if ($nicks->get("Developer") === true) {
            $this->getServer()->broadcastMessage("§f[§a+§f] " . $config->get("developer") . " §d" . $names . " §ahat den Server betreten! §f[§a" . count($all) . "§f/§a" . $config->get("slots") . "§f]");
        } else if ($nicks->get("Moderator") === true) {
            $this->getServer()->broadcastMessage("§f[§a+§f] " . $config->get("moderator") . " §b" . $names . " §ahat den Server betreten! §f[§a" . count($all) . "§f/§a" . $config->get("slots") . "§f]");
        } else if ($nicks->get("Builder") === true) {
            $this->getServer()->broadcastMessage("§f[§a+§f] " . $config->get("builder") . " §a" . $names . " §ahat den Server betreten! §f[§a" . count($all) . "§f/§a" . $config->get("slots") . "§f]");
        } else if ($nicks->get("Supporter") === true) {
            $this->getServer()->broadcastMessage("§f[§a+§f] " . $config->get("supporter") . " §b" . $names . " §ahat den Server betreten! §f[§a" . count($all) . "§f/§a" . $config->get("slots") . "§f]");
        } else if ($nicks->get("YouTuber") === true) {
            $this->getServer()->broadcastMessage("§f[§a+§f] " . $config->get("youtuber") . " §f" . $names . " §ahat den Server betreten! §f[§a" . count($all) . "§f/§a" . $config->get("slots") . "§f]");
        } else if ($nicks->get("Hero") === true) {
            $this->getServer()->broadcastMessage("§f[§a+§f] " . $config->get("hero") . " §d" . $names . " §ahat den Server betreten! §f[§a" . count($all) . "§f/§a" . $config->get("slots") . "§f]");
        } else if ($nicks->get("Suppremium") === true) {
            $this->getServer()->broadcastMessage("§f[§a+§f] " . $config->get("suppremium") . " §3" . $names . " §ahat den Server betreten! §f[§a" . count($all) . "§f/§a" . $config->get("slots") . "§f]");
        } else if ($nicks->get("Premium") === true) {
            $this->getServer()->broadcastMessage("§f[§a+§f] " . $config->get("premium") . " §6" . $names . " §ahat den Server betreten! §f[§a" . count($all) . "§f/§a" . $config->get("slots") . "§f]");
        } else {
            $this->getServer()->broadcastMessage("§f[§a+§f] " . $config->get("spieler") . " §f" . $player->getName() . " §ahat den Server betreten! §f[§a" . count($all) . "§f/§a" . $config->get("slots") . "§f]");
        }
        $this->addStrike($player);
        $fj = date('d.m.Y H:I') . date_default_timezone_set("Europe/Berlin");
        $gruppe = new Config($this->getDataFolder() . Main::$gruppefile . $player->getName() . ".json", Config::JSON);
        $log = new Config($this->getDataFolder() . Main::$logdatafile . $player->getLowerCaseName() . ".json", Config::JSON);
        $stats = new Config($this->getDataFolder() . Main::$statsfile . $player->getLowerCaseName() . ".json", Config::JSON);
        $user = new Config($this->getDataFolder() . Main::$userfile . $player->getLowerCaseName() . ".json", Config::JSON);
        $sstats = new Config($this->getDataFolder() . Main::$cloud . "stats.json", Config::JSON);
        $hei = new Config($this->getDataFolder() . Main::$heifile . $player->getLowerCaseName() . ".json", Config::JSON);
        $config = new Config($this->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $configs = new Config($this->getDataFolder() . Main::$setup . "Config" . ".yml", Config::YAML);
        $cfg = new Config($this->getDataFolder() . Main::$setup . "starterkit.yml", Config::YAML, array());

        $log->set("Name", $player->getName());
        $log->set("last-IP", $player->getAddress());
        $log->set("last-XboxID", $player->getPlayer()->getXuid());
        if ($configs->get("serverversion") == "altay") {
            $log->set("last-Geraet", $player->getPlayer()->getDeviceModel());
            $log->set("last-ID", $player->getPlayer()->getDeviceId());
        }
        $log->set("last-online", $fj);
        if ($user->get("heistatus") === false) {
            $player->sendMessage($config->get("heirat") . "Du bist nicht verheiratet!");
        }
        if ($gruppe->get("Clanstatus") === false) {
            $player->sendMessage($config->get("clans") . "Du bist im keinem Clan!");
        }
        //Spieler Erster Join
        if ($user->get("register") == null or false) {
            $player = $event->getPlayer();
            $ainv = $player->getArmorInventory();
            if ($configs->get("StarterKit") == true) {
                if ($cfg->get("Inventory", false)) {
                    foreach ($cfg->get("Slots", []) as $item) {
                        $result = Item::get($item["id"], $item["damage"], $item["count"]);
                        $result->setCustomName($item["name"]);
                        $result->setLore([$item["lore"]]);
                        $player->getInventory()->setItem($item["slot"], $result);
                    }
                }
                if ($cfg->get("Armor", false)) {
                    $data = $cfg->get("helm");
                    $item = Item::get($data["id"]);
                    $item->setCustomName($data["name"]);
                    $item->setLore([$data["lore"]]);
                    $ainv->setHelmet($item);

                    $data = $cfg->get("chest");
                    $item = Item::get($data["id"]);
                    $item->setCustomName($data["name"]);
                    $item->setLore([$data["lore"]]);
                    $ainv->setChestplate($item);

                    $data = $cfg->get("leggins");
                    $item = Item::get($data["id"]);
                    $item->setCustomName($data["name"]);
                    $item->setLore([$data["lore"]]);
                    $ainv->setLeggings($item);

                    $data = $cfg->get("boots");
                    $item = Item::get($data["id"]);
                    $item->setCustomName($data["name"]);
                    $item->setLore([$data["lore"]]);
                    $ainv->setBoots($item);
                }
            }

            //Resgister
            $sstats->set("Users", $sstats->get("Users") + 1);
            $sstats->save();
            $log->set("first-join", $fj);
            $log->set("first-ip", $player->getAddress());
            $log->set("first-XboxID", $player->getXuid());
            if ($configs->get("serverversion") == "altay") {
                $log->set("first-gereat", $player->getDeviceModel());
                $log->set("first-ID", $player->getPlayer()->getDeviceId());
            }
            $log->save();
            $gruppe->set("Default", true);
            $gruppe->set("Owner", false);
            $gruppe->set("Admin", false);
            $gruppe->set("Developer", false);
            $gruppe->set("Moderator", false);
            $gruppe->set("Builder", false);
            $gruppe->set("Supporter", false);
            $gruppe->set("YouTuber", false);
            $gruppe->set("Hero", false);
            $gruppe->set("Suppremium", false);
            $gruppe->set("Premium", false);
            $gruppe->set("Nick", false);
            $gruppe->set("NickP", false);
            $gruppe->set("NickPlayer", false);
            $gruppe->set("Nickname", $player->getName());
            $gruppe->set("ClanStatus", false);
            $user->set("Clananfrage", false);
            $user->set("Clan", "");
            $user->set("register", true);
            $gruppe->save();
            $hei->set("antrag", null);
            $hei->set("antrag-abgelehnt", 0);
            $hei->set("heiraten", null);
            $hei->set("heiraten-hit", 0);
            $hei->set("geschieden", 0);
            $hei->save();
            $user->set("scoreboard", 2);
            $user->set("coins", 100);
            $user->set("nodm", false);
            $user->set("clananfrage", false);
            $user->set("heistatus", false);
            $user->set("accept", false);
            $user->set("starterkit", true);
            $user->set("explode", false);
            $user->set("angry", false);
            $user->set("redstone", false);
            $user->set("smoke", false);
            $user->set("lava", false);
            $user->set("heart", false);
            $user->set("flame", false);
            $user->set("portal", false);
            $user->set("spore", false);
            $user->set("splash", false);
            $user->save();
            $stats->set("joins", 0);
            $stats->set("break", 0);
            $stats->set("place", 0);
            $stats->set("drop", 0);
            $stats->set("pick", 0);
            $stats->set("interact", 0);
            $stats->set("jumps", 0);
            $stats->set("messages", 0);
            $stats->set("votes", 0);
            $stats->set("consume", 0);
            $stats->set("kicks", 0);
            $stats->set("erfolge", 0);
            $stats->set("movefly", 0);
            $stats->set("movewalk", 0);
            $stats->set("jumperfolg", false); //10000
            $stats->set("breakerfolg", false); //1000000
            $stats->set("placeerfolg", false); //1000000
            $stats->set("messageerfolg", false); //1000000
            $stats->set("joinerfolg", false); //10000
            $stats->set("kickerfolg", false); //1000
            $stats->save();
            $player->setDisplayName("§eS§f:§f" . $player->getName());
            $player->setNameTag("§f[§eSpieler§f] §f" . $player->getName());

            //DiscordMessgae
            if ($dcsettings->get("DC") == true) {
                $nickname = $player->getName();
                $this->getServer()->broadcastMessage($config->get("prefix") . $player->getName() . " ist neu auf dem Server willkommen");
                $time = date('d.m.Y H:I') . date_default_timezone_set("Europe/Berlin");
                $format = "**__WILLKOMMEN__ : {time} : Spieler : {player} ist NEU auf dem Server und ist __Herzlichst Willkommen!__**";
                $msg = str_replace("{time}", $time, str_replace("{player}", $nickname, $format));
                $this->sendMessage($nickname, $msg);
            }
        }
    }

    public function onPlayerQuit(PlayerQuitEvent $event)
    {

        $player = $event->getPlayer();
        $all = $this->getServer()->getOnlinePlayers();
        //Discord
        $dcsettings = new Config($this->getDataFolder() . Main::$setup . "discordsettings" . ".yml", Config::YAML);
        $dcname = $dcsettings->get("chatname");
        if ($dcsettings->get("DC") == true) {
            $playername = $event->getPlayer()->getName();
            $ar = getdate();
            $time = $ar['hours'] . ":" . $ar['minutes'];
            $format = "**" . $dcname . " : {time} : {player} hat den CityBuild Server verlassen!**";
            $msg = str_replace("{time}", $time, str_replace("{player}", $playername, $format));
            $this->sendMessage($playername, $msg);
        }

        $event->setQuitMessage("");
        $nicks = new Config($this->getDataFolder() . Main::$gruppefile . $player->getName() . ".json", Config::JSON);
        $config = new Config($this->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);

        $names = $nicks->get("Nickname");
        if ($nicks->get("Default") === true) {
            $this->getServer()->broadcastMessage("§f[§c-§f] " . $config->get("spieler") . " §f" . $names . " §chat den Server verlassen §f[§a" . count($all) . "§f/§a" . $config->get("slots") . "§f]");
        } else if ($nicks->get("Owner") === true) {
            $this->getServer()->broadcastMessage("§f[§c-§f] " . $config->get("owner") . " §c" . $names . " §chat den Server verlassen §f[§a" . count($all) . "§f/§a" . $config->get("slots") . "§f]");
        } else if ($nicks->get("Admin") === true) {
            $this->getServer()->broadcastMessage("§f[§c-§f] " . $config->get("admin") . " §c" . $names . " §chat den Server verlassen §f[§a" . count($all) . "§f/§a" . $config->get("slots") . "§f]");
        } else if ($nicks->get("Developer") === true) {
            $this->getServer()->broadcastMessage("§f[§c-§f] " . $config->get("developer") . " §d" . $names . " §chat den Server verlassen §f[§a" . count($all) . "§f/§a" . $config->get("slots") . "§f]");
        } else if ($nicks->get("Moderator") === true) {
            $this->getServer()->broadcastMessage("§f[§c-§f] " . $config->get("moderator") . " §b" . $names . " §chat den Server verlassen §f[§a" . count($all) . "§f/§a" . $config->get("slots") . "§f]");
        } else if ($nicks->get("Builder") === true) {
            $this->getServer()->broadcastMessage("§f[§c-§f] " . $config->get("builder") . " §a" . $names . " §chat den Server verlassen §f[§a" . count($all) . "§f/§a" . $config->get("slots") . "§f]");
        } else if ($nicks->get("Supporter") === true) {
            $this->getServer()->broadcastMessage("§f[§c-§f] " . $config->get("supporter") . " §b" . $names . " §chat den Server verlassen §f[§a" . count($all) . "§f/§a" . $config->get("slots") . "§f]");
        } else if ($nicks->get("YouTuber") === true) {
            $this->getServer()->broadcastMessage("§f[§c-§f] " . $config->get("youtuber") . " §f" . $names . " §chat den Server verlassen §f[§a" . count($all) . "§f/§a" . $config->get("slots") . "§f]");
        } else if ($nicks->get("Hero") === true) {
            $this->getServer()->broadcastMessage("§f[§c-§f] " . $config->get("hero") . " §d" . $names . " §chat den Server verlassen §f[§a" . count($all) . "§f/§a" . $config->get("slots") . "§f]");
        } else if ($nicks->get("Suppremium") === true) {
            $this->getServer()->broadcastMessage("§f[§c-§f] " . $config->get("suppremium") . " §3" . $names . " §chat den Server verlassen §f[§a" . count($all) . "§f/§a" . $config->get("slots") . "§f]");
        } else if ($nicks->get("Premium") === true) {
            $this->getServer()->broadcastMessage("§f[§c-§f] " . $config->get("premium") . " §6" . $names . " §chat den Server verlassen §f[§a" . count($all) . "§f/§a" . $config->get("slots") . "§f]");
        } else {
            $this->getServer()->broadcastMessage("§f[§c-§f] " . $config->get("spieler") . " §f" . $player->getName() . " §chat den Server verlassen §f[§a" . count($all) . "§f/§a" . $config->get("slots") . "§f]");
        }
    }

    public function addStrike(Player $player)
    {
        $config = new Config($this->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        if ($config->get("blitze") == true) {
            $level = $player->getLevel();
            $light = new AddActorPacket();
            $light->type = "minecraft:lightning_bolt";
            $light->entityRuntimeId = Entity::$entityCount++;
            $light->metadata = array();
            $light->position = $player->asVector3()->add(0, $height = 0);
            $light->yaw = $player->getYaw();
            $light->pitch = $player->getPitch();
            $player->getServer()->broadcastPacket($level->getPlayers(), $light);
        }
    }


    public function getBrewingManager(): BrauManager
    {
        return $this->brewingManager;
    }

    public function onItemSpawn(ItemSpawnEvent $event)
    {
        $config = new Config($this->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        if ($config->get("inames") == true) {
            $entity = $event->getEntity();
            $item = $entity->getItem();
            $name = $item->getName();
            $entity->setNameTag($name);
            $entity->setNameTagVisible(true);
            $entity->setNameTagAlwaysVisible(true);
        }
    }

    public function onChat(PlayerChatEvent $event)
    {
        $config = new Config($this->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $voteconfig = new Config($this->getDataFolder() . Main::$setup . "vote" . ".yml", Config::YAML);
        $dcsettings = new Config($this->getDataFolder() . Main::$setup . "discordsettings" . ".yml", Config::YAML);
        $dcname = $dcsettings->get("chatname");

        $player = $event->getPlayer();
        $playername = $event->getPlayer()->getName();
        $message = $event->getMessage();
        $stats = new Config($this->getDataFolder() . Main::$statsfile . $player->getLowerCaseName() . ".json", Config::JSON);
        if ($voteconfig->get("MussVoten") == true) {
            if ($stats->get("votes") < $voteconfig->get("Mindestvotes")) {
                $player->sendMessage($config->get("error") . "§cDu musst mindestens 1x Gevotet haben um auf dem Server Schreiben zu können! §f-> §e" . $config->get("votelink"));
                $event->setCancelled(true);
                return true;
            } else {
                $event->setCancelled(false);
            }
            if ($dcsettings->get("DC") == true) {
                if ($stats->get("votes") >= $voteconfig->get("Mindestvotes")) {
                    $ar = getdate();
                    $time = $ar['hours'] . ":" . $ar['minutes'];
                    $format = "```" . $dcname . ": {time} : {player} : {msg}```";
                    $msg = str_replace("{msg}", $message, str_replace("{time}", $time, str_replace("{player}", $playername, $format)));
                    $this->sendMessage($playername, $msg);

                }
            }
        }
        $msg = $event->getMessage();
        $p = $event->getPlayer();

        if ($this->win != null && $this->price != null) {
            if ($msg == $this->win) {
                $this->getServer()->broadcastMessage($config->get("info") . "§7Der Spieler §6" . $p->getNameTag() . " §7hat das Wort: §e" . $this->win . " §7entschlüsselt und hat §a" . $this->price . "€ §7gewonnen!");
                $this->economy->addMoney($p->getName(), $this->price);
                $this->win = null;
                $this->price = null;
                $event->setCancelled();
            }
        }
    }

    public function onDeath(PlayerDeathEvent $event)
    {
        $dcsettings = new Config($this->getDataFolder() . Main::$setup . "discordsettings" . ".yml", Config::YAML);
        $dcname = $dcsettings->get("chatname");
        if ($dcsettings->get("DC") == true) {
            $playername = $event->getPlayer()->getName();
            $ar = getdate();
            $time = $ar['hours'] . ":" . $ar['minutes'];
            $format = "**" . $dcname . " : {time} : {player} starb an seiner eigenen Kotze!**";
            $msg = str_replace("{time}", $time, str_replace("{player}", $playername, $format));
            $this->sendMessage($playername, $msg);

        }
    }

    public function onKick(PlayerKickEvent $event)
    {
        $dcsettings = new Config($this->getDataFolder() . Main::$setup . "discordsettings" . ".yml", Config::YAML);
        $dcname = $dcsettings->get("chatname");
        if ($dcsettings->get("DC") == true) {
            $playername = $event->getPlayer()->getName();
            $ar = getdate();
            $time = $ar['hours'] . ":" . $ar['minutes'];
            $format = "**" . $dcname . " : {time} : {player} wurde vom Server gekickt**";
            $msg = str_replace("{time}", $time, str_replace("{player}", $playername, $format));
            $this->sendMessage($playername, $msg);

        }
    }

    public function transferPlayer(Player $player, string $server)
    {
        $pk = new ScriptCustomEventPacket();
        $pk->eventName = "bungeecord:main";
        $pk->eventData = Binary::writeShort(strlen("Connect")) . "Connect" . Binary::writeShort(strlen($server)) . $server;
        $player->sendDataPacket($pk);
    }

    public function isRepairable(Item $item): bool
    {
        return $item instanceof Tool || $item instanceof Armor;
    }

    public function backFromAsync($player, $result)
    {
        if ($player === "nolog") {
            return;
        } elseif ($player === "CONSOLE") {
            $player = new ConsoleCommandSender();
        } else {
            $playerinstance = $this->getServer()->getPlayerExact($player);
            if ($playerinstance === null) {
                return;
            } else {
                $player = $playerinstance;
            }
        }
    }

    public function onMessage(CommandSender $sender, Player $receiver): void
    {
        $this->lastSent[$receiver->getName()] = $sender->getName();
    }

    public function getLastSent(string $name): string
    {
        return $this->lastSent[$name] ?? "";
    }

    public function sendMessage(string $player = "nolog", string $msg)
    {
        $dcsettings = new Config($this->getDataFolder() . Main::$setup . "discordsettings" . ".yml", Config::YAML);
        $name = $dcsettings->get("chatname");
        $webhook = $dcsettings->get("webhookurl");
        $curlopts = [
            "content" => $msg,
            "username" => $name
        ];

        $this->getServer()->getAsyncPool()->submitTask(new task\SendAsyncTask($player, $webhook, serialize($curlopts)));

        return true;
    }

    public function onDataPacketReceive(DataPacketReceiveEvent $event)
    {
        $config = new Config($this->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);

        $player = $event->getPlayer();
        $packet = $event->getPacket();
        if ($packet instanceof InventoryTransactionPacket) {
            $transactionType = $packet->transactionType;
            if ($transactionType === InventoryTransactionPacket::TYPE_USE_ITEM || $transactionType === InventoryTransactionPacket::TYPE_USE_ITEM_ON_ENTITY) {
                $this->addClick($player);
                if ($this->getClicks($player) > 70 && !$player->isClosed()) {
                    $this->getServer()->broadcastMessage($config->get("info") . $player->getName() . "hat in der Letzten sekunde 70x Geklickt");
                    $player->kick("§cDu wurdest wegen AutoKlicker gekickt", false);
                }
            }
        }
    }

    public function getClicks(Player $player)
    {
        if (!isset($this->clicks[$player->getLowerCaseName()])) return 0;
        $time = $this->clicks[$player->getLowerCaseName()][0];
        $clicks = $this->clicks[$player->getLowerCaseName()][1];
        if ($time !== time()) {
            unset($this->clicks[$player->getLowerCaseName()]);
            return 0;
        }
        return $clicks;
    }

    public function addClick(Player $player)
    {
        if (!isset($this->clicks[$player->getLowerCaseName()])) $this->clicks[$player->getLowerCaseName()] = [time(), 0];
        $time = $this->clicks[$player->getLowerCaseName()][0];
        $clicks = $this->clicks[$player->getLowerCaseName()][1];
        if ($time !== time()) {
            $time = time();
            $clicks = 0;
        }
        $clicks++;
        $this->clicks[$player->getLowerCaseName()] = [$time, $clicks];
    }

    public function getElevators(Block $block, string $where = "", bool $searchForPrivate = false): int
    {
        if (!$searchForPrivate) {
            $blocks = [Block::DAYLIGHT_SENSOR];
        } else {
            $blocks = [Block::DAYLIGHT_SENSOR, Block::DAYLIGHT_SENSOR_INVERTED];
        }
        $count = 0;
        if ($where === "up") {
            $y = $block->getY() + 1;
            while ($y < $block->getLevel()->getWorldHeight()) {
                $blockToCheck = $block->getLevel()->getBlock(new Vector3($block->getX(), $y, $block->getZ()));
                if (in_array($blockToCheck->getId(), $blocks)) {
                    $count = $count + 1;
                }
                $y++;
            }
        } elseif ($where === "down") {
            $y = $block->getY() - 1;
            while ($y >= 0) {
                $blockToCheck = $block->getLevel()->getBlock(new Vector3($block->getX(), $y, $block->getZ()));
                if (in_array($blockToCheck->getId(), $blocks)) {
                    $count = $count + 1;
                }
                $y--;
            }
        } else {
            $y = 0;
            while ($y < $block->getLevel()->getWorldHeight()) {
                $blockToCheck = $block->getLevel()->getBlock(new Vector3($block->getX(), $y, $block->getZ()));
                if (in_array($blockToCheck->getId(), $blocks)) {
                    $count = $count + 1;
                }
                $y++;
            }
        }
        return $count;
    }


    public function getNextElevator(Block $block, string $where = "", bool $searchForPrivate = false): ?Block
    {
        if (!$searchForPrivate) {
            $blocks = [Block::DAYLIGHT_SENSOR];
        } else {
            $blocks = [Block::DAYLIGHT_SENSOR, Block::DAYLIGHT_SENSOR_INVERTED];
        }
        $elevator = null;
        if ($where === "up") {
            $y = $block->getY() + 1;
            while ($y < $block->getLevel()->getWorldHeight()) {
                $blockToCheck = $block->getLevel()->getBlock(new Vector3($block->getX(), $y, $block->getZ()));
                if (in_array($blockToCheck->getId(), $blocks)) {
                    $elevator = $blockToCheck;
                    break;
                }
                $y++;
            }
        } else {
            $y = $block->getY() - 1;
            while ($y >= 0) {
                $blockToCheck = $block->getLevel()->getBlock(new Vector3($block->getX(), $y, $block->getZ()));
                if (in_array($blockToCheck->getId(), $blocks)) {
                    $elevator = $blockToCheck;
                    break;
                }
                $y--;
            }
        }
        if ($elevator === null) return null;

        if ($this->config->get("checkFloor") !== true) return $elevator;

        $block1 = $elevator->getLevel()->getBlock(new Vector3($elevator->getX(), $elevator->getY() + 1, $elevator->getZ()));
        $block2 = $elevator->getLevel()->getBlock(new Vector3($elevator->getX(), $elevator->getY() + 2, $elevator->getZ()));
        if ($block1->getId() !== 0 || $block2->getId() !== 0) return $block;


        $blocksToCheck = [];

        $blocksToCheck[] = $block1->getLevel()->getBlock(new Vector3($block1->getX() + 1, $block1->getY(), $block1->getZ()));
        $blocksToCheck[] = $block1->getLevel()->getBlock(new Vector3($block1->getX() - 1, $block1->getY(), $block1->getZ()));
        $blocksToCheck[] = $block1->getLevel()->getBlock(new Vector3($block1->getX(), $block1->getY(), $block1->getZ() + 1));
        $blocksToCheck[] = $block1->getLevel()->getBlock(new Vector3($block1->getX(), $block1->getY(), $block1->getZ() - 1));

        $blocksToCheck[] = $block2->getLevel()->getBlock(new Vector3($block2->getX() + 1, $block2->getY(), $block2->getZ()));
        $blocksToCheck[] = $block2->getLevel()->getBlock(new Vector3($block2->getX() - 1, $block2->getY(), $block2->getZ()));
        $blocksToCheck[] = $block2->getLevel()->getBlock(new Vector3($block2->getX(), $block2->getY(), $block2->getZ() + 1));
        $blocksToCheck[] = $block2->getLevel()->getBlock(new Vector3($block2->getX(), $block2->getY(), $block2->getZ() - 1));

        $deniedBlocks = [Block::LAVA, Block::FLOWING_LAVA, Block::WATER, Block::FLOWING_WATER];
        foreach ($blocksToCheck as $blockToCheck) {
            if (in_array($blockToCheck->getId(), $deniedBlocks)) return $block;
        }

        return $elevator;
    }

    public function getFloor(Block $block, bool $searchForPrivate = false): int
    {
        if (!$searchForPrivate) {
            $blocks = [Block::DAYLIGHT_SENSOR];
        } else {
            $blocks = [Block::DAYLIGHT_SENSOR, Block::DAYLIGHT_SENSOR_INVERTED];
        }
        $sw = 0;
        $y = -1;
        while ($y < $block->getLevel()->getWorldHeight()) {
            $y++;
            $blockToCheck = $block->getLevel()->getBlock(new Vector3($block->getX(), $y, $block->getZ()));
            if (!in_array($blockToCheck->getId(), $blocks)) continue;
            $sw++;
            if ($blockToCheck === $block) break;
        }
        return $sw;
    }

    public function screenanimation(Player $player, int $effectID)
    {
        $packet = new OnScreenTextureAnimationPacket();
        $packet->effectId = $effectID;
        $player->sendDataPacket($packet);
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

    public function isMuted(): bool
    {
        return $this->universalMute;
    }

    public function setMuted(bool $bool = true): void
    {
        $this->universalMute = $bool;
    }

    public function getSessionById(int $id)
    {
        if (isset($this->sessions[$id])) {
            return $this->sessions[$id];
        } else {
            return null;
        }
    }

    public function destroySession(Player $player): bool
    {
        if (isset($this->sessions[$player->getId()])) {
            unset($this->sessions[$player->getId()]);
            return true;
        }
        return false;
    }

    public function getSessionByName(string $name)
    {
        foreach ($this->sessions as $session) {
            if ($session->getPlayer()->getName() == $name) {
                return $session;
            }
        }
        return null;
    }

    public function onDisable()
    {
        $config = new Config($this->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        foreach ($this->getServer()->getOnlinePlayers() as $player) {
            $player->transfer($config->get("rejoinserverip"), $config->get("rejoinserverport"));
        }
    }
}