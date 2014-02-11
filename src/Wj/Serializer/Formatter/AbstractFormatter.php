<?php

namespace Wj\Serializer\Formatter;

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
} 