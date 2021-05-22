<?php

namespace TheNote\core;

use pocketmine\utils\Config;

/**
 * Class CoreLang
 * @package TheNote\core
 */
class CoreLang
{

    /**
     * @var
     */
    private $plugin;

    /**
     * CoreLang constructor.
     */
    public function __construct()
    {
        $this->plugin = Main::getInstance();
    }

    /**
     * @return static
     */
    public static function getLang(): self
    {
        return new CoreLang();
    }

    /**
     * @return string|null
     */
    public function getSelectedLanguage(): ?string
    {
        $langsettings = new Config($this->plugin->getDataFolder() . Main::$lang . "LangConfig.yml", Config::YAML);
        return $langsettings->get("Lang");
    }

    /**
     * @return Config
     */
    private function getLangCfg()
    {
        return new Config($this->plugin->getDataFolder() . Main::$lang . "Lang_" . $this->getSelectedLanguage() . ".json", Config::JSON);
    }

    /**
     * @param string|null $val
     * @return string|null
     */
    public function getMessage(?string $val): ?string
    {
        if ($this->getLangCfg()->exists($val)) {
            return $this->getLangCfg()->get($val);
        }
        return null;
    }

    /**
     * @param string|null $val
     * @return string|null
     */
    public function getMsg(?string $val): ?string
    {
        return $this->getMessage($val);
    }

    /**
     * @param string|null $val
     * @return string|null
     */
    public function getValue(?string $val): ?string
    {
        return $this->getMessage($val);
    }
}