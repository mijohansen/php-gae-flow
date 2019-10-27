<?php

namespace GaeFlow;

use Composer\Script\Event;

class ScriptUtils {

    static function getUserProjectDir(Event $event) {
        $home = $event->getComposer()->getConfig()->get("home");
        $gcloudProject = self::getProjectFromExtra($event);
        return str_replace(".composer", "." . $gcloudProject, $home);
    }

    static function getProjectFromExtra(Event $event) {
        $extra = $event->getComposer()->getPackage()->getExtra();
        if (isset($extra[ComposerExtra::GCLOUD_PROJECT])) {
            return $extra[ComposerExtra::GCLOUD_PROJECT];
        } else {
            return null;
        }
    }

    static function getRouterPath(Event $event) {
        $packageData = Paths::getPackageData();
        $vendorDir = $event->getComposer()->getConfig()->get('vendor-dir');
        $packagePath = str_replace("/", DIRECTORY_SEPARATOR, $packageData["name"]);
        $routerPath = join(DIRECTORY_SEPARATOR, [
            $vendorDir,
            $packagePath,
            "router.php"
        ]);
        return $routerPath;
    }
}