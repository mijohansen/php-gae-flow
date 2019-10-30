<?php

namespace GaeFlow;

use Composer\Script\Event;

class ComposerExtra {

    const GCLOUD_PROJECT = "gcloud:project";
    const SERVE_ADDR = "serve:addr";
    const SERVE_DOCROOT = "serve:docroot";
    const SERVE_ENTRYPOINT = "serve:entrypoint";
    const SERVE_PORT = "serve:port";

    static function getServeEntrypoint(Event $event = null) {
        return self::get(self::SERVE_ENTRYPOINT, $event);
    }

    static function get($key, Event $event = null, $default = null) {
        $extra = self::getValues($event);
        return $extra[$key] ?? $default;
    }

    static function getValues(Event $event = null) {
        $data = Paths::composerJsonData($event);
        return $data["extra"] ?? [];
    }

    static function getServeAddr(Event $event = null) {
        return self::get(self::SERVE_ADDR, $event, "0.0.0.0");
    }

    static function getServePort(Event $event = null) {
        return self::get(self::SERVE_PORT, $event, 2004);
    }

    static function getGcloudProject(Event $event = null) {
        return self::get(self::GCLOUD_PROJECT, $event);
    }

    static function getServeDocroot(Event $event = null) {
        return self::get(self::SERVE_DOCROOT, $event, "/");
    }
}