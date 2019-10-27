<?php

use GaeFlow\ScriptErrors;
use GaeFlow\Script;
use PHPUnit\Framework\TestCase;

class ScriptTest extends TestCase {

    const TEST_PROJECT_NAME = "mydummyproject";

    public function testDeployScriptErrors() {
        $event = create_plugin_event(self::TEST_PROJECT_NAME);
        $result1 = Script::deploy($event, true);
        $this->assertEquals(ScriptErrors::NO_APP_YAML, $result1);
        $event->getComposer()->getPackage()->setExtra([]);
        $result2 = Script::deploy($event, true);
        $this->assertTrue(in_array($result2, [
            ScriptErrors::NO_PROJECT_KEY_IN_COMPOSER,
            ScriptErrors::NO_PROJECT_KEY_IN_GCLOUD
        ]));
    }

    public function testDeployScriptSuccess() {
        $event = create_plugin_event(self::TEST_PROJECT_NAME);
        touch("app.yaml");
        $result1 = Script::deploy($event, true);
        $this->assertIsString($result1);
        $this->assertStringStartsWith("gcloud", $result1);
        $this->assertStringContainsString(self::TEST_PROJECT_NAME, $result1);
        unlink("app.yaml");
    }

}
