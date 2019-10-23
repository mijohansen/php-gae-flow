<?php

namespace GaeFlow;

class Gcloud {

    static function getCurrentProject($dryRun = false) {
        $value = exec("gcloud config list --format 'value(core.project)'");
        if ($value) {
            return $value;
        } else {
            return false;
        }
    }

    static function deploy($secretAppYamlName, $gcloudProject, $dryRun = false) {
        $deployCommand = "gcloud app deploy $secretAppYamlName --project $gcloudProject --promote --quiet";
        if ($dryRun) {
            return $deployCommand;
        } else {
            return exec($deployCommand);
        }

    }
}