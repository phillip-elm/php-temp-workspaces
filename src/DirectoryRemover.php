<?php

namespace PhillipElm\TempWorkspaces;

/**
 * Class DirectoryRemover
 *
 * @package PhillipElm\TempWorkspaces
 * @deprecated This class has been deprecated in favour of the internal class DirectoryUtil.
 */
class DirectoryRemover
{
    /**
     * Completely remove the given directory.
     *
     * @param string $path
     *
     * @return bool
     */
    public static function remove(string $path): bool
    {
        return DirectoryUtil::remove($path);
    }
}
