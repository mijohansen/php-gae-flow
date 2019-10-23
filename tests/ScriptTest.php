<?php

use Composer\Composer;
use Composer\Factory;
use Composer\IO\NullIO;
use Composer\Package\RootPackage;
use Composer\Script\Event;
use GaeFlow\ResultState;
use GaeFlow\Script;
use PHPUnit\Framework\TestCase;

class ScriptTest extends TestCase {

    const TEST_PROJECT_NAME = "mydummyproject";

    public function testGetProjectDir() {
        $event = $this->createEvent();
        $result = Script::getProjectDir($event);
        $this->assertIsString($result);
        $this->assertStringContainsString(self::TEST_PROJECT_NAME, $result);
    }

    public function testGetProjectFromExtra() {
        $event = $this->createEvent();
        $result = Script::getProjectFromExtra($event);
        $this->assertIsString($result);
        $this->assertEquals(self::TEST_PROJECT_NAME, $result);
    }

    public function testDeployScriptErrors() {
        $event = $this->createEvent();
        $result1 = Script::deploy($event, true);
        $this->assertEquals(ResultState::NO_APP_YAML, $result1);
        $event->getComposer()->getPackage()->setExtra([]);
        $result2 = Script::deploy($event, true);
        $this->assertTrue(in_array($result2, [
            ResultState::NO_PROJECT_KEY_IN_COMPOSER,
            ResultState::NO_PROJECT_KEY_IN_GCLOUD
        ]));
    }

    public function testDeployScriptSuccess() {
        $event = $this->createEvent();
        touch("app.yaml");
        $result1 = Script::deploy($event, true);
        $this->assertIsString($result1);
        $this->assertStringStartsWith("gcloud", $result1);
        $this->assertStringContainsString(self::TEST_PROJECT_NAME, $result1);
        unlink("app.yaml");
    }

    private function createEvent() {
        $composer = new Composer();
        $io = new NullIO();
        $config = Factory::createConfig($io);
        $package = new RootPackage("dummy", "1.0.0", "prod");
        $package->setExtra([
            Script::PROJECT_EXTRA_KEY => self::TEST_PROJECT_NAME
        ]);
        $composer->setPackage($package);
        $composer->setConfig($config);
        return new Event("Dummy", $composer, $io);
    }
}
