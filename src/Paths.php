<?php

namespace GaeFlow;

use Composer\Autoload\ClassLoader;

class Paths {

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

    static function projectRoot() {
        return dirname(self::vendorDir());
    }

    static function getUserHomeDir() {
        return getenv("HOME") ? getenv("HOME") : getenv("USERPROFILE");
    }
}