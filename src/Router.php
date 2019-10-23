<?php

namespace GaeFlow;

class Router {

    public function __invoke() {
        if (preg_match('/\.(?:png|jpg|jpeg|gif|svg|css|js|map|woff|woff2|ttf|ico)$/', $_SERVER["REQUEST_URI"])) {
            return false;    // serve the requested resource as-is.
        } else {
            $composerJson = self::getComposerData();
            $gcloudProject = $composerJson["extra"][self::PROJECT_EXTRA_KEY];
            $projectEntryPoint = $composerJson["extra"][self::PROJECT_ENTRYPOINT];
            $envLocation = self::getUserHomeDir() . DIRECTORY_SEPARATOR . "." . $gcloudProject;
            Dotenv::create($envLocation, self::ENV_FILENAME_DEV)->load();
            $callable();
        }
    }
}