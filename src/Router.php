<?php

namespace GaeFlow;

use Dotenv\Dotenv;

class Router {

    public function __invoke() {
        if (preg_match('/\.(?:png|jpg|jpeg|gif|svg|css|js|map|woff|woff2|ttf|ico|json)$/', $_SERVER["REQUEST_URI"])) {
            return false;    // serve the requested resource as-is.
        } else {
            $gcloudProject = ComposerExtra::getServeEntrypoint();
            $projectEntryPoint = ComposerExtra::getServeEntrypoint();
            $envLocation = Paths::getUserProjectDir($gcloudProject);
            Dotenv::create($envLocation, Paths::ENV_FILENAME_DEV)->load();
            return require_once $projectEntryPoint;
        }
    }
}