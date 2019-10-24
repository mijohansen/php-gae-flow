<?php

namespace GaeFlow;

use Dotenv\Dotenv;

class Router {

    public function __invoke() {
        if (preg_match('/\.(?:png|jpg|jpeg|gif|svg|css|js|map|woff|woff2|ttf|ico|json)$/', $_SERVER["REQUEST_URI"])) {
            // return print_r($_SERVER['REQUEST_URI']);
            return false;    // serve the requested resource as-is.
        } else {
            $composerJson = Paths::composerJsonData();
            $gcloudProject = $composerJson["extra"][Script::PROJECT_EXTRA_KEY];
            $projectEntryPoint = $composerJson["extra"][Script::PROJECT_ENTRYPOINT];
            $envLocation = Paths::getUserHomeDir() . DIRECTORY_SEPARATOR . "." . $gcloudProject;
            Dotenv::create($envLocation, Script::ENV_FILENAME_DEV)->load();
            return require_once $projectEntryPoint;
        }
    }
}