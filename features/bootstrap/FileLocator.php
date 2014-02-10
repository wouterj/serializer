<?php

use Metadata\Driver\FileLocatorInterface;

class FileLocator implements FileLocatorInterface
{
    protected $configPath;

    public function __construct($configPath = 'config')
    {
        $this->configPath = $configPath;
    }

    public function findFileForClass(\ReflectionClass $class, $extension)
    {
        $path = $this->configPath.DIRECTORY_SEPARATOR.strtolower($class->name).'.'.$extension;

        if (file_exists($path)) {
            return $path;
        }
    }
}

