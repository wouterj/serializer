<?php

namespace Wj\Serializer;

use Wj\Serializer\Formatter\Manager as FormatterManager;
use Metadata\MetadataFactoryInterface;

/**
 * @author Wouter J <wouter@wouterj.nl>
 */
class Serializer
{
    /**
     * @var FormatterManager
     */
    private $formatterManager;
    /**
     * @var MetadataFactoryInterface
     */
    private $metadataFactory;

    /**
     * @var FormatterManager
     */
    public function __construct($formatterManager, MetadataFactoryInterface $metadataFactory)
    {
        $this->setFormatterManager($formatterManager);
        $this->setMetadataFactory($metadataFactory);
    }

    public function serialize($format, $object)
    {
        $metadata = $this->getMetadataFactory()->getMetadataForClass(get_class($object));

        return $this->getFormatterManager()->getFormatterByFormat($format)->format($object, $metadata);
    }

    protected function getFormatterManager()
    {
        return $this->formatterManager;
    }

    private function setFormatterManager(FormatterManager $manager)
    {
        $this->formatterManager = $manager;
    }

    protected function getMetadataFactory()
    {
        return $this->metadataFactory;
    }

    private function setMetadataFactory(MetadataFactoryInterface $factory)
    {
        $this->metadataFactory = $factory;
    }
}
