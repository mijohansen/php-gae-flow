<?php

namespace GaeFlow;

class ComposerExtra {

    const SERVE_ENTRYPOINT = "serve:entrypoint";
    const SERVE_HOST = "serve:host";
    const SERVE_PORT = "serve:port";
    const SERVE_FOLDER = "serve:public";
    const GCLOUD_PROJECT = "gcloud:project";

    static function getServeEntrypoint() {
        return self::get(self::SERVE_ENTRYPOINT);
    }

    static function getServeHost() {
        return self::get(self::SERVE_HOST, "0.0.0.0");
    }

    static function getServePort() {
        return self::get(self::SERVE_PORT, 2004);
    }

    static function getGcloudProject() {
        return self::get(self::GCLOUD_PROJECT);
    }

    static function getServeFolder() {
        return self::get(self::SERVE_FOLDER, "/");
    }

    static function getValues() {
        $data = Paths::composerJsonData();
        return $data["extra"] ?? [];
    }

    static function get($key, $default = null) {
        $extra = self::getValues();
        return $extra[$key] ?? $default;
    }
}