<?php

use GaeFlow\ScriptUtils;
use PHPUnit\Framework\TestCase;

class ScriptUtilsTest extends TestCase {

    const TEST_PROJECT_NAME = "mydummyproject";

    public function testGetProjectDir() {
        $event = create_plugin_event(self::TEST_PROJECT_NAME);
        $result = ScriptUtils::getUserProjectDir($event);
        $this->assertIsString($result);
        $this->assertStringContainsString(self::TEST_PROJECT_NAME, $result);
    }

    public function testGetProjectFromExtra() {
        $event = create_plugin_event(self::TEST_PROJECT_NAME);
        $result = ScriptUtils::getProjectFromExtra($event);
        $this->assertIsString($result);
        $this->assertEquals(self::TEST_PROJECT_NAME, $result);
    }

}
