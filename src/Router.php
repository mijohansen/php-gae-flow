<?php

namespace GaeFlow;

use Dotenv\Dotenv;

class Router {

    public static function loadDevEnv() {
        $gcloudProject = ComposerExtra::getGcloudProject();
        $envLocation = Paths::getUserProjectDir($gcloudProject);
        if(method_exists(Dotenv::class,"createImmutable" )){
            Dotenv::createImmutable($envLocation, Paths::ENV_FILENAME_DEV)->load();
        }else {
            Dotenv::create($envLocation, Paths::ENV_FILENAME_DEV)->load();
        }

    }

    public function __invoke() {
        if (preg_match('/\.(?:png|jpg|jpeg|gif|svg|css|js|map|woff|woff2|ttf|ico|json)$/', $_SERVER["REQUEST_URI"])) {
            return false;    // serve the requested resource as-is.
        } else {
            self::loadDevEnv();
            return require_once ComposerExtra::getServeEntrypoint();
        }
    }
}