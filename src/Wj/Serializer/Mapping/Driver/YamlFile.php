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
     * @param YamlParser $parser
     */
    public function __construct(FileLocatorInterface $locator, $parser)
    {
        parent::__construct($locator);

        $this->setParser($parser);
    }

    protected function loadMetadataFromFile(\ReflectionClass $class, $file)
    {
        $yaml = $this->getParser()->parse($file);

        foreach ($yaml as $className => $classMapping) {
            $classMetadata = new ClassMetadata($className);

            if (isset($classMapping['properties'])) {
                foreach ($classMapping['properties'] as $propertyName => $propertyMapping) {
                    $propertyMetadata = new PropertyMetadata($propertyName);

                    if (isset($propertyMapping['type'])) {
                        $propertyMetadata->type = $propertyMapping['type'];
                    }

                    if (isset($propertyMapping['map'])) {
                        $acceptedTypes = array('key', 'element', 'attribute');
                        if (!in_array($propertyMapping['map'], $acceptedTypes)) {
                            throw new \LogicException(sprintf(
                                'Mapping type for property "%s" ("%s") must be one of: %s.',
                                $propertyName,
                                $propertyMapping['map'],
                                implode('; ', $acceptedTypes)
                            ));
                        }

                        $propertyMetadata->type = $propertyMapping['map'];
                    }

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
}
