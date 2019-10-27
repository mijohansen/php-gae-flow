<?php

use GaeFlow\Paths;
use PHPUnit\Framework\TestCase;

class PathsTest extends TestCase {

    public function testVendorDir() {
        $vendorDir = Paths::vendorDir();
        $this->assertIsString($vendorDir);
        $this->assertDirectoryExists($vendorDir);
        $this->assertFileExists($vendorDir . DIRECTORY_SEPARATOR . "autoload.php");
    }

    public function testComposerJsonData() {
        $data = Paths::composerJsonData();
        $this->assertIsArray($data);
        $this->assertEquals("mijo/gae-flow", $data["name"]);
    }

    public function testProjectRoot() {
        $projectRoot = Paths::projectRoot();
        $this->assertDirectoryExists($projectRoot);
    }

    public function testGetUserHomeDir() {
        $userHomeDir = Paths::getUserHomeDir();
        $this->assertDirectoryExists($userHomeDir);
    }

    public function testComposerJsonPath() {
        $composerJsonPath = Paths::composerJsonPath();
        $this->assertFileExists($composerJsonPath);
    }
}
