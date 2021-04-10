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

use pocketmine\utils\Config;
use TheNote\core\formapi\SimpleForm;
use TheNote\core\Main;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use onebone\economyapi\EconomyAPI;

class RankShopCommand extends Command
{
    private $plugin;

    public function __construct(Main $plugin)
    {
        $this->plugin = $plugin;
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        parent::__construct("rankshop", $config->get("prefix") . "§6Kaufe einen Rang", "/rankshop", ["rshop"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args): bool
    {
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        if (!$sender instanceof Player) {
            $sender->sendMessage($config->get("error") . "§cDiesen Command kannst du nur Ingame benutzen");
            return false;
        }
        $this->Shop($sender);
        return true;
    }


    public function Shop($player){
        $name = $player->getName();
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
        $g1 = $groups->getNested("Groups." . $config->get("Rankname1") . ".groupprefix");
        $g2 = $groups->getNested("Groups." . $config->get("Rankname2") . ".groupprefix");
        $g3 = $groups->getNested("Groups." . $config->get("Rankname3") . ".groupprefix");
        $g4 = $groups->getNested("Groups." . $config->get("Rankname4") . ".groupprefix");


        $form = new SimpleForm(function (Player $player, int $data = null) {
            $settings = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
            $result = $data;
            if ($result === null) {
                return true;
            }
            switch ($result) {
                case 0:
                    $player->sendMessage($settings->get("shop") . "§eDanke das du da warst.");
                    break;
                case 1:
                    $this->Rank1($player);
                    break;
                case 2:
                    $this->Rank2($player);
                    break;
                case 3:
                    $this->Rank3($player);
                    break;
                case 4:
                    $this->Rank4($player);
            }
        });
        $form->setTitle("§0======§f[§cRangShop§f]§0======");
        $form->addButton("§cVerlassen");
        $form->addButton("Rang :" . $g1  ."\n" . "§0Kostet: " . $config->get("Rankprice1"));
        if ($config->get("Rank2") == true){
            $form->addButton("Rang :" . $g2 . "\n" . "§0Kostet: " . $config->get("Rankprice2"));
        }
        if ($config->get("Rank3") == true){
            $form->addButton("Rang :" . $g3 . "\n" . "§0Kostet: " . $config->get("Rankprice3"));
        }
        if ($config->get("Rank4") == true){
            $form->addButton("Rang :" . $g4 . "\n" . "§0Kostet: " . $config->get("Rankprice4"));
        }
        $form->sendToPlayer($player);
    }
    public function Rank1($player)
    {
        $settings = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $name = $player->getName();
        $money = new Config($this->plugin->getDataFolder() . Main::$cloud . "Money.yml", Config::YAML);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
        $playerdata = new Config($this->plugin->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        if($this->plugin->economy == null){
            $m = $money->getNested("money.$name");
        } else {
            $m = EconomyAPI::getInstance()->myMoney($player);
        }
        if ($m >= $config->get("Rankprice1")){
            if($this->plugin->economy == null){
                $money->setNested("money." . $player->getName(), $money->getNested("money." . $player->getName()) - $config->get("Rankprice1"));
                $money->save();
            } else {
                EconomyAPI::getInstance()->reduceMoney($player, $config->get("Rankprice1"));
            }
            $groupprefix = $groups->getNested("Groups." . $config->get("Rankname1") .".groupprefix");
            $playerdata->setNested($name . ".groupprefix", $groupprefix );
            $playerdata->setNested($name . ".group", $config->get("Rankname1"));
            $playerdata->save();
            $playergroup = $playerdata->getNested($name.".group");
            $nametag = str_replace("{name}", $player->getName(), $groups->getNested("Groups.{$playergroup}.nametag"));
            $displayname = str_replace("{name}", $player->getName(), $groups->getNested("Groups.{$playerdata->getNested($name.".group")}.displayname"));
            $permissionlist = (array)$groups->getNested("Groups.".$playergroup.".permissions", []);
            foreach($permissionlist as $name => $data) {
                $player->addAttachment($this->plugin)->setPermission($data, true);
            }
            $player->setNameTag($nametag);
            $player->setDisplayName($displayname);
            $player->sendMessage($settings->get("shop") . "§6Du hast soeben denn Rang §f:§e " . $config->get("Rankname1") ." §6Rang gekauft!");
        } else {
            $player->sendMessage($settings->get("error") . "§cDu hast zu wenig Geld um diesen Rang zu kaufen!");
        }
    }
    public function Rank2($player)
    {
        $settings = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $name = $player->getName();
        $money = new Config($this->plugin->getDataFolder() . Main::$cloud . "Money.yml", Config::YAML);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
        $playerdata = new Config($this->plugin->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        if($this->plugin->economy == null){
            $m = $money->getNested("money.$name");
        } else {
            $m = EconomyAPI::getInstance()->myMoney($player);
        }
        if ($m >= $config->get("Rankprice2")){
            if($this->plugin->economy == null){
                $money->setNested("money." . $player->getName(), $money->getNested("money." . $player->getName()) - $config->get("Rankprice2"));
                $money->save();
            } else {
                EconomyAPI::getInstance()->reduceMoney($player, $config->get("Rankprice2"));
            }
            $groupprefix = $groups->getNested("Groups." . $config->get("Rankname2") .".groupprefix");
            $playerdata->setNested($name . ".groupprefix", $groupprefix );
            $playerdata->setNested($name . ".group", $config->get("Rankname2"));
            $playerdata->save();
            $playergroup = $playerdata->getNested($name.".group");
            $nametag = str_replace("{name}", $player->getName(), $groups->getNested("Groups.{$playergroup}.nametag"));
            $displayname = str_replace("{name}", $player->getName(), $groups->getNested("Groups.{$playerdata->getNested($name.".group")}.displayname"));
            $permissionlist = (array)$groups->getNested("Groups.".$playergroup.".permissions", []);
            foreach($permissionlist as $name => $data) {
                $player->addAttachment($this->plugin)->setPermission($data, true);
            }
            $player->setNameTag($nametag);
            $player->setDisplayName($displayname);
            $player->sendMessage($settings->get("shop") . "§6Du hast soeben denn Rang §f:§e " . $config->get("Rankname2") ." §6Rang gekauft!");
        } else {
            $player->sendMessage($settings->get("error") . "§cDu hast zu wenig Geld um diesen Rang zu kaufen!");
        }
    }
    public function Rank3($player)
    {
        $settings = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $name = $player->getName();
        $money = new Config($this->plugin->getDataFolder() . Main::$cloud . "Money.yml", Config::YAML);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
        $playerdata = new Config($this->plugin->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        if($this->plugin->economy == null){
            $m = $money->getNested("money.$name");
        } else {
            $m = EconomyAPI::getInstance()->myMoney($player);
        }
        if ($m >= $config->get("Rankprice3")){
            if($this->plugin->economy == null){
                $money->setNested("money." . $player->getName(), $money->getNested("money." . $player->getName()) - $config->get("Rankprice3"));
                $money->save();
            } else {
                EconomyAPI::getInstance()->reduceMoney($player, $config->get("Rankprice3"));
            }
            $groupprefix = $groups->getNested("Groups." . $config->get("Rankname3") .".groupprefix");
            $playerdata->setNested($name . ".groupprefix", $groupprefix );
            $playerdata->setNested($name . ".group", $config->get("Rankname3"));
            $playerdata->save();
            $playergroup = $playerdata->getNested($name.".group");
            $nametag = str_replace("{name}", $player->getName(), $groups->getNested("Groups.{$playergroup}.nametag"));
            $displayname = str_replace("{name}", $player->getName(), $groups->getNested("Groups.{$playerdata->getNested($name.".group")}.displayname"));
            $permissionlist = (array)$groups->getNested("Groups.".$playergroup.".permissions", []);
            foreach($permissionlist as $name => $data) {
                $player->addAttachment($this->plugin)->setPermission($data, true);
            }
            $player->setNameTag($nametag);
            $player->setDisplayName($displayname);
            $player->sendMessage($settings->get("shop") . "§6Du hast soeben denn Rang §f:§e " . $config->get("Rankname3") ." §6Rang gekauft!");
        } else {
            $player->sendMessage($settings->get("error") . "§cDu hast zu wenig Geld um diesen Rang zu kaufen!");
        }
    }
    public function Rank4($player)
    {
        $settings = new Config($this->plugin->getDataFolder() . Main::$setup . "settings" . ".json", Config::JSON);
        $name = $player->getName();
        $money = new Config($this->plugin->getDataFolder() . Main::$cloud . "Money.yml", Config::YAML);
        $config = new Config($this->plugin->getDataFolder() . Main::$setup . "Config.yml", Config::YAML);
        $groups = new Config($this->plugin->getDataFolder() . Main::$cloud . "groups.yml", Config::YAML);
        $playerdata = new Config($this->plugin->getDataFolder() . Main::$cloud . "players.yml", Config::YAML);
        if($this->plugin->economy == null){
            $m = $money->getNested("money.$name");
        } else {
            $m = EconomyAPI::getInstance()->myMoney($player);
        }
        if ($m >= $config->get("Rankprice4")){
            if($this->plugin->economy == null){
                $money->setNested("money." . $player->getName(), $money->getNested("money." . $player->getName()) - $config->get("Rankprice3"));
                $money->save();
            } else {
                EconomyAPI::getInstance()->reduceMoney($player, $config->get("Rankprice4"));
            }
            $groupprefix = $groups->getNested("Groups." . $config->get("Rankname4") .".groupprefix");
            $playerdata->setNested($name . ".groupprefix", $groupprefix );
            $playerdata->setNested($name . ".group", $config->get("Rankname4"));
            $playerdata->save();
            $playergroup = $playerdata->getNested($name.".group");
            $nametag = str_replace("{name}", $player->getName(), $groups->getNested("Groups.{$playergroup}.nametag"));
            $displayname = str_replace("{name}", $player->getName(), $groups->getNested("Groups.{$playerdata->getNested($name.".group")}.displayname"));
            $permissionlist = (array)$groups->getNested("Groups.".$playergroup.".permissions", []);
            foreach($permissionlist as $name => $data) {
                $player->addAttachment($this->plugin)->setPermission($data, true);
            }
            $player->setNameTag($nametag);
            $player->setDisplayName($displayname);
            $player->sendMessage($settings->get("shop") . "§6Du hast soeben denn Rang §f:§e " . $config->get("Rankname4") ." §6Rang gekauft!");
        } else {
            $player->sendMessage($settings->get("error") . "§cDu hast zu wenig Geld um diesen Rang zu kaufen!");
        }
    }

}