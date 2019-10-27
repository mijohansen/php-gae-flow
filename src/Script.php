<?php

namespace GaeFlow;

use Composer\Script\Event;
use Symfony\Component\Yaml\Yaml;

/**
 *
 * This class holds the scripts that could be run from composer.json as plugins.
 * All config is done through adding extrafields to composer.json. So that is really
 * the only interface that should be between this package and the package consumer.
 *
 * @package GaeFlow
 */
class Script {

    static function deploy(Event $event, $dryRun = false) {
        $gcloudProject = ScriptUtils::getProjectFromExtra($event);
        if (!strlen($gcloudProject)) {
            $projectExtraKey = ComposerExtra::GCLOUD_PROJECT;
            $event->getIO()->write("You need to put '$projectExtraKey' in the extra section of composer.json.");
            $gcloudProject = Gcloud::getCurrentProject();
            if ($gcloudProject && $event->getIO()->askConfirmation("But you got $gcloudProject as default project. Should I add it?")) {
                return ScriptErrors::NO_PROJECT_KEY_IN_GCLOUD;
            } else {
                return ScriptErrors::NO_PROJECT_KEY_IN_COMPOSER;
            }
        }
        $projectHome = ScriptUtils::getUserProjectDir($event);
        if (!is_dir($projectHome)) {
            $event->getIO()->writeError("You need to 'mkdir $projectHome'");
            if ($event->getIO()->askConfirmation("Should I create it?")) {
                mkdir($projectHome);
            } else {
                return ScriptErrors::NO_PROJECT_SECRET_DIR;
            }
        }
        $prodEnvPath = $projectHome . DIRECTORY_SEPARATOR . Paths::ENV_FILENAME_PROD;
        if (!is_file($prodEnvPath)) {
            $event->getIO()->writeError("You need to add properties at `$prodEnvPath`");
            if ($event->getIO()->askConfirmation("Should I create it?")) {
                touch($prodEnvPath);
            } else {
                return ScriptErrors::NO_ENV_FILENAME_PROD;
            }
        }
        if (!is_file("app.yaml")) {
            $event->getIO()->writeError("You need an app.yaml file.");
            return ScriptErrors::NO_APP_YAML;
        }
        $secretAppYamlName = "secret.app.yaml";
        $template = Yaml::parseFile("app.yaml");
        $template["env_variables"] = parse_ini_file($prodEnvPath);
        file_put_contents($secretAppYamlName, Yaml::dump($template, 2));
        $result = Gcloud::deploy($secretAppYamlName, $gcloudProject, $dryRun);
        unlink($secretAppYamlName);
        return $result;
    }

    static function serve(Event $event, $dryRun = false) {
        $port = ComposerExtra::getServePort();
        $addr = ComposerExtra::getServeAddr();
        $docroot = ComposerExtra::getServeDocroot();
        $router = ScriptUtils::getRouterPath($event);
        $event->getIO()->write("Starting Server at port:" . $port);
        if ($dryRun) {
            return true;
        } else {
            $result = Cmds::buildIn($addr, $port, $router, $docroot);
            $event->getIO()->write($result);
        }
    }

}