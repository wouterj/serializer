<?php

namespace Wj\Serializer\Mapping\Driver;

use Wj\Serializer\Mapping\PropertyMetadata;
use Metadata\Driver\AbstractFileDriver;
use Metadata\Driver\FileLocatorInterface;
use Metadata\ClassMetadata;
use Symfony\Component\Yaml\Parser as YamlParser;

class YamlFile extends AbstractFileDriver
{
    /**
     * @var YamlParser
     */
    private $parser;

    /**
     * @param FileLocatorInterface $locator
     * @param YamlParser           $parser
     */
    public function __construct(FileLocatorInterface $locator, YamlParser $parser = null)
    {
        parent::__construct($locator);

        $parser = $parser ?: new YamlParser();
        $this->setParser($parser);
    }

    protected function loadMetadataFromFile(\ReflectionClass $class, $file)
    {
        $yaml = $this->getParser()->parse(file_get_contents($file));

        foreach ($yaml as $className => $classMapping) {
            $classMetadata = new ClassMetadata($className);

            if (isset($classMapping['properties'])) {
                foreach ($classMapping['properties'] as $propertyName => $propertyMapping) {
                    $propertyMetadata = new PropertyMetadata($className, $propertyName);

                    $this->parseType($propertyMetadata, $propertyMapping, $propertyName);
                    $this->parseMapType($propertyMetadata, $propertyMapping, $propertyName);

                    $classMetadata->addPropertyMetadata($propertyMetadata);
                }
            }
        }

        return $classMetadata;
    }

    protected function getExtension()
    {
        return 'yml';
    }

    protected function getParser()
    {
        return $this->parser;
    }

    private function setParser(YamlParser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * @throws \LogicException
     */
    protected function parseType($propertyMetadata, $propertyMapping, $propertyName)
    {
        if (isset($propertyMapping['type'])) {
            MappingValidator::validateType($propertyMapping, $propertyName);

            $propertyMetadata->type = $propertyMapping['type'];
        }
    }

    /**
     * @throws \LogicException
     */
    protected function parseMapType($propertyMetadata, $propertyMapping, $propertyName)
    {
        if (isset($propertyMapping['map'])) {
            MappingValidator::validateMapType($propertyMapping, $propertyName);

            $propertyMetadata->map = $propertyMapping['map'];
        }
    }
}
