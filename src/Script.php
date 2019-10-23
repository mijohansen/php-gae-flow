<?php

namespace GaeFlow;

use Composer\Script\Event;
use Symfony\Component\Yaml\Yaml;

/**
 *
 * Generic class that should be delivered as a package dependency.s
 * Class Gae
 * @package VkpNinja
 */
class Script {

    const ENV_FILENAME_DEV = "dev.env";
    const ENV_FILENAME_PROD = "prod.env";
    const PROJECT_EXTRA_KEY = "gcloud:project";
    const SERVERPORT = "";
    const PROJECT_ENTRYPOINT = "gcloud:entrypoint";

    static function getProjectDir(Event $event) {
        $home = $event->getComposer()->getConfig()->get("home");
        $gcloudProject = self::getProjectFromExtra($event);
        return str_replace(".composer", "." . $gcloudProject, $home);
    }

    static function getProjectFromExtra(Event $event) {
        $extra = $event->getComposer()->getPackage()->getExtra();
        if (isset($extra[self::PROJECT_EXTRA_KEY])) {
            return $extra[self::PROJECT_EXTRA_KEY];
        } else {
            return null;
        }

    }

    static function deploy(Event $event, $dryRun = false) {
        $gcloudProject = self::getProjectFromExtra($event);
        if (!strlen($gcloudProject)) {
            $projectExtraKey = self::PROJECT_EXTRA_KEY;
            $event->getIO()->write("You need to put '$projectExtraKey' in the extra section of composer.json.");
            $gcloudProject = Gcloud::getCurrentProject();
            if ($gcloudProject && $event->getIO()->askConfirmation("But you got $gcloudProject as default project. Should I add it?")) {
                return ResultState::NO_PROJECT_KEY_IN_GCLOUD;
            } else {
                return ResultState::NO_PROJECT_KEY_IN_COMPOSER;
            }
        }
        $projectHome = self::getProjectDir($event);
        if (!is_dir($projectHome)) {
            $event->getIO()->writeError("You need to 'mkdir $projectHome'");
            if ($event->getIO()->askConfirmation("Should I create it?")) {
                mkdir($projectHome);
            } else {
                return ResultState::NO_PROJECT_SECRET_DIR;
            }
        }
        $prodEnvPath = $projectHome . DIRECTORY_SEPARATOR . self::ENV_FILENAME_PROD;
        if (!is_file($prodEnvPath)) {
            $event->getIO()->writeError("You need to add properties at `$prodEnvPath`");
            if ($event->getIO()->askConfirmation("Should I create it?")) {
                touch($prodEnvPath);
            } else {
                return ResultState::NO_ENV_FILENAME_PROD;
            }
        }
        if (!is_file("app.yaml")) {
            $event->getIO()->writeError("You need an app.yaml file.");
            return ResultState::NO_APP_YAML;
        }
        $secretAppYamlName = "secret.app.yaml";
        $template = Yaml::parseFile("app.yaml");
        $template["env_variables"] = parse_ini_file($prodEnvPath);
        file_put_contents($secretAppYamlName, Yaml::dump($template, 2));
        $result = Gcloud::deploy($secretAppYamlName, $gcloudProject, $dryRun);
        unlink($secretAppYamlName);
        return $result;
    }

    static function devserve(Event $event, $dryRun = false) {
        $port = 2004;
        $host = "0.0.0.0";
        $root = null;
        $entrypoint = realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "entrypoint-dev.php");
        $event->getIO()->write("Starting Server at entrypoint:" . $entrypoint);

        if ($dryRun) {
            return true;
        } else {
            $result =  Cmds::buildIn($host, $port, $entrypoint, $root);
            $event->getIO()->write($result);
        }
    }

}