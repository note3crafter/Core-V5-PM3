<?php

//   ╔═════╗╔═╗ ╔═╗╔═════╗╔═╗    ╔═╗╔═════╗╔═════╗╔═════╗
//   ╚═╗ ╔═╝║ ║ ║ ║║ ╔═══╝║ ╚═╗  ║ ║║ ╔═╗ ║╚═╗ ╔═╝║ ╔═══╝
//     ║ ║  ║ ╚═╝ ║║ ╚══╗ ║   ╚══╣ ║║ ║ ║ ║  ║ ║  ║ ╚══╗
//     ║ ║  ║ ╔═╗ ║║ ╔══╝ ║ ╠══╗   ║║ ║ ║ ║  ║ ║  ║ ╔══╝
//     ║ ║  ║ ║ ║ ║║ ╚═══╗║ ║  ╚═╗ ║║ ╚═╝ ║  ║ ║  ║ ╚═══╗
//     ╚═╝  ╚═╝ ╚═╝╚═════╝╚═╝    ╚═╝╚═════╝  ╚═╝  ╚═════╝
//   Copyright by TheNote! Not for Resale! Not for others
//

namespace TheNote\core\server\structure;


use TheNote\core\Main;
use TheNote\core\server\FileBrowser;
use TheNote\core\server\structure\type\PillagerOutpost;

class StructureManager {

    public const PILLEGEROUTPOST_PATH = "structures/pillageroutpost/";

    protected static $classPaths = [];
    protected static $structures = [];

    public static function saveResources(array $resources) {
        $saved = 0;
        $startTime = microtime(true);

        foreach ($resources as $resource) {
            if($resource->getExtension() === "nbt") {
                if(Main::getInstance()->saveResource(FileBrowser::removePathFromRoot($resource->getPathname(), "resources"))) {
                    $saved++;
                }
            }
        }

        if($saved > 0) {
            Main::getInstance()->getLogger()->info("Saved $saved structures! (" . (string)round(microtime(true)-$startTime, 2) . " seconds)");
        }
    }

    public static function lazyInit() {
        if(count(self::$classPaths) === 0) {
            self::init();
        }
    }

    public static function init() {
        $dataFolder = getcwd() . DIRECTORY_SEPARATOR . "plugin_data" . DIRECTORY_SEPARATOR . Main::$plname . DIRECTORY_SEPARATOR;

        self::$classPaths[PillagerOutpost::class] = $dataFolder . self::PILLEGEROUTPOST_PATH;
    }

    public static function registerStructure(string $path, Structure $structure) {
        if(isset(self::$structures[$path])) {
            return;
        }

        self::$structures[$path] = $structure;
    }

    public static function getStructure(string $class): ?Structure {
        self::lazyInit();
        $path = self::$classPaths[$class];

        if(!isset(self::$structures[$path])) {
            self::registerStructure($path, new $class($path));
        }

        return self::$structures[self::$classPaths[$class]] ?? null;
    }
}