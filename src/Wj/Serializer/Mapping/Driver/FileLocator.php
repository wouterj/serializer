<?php

namespace Wj\Serializer\Mapping\Driver;

use Metadata\Driver\FileLocatorInterface;

class FileLocator implements FileLocatorInterface
{
    protected $configPath;

    public function __construct($configPath = 'config')
    {
        $this->configPath = DIRECTORY_SEPARATOR.$configPath;
    }

    public function findFileForClass(\ReflectionClass $class, $extension)
    {
        $basePath = dirname($class->getFileName());
        $path = $basePath.$this->configPath.DIRECTORY_SEPARATOR.strtolower($class->name).'.'.$extension;

        if (file_exists($path)) {
            return $path;
        }
    }
}
