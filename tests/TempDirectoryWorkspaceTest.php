<?php

namespace PhillipElm\TempWorkspaces\Tests;

use PhillipElm\TempWorkspaces\TempDirectoryWorkspace;
use PHPUnit\Framework\TestCase;

/**
 * Class TemporaryDirectoryWorkspaceTest
 *
 * @package PhillipElm\TempWorkspaces\Tests
 * @group   TempDirectoryWorkspace
 */
class TempDirectoryWorkspaceTest extends TestCase
{
    public function test()
    {
        $workspace = TempDirectoryWorkspace::create();

        $paths = [];

        foreach (range('a', 'd') as $value) {
            $path     = $workspace->path($value);
            $paths [] = $path;

            $workspace->putContents($value, "I'm a file.");
        }

        unset($workspace);

        clearstatcache(true);

        foreach ($paths as $path) {
            $this->assertFalse(is_file($path));
        }
    }
}
