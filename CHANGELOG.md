# Changelog

### v1.1.1

- Update `DirectoryWorkspace` contract to reflect changes from `v1.1.0`

### v1.1.0

- `BaseDirectoryWorkspace`
    - `copy`, `import` and `export`:
        - can now copy directories
    - `putContents`
        - Can now call `mkdir()` when the path to the destination file does not exist 
        - Default behaviour unchanged, New `$mkdir` parameter must be set to true 
- `DirectoryRemover` class deprecated in favour of `DirectoryUtil` class, which is marked internal.
    - This class can change without affecting semver

### v1.0.0

Initial Release

## Upcoming Release Notes

These are notes for changes that should be considered for upcoming releases.

### General Notes

- `DirectoryWorkspace` could be useful outside of just temporary workspaces.
    - Could fork to a general workspace library


### 2.0 

- `BaseDirectoryWorkspace`
    - `putContents`
        - Change the default value of `$mkdir` to `true`.
- Remove deprecated classes


