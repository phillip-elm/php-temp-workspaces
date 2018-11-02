# PHP Temporary Directory Workspaces

This is a simple library written to facilitate the quick creation of temporary directories.

When the object is destroyed, the temporary directory and its contents are deleted.

## Installing

```
composer require phillip-elm/temp-workspaces
```

## Basic Usage

```php
<?php

use PhillipElm\TempWorkspaces\TempDirectoryWorkspace;

function example()
{
  // This creates a workspace and returns it.
  $workspace = TempDirectoryWorkspace::create();

  // This will make a new directory within your workspace.
  $workspace->mkdir('example/test', 0777, true);

  // This will return the full path of the given sub-path relative to the workspace root.
  $path = $workspace->path('example/test/test.txt');

  $workspace->putContents('example/test/test.txt', 'This is a test');

  echo $workspace->getContents('example/test/test.txt');

  // When called without an argument, path() returns the root of the workspace without a trailing slash.
  echo $workspace->path();

  // Once $workspace is destructed, the temporary directory and its contents will be purged.
}
```

## Documentation

This library was put together in a bit of a rush. As such, there is no formal generated PHPDoc or guide.

The project is broken down into the following interfaces / classes:

- `src/Contracts/DirectoryWorkspace.php`
  - The interface that contains the methods headers you can call on the object
- `src/BaseDirectoryWorkspace.php`
  - The implementation for functions contained in the `DirectoryWorkspace` interface.
- `src/TempDirectoryWorkspace.php`
  - The extension of `BaseDirectoryWorkspace` that contains the clean-on-destruct functionality.
- `src/DirectoryRemover.php`
  - A simple class to remove directories recursively without using `exec`.

Additionally, you may review the tests for more examples on how to use this library.
