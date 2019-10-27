<?php

namespace GaeFlow;

class ComposerExtra {

    const SERVE_ENTRYPOINT = "serve:entrypoint";
    const SERVE_ADDR = "serve:addr";
    const SERVE_PORT = "serve:port";
    const SERVE_DOCROOT = "serve:docroot";
    const GCLOUD_PROJECT = "gcloud:project";

    static function getServeEntrypoint() {
        return self::get(self::SERVE_ENTRYPOINT);
    }

    static function getServeAddr() {
        return self::get(self::SERVE_ADDR, "0.0.0.0");
    }

    static function getServePort() {
        return self::get(self::SERVE_PORT, 2004);
    }

    static function getGcloudProject() {
        return self::get(self::GCLOUD_PROJECT);
    }

    static function getServeDocroot() {
        return self::get(self::SERVE_DOCROOT, "/");
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