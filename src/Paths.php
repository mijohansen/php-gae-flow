<?php

namespace GaeFlow;

use Composer\Autoload\ClassLoader;

class Paths {
    const ENV_FILENAME_DEV = "dev.env";
    const ENV_FILENAME_PROD = "prod.env";

    static function vendorDir() {
        if (defined("COMPOSER_VENDOR_DIR")) {
            $vendorDir = COMPOSER_VENDOR_DIR;
        } else {
            $reflection = new \ReflectionClass(ClassLoader::class);
            $vendorDir = dirname(dirname($reflection->getFileName()));
        }
        return $vendorDir;
    }

    static function composerJsonPath() {
        return self::projectRoot() . DIRECTORY_SEPARATOR . "composer.json";
    }

    static function composerJsonData() {
        $content = file_get_contents(self::composerJsonPath());
        return json_decode($content, JSON_OBJECT_AS_ARRAY);
    }

    static function getPackageData() {
        $path = join(DIRECTORY_SEPARATOR, [
                dirname(__FILE__),
                "..",
                "composer.json"
            ]
        );
        $content = file_get_contents($path);
        return json_decode($content, JSON_OBJECT_AS_ARRAY);
    }

    static function projectRoot() {
        return dirname(self::vendorDir());
    }

    static function getUserHomeDir() {
        return getenv("HOME") ? getenv("HOME") : getenv("USERPROFILE");
    }

    static function getUserProjectDir($gcloudProject) {
        return Paths::getUserHomeDir() . DIRECTORY_SEPARATOR . "." . $gcloudProject;
    }
}