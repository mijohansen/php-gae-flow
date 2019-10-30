<?php

namespace GaeFlow;

use Composer\Script\Event;
use SimpleXMLElement;

/**
 * @package GaeFlow
 */
class Intellij {

    /**
     * @return SimpleXMLElement
     */
    static public function createRunConfig() {
        $component = new SimpleXMLElement('<component/>');
        $component->addAttribute("name", "ProjectRunConfigurationManager");
        $configuration = $component->addChild("configuration");
        $configuration->addAttribute("default", "false");
        $configuration->addAttribute("factoryName", "PHP Console");
        $configuration->addAttribute("name", "serve");
        $configuration->addAttribute("path", exec("which composer"));
        $configuration->addAttribute("scriptParameters", "serve");
        $configuration->addAttribute("type", "PhpLocalRunConfigurationType");
        $CommandLine = $configuration->addChild("CommandLine");
        $CommandLine->addAttribute("workingDirectory", "\$PROJECT_DIR\$");
        $method = $configuration->addChild("method");
        $method->addAttribute("v", 2);
        return $component;
    }
}


