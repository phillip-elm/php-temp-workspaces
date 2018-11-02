<?php


namespace PhillipElm\TempWorkspaces\Tests;

use PhillipElm\TempWorkspaces\TempDirectoryWorkspace;
use PHPUnit\Framework\TestCase;

/**
 * Class BaseDirectoryWorkspaceTest
 *
 * @package PhillipElm\TempWorkspaces\Tests
 * @group BaseDirectoryWorkspace
 */
class BaseDirectoryWorkspaceTest extends TestCase
{
    public function testGeneral()
    {
        $subDir = 'we/need/to/go/deeper';

        // Now, test with TemporaryWorkingDirectory
        $workspace  = TempDirectoryWorkspace::create();
        $workingDir = $workspace->path();
        $fullSubDir = "$workingDir/$subDir";

        $this->assertEquals($workspace->path($subDir), $fullSubDir);

        $this->assertTrue($workspace->mkdir('test/example'));

        $filePath = $workspace->path('test/example/test.txt');
        file_put_contents($filePath, 'ok');

        $this->assertTrue(is_file($filePath));

        unset($workspace);
        clearstatcache(true);

        $this->assertFalse(is_file($filePath));
    }

    public function testPutGetContents()
    {
        $workspace = TempDirectoryWorkspace::create();

        $this->assertEquals($workspace->putContents('test', 'test'), 4);
        $this->assertEquals($workspace->getContents('test'), 'test');
    }

    public function testExistenceChecks()
    {
        $workspace = TempDirectoryWorkspace::create();

        // Create a directory and file, separately.
        $this->assertTrue($workspace->mkdir('dir/subdir'));
        $this->assertEquals($workspace->putContents('test', 'test'), 4);

        // Check various existences
        $this->assertTrue($workspace->exists('test'));
        $this->assertTrue($workspace->exists('dir/subdir'));

        $this->assertTrue($workspace->isFile('test'));
        $this->assertFalse($workspace->isFile('dir/subdir'));

        $this->assertFalse($workspace->isDirectory('test'));
        $this->assertTrue($workspace->isDirectory('dir/subdir'));
    }

    public function testCopyMoveDeleteFile()
    {
        $workspace = TempDirectoryWorkspace::create();

        // Make a file
        $workspace->putContents('test', 'test');

        // Copy the file
        $this->assertTrue($workspace->copy('test', 'copied'));

        // Ensure it exists
        $this->assertTrue($workspace->isFile('copied'));

        // Move it
        $this->assertTrue($workspace->move('copied', 'moved'));

        // Ensure it exists under the new name and that the old one is gone
        $this->assertFalse($workspace->isFile('copied'));
        $this->assertTrue($workspace->isFile('moved'));

        // Delete it
        $this->assertTrue($workspace->delete('moved'));
        $this->assertFalse($workspace->exists('moved'));
    }

    public function testCopyMoveDeleteDirectory()
    {
        $workspace = TempDirectoryWorkspace::create();

        // Make a file
        $workspace->mkdir('test');

        // Move it
        $this->assertTrue($workspace->move('test', 'moved'));

        // Ensure it exists under the new name and that the old one is gone
        $this->assertFalse($workspace->isDirectory('test'));
        $this->assertTrue($workspace->isDirectory('moved'));

        // Delete it
        $this->assertTrue($workspace->delete('moved'));
        $this->assertFalse($workspace->exists('moved'));
    }

    public function testImportExport()
    {
        $internal = TempDirectoryWorkspace::create();
        $external = TempDirectoryWorkspace::create();

        $external->putContents('test', 'test');

        // Copy import + export
        $this->assertTrue($internal->import($external->path('test'), 'test', true));
        $this->assertTrue($internal->export('test', $external->path('import'), true));
        $this->assertTrue($internal->isFile('test'));
        $this->assertTrue($external->isFile('test'));
        $this->assertTrue($external->isFile('import'));

        // Move import
        $this->assertTrue($internal->import($external->path('test'), 'test', false));
        $this->assertTrue($internal->isFile('test'));
        $this->assertFalse($external->isFile('test'));

        // Move export
        $this->assertTrue($internal->export('test', $external->path('import'), false));
        $this->assertTrue($external->isFile('import'));
        $this->assertFalse($internal->isFile('test'));
    }
}
