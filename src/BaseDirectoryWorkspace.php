<?php

namespace PhillipElm\TempWorkspaces;

use PhillipElm\TempWorkspaces\Contracts\DirectoryWorkspace;

/**
 * The base working directory class. Provides common functionality.
 *
 * @package PhillipElm\TempWorkspaces
 */
class BaseDirectoryWorkspace implements DirectoryWorkspace
{
    /**
     * The path that this object set the CWD to.
     *
     * @var string
     */
    protected $path;

    /**
     * WorkingDirectory constructor.
     *
     * @param string $path
     *
     * @throws ChangeDirectoryFailedException
     */
    protected function __construct(string $path)
    {
        $this->path = $path;
    }

    /** @inheritdoc */
    public function path(?string $subPath = null): string
    {
        if ($subPath) {
            return "$this->path/$subPath";
        } else {
            return $this->path;
        }
    }

    /** @inheritdoc */
    public function exists(string $subPath): bool
    {
        return file_exists($this->path($subPath));
    }

    /** @inheritdoc */
    public function isFile(string $subPath): bool
    {
        return is_file($this->path($subPath));
    }

    /** @inheritdoc */
    public function isDirectory(string $subPath): bool
    {
        return is_dir($this->path($subPath));
    }

    /** @inheritdoc */
    public function mkdir(string $subPath, int $permissions = 0777, bool $recursive = true): bool
    {
        return mkdir("$this->path/$subPath", $permissions, $recursive);
    }

    /**
     * Copy a file within this workspace. Do not use this to copy outside of the workspace.
     *
     * @param string $subSrc
     * @param string $subDst
     *
     * @return bool
     */
    public function copy(string $subSrc, string $subDst): bool
    {
        return copy($this->path($subSrc), $this->path($subDst));
    }

    /**
     * Move a file within this workspace. Do not use to move files in/out of this workspace.
     *
     * @param string $subSrc
     * @param string $subDst
     *
     * @return bool
     */
    public function move(string $subSrc, string $subDst): bool
    {
        return rename($this->path($subSrc), $this->path($subDst));
    }

    /**
     * Delete the given file / directory path.
     *
     * @param string $subPath
     *
     * @return bool
     */
    public function delete(string $subPath): bool
    {
        $path = $this->path($subPath);

        if (is_file($path)) {
            return unlink($path);
        } elseif (is_dir($path)) {
            return DirectoryRemover::remove($path);
        } else {
            return false;
        }
    }

    /**
     * Call file_put_contents on the give sub path.
     *
     * @param string $subPath
     * @param        $contents
     *
     * @return int|false
     */
    public function putContents(string $subPath, $contents)
    {
        $path    = $this->path($subPath);
        $dirName = pathinfo($path, PATHINFO_DIRNAME);

        if (!is_dir($dirName)) {
            return false;
        }

        return file_put_contents($this->path($subPath), $contents);
    }

    /**
     * Call file_get_contents on the local sub path.
     *
     * @param string $subPath
     *
     * @return string|false
     */
    public function getContents(string $subPath)
    {
        return file_get_contents($this->path($subPath));
    }

    /**
     * Import a file into this temporary workspace
     *
     * @param string $src    The source full path. MUST be on the same filesystem
     * @param string $subDst The local destination path
     * @param bool   $copy   if TRUE, will copy the file in. If FALSE, will move it.
     *
     * @return bool
     */
    public function import(string $src, string $subDst, bool $copy): bool
    {
        $dst = $this->path($subDst);

        if ($copy) {
            return copy($src, $dst);
        } else {
            return rename($src, $dst);
        }
    }

    /**
     * Export a file from this workspace to a full path on the same filesystem
     *
     * @param string $subSrc The local file path in this workspace
     * @param string $dst    The full path to write to
     * @param bool   $copy   If TRUE, will copy the file out. If FALSE, will move it.
     *
     * @return bool
     */
    public function export(string $subSrc, string $dst, bool $copy): bool
    {
        $src = $this->path($subSrc);

        if ($copy) {
            return copy($src, $dst);
        } else {
            return rename($src, $dst);
        }
    }
}
