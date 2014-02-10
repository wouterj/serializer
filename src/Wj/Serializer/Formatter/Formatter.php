<?php

namespace Wj\Serializer\Formatter;

use Metadata\ClassMetadata;

/**
 * @author Wouter J <wouter@wouterj.nl>
 */
interface Formatter
{
    /**
     * Formats the object.
     *
     * @param object        $object   The object
     * @param ClassMetadata $metadata The meta data for the object
     */
    public function format($object, ClassMetadata $metadata);
}
