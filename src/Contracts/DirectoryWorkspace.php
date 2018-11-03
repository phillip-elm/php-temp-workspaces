<?php

namespace PhillipElm\TempWorkspaces\Contracts;

/**
 * Interface DirectoryWorkspace
 *
 * @package PhillipElm\TempWorkspaces\Contracts
 */
interface DirectoryWorkspace
{
    /**
     * Get the path of this directory. If a sub path is provided, it is appended.
     *
     * @param string|null $subPath A leading slash is not required.
     *
     * @return string
     */
    public function path(?string $subPath = null): string;

    /**
     * Check to see if the given path exists as either a file or a directory.
     *
     * @param string $subPath
     *
     * @return bool
     */
    public function exists(string $subPath): bool;

    /**
     * Check to see if the given path exists as a file.
     *
     * @param string $subPath
     *
     * @return bool
     */
    public function isFile(string $subPath): bool;

    /**
     * Check to see if the given path exists as a directory.
     *
     * @param string $subPath
     *
     * @return bool
     */
    public function isDirectory(string $subPath): bool;

    /**
     * Create a sub directory within this workspace.
     *
     * This method mirrors the input for `\mkdir`, except that $recursive is true.
     *
     * @param string $subPath     The sub path to create
     * @param int    $permissions The permissions to assign.
     * @param bool   $recursive   Whether or not to create recursively.
     *
     * @return boolean
     */
    public function mkdir(string $subPath, int $permissions = 0777, bool $recursive = true): bool;

    /**
     * Copy a file within this workspace. Do not use this to copy outside of the workspace.
     *
     * @param string $subSrc
     * @param string $subDst
     *
     * @return bool
     */
    public function copy(string $subSrc, string $subDst): bool;

    /**
     * Move a file within this workspace. Do not use to move files in/out of this workspace.
     *
     * @param string $subSrc
     * @param string $subDst
     *
     * @return bool
     */
    public function move(string $subSrc, string $subDst): bool;

    /**
     * Import a file into this temporary workspace
     *
     * @param string $src    The source full path. MUST be on the same filesystem
     * @param string $subDst The local destination path
     * @param bool   $copy   if TRUE, will copy the file in. If FALSE, will move it.
     *
     * @return bool
     */
    public function import(string $src, string $subDst, bool $copy): bool;

    /**
     * Export a file from this workspace to a full path on the same filesystem
     *
     * @param string $subSrc The local file path in this workspace
     * @param string $dst    The full path to write to
     * @param bool   $copy   If TRUE, will copy the file out. If FALSE, will move it.
     *
     * @return bool
     */
    public function export(string $subSrc, string $dst, bool $copy): bool;

    /**
     * Delete the given file path.
     *
     * @param string $subPath
     *
     * @return bool
     */
    public function delete(string $subPath): bool;

    /**
     * Call file_put_contents on the give sub path.
     *
     * @param string                $subPath          The path within this workspace to write to
     * @param string|array|resource $contents         The contents to write. Passed directory to file_put_contents
     * @param bool                  $mkdir            if TRUE, will mkdir if the path does not exist. Otherwise false
     * @param int|null              $mkdirPermissions The permissions to create with.
     *
     * @return int|false
     * @see \file_put_contents()
     */
    public function putContents(string $subPath, $contents, bool $mkdir = false, ?int $mkdirPermissions = 0777);

    /**
     * Call file_get_contents on the local sub path.
     *
     * @param string $subPath
     *
     * @return string|false The string data from the file, or false on failure
     */
    public function getContents(string $subPath);
}
