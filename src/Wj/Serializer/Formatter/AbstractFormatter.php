<?php

namespace Wj\Serializer\Formatter;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Wj\Serializer\Serializer;

/**
 * @author Wouter J <wouter@wouterj.nl>
 */
abstract class AbstractFormatter implements Formatter
{
    /**
     * @var Serializer
     */
    private $serializer;
    /**
     * @var PropertyAccessor
     */
    private $accessor;

    /**
     * @return Serializer
     */
    protected function getSerializer()
    {
        return $this->serializer;
    }

    public function setSerializer(Serializer $serializer)
    {
        $this->serializer = $serializer;
    }

    public function getAccessor()
    {
        if (null === $this->accessor) {
            $this->createAccessor();
        }

        return $this->accessor;
    }

    public function createAccessor()
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }
} 