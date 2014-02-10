<?php

namespace Wj\Serializer;

use Wj\Serializer\Mapping\Driver\FileLocator;
use Wj\Serializer\Formatter\Formatter;
use Wj\Serializer\Formatter\Manager as FormatterManager;
use Metadata\MetadataFactory;
use Metadata\Driver\DriverChain;
use Metadata\Driver\FileLocatorInterface;

/**
 * This class builds the serializer.
 *
 * @author Wouter J <wouter@wouterj.nl>
 */
class SerializerBuilder
{
    private $formats = array();
    private $driver;
    private $locator;

    public function __construct()
    {
        $this->driver = new DriverChain();
        $this->locator = new FileLocator();
    }

    public function registerFormat($format, Formatter $formatter)
    {
        $this->formats[$format] = $formatter;
    }

    public function setDriverChain(DriverChain $driver)
    {
        $this->driver = $driver;
    }

    public function addDriver(DriverInterface $driver)
    {
        $this->driver->addDriver($driver);
    }

    public function setFileLocator(FileLocatorInterface $locator)
    {
        $this->locator = $locator;
    }

    public function getSerializer()
    {
        $formatManager = new FormatterManager($this->formats);
        $driver = $this->driver;
        if (null === $driver) {
            $driver = new DriverChain(array(
                new YamlFile($this->locator),
            ));
        }
        $factory = new MetadataFactory($driver);

        return new Serializer($formatManager, $factory);
    }
}
