<?php


namespace PhillipElm\TempWorkspaces;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

/**
 * Internal directory utility.
 *
 * As it is marked internal, it could change at any time without concern for backwards compat. Don't use this class
 * directly in your code.
 *
 * @package PhillipElm\TempWorkspaces
 * @internal
 */
class DirectoryUtil
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
        if (strlen($path) < 17) {
            // "/tmp/{12 chars}" == 17 characters
            throw new \RuntimeException("Path $path is too short. Paths generated by this library are at least 17 characters in length.");
        }

        $it    = new RecursiveDirectoryIterator($path, RecursiveDirectoryIterator::SKIP_DOTS);
        $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);

        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }

        return rmdir($path);
    }

    /**
     * Copy files within $src to $dst.
     *
     * If a failure occurs mid-operation and $dst did not exist before the operation, $dst will be removed.
     *
     * @param string   $src              The directory to copy files from. This directory is not included itself.
     * @param string   $dst              The source directory to store files in. If it does not exist, it will be
     *                                   created using $mkdirPermissiosn or the $src permissions.
     * @param int|null $mkdirPermissions The permissions to make the parent directory with. If not provided, will use
     *                                   the $src
     *
     * @return bool TRUE if successful, FALSE if failed.
     */
    public static function copy(string $src, string $dst, ?int $mkdirPermissions = null): bool
    {
        $dstExistedPrior = is_dir($dst);
        if (!$dstExistedPrior) {
            if (!$mkdirPermissions) {
                $mkdirPermissions = fileperms($src);
            }

            if (!mkdir($dst, $mkdirPermissions, true)) {
                return false;
            }
        }

        $rdi      = new RecursiveDirectoryIterator($src, \RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new RecursiveIteratorIterator($rdi, \RecursiveIteratorIterator::SELF_FIRST);

        foreach ($iterator as $item) {
            /** @var \FilesystemIterator $item */
            $dstPath = $dst . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
            if ($item->isDir()) {
                if (is_dir($dstPath)) {
                    continue;
                } else {
                    $perms  = fileperms($item->getPathname());
                    $result = mkdir($dstPath, $perms, true);
                }
            } else {
                $result = copy($item, $dstPath);
            }

            if (!$result) {
                if (!$dstExistedPrior) {
                    // Remove $dst as it didn't exist before this operation
                    self::remove($dst);
                }

                return false;
            }
        }

        return true;
    }
}
