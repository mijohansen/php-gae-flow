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
        $addr = ComposerExtra::getServeAddr($event);
        $docroot = ComposerExtra::getServeDocroot($event);
        $port = ComposerExtra::getServePort($event);
        $routerPath = ScriptUtils::getRouterPath($event);
        if (!is_null($docroot) && strlen($docroot) > 0) {
            $projectRoot = Paths::projectRoot($event);
            $docroot = str_replace('/', DIRECTORY_SEPARATOR, $docroot);
            $docroot = str_replace('\\', DIRECTORY_SEPARATOR, $docroot);
            $trimmed = trim($docroot, DIRECTORY_SEPARATOR);
            $docroot = $projectRoot . DIRECTORY_SEPARATOR . $trimmed . DIRECTORY_SEPARATOR;
        }
        $event->getIO()->write("Starting Server at port:" . $port);
        if ($dryRun) {
            return true;
        } else {
            $result = Cmds::buildIn($addr, $port, $routerPath, $docroot);
            $event->getIO()->write($result);
        }
    }

    static function sync(Event $event, $dryRun = false) {
        $ideaPath = Paths::projectRoot($event) . DIRECTORY_SEPARATOR . ".idea";
        $runConfLoc = $ideaPath . DIRECTORY_SEPARATOR . "runConfigurations";
        $serveLocation = $runConfLoc . DIRECTORY_SEPARATOR . "gaeflow.xml";
        if (is_dir($ideaPath)) {
            if (!is_dir($runConfLoc)) {
                mkdir($runConfLoc);
            }
            Intellij::createRunConfig()->asXML($serveLocation);
        }
    }

}