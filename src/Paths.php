<?php

namespace GaeFlow;

use Composer\Autoload\ClassLoader;
use Composer\Script\Event;
use ReflectionClass;

class Paths {

    const ENV_FILENAME_DEV = "dev.env";
    const ENV_FILENAME_PROD = "prod.env";

    static function composerJsonData(Event $event = null) {
        $content = file_get_contents(self::composerJsonPath($event));
        return json_decode($content, JSON_OBJECT_AS_ARRAY);
    }

    static function composerJsonPath(Event $event = null) {
        return self::projectRoot($event) . DIRECTORY_SEPARATOR . "composer.json";
    }

    static function projectRoot(Event $event = null) {
        return dirname(self::vendorDir($event));
    }

    static function vendorDir(Event $event = null) {
        if (defined("COMPOSER_VENDOR_DIR")) {
            $vendorDir = COMPOSER_VENDOR_DIR;
        } elseif (!is_null($event)) {
            $vendorDir = $event->getComposer()->getConfig()->get("vendor-dir");
        } else {
            $reflection = new ReflectionClass(ClassLoader::class);
            $vendorDir = dirname(dirname($reflection->getFileName()));
        }
        return $vendorDir;
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

    static function getUserProjectDir($gcloudProject) {
        return Paths::getUserHomeDir() . DIRECTORY_SEPARATOR . "." . $gcloudProject;
    }

    static function getUserHomeDir() {
        return getenv("HOME") ? getenv("HOME") : getenv("USERPROFILE");
    }
}