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

class FileBrowser
{

    public static function getAllSubdirectories(string $dir): array
    {
        $scanDirectory = function (string $dir): \Generator {
            foreach (glob($dir . "/*") as $subDir) {
                if (is_dir($subDir)) {
                    yield $subDir;
                }
            }
        };

        $all = [];
        $toCheck = [$dir => 0];

        check:
        foreach (array_keys($toCheck) as $scanning) {
            foreach ($scanDirectory($scanning) as $subDirectory) {
                if (!in_array($subDirectory, $all)) {
                    $all[] = $subDirectory;
                    $toCheck[$subDirectory] = 0;
                }
            }

            unset($toCheck[$scanning]);
        }

        if (!empty($toCheck)) {
            goto check;
        }

        return $all;
    }

    public static function saveResource(string $sourceFile, string $targetFile, bool $rewrite = false): bool
    {
        if (file_exists($targetFile)) {
            if ($rewrite) {
                unlink($targetFile);
            } else {
                return false;
            }
        }

        $dirs = explode(DIRECTORY_SEPARATOR, $targetFile);
        $file = array_pop($dirs);

        $tested = "";
        foreach ($dirs as $dir) {
            $tested .= $dir . DIRECTORY_SEPARATOR;
            if (!file_exists($tested)) {
                @mkdir($tested);
            }
        }

        file_put_contents($targetFile, file_get_contents($sourceFile));
        return true;
    }

    public static function removePathFromRoot(string $path, string $root): string
    {
        $position = strpos($path, $root);
        if ($position === false) {
            return $path;
        }

        return substr($path, $position + strlen($root));
    }
}