<?php

namespace PhillipElm\TempWorkspaces;

use PhillipElm\TempWorkspaces\Contracts\DirectoryWorkspace;

/**
 * Creates a temporary directory, and destroys it on termination.
 *
 * @package PhillipElm\TempWorkspaces
 */
class TempDirectoryWorkspace extends BaseDirectoryWorkspace implements DirectoryWorkspace
{
    protected function __construct(string $path)
    {
        parent::__construct($path);
    }

    /**
     * @param int $tmpDirPermissions
     *
     * @return Contract|null
     */
    public static function create(int $tmpDirPermissions = 0777): ?DirectoryWorkspace
    {
        // Create a random string
        $randomString = sha1(microtime());
        $randomString = substr($randomString, 0, 12);
        $dir          = sprintf('%s/%s', sys_get_temp_dir(), $randomString);

        // Create the temporary directory
        if (!mkdir($dir, $tmpDirPermissions, true)) {
            return null;
        }

        return new self($dir);
    }

    /**
     * Destroy the temporary directory.
     */
    public function __destruct()
    {
        DirectoryRemover::remove($this->path);
    }
}
