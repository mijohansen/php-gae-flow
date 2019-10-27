<?php

use Composer\Composer;
use Composer\Factory;
use Composer\IO\NullIO;
use Composer\Package\RootPackage;
use Composer\Script\Event;
use GaeFlow\ComposerExtra;

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "vendor/autoload.php";

function create_plugin_event($projectName = "dummydummy") {
    $composer = new Composer();
    $io = new NullIO();
    $config = Factory::createConfig($io);
    $package = new RootPackage("dummy", "1.0.0", "prod");
    $package->setExtra([
        ComposerExtra::GCLOUD_PROJECT => $projectName
    ]);
    $composer->setPackage($package);
    $composer->setConfig($config);
    return new Event("Dummy", $composer, $io);
}